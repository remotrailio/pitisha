<?php

namespace App\Providers;

use App\Enums\NavCategory;
use App\Models\Setting;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        View::replaceNamespace('mail', app(Markdown::class)->htmlComponentPaths());

        if ($override = config('mail.to_override')) {
            Mail::alwaysTo($override);
        }

        // Guard against fresh environments where migrations have not run yet.
        // package:discover and other artisan bootstrap commands hit boot() before
        // the database is ready, so we fall back to an empty Setting instance.
        try {
            View::share('__settings', app_settings());
        } catch (\Throwable) {
            View::share('__settings', new Setting());
        }

        try {
            // Cache plain arrays only — Eloquent models cannot safely survive unserialize() during early boot.
            $topCategories = Cache::remember('nav.top_categories', now()->addHour(), function () {
                $priorityNames = NavCategory::names();

                // Priority enum categories that are active and have at least one published event.
                $priority = \App\Models\Category::withCount(['events' => fn ($q) => $q->where('status', 'published')])
                    ->where('is_active', true)
                    ->whereIn('name', $priorityNames)
                    ->get()
                    ->filter(fn ($cat) => $cat->events_count > 0)
                    ->sortBy(fn ($cat) => array_search($cat->name, $priorityNames))
                    ->values();

                // Fill remaining slots with other active categories that have events.
                $needed = 4 - $priority->count();
                $filler = $needed > 0
                    ? \App\Models\Category::withCount(['events' => fn ($q) => $q->where('status', 'published')])
                        ->where('is_active', true)
                        ->whereNotIn('name', $priorityNames)
                        ->having('events_count', '>', 0)
                        ->orderByDesc('events_count')
                        ->limit($needed)
                        ->get()
                    : collect();

                return $priority->concat($filler)
                    ->map(fn ($cat) => ['name' => $cat->name, 'slug' => $cat->slug])
                    ->all();
            });

            // Cast each plain array back to an object so blade can use $cat->name / $cat->slug.
            View::share('__topCategories', collect($topCategories)->map(fn ($item) => (object) $item));
        } catch (\Throwable) {
            View::share('__topCategories', collect());
        }
    }
}
