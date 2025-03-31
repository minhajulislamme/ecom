<?php

namespace App\Http\Controllers\Backend\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductVariation;

class ProductVariationImageController extends Controller
{
    /**
     * Store a newly created variation image in storage.
     */
    public function store(Request $request, ProductVariation $variation)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_primary' => 'boolean',
            'alt_text' => 'nullable|string|max:255',
        ]);

        // Handle file upload
        $path = $request->file('image')->store('variations', 'public');

        // Set as primary if requested or if it's the first image
        $isPrimary = $request->has('is_primary') ? $request->is_primary : !$variation->images()->exists();

        // If setting this image as primary, unset any existing primary images
        if ($isPrimary) {
            $variation->images()->where('is_primary', true)->update(['is_primary' => false]);

            // Also update the main variation image
            $variation->update([
                'image' => $path
            ]);
        }

        // Get the highest sort order and add 1 or default to 0
        $maxSortOrder = $variation->images()->max('sort_order') ?? -1;

        $variationImage = new ProductVariationImage([
            'image_path' => $path,
            'is_primary' => $isPrimary,
            'sort_order' => $maxSortOrder + 1,
            'alt_text' => $request->alt_text,
        ]);

        $variation->images()->save($variationImage);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Variation image uploaded successfully',
                'image' => $variationImage
            ], 201);
        }

        return back()->with('success', 'Variation image uploaded successfully');
    }

    /**
     * Update the specified variation image in storage.
     */
    public function update(Request $request, ProductVariationImage $variationImage)
    {
        $request->validate([
            'is_primary' => 'boolean',
            'sort_order' => 'nullable|integer',
            'alt_text' => 'nullable|string|max:255',
        ]);

        $variation = $variationImage->productVariation;

        // If setting this image as primary, unset any existing primary images
        if ($request->has('is_primary') && $request->is_primary) {
            $variation->images()->where('id', '!=', $variationImage->id)
                ->where('is_primary', true)
                ->update(['is_primary' => false]);

            // Update the main variation image
            $variation->update([
                'image' => $variationImage->image_path
            ]);
        }

        $variationImage->update($request->only(['is_primary', 'sort_order', 'alt_text']));

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Variation image updated successfully',
                'image' => $variationImage
            ]);
        }

        return back()->with('success', 'Variation image updated successfully');
    }

    /**
     * Remove the specified variation image from storage.
     */
    public function destroy(ProductVariationImage $variationImage)
    {
        // Store variation for redirect
        $variation = $variationImage->productVariation;

        // Check if this is the primary image
        $isPrimary = $variationImage->is_primary;

        // Delete the image from storage
        Storage::disk('public')->delete($variationImage->image_path);

        // Delete from database
        $variationImage->delete();

        // If this was the primary image, set another image as primary if available
        if ($isPrimary) {
            $newPrimary = $variation->images()->first();
            if ($newPrimary) {
                $newPrimary->update(['is_primary' => true]);

                // Update the main variation image
                $variation->update([
                    'image' => $newPrimary->image_path
                ]);
            } else {
                // No more images, clear the main image
                $variation->update(['image' => null]);
            }
        }

        return back()->with('success', 'Variation image deleted successfully');
    }
}
