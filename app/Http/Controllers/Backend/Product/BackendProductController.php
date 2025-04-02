<?php

namespace App\Http\Controllers\Backend\Product;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use App\Models\ProductImage;
use App\Models\ProductVariation;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class BackendProductController extends Controller
{
    /**
     * Display a listing of all products.
     */
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(10);
        return view('backend.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        return view('backend.products.create');
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:sub_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'image' => 'nullable|string',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_images' => 'nullable|array|max:5',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        DB::beginTransaction();
        try {
            // Create the product
            $product = Product::create([
                'category_id' => $validated['category_id'],
                'subcategory_id' => $validated['subcategory_id'],
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'description' => $validated['description'],
                'base_price' => $validated['base_price'],
                'image' => $validated['image'] ?? null,
                'status' => $validated['status'],
            ]);

            // Setup image manager
            $manager = new ImageManager(new Driver());

            // Create products directory if it doesn't exist
            $productsPath = public_path('upload/products/');
            if (!file_exists($productsPath)) {
                mkdir($productsPath, 0755, true);
            }

            // Handle main product image
            if ($request->hasFile('main_image')) {
                // Process and convert image to WebP format
                $image = $manager->read($request->file('main_image'));
                $filename = uniqid() . '.webp';
                $filePath = 'upload/products/' . $filename;
                $fullPath = public_path($filePath);

                // Convert and save image to webp format with 80% quality
                $encodedImage = $image->toWebp(80);
                file_put_contents($fullPath, $encodedImage);

                // Create a primary image record
                $product->images()->create([
                    'image_path' => $filePath,
                    'is_primary' => true,
                    'sort_order' => 0,
                    'alt_text' => $product->name,
                ]);

                // Also update the product image field for backwards compatibility
                $product->update(['image' => $filePath]);
            }

            // Handle gallery images
            if ($request->hasFile('gallery_images')) {
                $sortOrder = 1; // Start at 1 since main image is 0
                foreach ($request->file('gallery_images') as $galleryImage) {
                    // Process and convert image to WebP format
                    $image = $manager->read($galleryImage);
                    $filename = uniqid() . '.webp';
                    $filePath = 'upload/products/' . $filename;
                    $fullPath = public_path($filePath);

                    // Convert and save image to webp format with 80% quality
                    $encodedImage = $image->toWebp(80);
                    file_put_contents($fullPath, $encodedImage);

                    $product->images()->create([
                        'image_path' => $filePath,
                        'is_primary' => false,
                        'sort_order' => $sortOrder++,
                        'alt_text' => $product->name . ' image ' . $sortOrder,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('backend.products.edit', $product->id)
                ->with('success', 'Product created successfully. Now you can add attributes and variations.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create product: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $product->load('attributes.values', 'variations.attributeValues', 'images', 'variations.images');
        return view('backend.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $product->load('attributes.values', 'variations.attributeValues', 'images', 'variations.images');
        return view('backend.products.edit', compact('product'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:sub_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'image' => 'nullable|string',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_images' => 'nullable|array|max:5',
            'status' => 'required|in:active,inactive',
        ]);

        if ($product->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        DB::beginTransaction();
        try {
            // Update product basic info
            $product->update([
                'category_id' => $validated['category_id'],
                'subcategory_id' => $validated['subcategory_id'],
                'name' => $validated['name'],
                'slug' => $validated['slug'] ?? $product->slug,
                'description' => $validated['description'],
                'base_price' => $validated['base_price'],
                'image' => $validated['image'] ?? $product->image,
                'status' => $validated['status'],
            ]);

            // Setup image manager
            $manager = new ImageManager(new Driver());

            // Create products directory if it doesn't exist
            $productsPath = public_path('upload/products/');
            if (!file_exists($productsPath)) {
                mkdir($productsPath, 0755, true);
            }

            // Handle main product image
            if ($request->hasFile('main_image')) {
                // Process and convert image to WebP format
                $image = $manager->read($request->file('main_image'));
                $filename = uniqid() . '.webp';
                $filePath = 'upload/products/' . $filename;
                $fullPath = public_path($filePath);

                // Convert and save image to webp format with 80% quality
                $encodedImage = $image->toWebp(80);
                file_put_contents($fullPath, $encodedImage);

                // Remove old primary image designation
                $product->images()->where('is_primary', true)->update(['is_primary' => false]);

                // Create a new primary image record
                $product->images()->create([
                    'image_path' => $filePath,
                    'is_primary' => true,
                    'sort_order' => 0,
                    'alt_text' => $product->name,
                ]);

                // Also update the product image field for backwards compatibility
                $product->update(['image' => $filePath]);
            }

            // Handle gallery images
            if ($request->hasFile('gallery_images')) {
                // Get the max sort order currently
                $maxSortOrder = $product->images()->max('sort_order') ?? 0;
                $sortOrder = $maxSortOrder + 1;

                foreach ($request->file('gallery_images') as $galleryImage) {
                    // Process and convert image to WebP format
                    $image = $manager->read($galleryImage);
                    $filename = uniqid() . '.webp';
                    $filePath = 'upload/products/' . $filename;
                    $fullPath = public_path($filePath);

                    // Convert and save image to webp format with 80% quality
                    $encodedImage = $image->toWebp(80);
                    file_put_contents($fullPath, $encodedImage);

                    $product->images()->create([
                        'image_path' => $filePath,
                        'is_primary' => false,
                        'sort_order' => $sortOrder++,
                        'alt_text' => $product->name . ' image ' . $sortOrder,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('backend.products.edit', $product->id)
                ->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        // Delete all associated images from storage
        foreach ($product->images as $image) {
            $fullPath = public_path($image->image_path);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }

        // Delete variation images and clean up product_variation_attribute_values
        foreach ($product->variations as $variation) {
            // Delete variation images
            foreach ($variation->images as $image) {
                $fullPath = public_path($image->image_path);
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
            }

            // Explicitly detach attribute values to clean pivot table
            $variation->attributeValues()->detach();
        }

        $product->delete();
        return redirect()->route('backend.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    /**
     * Add a new attribute to the product.
     */
    public function addAttribute(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'values' => 'required|string',
        ]);

        // Check if attribute already exists for this product
        $attributeExists = $product->attributes()->where('name', $validated['name'])->exists();
        if ($attributeExists) {
            return redirect()->route('backend.products.edit', $product->id)
                ->with('error', 'An attribute with this name already exists for this product.');
        }

        DB::beginTransaction();
        try {
            // Create attribute
            $attribute = $product->attributes()->create([
                'name' => $validated['name'],
            ]);
            // Create attribute values
            $valueArray = array_map('trim', explode(',', $validated['values']));
            foreach ($valueArray as $value) {
                if (!empty($value)) {
                    $attribute->values()->create([
                        'value' => $value
                    ]);
                }
            }
            DB::commit();
            return redirect()->route('backend.products.edit', $product->id)
                ->with('success', 'Attribute added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('backend.products.edit', $product->id)
                ->with('error', 'Failed to add attribute: ' . $e->getMessage());
        }
    }

    /**
     * Remove an attribute from the product.
     */
    public function removeAttribute(Product $product, ProductAttribute $attribute)
    {
        $attribute->delete();
        return redirect()->route('backend.products.edit', $product->id)
            ->with('success', 'Attribute removed successfully.');
    }

    /**
     * Show the form for adding variations.
     */
    public function createVariations(Product $product)
    {
        $product->load('attributes.values');
        // Check if product has at least one attribute
        if ($product->attributes->isEmpty()) {
            return redirect()->route('backend.products.edit', $product->id)
                ->with('error', 'Please add at least one attribute before creating variations.');
        }
        return view('backend.products.variations.create', compact('product'));
    }

    /**
     * Store new variations.
     */
    public function storeVariations(Request $request, Product $product)
    {
        $request->validate([
            'variations' => 'required|array',
            'variations.*.attribute_values' => 'required|array',
            'variations.*.sku' => 'nullable|string|max:255',
            'variations.*.price' => 'required|numeric|min:0',
            'variations.*.stock_quantity' => 'required|integer|min:0',
            'variations.*.is_default' => 'sometimes|boolean',
            'variations.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // Load existing variations with their attribute values
            $existingVariations = $product->variations()->with('attributeValues')->get();

            foreach ($request->variations as $variationData) {
                // Check if a variation with this exact combination of attribute values already exists
                $attributeValueIds = $variationData['attribute_values'];
                sort($attributeValueIds); // Sort to ensure consistent comparison

                // Check for duplicate variations
                $isDuplicate = false;
                foreach ($existingVariations as $existingVariation) {
                    $existingAttributeValueIds = $existingVariation->attributeValues->pluck('id')->toArray();
                    sort($existingAttributeValueIds);

                    if ($attributeValueIds == $existingAttributeValueIds) {
                        $isDuplicate = true;
                        break;
                    }
                }

                if ($isDuplicate) {
                    continue; // Skip this variation as it already exists
                }

                // Create the variation
                $variation = $product->variations()->create([
                    'sku' => $variationData['sku'] ?? null,
                    'price' => $variationData['price'],
                    'stock_quantity' => $variationData['stock_quantity'],
                    'is_default' => $variationData['is_default'] ?? false,
                ]);

                // Attach attribute values to the variation
                $variation->attributeValues()->attach($attributeValueIds);

                // Handle variation image if uploaded
                if (isset($variationData['image']) && $variationData['image'] instanceof \Illuminate\Http\UploadedFile) {
                    // Setup image manager
                    $manager = new ImageManager(new Driver());

                    // Create variations directory if it doesn't exist
                    $variationsPath = public_path('upload/variations');
                    if (!file_exists($variationsPath)) {
                        mkdir($variationsPath, 0755, true);
                    }

                    // Process and convert image to WebP format
                    $image = $manager->read($variationData['image']);
                    $filename = uniqid() . '.webp';
                    $filePath = 'upload/variations/' . $filename;
                    $fullPath = public_path($filePath);

                    // Convert and save image to webp format with 80% quality
                    $encodedImage = $image->toWebp(80);
                    file_put_contents($fullPath, $encodedImage);

                    // Update the variation with the image path
                    $variation->update(['image' => $filePath]);

                    // Create a variation image record
                    $variation->images()->create([
                        'image_path' => $filePath,
                        'is_primary' => true,
                        'sort_order' => 0,
                        'alt_text' => $product->name . ' ' . $variation->sku,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('backend.products.edit', $product->id)
                ->with('success', 'Variations added successfully. Duplicate variations were skipped.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('backend.products.variations.create', $product->id)
                ->with('error', 'Failed to add variations: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing a variation.
     */
    public function editVariation(Product $product, ProductVariation $variation)
    {
        // Ensure the variation belongs to the product
        if ($variation->product_id !== $product->id) {
            return redirect()->route('backend.products.edit', $product->id)
                ->with('error', 'This variation does not belong to the specified product.');
        }

        // Load the variation with its related data
        $variation->load('attributeValues.attribute', 'images');

        return view('backend.products.variations.edit', compact('product', 'variation'));
    }

    /**
     * Update the specified variation.
     */
    public function updateVariation(Request $request, Product $product, ProductVariation $variation)
    {
        // Ensure the variation belongs to the product
        if ($variation->product_id !== $product->id) {
            return redirect()->route('backend.products.edit', $product->id)
                ->with('error', 'This variation does not belong to the specified product.');
        }

        // Validate the request data
        $validated = $request->validate([
            'sku' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'is_default' => 'sometimes|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_image' => 'sometimes|boolean',
        ]);

        DB::beginTransaction();
        try {
            // Update variation basic details
            $updateData = [
                'sku' => $validated['sku'],
                'price' => $validated['price'],
                'stock_quantity' => $validated['stock_quantity'],
                'is_default' => $request->has('is_default') ? true : false
            ];

            // If this variation is being set as default, remove default status from other variations
            if ($request->has('is_default') && !$variation->is_default) {
                $product->variations()
                    ->where('id', '!=', $variation->id)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }

            // Setup image manager
            $manager = new ImageManager(new Driver());

            // Create variations directory if it doesn't exist
            $variationsPath = public_path('upload/variations');
            if (!file_exists($variationsPath)) {
                mkdir($variationsPath, 0755, true);
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete the old image if it exists
                if ($variation->image) {
                    $oldImagePath = public_path($variation->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                // Process and convert image to WebP format
                $image = $manager->read($request->file('image'));
                $filename = uniqid() . '.webp';
                $filePath = 'upload/variations/' . $filename;
                $fullPath = public_path($filePath);

                // Convert and save image to webp format with 80% quality
                $encodedImage = $image->toWebp(80);
                file_put_contents($fullPath, $encodedImage);

                $updateData['image'] = $filePath;

                // Create or update variation primary image record
                $variation->images()->updateOrCreate(
                    ['is_primary' => true],
                    [
                        'image_path' => $filePath,
                        'sort_order' => 0,
                        'alt_text' => $product->name . ' ' . $validated['sku']
                    ]
                );
            }
            // Handle image removal if requested
            else if ($request->has('remove_image') && $variation->image) {
                // Delete the file from storage using direct file operations
                $oldImagePath = public_path($variation->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }

                $updateData['image'] = null;

                // Remove primary image designation
                $primaryImage = $variation->images()->where('is_primary', true)->first();
                if ($primaryImage) {
                    $primaryImage->delete();
                }
            }

            // Update the variation
            $variation->update($updateData);

            DB::commit();

            return redirect()->route('backend.products.variations.edit', ['product' => $product->id, 'variation' => $variation->id])
                ->with('success', 'Variation updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Failed to update variation: ' . $e->getMessage());
        }
    }

    /**
     * Remove a variation from the product.
     */
    public function removeVariation(Product $product, ProductVariation $variation)
    {
        // Ensure the variation belongs to the product
        if ($variation->product_id !== $product->id) {
            return redirect()->route('backend.products.edit', $product->id)
                ->with('error', 'This variation does not belong to the specified product.');
        }

        DB::beginTransaction();
        try {
            // Delete variation images from storage
            foreach ($variation->images as $image) {
                unlink(public_path($image->image_path));
            }

            // Detach attribute values to clean pivot table
            $variation->attributeValues()->detach();

            // Delete the variation
            $variation->delete();

            DB::commit();
            return redirect()->route('backend.products.edit', $product->id)
                ->with('success', 'Variation deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('backend.products.edit', $product->id)
                ->with('error', 'Failed to delete variation: ' . $e->getMessage());
        }
    }
}
