<?php

namespace App\Filament\Organizer\Resources\Events\RelationManagers;

use App\Mail\CheckerInvitation;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\DetachAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;

class CheckersRelationManager extends RelationManager
{
    protected static string $relationship = 'checkers';

    protected static ?string $inverseRelationship = 'checkerEvents';

    protected static ?string $title = 'Active Checkers';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('email'),
                TextColumn::make('pivot.created_at')
                    ->label('Added')
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

                        // Cancel any existing pending invite before creating a fresh one
                        $event->checkerInvitations()
                            ->where('email', $email)
                            ->whereNull('accepted_at')
                            ->delete();

                        $invitation = $event->checkerInvitations()->create(['email' => $email]);

                        Mail::to($email)->send(new CheckerInvitation($invitation->load('event.organizer')));

                        Notification::make()
                            ->title('Invitation sent')
                            ->body("An invite has been emailed to {$email}. They will appear here once they accept.")
                            ->success()
                            ->send();
                    }),
            ])
            ->recordActions([
                DetachAction::make()
                    ->label('Revoke')
                    ->modalHeading('Revoke checker access')
                    ->modalDescription('This person will no longer be able to check in attendees for this event.')
                    ->modalSubmitActionLabel('Yes, revoke'),
            ]);
    }
}
