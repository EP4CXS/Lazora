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
        return Inertia::render('admin/Dashboard', [
            'orderStats' => [
                'total' => $this->adminOrders->totalCount(),
                'pendingConfirmation' => $this->adminOrders->pendingCount(),
            ],
        ]);
    }
}
