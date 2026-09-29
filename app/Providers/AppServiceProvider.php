<?php

namespace App\Providers;

use App\Services\Cart;
use Illuminate\Auth\Events\Login;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Bootstrap 5 markup for $paginator->links() (used by the admin/farmer dashboard).
        Paginator::useBootstrapFive();

        // When a customer logs in: bring back the basket they saved earlier
        // (merged with any guest basket) and queue the "Welcome back" popup.
        Event::listen(Login::class, function (Login $event) {
            $user = $event->user;

            if (($user->role ?? null) !== 'customer') {
                return;
            }

            try {
                $items = (new Cart($user->id))->restoreSaved();
            } catch (\Throwable $e) {
                report($e);
                $items = collect();
            }

            session(['welcome_back' => [
                'name' => explode(' ', trim((string) $user->name))[0] ?: 'there',
                'items' => $items->map(fn ($item) => [
                    'name' => $item['product']->name,
                    'quantity' => $item['quantity'],
                    'unit' => $item['product']->unit,
                    'image' => $item['product']->imageUrl(),
                    'subtotal' => $item['subtotal'],
                ])->values()->all(),
                'total' => $items->sum('subtotal'),
            ]]);
        });
    }
}
