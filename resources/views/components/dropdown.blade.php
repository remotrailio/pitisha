@props(['align' => 'right', 'width' => '56', 'contentClasses' => ''])

@php
$alignmentClasses = match ($align) {
    'left'  => 'origin-top-left start-0',
    'top'   => 'origin-top',
    default => 'origin-top-right end-0',
};

$widthClass = match ($width) {
    '48' => 'w-48',
    '56' => 'w-56',
    '64' => 'w-64',
    '72' => 'w-72',
    default => $width,
};
@endphp

<div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = !open">
        {{ $trigger }}
    </div>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 translate-y-1 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-1 scale-95"
         class="absolute z-50 mt-2 {{ $widthClass }} {{ $alignmentClasses }} rounded-2xl border border-gray-100 bg-white shadow-xl shadow-gray-200/60"
         style="display: none;"
         @click="open = false">
        <div class="p-1 {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>
