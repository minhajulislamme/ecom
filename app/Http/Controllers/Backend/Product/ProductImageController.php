<?php

namespace App\Http\Controllers\Backend\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    /**
     * Store a newly created product image in storage.
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_primary' => 'boolean',
            'alt_text' => 'nullable|string|max:255',
        ]);

        // Handle file upload
        $path = $request->file('image')->store('products', 'public');

        // Set as primary if requested or if it's the first image
        $isPrimary = $request->has('is_primary') ? $request->is_primary : !$product->images()->exists();

        // If setting this image as primary, unset any existing primary images
        if ($isPrimary) {
            $product->images()->where('is_primary', true)->update(['is_primary' => false]);
        }

        // Get the highest sort order and add 1 or default to 0
        $maxSortOrder = $product->images()->max('sort_order') ?? -1;

        $productImage = new ProductImage([
            'image_path' => $path,
            'is_primary' => $isPrimary,
            'sort_order' => $maxSortOrder + 1,
            'alt_text' => $request->alt_text,
        ]);

        $product->images()->save($productImage);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Product image uploaded successfully',
                'image' => $productImage
            ], 201);
        }

        return back()->with('success', 'Product image uploaded successfully');
    }

    /**
     * Update the specified product image in storage.
     */
    public function update(Request $request, ProductImage $productImage)
    {
        $request->validate([
            'is_primary' => 'boolean',
            'sort_order' => 'nullable|integer',
            'alt_text' => 'nullable|string|max:255',
        ]);

        $product = $productImage->product;

        // If setting this image as primary, unset any existing primary images
        if ($request->has('is_primary') && $request->is_primary) {
            $product->images()->where('id', '!=', $productImage->id)
                ->where('is_primary', true)
                ->update(['is_primary' => false]);
        }

        $productImage->update($request->only(['is_primary', 'sort_order', 'alt_text']));

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Product image updated successfully',
                'image' => $productImage
            ]);
        }

        return back()->with('success', 'Product image updated successfully');
    }

    /**
     * Remove the specified product image from storage.
     */
    public function destroy(ProductImage $productImage)
    {
        // Store product for redirect
        $product = $productImage->product;

        // Delete the image from storage
        Storage::disk('public')->delete($productImage->image_path);

        // Delete from database
        $productImage->delete();

        // If this was the primary image, set another image as primary if available
        if ($productImage->is_primary) {
            $newPrimary = $product->images()->first();
            if ($newPrimary) {
                $newPrimary->update(['is_primary' => true]);
            }
        }

        return back()->with('success', 'Product image deleted successfully');
    }
}
