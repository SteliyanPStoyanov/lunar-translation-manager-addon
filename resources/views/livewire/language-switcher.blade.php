<div>
    <div class="mr-3" x-data="{ isOpen: false }"
    >
        <button @class([ 'ml-4 block hover:opacity-75', 'pt-0' => $showFlags, ])
                id="filament-language-switcher"
                x-on:mouseover="isOpen = true">
            <span @class([
                    'flex items-center justify-center rounded-full bg-cover bg-center',
                    'w-8 h-8 bg-gray-200 dark:bg-gray-900' => $showFlags,
                    'w-[2.3rem] h-[2.3rem] bg-[#030712]' => !$showFlags,
                ]) >
            <span class="opacity-100">
                @if ($showFlags)
                    {{ try_svg('flag-1x1-'.$currentLanguage['flag'], 'rounded-full w-6 h-6') }}
                @else
                    <x-hub::icon ref="heroicon-o-languag" class="w-5 h-5"/>
                @endif
            </span>
            </span>
        </button>

        <div x-ref="panel" x-show="isOpen" x-cloak x-on:mouseleave="isOpen = false"
             x-transition:enter-start="opacity-0 scale-95" x-transition:leave-end="opacity-0 scale-95"
             class="ffi-dropdown-panel absolute z-10 bg-gray- divide-y divide-gray-100 rounded-lg bg-white shadow-lg ring-1 ring-gray-950/5 transition dark:divide-white/5 dark:bg-gray-900 dark:ring-white/10 max-w-[14rem]"
             style="top: 50px;">
            <div class="p-1">
                <ul>
                    @foreach ($otherLanguages as $language)
                        <li class="p-0">
                            @php $isCurrent = $currentLanguage['code'] === $language['code']; @endphp
                            <a @class([
                            'group flex w-full cursor-pointer items-center whitespace-nowrap rounded-md p-2 text-sm outline-none text-gray-500 dark:text-gray-200',
                            'hover:bg-gray-50 focus:bg-gray-50 dark:hover:bg-white/5 dark:focus:bg-white/5 hover:text-gray-700 focus:text-gray-500 dark:hover:text-gray-200 dark:focus:text-gray-400' => !$isCurrent,
                            'cursor-default bg-gray-200' => $isCurrent,
                        ])
                               @if (!$isCurrent)
                                   @php $langCode = $language['code']; @endphp
                                   wire:click.prevent="switch('{{$langCode}}')"
                                @endif >
                       <span class="text-sm font-medium flex w-full">
                            @if ($showFlags)
                               {{ try_svg('flag-4x3-'.$language['flag'], 'w-4 h-4') }}
                               <span class="ml-2">{{ $language['name'] }}</span>
                           @else
                               <span @class(['font-semibold' => $isCurrent])>{{ str($language['code'])->upper()->value() . " - {$language['name']}" }}</span>
                           @endif
                        </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
