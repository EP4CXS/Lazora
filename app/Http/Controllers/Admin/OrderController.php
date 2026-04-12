<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\DenyOrderRequest;
use App\Models\Order;
use App\Services\Admin\AdminOrderService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function __construct(private readonly AdminOrderService $adminOrders) {}

    public function index(): Response
    {
        return Inertia::render('admin/orders/Index', [
            'orders' => $this->adminOrders->paginate(15),
        ]);
    }

    public function show(Order $order): Response
    {
        $order->load(['user', 'items.product']);

        return Inertia::render('admin/orders/Show', [
            'order' => $order,
        ]);
    }

    public function confirm(Order $order): RedirectResponse
    {
        $this->adminOrders->confirm($order);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Order confirmed.')]);

        return back();
    }

    public function deny(DenyOrderRequest $request, Order $order): RedirectResponse
    {
        $this->adminOrders->deny($order, $request->validated('reason'));

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Order denied and customer notified.')]);

        return back();
    }
}
