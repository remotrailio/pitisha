<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Mail\Markdown;
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
            $priorityNames = ['Music', 'Tech', 'Sports', 'Conferences'];

            // Fetch whichever priority categories actually exist and are active.
            $priority = \App\Models\Category::withCount(['events' => fn ($q) => $q->where('status', 'published')])
                ->where('is_active', true)
                ->whereIn('name', $priorityNames)
                ->get()
                ->sortBy(fn ($cat) => array_search($cat->name, $priorityNames))
                ->values();

            // Fill remaining slots with top categories by event count, excluding those already selected.
            $needed = 4 - $priority->count();
            $filler = $needed > 0
                ? \App\Models\Category::withCount(['events' => fn ($q) => $q->where('status', 'published')])
                    ->where('is_active', true)
                    ->whereNotIn('name', $priorityNames)
                    ->orderByDesc('events_count')
                    ->limit($needed)
                    ->get()
                : collect();

            View::share('__topCategories', $priority->concat($filler));
        } catch (\Throwable) {
            View::share('__topCategories', collect());
        }
    }
}
