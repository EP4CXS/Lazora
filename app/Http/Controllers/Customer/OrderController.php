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
        $lines = $order->items->map(function ($item) {
            $name = $item->product?->name ?? 'Product #'.$item->product_id;

            return sprintf(
                '- %dx %s @ %s = %s',
                $item->quantity,
                $name,
                number_format((float) $item->unit_price, 2),
                number_format((float) $item->line_total, 2)
            );
        })->implode("\n");

        $phone = $customer->phone_number ?? 'n/a';

        return implode("\n", array_filter([
            'New order on '.config('app.name'),
            'Order: '.$order->order_number,
            'Customer: '.$customer->name,
            'Email: '.$customer->email,
            'Phone: '.$phone,
            'Total: '.number_format((float) $order->total, 2),
            'Payment: '.$order->payment_status->value,
            'Status: '.$order->status->value,
            'Items:',
            $lines !== '' ? $lines : '- (no line items)',
        ]));
    }
}
