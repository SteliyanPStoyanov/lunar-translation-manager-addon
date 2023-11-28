<?php

namespace Lunar\TranslationManager\Http\Livewire\Pages;

use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Lunar\Hub\Http\Livewire\Traits\Notifies;
use Lunar\Hub\Models\SavedSearch;
use Lunar\LivewireTables\Components\Columns\TextColumn;
use Lunar\LivewireTables\Components\Filters\SelectFilter;
use Lunar\LivewireTables\Components\Table;
use Lunar\TranslationManager\Http\Livewire\Tables\Components\Columns\IconColumn;
use Lunar\TranslationManager\Tables\TranslationsTableBuilder;
use Lunar\TranslationManager\Models\LanguageLine;

class TranslationTable extends Table
{
    use Notifies;

    /**
     * {@inheritDoc}
     */
    protected $tableBuilderBinding = TranslationsTableBuilder::class;

    /**
     * {@inheritDoc}
     */
    public bool $searchable = true;

    /**
     * {@inheritDoc}
     */
    public bool $canSaveSearches = true;

    /**
     * {@inheritDoc}
     */
    protected $listeners = [
        'saveSearch' => 'handleSaveSearch'
    ];


    /**
     * @param $id
     * @param $code
     * @return void
     */
    public function addTrans($id, $code)
    {
        $this->emit('addTrans', $id, $code);
    }

    /**
     * @param $id
     * @param $code
     * @return void
     */
    public function updateTrans($id, $code)
    {
        $this->emit('updateTrans', $id, $code);
    }


    /**
     * {@inheritDoc}
     */
    public function build()
    {
        $groups = LanguageLine::get()->groupBy('group')->toArray();

        $groupsArray = [];
        foreach ($groups as $key => $group) {
            $groupsArray[$key] = $key;
        }

        $this->tableBuilder->addFilter(
            SelectFilter::make('group')->options(function () use ($groupsArray) {
                $statuses = collect($groupsArray);
                return collect([
                    null => 'All groups',
                ])->merge($statuses);
            })->query(function ($filters, $query) {
                $value = $filters->get('group');

                if ($value) {
                    $query->where('group', $value);
                }
            })
        );
        $baseAddColumns = [];
        foreach (config('translation-manager.available_locales') as $locale) {
            $localeCode = $locale['code'];
            $baseAddColumns[] = IconColumn::make('text' . $locale['code'], function ($record) use ($localeCode) {
                return ['code' => $localeCode, 'record' => $record];
            })->heading(
                $locale['code']
            );
        }
        $baseColumns = [

            TextColumn::make('group', function ($record) {
                return $record->group;
            })->sortable()->heading(
                __('translation::tables.headings.title')
            ),
            TextColumn::make('key', function ($record) {
                return $record->key;
            })->sortable()->heading(
                __('translation::tables.headings.key')
            ),
            TextColumn::make('preview-in-your-lang', function ($record) {
                return __($record->group . '.' . $record->key);
            })->heading(
                __('translation::tables.headings.preview-in-your-lang', ['lang' => app()->getLocale()])
            )
        ];
        $this->tableBuilder->baseColumns(array_merge($baseColumns, $baseAddColumns));

    }

    /**
     * Remove a saved search record.
     *
     * @param int $id
     * @return void
     */
    public function deleteSavedSearch($id)
    {
        SavedSearch::destroy($id);

        $this->resetSavedSearch();

        $this->notify(
            __('translation::notifications.saved_searches.deleted')
        );
    }

    /**
     * Save a search.
     *
     * @return void
     * @throws ValidationException
     */
    public function saveSearch()
    {
        $this->validateOnly('savedSearchName', [
            'savedSearchName' => 'required',
        ]);

        auth()->getUser()->savedSearches()->create([
            'name' => $this->savedSearchName,
            'term' => $this->query,
            'component' => $this->getName(),
            'filters' => $this->filters,
        ]);

        $this->notify(__('translation::notifications.saved_searches.saved'));

        $this->savedSearchName = null;

        $this->emit('savedSearch');
    }

    /**
     * Return the saved searches available to the table.
     */
    public function getSavedSearchesProperty(): Collection
    {

        return auth()->getUser()->savedSearches()->whereComponent(
            $this->getName()
        )->get()->map(function ($savedSearch) {
            return [
                'key' => $savedSearch->id,
                'label' => $savedSearch->name,
                'filters' => $savedSearch->filters,
                'query' => $savedSearch->term,
            ];
        });
    }

    /**
     * {@inheritDoc}
     */
    public function getData()
    {

        $filters = $this->filters;
        $query = $this->query;

        if ($this->savedSearch) {
            $search = $this->savedSearches->first(function ($search) {
                return $search['key'] == $this->savedSearch;
            });

            if ($search) {
                $filters = $search['filters'];
                $query = $search['query'];
            }
        }

        return $this->tableBuilder
            ->searchTerm($query)
            ->queryStringFilters($filters)
            ->perPage($this->perPage)
            ->sort(
                $this->sortField ?: 'created_at',
                $this->sortDir ?: 'desc',
            )
            ->getData();
    }

}
