<?php

namespace Lunar\TranslationManager\Http\Livewire\Tables\Components\Columns;

use Lunar\LivewireTables\Components\Columns\BaseColumn;

class IconColumn extends BaseColumn
{

    /**
     * {@inheritDoc}
     */
    public function render()
    {
        return view('translation::columns.icons', [
            'record' => $this->record,
            'value' => $this->getValue(),
        ]);
    }
}
