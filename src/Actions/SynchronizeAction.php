<?php

namespace Lunar\TranslationManager\Actions;

use Lunar\TranslationManager\Commands\SynchronizeTranslationsCommand;
use Lunar\TranslationManager\Helpers\TranslationScanner;
use Lunar\TranslationManager\Models\LanguageLine;

/**
 * Handles synchronization of translation lines.
 *
 * This class provides functionality to synchronize translation lines with the application's
 * source code. It scans the application for translation keys and updates the database accordingly.
 * This includes adding new translation lines and removing obsolete ones.
 */
class SynchronizeAction
{

    /**
     * Runs the synchronization process for the translations.
     *
     * This static method scans the application for translation keys and groups using the
     * `TranslationScanner::scan()` method. It then synchronizes these keys with the database.
     * It deletes obsolete `LanguageLine` entries from the database and creates new entries for
     * new translation keys found.
     *
     * If a `SynchronizeTranslationsCommand` object is provided, it is used to output detailed
     * information about the synchronization process, including the time taken for each key.
     *
     * @param SynchronizeTranslationsCommand|null $command An optional command object for detailed output.
     * @return array Returns an array containing counts of total and deleted translation lines.
     */
    public static function synchronize(SynchronizeTranslationsCommand $command = null): array
    {
        // Extract all translation groups, keys and text
        $groupsAndKeys = TranslationScanner::scan();

        $result = [];
        $result['total_count'] = 0;

        // Find and delete old LanguageLines that no longer exist in the translation files
        $result['deleted_count'] = LanguageLine::query()
            ->whereNotIn('group', array_column($groupsAndKeys, 'group'))
            ->orWhereNotIn('key', array_column($groupsAndKeys, 'key'))
            ->delete();

        // Create new LanguageLines for the groups and keys that don't exist yet
        foreach ($groupsAndKeys as $groupAndKey) {

            $startTime = microtime(true);

            $existingItem = LanguageLine::where('group', $groupAndKey['group'])
                ->where('key', $groupAndKey['key'])
                ->first();

            if (!$existingItem) {
                LanguageLine::create([
                    'group' => $groupAndKey['group'],
                    'key' => $groupAndKey['key'],
                    'text' => $groupAndKey['text'],
                ]);

                $result['total_count'] += 1;

                $runTime = number_format((microtime(true) - $startTime) * 1000, 2);
                $command?->components()->twoColumnDetail($groupAndKey['group'] . '.' . $groupAndKey['key'], "<fg=gray>{$runTime} ms</> <fg=green;options=bold>DONE</>");
            }
        }

        return $result;
    }

    /**
     * A convenience wrapper for the synchronize method.
     *
     * This method provides a simplified interface to the `synchronize` method. It does not require
     * any parameters and can be called directly to perform the synchronization process.
     *
     * @return array Returns the result of the `synchronize` method.
     */
    public static function run(): array
    {
        return static::synchronize();
    }
}
