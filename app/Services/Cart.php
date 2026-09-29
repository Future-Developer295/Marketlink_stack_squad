<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class Cart
{
    protected string $sessionKey = 'cart';

    /** null = not checked yet. Avoids a crash (and repeated queries) if `php artisan migrate` was not run. */
    protected static ?bool $tableReady = null;

    protected function tableReady(): bool
    {
        return self::$tableReady ??= Schema::hasTable('cart_items');
    }

    /**
     * @param  int|null  $userId  Owner of the saved basket. Defaults to the logged-in user.
     *                            (During the Login event Auth::id() is still null, so it can be passed in.)
     */
    public function __construct(protected ?int $userId = null)
    {
    }

    public function add(int $productId, int $quantity = 1): void
    {
        $product = Product::find($productId);

        if (! $product || ! $product->is_active || $product->stock_quantity < 1) {
            throw ValidationException::withMessages(['quantity' => 'This product is no longer available.']);
        }

        $cart = $this->raw();
        $newQuantity = ($cart[$productId] ?? 0) + max($quantity, 1);
        if ($newQuantity > $product->stock_quantity) {
            throw ValidationException::withMessages(['quantity' => 'Only '.$product->stock_quantity.' units are available. Check your basket quantity.']);
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
                throw ValidationException::withMessages(['quantity' => 'Only '.$product->stock_quantity.' units are available.']);
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
     * Called right after a customer logs in: merge the basket they saved last time
     * (database) with anything they added as a guest (session), and keep both in sync.
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

        session([$this->sessionKey => $merged]);

        // items() drops unavailable products, caps quantities to stock, then saves.
        return $this->items();
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

        $this->save($items->mapWithKeys(fn ($item) => [$item['product']->id => $item['quantity']])->all());

        return $items;
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

        $this->persist($cart);
    }

    protected function ownerId(): ?int
    {
        return $this->userId ?? Auth::id();
    }

    /**
     * Mirror the session basket into the database for logged-in customers.
     */
    protected function persist(array $cart): void
    {
        $userId = $this->ownerId();

        if (! $userId || ! $this->tableReady()) {
            return;
        }

        $validIds = Product::whereIn('id', array_keys($cart))->pluck('id')->all();
        $cart = array_intersect_key($cart, array_flip($validIds));

        CartItem::where('user_id', $userId)->whereNotIn('product_id', array_keys($cart))->delete();

        if ($cart === []) {
            return;
        }

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
            ['quantity', 'updated_at'],
        );
    }
}
