<?php

namespace App\Filament\Widgets;

use App\Models\Organizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class TopOrganizersWidget extends TableWidget
{
    protected static ?int $sort = 5;

    protected static ?string $heading = 'Top Organizers';

    protected int|string|array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Organizer::query()
                    ->select('organizers.*')
                    ->selectRaw('(SELECT COUNT(DISTINCT e.id) FROM events e WHERE e.organizer_id = organizers.id) as events_count')
                    ->selectRaw('(SELECT COUNT(t.id) FROM tickets t INNER JOIN orders o ON o.id = t.order_id INNER JOIN events e ON e.id = o.event_id WHERE e.organizer_id = organizers.id) as tickets_count')
                    ->selectRaw('(SELECT COALESCE(SUM(o.total - o.fees), 0) FROM orders o INNER JOIN events e ON e.id = o.event_id WHERE e.organizer_id = organizers.id AND o.payment_status = ?) as revenue', ['paid'])
                    ->orderByDesc('revenue')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('display_name')
                    ->label('Organizer')
                    ->searchable(),

                TextColumn::make('events_count')
                    ->label('Events')
                    ->state(fn (Organizer $record): int => (int) $record->events_count)
                    ->alignCenter(),

                TextColumn::make('tickets_count')
                    ->label('Tickets Sold')
                    ->state(fn (Organizer $record): int => (int) $record->tickets_count)
                    ->alignCenter(),

                TextColumn::make('revenue')
                    ->label('Revenue')
                    ->state(fn (Organizer $record): string => 'KES ' . number_format((float) $record->revenue, 2))
                    ->alignEnd(),
            ])
            ->paginated(false);
    }
}
