<?php

namespace App\Http\Controllers\Frontend\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch new arrivals (most recent 10 products)
        $newArrivals = Product::with([
            'category',
            'images',
            'variations' => function ($query) {
                $query->where('is_default', true)->orWhereNull('is_default');
            }
        ])
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // For each product, get the primary image or first image
        foreach ($newArrivals as $product) {
            $primaryImage = $product->images()->where('is_primary', true)->first();
            if (!$primaryImage) {
                $primaryImage = $product->images()->first();
            }
            $product->primaryImage = $primaryImage;

            // Get default variation or first variation for price display
            $defaultVariation = $product->variations->first();
            $product->displayPrice = $defaultVariation ? $defaultVariation->price : $product->base_price;

            // Calculate discount if applicable
            if ($defaultVariation && $product->base_price > $defaultVariation->price) {
                $product->discount = round((($product->base_price - $defaultVariation->price) / $product->base_price) * 100);
            } else {
                $product->discount = 0;
            }
        }

        return view('frontend.index', compact('newArrivals'));
    }
}
