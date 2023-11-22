<div xmlns:x-hub="http://www.w3.org/1999/html">
    <span class="">
        @if(in_array($value['code'], array_keys($value['record']->text)) === true && !empty($value['record']->text[$value['code']]))
            <button type="button" wire:click.prevent="updateTrans({{$value['record']->id}} , '{{$value['code']}}')">
            {!! \Lunar\Hub\LunarHub::icon('check-circle', 'lt-text-green-600') !!}
            </button>
        @else
            <button type="button"
                    wire:click.prevent="addTrans({{$value['record']->id}} , '{{$value['code']}}')">
                 {!! \Lunar\Hub\LunarHub::icon('x-circle', 'lt-text-red-600') !!}
            </button>

        @endif

    </span>

</div>
