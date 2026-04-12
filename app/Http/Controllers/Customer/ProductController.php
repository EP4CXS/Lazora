<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Product\FilterProductRequest;
use App\Models\Product;
use App\Services\Customer\ProductService;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function __construct(private readonly ProductService $products) {}

    public function index(FilterProductRequest $request): Response
    {
        $filters = $request->validated();

        return Inertia::render('customer/Products', [
            'filters' => $filters,
            'categories' => $this->products->categories(),
            'products' => $this->products->paginate($filters),
        ]);
    }

    public function show(Product $product): Response
    {
        abort_unless($product->is_active, 404);

        return Inertia::render('customer/products/Show', [
            'product' => $product,
        ]);
    }
}
