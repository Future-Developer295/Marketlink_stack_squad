<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class Cart
{
    protected string $sessionKey = 'cart';

    public function add(int $productId, int $quantity = 1): void
    {
        $product = Product::find($productId);

        if (! $product) {
            return;
        }

        $cart = $this->raw();
        $newQuantity = ($cart[$productId] ?? 0) + max($quantity, 1);
        $cart[$productId] = min($newQuantity, max($product->stock_quantity, 0));

        if ($cart[$productId] <= 0) {
            unset($cart[$productId]);
        }

        $this->save($cart);
    }

    public function update(int $productId, int $quantity): void
    {
        $product = Product::find($productId);
        $cart = $this->raw();

        if (! $product || $quantity <= 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId] = min($quantity, max($product->stock_quantity, 0));
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
        return array_sum($this->raw());
    }

    public function items(): Collection
    {
        $raw = $this->raw();
        $products = Product::with('farmer.user')->whereIn('id', array_keys($raw))->get()->keyBy('id');

        return collect($raw)
            ->map(function ($quantity, $productId) use ($products) {
                $product = $products->get((int) $productId);

                if (! $product) {
                    return null;
                }

                return [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $product->price * $quantity,
                ];
            })
            ->filter()
            ->values();
    }

    public function groupedByFarmer(): Collection
    {
        return $this->items()->groupBy(fn ($item) => $item['product']->farmer_id);
    }

    public function total(): float
    {
        return $this->items()->sum('subtotal');
    }

    protected function save(array $cart): void
    {
        session([$this->sessionKey => $cart]);
    }
}
