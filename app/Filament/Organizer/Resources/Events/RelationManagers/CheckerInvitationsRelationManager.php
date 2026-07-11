<?php

namespace App\Filament\Organizer\Resources\Events\RelationManagers;

use App\Mail\CheckerInvitation;
use App\Models\EventCheckerInvitation;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;

class CheckerInvitationsRelationManager extends RelationManager
{
    protected static string $relationship = 'checkerInvitations';

    protected static ?string $title = 'Invitations';

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('email')
                    ->searchable(),

                TextColumn::make('status')
                    ->badge()
                    ->getStateUsing(fn (EventCheckerInvitation $record) => match (true) {
                        $record->accepted_at !== null                          => 'Accepted',
                        $record->created_at->addDays(7)->isPast()             => 'Expired',
                        default                                                => 'Pending',
                    })
                    ->color(fn (string $state) => match ($state) {
                        'Accepted' => 'success',
                        'Expired'  => 'danger',
                        default    => 'warning',
                    }),

                TextColumn::make('accepted_at')
                    ->label('Accepted')
                    ->since()
                    ->placeholder('—'),

                TextColumn::make('created_at')
                    ->label('Sent')
                    ->since()
                    ->sortable(),
            ])
            ->headerActions([
                Action::make('send_invite')
                    ->label('Send Invite')
                    ->schema([
                        TextInput::make('email')
                            ->label('Staff email address')
                            ->email()
                            ->required()
                            ->placeholder('staff@example.com'),
                    ])
                    ->action(function (array $data): void {
                        $email = $data['email'];
                        $event = $this->getOwnerRecord();

                        $user = User::where('email', $email)->first();

                        if (! $user) {
                            Notification::make()
                                ->title('No account found')
                                ->body('No user with that email address has an account.')
                                ->danger()
                                ->send();
                            return;
                        }

                        if ($event->organizer?->user_id === $user->id) {
                            Notification::make()
                                ->title('Not needed')
                                ->body("{$user->name} is the organiser of this event and already has check-in access.")
                                ->warning()
                                ->send();
                            return;
                        }

                        if ($event->checkers()->where('user_id', $user->id)->exists()) {
                            Notification::make()
                                ->title('Already a checker')
                                ->body("{$user->name} is already a checker for this event.")
                                ->warning()
                                ->send();
                            return;
                        }

                        // Cancel any existing pending invite for this email before creating a new one
                        $event->checkerInvitations()
                            ->where('email', $email)
                            ->whereNull('accepted_at')
                            ->delete();

                        $invitation = $event->checkerInvitations()->create(['email' => $email]);

                        Mail::to($email)->send(new CheckerInvitation($invitation->load('event.organizer')));

                        Notification::make()
                            ->title('Invitation sent')
                            ->body("An invite has been emailed to {$email}.")
                            ->success()
                            ->send();
                    }),
            ])
            ->recordActions([
                Action::make('resend')
                    ->label('Resend')
                    ->icon('heroicon-o-arrow-path')
                    ->visible(fn (EventCheckerInvitation $record) => $record->accepted_at === null)
                    ->action(function (EventCheckerInvitation $record): void {
                        // Fresh token on resend
                        $record->update(['token' => \Illuminate\Support\Str::random(48)]);
                        Mail::to($record->email)->send(new CheckerInvitation($record->load('event.organizer')));

                        Notification::make()
                            ->title('Invitation resent')
                            ->success()
                            ->send();
                    }),

                DeleteAction::make()
                    ->label('Cancel')
                    ->visible(fn (EventCheckerInvitation $record) => $record->accepted_at === null),
            ]);
    }
}
