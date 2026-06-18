<section class="relative py-24 md:py-32" style="background-image: linear-gradient(rgba(0,0,0,0.50), rgba(0,0,0,0.50)), url('https://images.unsplash.com/photo-1523805009345-7448845a9e53?w=1600'); background-size: cover; background-position: center;">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-3xl text-center text-white">
            <h1 class="mb-6 font-heading text-4xl font-bold tracking-tight md:text-6xl">
                Discover Amazing Events &amp; Experiences in Kenya
            </h1>

            <p class="mb-8 text-xl text-white/90 md:text-2xl">
                From safaris to music festivals, explore the best events and create unforgettable memories
            </p>

            <div class="relative mx-auto mb-6">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3" />
                    </svg>
                </div>
                <form action="{{ route('events.index') }}" method="GET">
                    <input type="search" name="q" placeholder="Search events, experiences, safaris..."
                        class="flex w-full rounded-xl border border-slate-200 bg-white px-3 pl-12 h-12 text-base text-slate-900 placeholder-slate-400 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                </form>
            </div>

            @if ($heroCategories->isNotEmpty())
                <div class="flex flex-wrap items-center justify-center gap-2">
                    @foreach ($heroCategories as $cat)
                        <a href="{{ route('events.index', ['selectedCategories[]' => $cat->slug]) }}"
                            class="rounded-lg border border-white/30 bg-white/20 px-4 py-2 text-sm font-medium text-white backdrop-blur-sm transition-all hover:bg-white/30">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>
