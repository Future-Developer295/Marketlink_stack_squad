<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class Cart
{
    protected string $sessionKey = 'cart';

    /** null = not checked yet. */
    protected static ?bool $tableReady = null;

    protected function tableReady(): bool
    {
        return self::$tableReady ??= Schema::hasTable('cart_items');
    }

    /**
     * @param  int|null  $userId  Login event ke waqt Auth::id() null hota hai, isliye yahan pass karein.
     */
    public function __construct(protected ?int $userId = null)
    {
    }

    /** Cart ka malik: pass kiya gaya user, warna logged-in user. */
    protected function ownerId(): ?int
    {
        return $this->userId ?? Auth::id();
    }

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

        if (($userId = $this->ownerId()) && $this->tableReady()) {
            CartItem::where('user_id', $userId)->delete();
        }
    }

    /**
     * Login ke baad: database wali purani basket + guest session basket merge karo
     * aur dono ko sync kar do.
     */
    public function restoreSaved(): Collection
    {
        $userId = $this->ownerId();

        if (! $userId) {
            return collect();
        }

        $merged = $this->tableReady()
            ? CartItem::where('user_id', $userId)->pluck('quantity', 'product_id')->all()
            : [];

        foreach ($this->raw() as $productId => $quantity) {
            $merged[$productId] = ($merged[$productId] ?? 0) + (int) $quantity;
        }

        // save() session + database dono update karta hai.
        $this->save($merged);

        // items() unavailable products hata deta hai aur quantity stock tak cap karta hai.
        return $this->items();
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

        $clean = $items->mapWithKeys(
            fn ($item) => [$item['product']->id => $item['quantity']]
        )->all();

        // Sirf tab save karo jab kuch badla ho (har page view par DB write nahi).
        if ($clean != $raw) {
            $this->save($clean);
        }

        return $items;
    }

    public function groupedByFarmer(): Collection
    {
        return $this->items()
            ->groupBy(fn ($item) => $item['product']->farmer_id);
    }

    public function total(): float
    {
        return $this->items()->sum('subtotal');
    }

    protected function save(array $cart): void
    {
        session([$this->sessionKey => $cart]);

        $this->persist($cart);
    }

    /** Logged-in customer ki basket database mein likho, taake logout ke baad bhi rahe. */
    protected function persist(array $cart): void
    {
        $userId = $this->ownerId();

        if (! $userId || ! $this->tableReady()) {
            return;
        }

        try {
            DB::transaction(function () use ($userId, $cart) {
                if (empty($cart)) {
                    CartItem::where('user_id', $userId)->delete();
                    return;
                }

                CartItem::where('user_id', $userId)
                    ->whereNotIn('product_id', array_keys($cart))
                    ->delete();

                $now = now();

                CartItem::upsert(
                    collect($cart)->map(fn ($quantity, $productId) => [
                        'user_id' => $userId,
                        'product_id' => (int) $productId,
                        'quantity' => (int) $quantity,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ])->values()->all(),
                    ['user_id', 'product_id'],
                    ['quantity', 'updated_at']
                );
            });
        } catch (\Throwable $e) {
            report($e);
        }
    }
}