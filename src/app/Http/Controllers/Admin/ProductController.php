<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with(['vendor.user', 'translations', 'images']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('translations', fn ($q) => $q->where('title', 'like', "%{$search}%"));
        }

        $products = $query->latest()->paginate(20);

        return view('admin.products.index', compact('products'));
    }

    public function show(Product $product): View
    {
        $product->load(['vendor.user', 'translations', 'prices', 'images', 'reviews' => fn ($q) => $q->latest()->limit(5)]);

        return view('admin.products.show', compact('product'));
    }

    public function approve(Product $product): RedirectResponse
    {
        $product->update(['status' => 'active']);

        return redirect()->back()->with('success', '상품이 승인되었습니다.');
    }

    public function reject(Request $request, Product $product): RedirectResponse
    {
        $request->validate(['reason' => 'nullable|string|max:500']);

        $product->update(['status' => 'draft']);

        return redirect()->back()->with('success', '상품이 반려되었습니다.');
    }

    public function toggle(Product $product): RedirectResponse
    {
        $newStatus = $product->status === 'active' ? 'inactive' : 'active';
        $product->update(['status' => $newStatus]);

        $label = $newStatus === 'active' ? '활성화' : '비활성화';

        return redirect()->back()->with('success', "상품이 {$label}되었습니다.");
    }
}
