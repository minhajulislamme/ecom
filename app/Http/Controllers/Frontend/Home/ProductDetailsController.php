<?php

namespace App\Http\Controllers\Frontend\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use App\Models\ProductVariation;

class ProductDetailsController extends Controller
{
    public function ProductDetails($id, $slug)
    {
        // Find product by ID, with its relationships loaded
        $product = Product::with([
            'category',
            'subcategory',
            'attributes.values',
            'variations.attributeValues.attribute',
            'variations.images',
            'images'
        ])->findOrFail($id);

        // Verify the slug matches for SEO purposes
        if ($product->slug !== $slug) {
            return redirect()->route('product.details', ['id' => $product->id, 'slug' => $product->slug]);
        }

        // Get the primary image or default to the first image if no primary image set
        $primaryImage = $product->images()->where('is_primary', true)->first();
        if (!$primaryImage) {
            $primaryImage = $product->images()->first();
        }

        // Get other (non-primary) images for the product gallery
        $galleryImages = $product->images()->where('is_primary', false)->orderBy('sort_order')->get();

        // Get the default variation or first variation if no default is set
        $defaultVariation = $product->variations()->where('is_default', true)->first();
        if (!$defaultVariation && $product->variations->count() > 0) {
            $defaultVariation = $product->variations->first();
        }

        // Organize product attributes into a format that's easier to use in the template
        $attributes = [];
        foreach ($product->attributes as $attribute) {
            $attributes[$attribute->name] = [
                'id' => $attribute->id,
                'values' => $attribute->values->pluck('value', 'id')->toArray()
            ];
        }

        return view('frontend.products.productdetails', compact(
            'product',
            'primaryImage',
            'galleryImages',
            'defaultVariation',
            'attributes'
        ));
    }

    /**
     * Handle AJAX request to get variation details by selected attribute values
     */
    public function getProductVariation(Request $request)
    {
        // Validate request
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'attribute_values' => 'required|array',
        ]);

        $productId = $request->product_id;
        $attributeValueIds = $request->attribute_values;

        // Get the product with all its attributes and variations
        $product = Product::with([
            'attributes.values',
            'variations.attributeValues.attribute',
            'variations.images'
        ])->findOrFail($productId);

        // Find variations that match ALL the selected attribute values
        $variations = $product->variations;

        // Find the variation that has exactly all the selected attribute values
        $matchingVariation = null;
        foreach ($variations as $variation) {
            $variationAttributeValueIds = $variation->attributeValues->pluck('id')->toArray();

            // Check if the variation has exactly the same attribute values
            // This means same count and all values match
            if (
                count($variationAttributeValueIds) === count($attributeValueIds) &&
                count(array_diff($variationAttributeValueIds, $attributeValueIds)) === 0 &&
                count(array_diff($attributeValueIds, $variationAttributeValueIds)) === 0
            ) {
                $matchingVariation = $variation;
                break;
            }
        }

        if ($matchingVariation) {
            // Prepare variation images data
            $variationImages = [];

            // Get the primary image for this variation
            $primaryVariationImage = $matchingVariation->images()->where('is_primary', true)->first();
            if ($primaryVariationImage) {
                $variationImages[] = [
                    'url' => asset($primaryVariationImage->image_path),
                    'is_primary' => true,
                    'alt_text' => $matchingVariation->sku ?? 'Product variation'
                ];
            }

            // Add all other variation images
            foreach ($matchingVariation->images()->where('is_primary', false)->get() as $image) {
                $variationImages[] = [
                    'url' => asset($image->image_path),
                    'is_primary' => false,
                    'alt_text' => $image->alt_text ?? 'Variation image'
                ];
            }

            // Calculate discount percentage if applicable
            $discountPercentage = null;
            if ($matchingVariation->price < $product->base_price) {
                $discountPercentage = round((($product->base_price - $matchingVariation->price) / $product->base_price) * 100);
            }

            // Get attribute information for display
            $attributeInfo = [];
            foreach ($matchingVariation->attributeValues as $attributeValue) {
                $attributeInfo[] = [
                    'attribute_name' => $attributeValue->attribute->name,
                    'value' => $attributeValue->value
                ];
            }

            return response()->json([
                'success' => true,
                'variation_id' => $matchingVariation->id,
                'price' => $matchingVariation->price,
                'base_price' => $product->base_price,
                'discount_percentage' => $discountPercentage,
                'stock_quantity' => $matchingVariation->stock_quantity,
                'is_available' => $matchingVariation->stock_quantity > 0,
                'sku' => $matchingVariation->sku,
                'variation_images' => $variationImages,
                'attribute_info' => $attributeInfo
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No matching variation found for the selected attributes.'
        ]);
    }
}
