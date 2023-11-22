<?php

namespace Lunar\TranslationManager;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Livewire;
use Illuminate\Filesystem\Filesystem;
use Lunar\Hub\Facades\Menu;
use Lunar\TranslationManager\Commands\SynchroniseMissingTranslationKeys;
use Lunar\TranslationManager\Commands\SynchronizeTranslationsCommand;
use Lunar\TranslationManager\Drivers\Translation;
use Lunar\TranslationManager\Http\Livewire\LanguageSwitcher;
use Lunar\TranslationManager\Http\Livewire\Pages\TranslationIndex;
use Lunar\TranslationManager\Http\Livewire\Pages\TranslationTable;
use Lunar\TranslationManager\Models\LanguageLine;
use Lunar\TranslationManager\Search\TranslationIndexer;
use Lunar\Hub\Auth\Manifest;
use Lunar\Hub\Auth\Permission;

class TranslationManagerServiceProvider extends ServiceProvider
{
    protected $root = __DIR__ . '/..';

    public function register()
    {
        $this->registerContainerBindings();
    }

    public function boot(): void
    {
        $this->app->booted(function () {
            $manifest = $this->app->get(Manifest::class);
            $manifest->addPermission(function (Permission $permission) {
                $permission->name = 'Manage translation';
                $permission->handle = 'manage-translation'; // or 'group:handle to group permissions
                $permission->description = 'Allow the staff member to manage translation';
            });
        });

        $slot = Menu::slot('sidebar');

        $slot->addItem(function ($item) {
            $item->name(__('translation::menu.sidebar.translation'))
                ->handle('hub.translation')
                ->route('hub.translation.index')
                ->icon('translate');
        });

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'translation');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'translation');


        if ($this->app->runningInConsole()) {
            $this->publishes([
                "{$this->root}/config/translation-manager.php" => config_path("translation-manager.php"),
                "{$this->root}/config/translation-loader.php" => config_path("translation-loader.php"),
            ], 'translation-config');

            $this->publishes([
                __DIR__ . '/../resources/views/partials/navigation' => resource_path('views/vendor/adminhub/partials/navigation'),
                ], 'translation-views');

            $this->commands(
                [
                    SynchroniseMissingTranslationKeys::class,
                    SynchronizeTranslationsCommand::class
                ]
            );
        }

        Config::set('lunar.search.models', array_merge(config('lunar.search.models'),[LanguageLine::class]));
        Config::set('lunar.search.indexers', array_merge(config('lunar.search.indexers'), [LanguageLine::class => TranslationIndexer::class,]));

        $this->registerHelpers();

        $this->registerLivewireComponents();


    }

    public function registerLivewireComponents(): void
    {
        Livewire::component('translations.admin.translation.index', TranslationIndex::class);
        Livewire::component('translations.admin.translation.language-switcher', LanguageSwitcher::class);
        Livewire::component('translations.admin.translation.table', TranslationTable::class);

    }

    /**
     * Register package bindings in the container.
     *
     * @return void
     */
    private function registerContainerBindings()
    {
        $this->app->singleton(Scanner::class, function () {
            $config = $this->app['config']['translation-manager'];

            return new Scanner(new Filesystem(), $config['scan_paths'], $config['translation_methods']);
        });

        $this->app->singleton(Translation::class, function ($app) {
            return (new TranslationManager($app, $app['config']['translation'], $app->make(Scanner::class)))->resolve();
        });
    }

    /**
     * Register package helper functions.
     *
     * @return void
     */
    private function registerHelpers()
    {
        require __DIR__ . '/../resources/helpers.php';
    }

}
