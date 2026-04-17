<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Order\StoreOrderRequest;
use App\Models\Order;
use App\Models\SmsMessage;
use App\Services\Customer\CartService;
use App\Services\Customer\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orders,
        private readonly CartService $cart,
    ) {}

    public function index(Request $request): Response
    {
        $tab = $request->query('tab');
        if (! in_array($tab, ['orders', 'tracking', 'cart'], true)) {
            $tab = 'orders';
        }

        $cartSummary = $this->cart->summary($request->user());

        return Inertia::render('customer/orders/Index', [
            'tab' => $tab,
            'orders' => $this->orders->paginateForCustomer($request->user()),
            'cart' => [
                'items' => $cartSummary['items']->values()->all(),
                'subtotal' => $cartSummary['subtotal'],
                'count' => $cartSummary['count'],
            ],
        ]);
    }

    public function show(Order $order): Response
    {
        return Inertia::render('customer/orders/Show', [
            'order' => $this->orders->findOwned(request()->user(), $order),
        ]);
    }

    public function store(StoreOrderRequest $request): RedirectResponse
    {
        if ($request->boolean('from_cart')) {
            $order = $this->orders->checkoutFromCart($request->user());
        } else {
            $order = $this->orders->placeQuickOrder(
                $request->user(),
                (int) $request->validated('product_id'),
                (int) $request->validated('quantity')
            );
        }

        SmsMessage::query()->create([
            'user_id' => $request->user()->id,
            'phone_number' => env('SMS_NOTIFY_PHONE', '+639661841984'),
            'message' => "New order placed by {$request->user()->name} ({$request->user()->phone_number}) - {$order->order_number}",
            'status' => 'pending',
        ]);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Order placed successfully.')]);

        return to_route('customer.orders.show', $order);
    }
}
