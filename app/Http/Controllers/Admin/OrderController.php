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
    
    // 1. This method is responsible for displaying a paginated list of orders in the admin panel. It uses the AdminOrderService to retrieve the orders and passes them to the 'admin/orders/Index' view using Inertia.

    public function index(): Response
    {

    // 2. The paginate method is called on the AdminOrderService to retrieve a paginated list of orders, with 15 orders per page. This allows the admin to navigate through the list of orders easily without overwhelming the interface with too many orders at once.
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
