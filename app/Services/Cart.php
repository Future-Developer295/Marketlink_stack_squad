<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class Cart
{
    protected string $sessionKey = 'cart';

    public function add(int $productId, int $quantity = 1): void
    {
        $product = Product::find($productId);

        if (! $product || ! $product->is_active || $product->stock_quantity < 1) {
            throw ValidationException::withMessages([
                'quantity' => 'This product is no longer available.'
            ]);
        }

        $cart = $this->raw();

        $newQuantity = ($cart[$productId] ?? 0) + max($quantity, 1);

        if ($newQuantity > $product->stock_quantity) {
            throw ValidationException::withMessages([
                'quantity' => 'Only ' . $product->stock_quantity . ' units are available. Check your basket quantity.'
            ]);
        }

        $cart[$productId] = $newQuantity;

        if ($cart[$productId] <= 0) {
            unset($cart[$productId]);
        }

        $this->save($cart);
    }

    public function update(int $productId, int $quantity): void
    {
        $product = Product::find($productId);
        $cart = $this->raw();

        if (! $product || ! $product->is_active || $product->stock_quantity < 1 || $quantity <= 0) {
            unset($cart[$productId]);
        } else {
            if ($quantity > $product->stock_quantity) {
                throw ValidationException::withMessages([
                    'quantity' => 'Only ' . $product->stock_quantity . ' units are available.'
                ]);
            }

            $cart[$productId] = $quantity;
        }

        $this->save($cart);
    }

    public function remove(int $productId): void
    {
        $cart = $this->raw();

        unset($cart[$productId]);

        $this->save($cart);
    }

    public function clear(): void
    {
        session()->forget($this->sessionKey);
    }

    public function raw(): array
    {
        return session($this->sessionKey, []);
    }

    public function count(): int
    {
        return count($this->raw());
    }

    public function items(): Collection
    {
        $raw = $this->raw();

        $products = Product::with('farmer.user')
            ->whereIn('id', array_keys($raw))
            ->get()
            ->keyBy('id');

        $items = collect($raw)
            ->map(function ($quantity, $productId) use ($products) {
                $product = $products->get((int) $productId);

                if (! $product || ! $product->is_active || $product->stock_quantity < 1) {
                    return null;
                }

                $quantity = min((int) $quantity, $product->stock_quantity);

                return [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $product->price * $quantity,
                ];
            })
            ->filter()
            ->values();

        $this->save(
            $items->mapWithKeys(
                fn($item) => [
                    $item['product']->id => $item['quantity']
                ]
            )->all()
        );

        return $items;
    }

    public function groupedByFarmer(): Collection
    {
        return $this->items()
            ->groupBy(fn($item) => $item['product']->farmer_id);
    }

    public function total(): float
    {
        return $this->items()->sum('subtotal');
    }

    protected function save(array $cart): void
    {
        session([
            $this->sessionKey => $cart
        ]);
    }
}
