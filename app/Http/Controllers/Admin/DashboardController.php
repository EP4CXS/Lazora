<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\AdminOrderService;
use Inertia\Inertia;
use Inertia\Response;

// 2. This is a DashboardController class that extends the abstract Controller class. It is responsible for handling requests related to the admin dashboard.
class DashboardController extends Controller
{
    public function __construct(private readonly AdminOrderService $adminOrders) {}

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
