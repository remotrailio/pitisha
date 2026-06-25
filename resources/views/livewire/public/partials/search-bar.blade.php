<x-search-input
    :form="false"
    :spinner="true"
    placeholder="Search events, experiences, safaris..."
    wire:model.live.debounce.300ms="search"
    class="mt-6 max-w-2xl"
    inputClass="h-12 rounded-xl pl-12 border-gray-200 bg-white shadow-sm focus:border-brand-500"
/>
