<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateEventCategoriesToPivotCommand extends Command
{
    protected $signature = 'app:migrate-event-categories
                            {--dry-run : Preview what would be inserted without making changes}';

    protected $description = 'Copy existing events.category_id values into the category_event pivot table';

    public function handle(): int
    {
        $isDry = $this->option('dry-run');

        $events = DB::table('events')
            ->whereNotNull('category_id')
            ->select('id', 'category_id')
            ->get();

        if ($events->isEmpty()) {
            $this->info('No events with a category_id found. Nothing to migrate.');
            return self::SUCCESS;
        }

        $this->info("Found {$events->count()} event(s) with a category_id.");

        if ($isDry) {
            $this->table(['event_id', 'category_id'], $events->map(fn ($e) => [$e->id, $e->category_id]));
            $this->warn('Dry run — no changes made.');
            return self::SUCCESS;
        }

        $rows = $events->map(fn ($e) => [
            'event_id'    => $e->id,
            'category_id' => $e->category_id,
            'created_at'  => now(),
            'updated_at'  => now(),
        ])->all();

        // Insert, ignoring any rows already in the pivot to make this safe to re-run.
        DB::table('category_event')->insertOrIgnore($rows);

        $this->info("Inserted {$events->count()} row(s) into category_event.");
        $this->line('Run this before dropping the category_id column from events.');

        return self::SUCCESS;
    }
}
