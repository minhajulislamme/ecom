<?php

namespace App\Http\Controllers\Backend\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductVariation;
use App\Models\ProductVariationImage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductVariationImageController extends Controller
{
    /**
     * Store a newly created variation image in storage.
     */
    public function store(Request $request, ProductVariation $variation)
    {
        $request->validate([
            'image_path' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_primary' => 'boolean',
            'alt_text' => 'nullable|string|max:255',
        ]);

        // Process and convert image to WebP format using Intervention Image
        $manager = new ImageManager(new Driver());
        $image = $manager->read($request->file('image_path'));

        // Create variations directory if it doesn't exist
        $variationsPath = public_path('variations');
        if (!file_exists($variationsPath)) {
            mkdir($variationsPath, 0755, true);
        }

        // Generate a unique filename with webp extension
        $filename = uniqid() . '.webp';
        $filePath = 'variations/' . $filename;
        $fullPath = public_path($filePath);

        // Convert and save image to webp format with 80% quality
        $encodedImage = $image->toWebp(80);
        file_put_contents($fullPath, $encodedImage);

        // Path for database storage
        $path = $filePath;

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

        // Delete the image from storage using direct file operations
        $fullPath = public_path($variationImage->image_path);
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }

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
