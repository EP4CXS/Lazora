<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\Customer\ProductService;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(private readonly ProductService $products) {}

    public function __invoke(): Response
    {
        return Inertia::render('customer/Dashboard', $this->products->dashboardCatalog());
    }
}
