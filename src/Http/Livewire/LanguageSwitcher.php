<?php

namespace Lunar\TranslationManager\Http\Livewire;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Lunar\Hub\Http\Livewire\Traits\Notifies;

class LanguageSwitcher extends Component
{

    use Notifies;

    public $currentLanguage;
    public $otherLanguages;
    public $showFlags;
    public $locales;

    public function mount()
    {
        $this->locales = config('translation-manager.available_locales');

        $currentLocale = app()->getLocale();
        $this->currentLanguage = collect($this->locales)->firstWhere('code', $currentLocale);
        $this->otherLanguages = $this->locales;

        $this->showFlags = config('translation-manager.show_flags');
    }

    /**
     * @throws FileNotFoundException
     */
    public function switch($langCode)
    {
        if (config('translation-manager.language_switcher')) {
            $filePath = base_path('config/app.php');
            app()->setLocale($langCode);
            // Load the existing content of the file
            $fileContent = File::get($filePath);

            // Replace the locale and fallback_locale values

            $fileContent = preg_replace("/'locale' => '[^']+'/", "'locale' => '$langCode'", $fileContent);
            $fileContent = preg_replace("/'fallback_locale' => '[^']+'/", "'fallback_locale' => '$langCode'", $fileContent);


            // Combine the comments with the modified file content
            $contentToWrite = $fileContent;
            $this->currentLanguage = collect($this->locales)->firstWhere('code', $langCode);
            // Write the new content to the file
            file_put_contents($filePath, $contentToWrite);
            $this->notify(__('translation::notifications.language-is-change'));
            return redirect()->to('hub');

        }
    }

    public function render()
    {
        return view('translation::livewire.language-switcher');
    }
}
