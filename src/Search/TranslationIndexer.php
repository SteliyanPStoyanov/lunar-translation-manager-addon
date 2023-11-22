<?php

namespace Lunar\TranslationManager\Search;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Lunar\Search\ScoutIndexer;

class TranslationIndexer extends ScoutIndexer
{
    public function getSortableFields(): array
    {
        return [];
    }

    public function getFilterableFields(): array
    {
        return [];
    }

    public function makeAllSearchableUsing(Builder $query): Builder
    {
        return $query;
    }

    public function toSearchableArray(Model $model): array
    {
        // Do this here so other additions to the data appear under the attributes,
        // more of a vanity thing than anything else.

        $data = array_merge([
            'id' => $model->id,
            'key' => $model->key,
            'group ' => $model->group,
            'text_en' => $model->text["en"] ?? null,
            'text_bg' => $model->text["bg"] ?? null
        ], $this->mapSearchableAttributes($model));

        return $data;
    }
}
