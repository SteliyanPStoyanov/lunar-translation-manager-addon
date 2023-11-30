<div class="flex-col space-y-4">
    <div class="flex items-center justify-between">
        <strong class="text-lg font-bold md:text-2xl">
            {{ __('translation::common.title') }}
        </strong>
        <div class="text-right">
            <x-hub::button wire:click.prevent="synchronize">
                {{ __('translation::common.synchronize') }}
            </x-hub::button>
        </div>
    </div>
    @livewire('translations.admin.translation.table')
    @if($this->showCreateForm)
        <x-hub::modal.dialog wire:model="showCreateForm">
            <x-slot name="title">
                {{ __('translation::catalogue.translation.modal.title') }}
            </x-slot>
            <x-slot name="content">
                <div class="relative">
                    @if($this->record)
                        <div class="border border-gray-300 bg-gray-100 rounded-b relative mb-[10px] p-1">
                            <div class="w-full  relative py-3 px-3">
                                <div class="flex absolute w-full top-0 left-0 right-0 items-center">
                                    <span class="block border-t border-gray-300 flex-1"></span>
                                    <h2>{{ __('translation::common.preview') }}</h2>
                                    <span class="block border-t border-gray-300 flex-1"></span>
                                </div>
                            </div>
                            <div>
                                {{ trans($this->record->group . '.' . $this->record->key) }}
                            </div>
                        </div>
                    @endif
                    <div class="mt-4">
                        <x-hub::input.group :label="__('translation::inputs.translate',[ 'value' => $this->transCode])" for="translated" :error="$errors->first('translated')" required="required">
                            <x-hub::input.text id="translated" wire:model="translated" :error="$errors->first('translated')"/>
                        </x-hub::input.group>
                    </div>
                </div>
            </x-slot>
            <x-slot name="footer">
                <x-hub::button type="button"  wire:click="saveTranslation" >
                    {{ __('translation::catalogue.save.btn') }}
                </x-hub::button>
            </x-slot>
        </x-hub::modal.dialog>
    @endif
</div>
