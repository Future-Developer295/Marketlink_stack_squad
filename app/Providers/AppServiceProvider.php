<?php

namespace App\Providers;

use App\Models\FarmerProfile;
use App\Services\Cart;
use Illuminate\Auth\Events\Login;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        View::composer(['Dashboard._master', 'Dashboard.Farmer._master'], function ($view) {
            $user = auth()->user();
            $avatarUrl = null;

            if ($user && $user->role === 'farmer') {
                $image = FarmerProfile::where('user_id', $user->id)->value('farmer_image');

                if ($image && is_file(public_path('farmer_images/' . basename($image)))) {
                    $avatarUrl = asset('farmer_images/' . basename($image));
                }
            }

            $view->with('mlAvatarUrl', $avatarUrl);
        });

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
