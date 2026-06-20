<div>
    {{-- Hero --}}
    @include('components.hero')

    {{-- Categories --}}
    {{-- @if ($categories->isNotEmpty())
        <section class="py-12 bg-white border-b border-slate-100">
            <div class="mx-auto max-w-360 px-4 sm:px-6 lg:px-8">
                <h2 class="text-lg font-semibold text-slate-700 mb-6">Browse by Category</h2>
                <div class="flex flex-wrap gap-3">
                    @foreach ($categories as $cat)
                        <a href="{{ route('events.index', ['selectedCategories[]' => $cat->slug]) }}"
                            class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:border-blue-400 hover:text-blue-600 hover:bg-blue-50 transition-all">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif --}}

    {{-- Upcoming Events --}}
    @if ($upcoming->isNotEmpty())
        <section class="py-16 bg-white">
            <div class="mx-auto max-w-360 px-4 sm:px-6 lg:px-8">
                <div class="mb-8 flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-slate-900">Upcoming Events</h2>
                    <a href="{{ route('events.index', ['sort' => 'start_at']) }}"
                        class="hidden sm:inline-flex items-center justify-center gap-2 whitespace-nowrap px-4 py-2 text-sm font-medium text-slate-700 transition-all hover:border-blue-300 hover:text-blue-600 h-9">
                        View More
                    </a>
                </div>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($upcoming as $event)
                        @include('livewire.public.partials.event-card', ['event' => $event])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Become an Organizer CTA --}}
    {{-- @auth
        @if (auth()->user()->isOrganizer() || auth()->user()->isAdmin())
        @else
            @include('livewire.public.partials.organizer-cta')
        @endif
    @else
        @include('livewire.public.partials.organizer-cta')
    @endauth --}}
</div>
