<?php

namespace Lunar\TranslationManager\Http\Livewire\Pages;

use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Livewire\Component;
use Lunar\Hub\Facades\Slot;
use Lunar\Hub\Http\Livewire\Traits\Notifies;
use Lunar\Models\Product;
use Lunar\TranslationManager\Actions\SynchronizeAction;
use Lunar\TranslationManager\Models\LanguageLine;

class TranslationIndex extends Component
{
    use Notifies;

    public string $transCode;
    public bool $showCreateForm = false;
    public string $translated;
    public LanguageLine $record;
    protected $listeners = [
        'addTrans' => 'addTrans',
        'updateTrans' => 'updateTrans',
    ];

    /**
     * Synchronizes the translations using the `SynchronizeAction`.
     *
     * This method calls the `SynchronizeAction::run()` method to synchronize translations.
     * It then notifies the user about the success of the synchronization, including the total count
     * of translations synchronized. If there are any translations that have been deleted during
     * the synchronization process, it also notifies the user about the count of deleted translations.
     *
     * Notifications are sent using the `notify` method with localized messages. The localization keys
     * 'translation::notifications.synchronization-success' and 'translation::notifications.synchronization-deleted'
     * are used for success and deletion messages, respectively. These messages are expected to be defined
     * in the localization files, where they can be customized as needed.
     *
     * @return void
     */
    public function synchronize(): void
    {
        $result = SynchronizeAction::run();
        $this->notify(__('translation::notifications.synchronization-success', ['count' => $result['total_count']]));

        if ($result['deleted_count'] > 0) {
            $this->notify(__('translation::notifications.synchronization-deleted', ['count' => $result['deleted_count']]));
        }
    }

    /**
     * @param LanguageLine $record
     * @param $code
     * @return void
     */
    public function addTrans(LanguageLine $record, $code): void
    {
        $this->record = $record;
        $this->transCode = $code;
        $this->showCreateForm = true;
    }

    /**
     * @param LanguageLine $record
     * @param $code
     * @return void
     */
    public function updateTrans(LanguageLine $record, $code): void
    {
        $this->record = $record;
        $this->transCode = $code;
        $this->translated = $record->text[$code];

        $this->showCreateForm = true;
    }

    /**
     * @return void
     */
    public function saveTranslation()
    {
        $text = $this->record->text;
        $text[$this->transCode] = $this->translated;
        $this->record->text = $text;
        $this->emit('bulkAction.reset');
        $this->record->save();

        if (str_contains($this->record->group, '::')) {
            [$vendor, $group] = explode('::', $this->record->group, 2);
            $filePath = resource_path("lang/vendor/{$vendor}/{$this->transCode}/{$group}.php");
        } else {
            $filePath = resource_path("lang/{$this->transCode}/{$this->record->group}.php");
        }

        // Load or initialize the language file content
        $fileContent = File::exists($filePath) ? include $filePath : [];
        $fileContent[$this->record->key] = $this->translated;

        // Convert the array to a string of valid PHP code
        $exportedArray = var_export($fileContent, true);

        // Prepare the content to be written
        $contentToWrite = "<?php\n\nreturn " . $exportedArray . ";\n";
        file_put_contents($filePath, $contentToWrite);

        $this->showCreateForm = false;

        $this->notify(__('translation::notifications.isCreated'));
    }


    /**
     * Render the livewire component.
     *
     * @return View
     */
    public function render()
    {

        return view('translation::livewire.pages.translation.index')
            ->layout('adminhub::layouts.app', [
                'title' => __('translation::common.title'),
            ]);
    }
}
