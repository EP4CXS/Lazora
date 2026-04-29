<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\AdminOrderService;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(private readonly AdminOrderService $adminOrders) {}

    // 1. This method is the __invoke method, which allows the controller to be used as a single-action controller. It returns an Inertia response that renders the 'admin/Dashboard' view with order statistics.
    public function __invoke(): Response
    {
        // 2. The order statistics include the total count of orders and the count of pending confirmation orders, which are retrieved using the AdminOrderService.
        return Inertia::render('admin/Dashboard', [
            // 3. The 'orderStats' key in the array passed to the view contains the total number of orders and the number of pending confirmation orders, which can be used in the view to display this information to the admin user.
            'orderStats' => [
                'total' => $this->adminOrders->totalCount(),
                'pendingConfirmation' => $this->adminOrders->pendingCount(),
            ],
        ]);
    }
}
