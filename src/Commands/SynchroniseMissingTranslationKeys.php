<?php

namespace Lunar\TranslationManager\Commands;

class SynchroniseMissingTranslationKeys extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lunar:sync-missing-translation-keys {language?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add all of the missing translation keys for all languages or a single language';

    /**
     * @return void|null
     */
    public function handle()
    {
        $language = $this->argument('language') ?: false;

        try {
            // if we have a language, pass it in, if not the method will
            // automagically sync all languages
            $this->translation->saveMissingTranslations($language);

            $this->info(__('translation::translation.keys_synced'));
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
