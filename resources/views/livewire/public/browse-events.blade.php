<div x-data="{ filtersOpen: false }">

    {{-- Page body --}}
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex gap-8">

            {{-- Sidebar (desktop) --}}
            <aside class="hidden w-60 shrink-0 lg:block">
                <div class="sticky top-20">
                    @include('livewire.public.partials.filters-sidebar', [
                        'categories' => $categories,
                        'activeFilterCount' => $activeFilterCount,
                    ])
                </div>
            </aside>

            {{-- Main content --}}
            <div class="min-w-0 flex-1">

                {{-- Mobile toolbar --}}
                <div class="mb-5 flex items-center justify-between lg:hidden">
                    <button @click="filtersOpen = true"
                        class="inline-flex items-center gap-2 border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:border-brand-300 hover:bg-brand-50 transition-all">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18M7 8h10M10 12h4" />
                        </svg>
                        Filters
                        @if ($activeFilterCount > 0)
                            <span
                                class="flex h-5 w-5 items-center justify-center bg-brand-600 text-xs font-bold text-white">
                                {{ $activeFilterCount }}
                            </span>
                        @endif
                    </button>

                    <select wire:model.live="sort"
                        class="rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-500/20 transition-colors cursor-pointer">
                        <option value="start_at">Upcoming first</option>
                        <option value="published_at">Newest</option>
                    </select>
                </div>

                {{-- Desktop sort + count --}}
                <div class="mb-5 hidden items-center justify-between lg:flex">
                    <p class="text-sm text-gray-500">
                        <span class="font-semibold text-gray-900">{{ $events->total() }}</span>
                        {{ Str::plural('event', $events->total()) }} found
                    </p>
                    <select wire:model.live="sort"
                        class="rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-500/20 transition-colors cursor-pointer">
                        <option value="start_at">Upcoming first</option>
                        <option value="published_at">Newest</option>
                    </select>
                </div>

                {{-- Mobile count --}}
                <p class="mb-4 text-sm text-gray-500 lg:hidden">
                    <span class="font-semibold text-gray-900">{{ $events->total() }}</span>
                    {{ Str::plural('event', $events->total()) }} found
                </p>

                {{-- Active filter chips --}}
                @if ($activeFilterCount > 0)
                    <div class="mb-5 flex flex-wrap gap-2">
                        @foreach ($selectedCategories as $slug)
                            <span
                                class="inline-flex items-center gap-1 bg-brand-50 border border-brand-200 px-3 py-1 text-xs font-medium text-brand-700">
                                {{ $categories->firstWhere('slug', $slug)?->name ?? $slug }}
                            </span>
                        @endforeach
                        @foreach ($selectedCities as $city)
                            <span
                                class="inline-flex items-center gap-1 bg-brand-50 border border-brand-200 px-3 py-1 text-xs font-medium text-brand-700">
                                {{ $city }}
                            </span>
                        @endforeach
                        @if ($selectedDate)
                            <span
                                class="inline-flex items-center gap-1 bg-brand-50 border border-brand-200 px-3 py-1 text-xs font-medium text-brand-700">
                                {{ ['today' => 'Today', 'tomorrow' => 'Tomorrow', 'this_week' => 'This week', 'this_month' => 'This month'][$selectedDate] ?? '' }}
                            </span>
                        @endif
                        <button wire:click="clearFilters"
                            class="text-xs text-gray-400 underline underline-offset-2 hover:text-gray-600 transition-colors">
                            Clear all
                        </button>
                    </div>
                @endif

                {{-- Event grid --}}
                @if ($events->isNotEmpty())
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                        @foreach ($events as $event)
                            @include('livewire.public.partials.event-card', [
                                'event' => $event,
                                'purchasedEventIds' => $purchasedEventIds,
                            ])
                        @endforeach
                    </div>

                    @if ($events->hasMorePages())
                        <div class="mt-10 flex justify-center">
                            <button wire:click="nextPage"
                                class="inline-flex items-center justify-center gap-3 rounded-full border border-gray-200 bg-white h-10 px-5 text-sm font-medium text-gray-700 text-nowrap hover:border-brand-300 hover:bg-brand-50 transition-[color,background] duration-200 focus-visible:outline focus-visible:outline-offset-1">
                                <svg wire:loading wire:target="nextPage" class="h-4 w-4 animate-spin text-brand-600"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                                </svg>
                                Load More Events
                            </button>
                        </div>
                    @endif
                @else
                    <div class="border border-dashed border-gray-200 bg-white py-24 text-center">
                        <svg class="mx-auto mb-4 h-12 w-12 text-gray-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-sm font-medium text-gray-500">No events match your filters.</p>
                        <p class="mt-1 text-xs text-gray-400">Try broadening your search or removing some filters.</p>
                        <button wire:click="clearFilters"
                            class="mt-4 inline-flex items-center justify-center gap-3 rounded-full bg-brand-600 h-10 px-5 text-sm font-semibold text-white text-nowrap hover:bg-brand-700 transition-[color,background] duration-200 focus-visible:outline focus-visible:outline-offset-1">
                            Clear Filters
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Mobile filter slide-over --}}
    <div x-show="filtersOpen" @click="filtersOpen = false" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 bg-gray-900/30 backdrop-blur-sm lg:hidden"
        style="display: none;">

        <div @click.stop x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="flex h-full w-80 flex-col bg-white shadow-2xl shadow-gray-200">

            <div class="flex items-center justify-between border-b border-gray-100 px-4 py-4">
                <h2 class="text-base font-semibold text-gray-900">Filters</h2>
                <button @click="filtersOpen = false"
                    class="flex h-8 w-8 items-center justify-center text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-4 py-4">
                @include('livewire.public.partials.filters-sidebar', [
                    'categories' => $categories,
                    'activeFilterCount' => $activeFilterCount,
                ])
            </div>

            <div class="border-t border-gray-100 p-4">
                <button @click="filtersOpen = false"
                    class="w-full bg-brand-600 py-3 text-sm font-semibold text-white hover:bg-brand-700 transition-colors">
                    Show {{ $events->total() }} {{ Str::plural('Event', $events->total()) }}
                </button>
            </div>
        </div>
    </div>

</div>
