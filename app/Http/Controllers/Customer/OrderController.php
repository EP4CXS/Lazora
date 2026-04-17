<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Order\StoreOrderRequest;
use App\Models\Order;
use App\Models\SmsMessage;
use App\Models\User;
use App\Services\Customer\CartService;
use App\Services\Customer\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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

        $order->loadMissing(['items.product']);

        SmsMessage::query()->create([
            'user_id' => $request->user()->id,
            'phone_number' => env('SMS_NOTIFY_PHONE', '+639661841984'),
            'message' => $this->buildOrderPlacedSmsMessage($request->user(), $order),
            'status' => 'pending',
        ]);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Order placed successfully.')]);

        return to_route('customer.orders.show', $order);
    }

    private function buildOrderPlacedSmsMessage(User $customer, Order $order): string
    {
        $phone = $customer->phone_number ?? 'n/a';

        $items = $order->items->map(function ($item) {
            $name = $item->product?->name ?? '#'.$item->product_id;
            $shortName = Str::limit($name, 28, '');

            return $item->quantity.'× '.$shortName;
        })->implode(', ');

        $items = Str::limit($items, 90, '…');

        $total = number_format((float) $order->total, 2);

        return sprintf(
            'New order %s — %s (%s). %s. Total PHP %s.',
            $order->order_number,
            Str::limit($customer->name, 40, ''),
            $phone,
            $items !== '' ? 'Items: '.$items : 'Items: (none)',
            $total
        );
    }
}
