@props([
    'placeholder' => 'Search events...',
    'inputClass'  => '',
    'form'        => true,
    'spinner'     => false,
])

@php
    $hasModel = $attributes->whereStartsWith('wire:model')->isNotEmpty()
             || $attributes->whereStartsWith('x-model')->isNotEmpty();

    $wrapClass = $attributes->get('class', '');
    $inputAttrs = $attributes->except('class');
@endphp

@if ($form)
    <form action="{{ route('events.index') }}" method="GET" class="relative {{ $wrapClass }}">
@else
    <div class="relative {{ $wrapClass }}">
@endif

    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
        class="absolute left-3.5 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400 pointer-events-none">
        <circle cx="11" cy="11" r="8" />
        <path d="m21 21-4.3-4.3" />
    </svg>

    <input
        type="search"
        @if (!$hasModel) name="q" value="{{ request('q') }}" @endif
        placeholder="{{ $placeholder }}"
        {{ $inputAttrs->merge(['class' => "w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-full text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 hover:border-gray-300 transition-all duration-150 {$inputClass}"]) }}
    >

    @if ($spinner)
        <div wire:loading wire:target="{{ $inputAttrs->whereStartsWith('wire:model')->first() }}"
            class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
            <svg class="h-4 w-4 animate-spin text-blue-400" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
            </svg>
        </div>
    @endif

@if ($form)
    </form>
@else
    </div>
@endif
