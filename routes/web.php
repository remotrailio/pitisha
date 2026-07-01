<?php

use App\Http\Controllers\DownloadTicketsPdfController;
use App\Http\Controllers\OrganizerOnboardingController;
use App\Http\Controllers\TicketVerificationController;
use App\Livewire\My\MyOrders;
use App\Livewire\My\MyProfile;
use App\Livewire\My\MyTickets;
use App\Livewire\Public\BecomeOrganizer;
use App\Livewire\Public\BrowseEvents;
use App\Livewire\Public\CheckoutStart;
use App\Livewire\Public\EventDetails;
use App\Livewire\Public\Homepage;
use App\Livewire\Public\OrderConfirmation;
use App\Livewire\Public\OrderStatus;
use App\Livewire\Public\OrganizerProfile;
use App\Livewire\Public\Solutions;
use App\Models\Category;
use Illuminate\Support\Facades\Route;

Route::get('/', Homepage::class)->name('home');
Route::get('/solutions', Solutions::class)->name('solutions');
Route::view('/hero-image', 'public.hero-image')->name('hero-image');
Route::view('/why', 'public.why')->name('why');
Route::get('/for-organizers', BecomeOrganizer::class)->name('organizers.become');
Route::get('/events', BrowseEvents::class)->name('events.index');
Route::redirect('/browse', '/events');
Route::get('/events/{slug}', EventDetails::class)->name('events.show');
Route::get('/organizers/{slug}', OrganizerProfile::class)->name('organizers.show');
Route::get('/categories/{category}', function (Category $category) {
    abort_unless($category->is_active, 404);
    return redirect()->route('events.index', ['category' => $category->slug]);
})->name('categories.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/my/tickets', MyTickets::class)->name('my.tickets');
    Route::get('/my/orders', MyOrders::class)->name('my.orders');
    Route::get('/my/profile', MyProfile::class)->name('my.profile');
});

Route::get('/checkout/{slug}', CheckoutStart::class)->name('checkout.start');
Route::get('/orders/{uuid}/confirmation', OrderConfirmation::class)->name('orders.confirmation');
Route::get('/orders/{uuid}/status', OrderStatus::class)->name('orders.status');
Route::get('/orders/{uuid}/tickets/download', DownloadTicketsPdfController::class)->name('orders.tickets.download');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/become-organizer', [OrganizerOnboardingController::class, 'show'])
        ->name('organizer.onboard');
    Route::post('/become-organizer', [OrganizerOnboardingController::class, 'store'])
        ->name('organizer.onboard.store');
});

// Check-in: no auth required — ticket_code is a random 12-char alphanumeric secret
Route::get('/check-in/{ticket_code}', TicketVerificationController::class)
    ->name('tickets.verify');

require __DIR__.'/auth.php';
