<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Cart\StoreCartItemRequest;
use App\Http\Requests\Customer\Cart\UpdateCartItemRequest;
use App\Models\CartItem;
use App\Services\Customer\CartService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cart) {}

    public function index(): RedirectResponse
    {
        return redirect()->route('customer.orders.index', ['tab' => 'cart']);
    }

    public function store(StoreCartItemRequest $request): RedirectResponse
    {
        $this->cart->add(
            $request->user(),
            (int) $request->validated('product_id'),
            (int) $request->validated('quantity'),
            (string) ($request->validated('size') ?? ''),
        );

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Added to cart.')]);

        return back();
    }

    public function update(UpdateCartItemRequest $request, CartItem $cartItem): RedirectResponse
    {
        $this->cart->updateQuantity(
            $request->user(),
            $cartItem,
            (int) $request->validated('quantity')
        );

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Cart updated.')]);

        return back();
    }

    public function destroy(CartItem $cartItem): RedirectResponse
    {
        $this->cart->remove(request()->user(), $cartItem);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Removed from cart.')]);

        return back();
    }
}
