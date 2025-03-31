<?php

namespace App\Http\Controllers\Backend\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CategoryController extends Controller
{
    public function AllCategory()
    {
        $categories = Category::latest()->get();
        return view('backend.category.all_category', compact('categories'));
    }
    public function CategoryAdd()
    {
        return view('backend.category.add_category');
    }

    public function CategoryStore(Request $request)
    {
        $request->validate([
            'category_name' => 'required|unique:categories|max:255',
            'category_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        try {
            $image = $request->file('category_image');
            $name_gen = hexdec(uniqid()) . '.webp';
            $save_url = 'upload/category/' . $name_gen;

            // Create directory if it doesn't exist
            if (!file_exists(public_path('upload/category'))) {
                mkdir(public_path('upload/category'), 0755, true);
            }

            // Initialize Intervention Image with GD driver
            $manager = new ImageManager(new Driver());

            // Load, resize, optimize and save the image as WebP
            $manager->read($image)
                ->resize(800, 800, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->toWebp(75)  // Convert to WebP with quality setting
                ->save(public_path($save_url));

            Category::create([
                'category_name' => $request->category_name,
                'category_slug' => strtolower(str_replace(' ', '-', $request->category_name)),
                'category_image' => $save_url,
                'status' => 'active',
            ]);

            $notification = [
                'message' => 'Category Added Successfully',
                'alert-type' => 'success'
            ];

            return redirect()->route('all.category')->with($notification);
        } catch (\Exception $e) {
            $notification = [
                'message' => 'Error: ' . $e->getMessage(),
                'alert-type' => 'error'
            ];

            return back()->with($notification)->withInput();
        }
    }

    public function CategoryEdit($id)
    {
        $category = Category::findOrFail($id);
        return view('backend.category.edit_category', compact('category'));
    }

    public function CategoryUpdate(Request $request)
    {
        $request->validate([
            'category_name' => 'required|max:255',
            'category_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        try {
            $category = Category::findOrFail($request->id);

            // Update basic info
            $category->category_name = $request->category_name;
            $category->category_slug = strtolower(str_replace(' ', '-', $request->category_name));

            // Update status
            $category->status = $request->has('status') ? 'active' : 'inactive';

            // Handle image update
            if ($request->hasFile('category_image')) {
                // Delete old image
                if ($category->category_image && file_exists(public_path($category->category_image))) {
                    unlink(public_path($category->category_image));
                }

                $image = $request->file('category_image');
                $name_gen = hexdec(uniqid()) . '.webp';
                $save_url = 'upload/category/' . $name_gen;

                // Ensure directory exists
                if (!file_exists(public_path('upload/category'))) {
                    mkdir(public_path('upload/category'), 0755, true);
                }

                // Process and save new image
                $manager = new ImageManager(new Driver());
                $manager->read($image)
                    ->resize(800, 800, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->toWebp(75)  // Convert to WebP with quality setting
                    ->save(public_path($save_url));

                $category->category_image = $save_url;
            }

            $category->save();

            $notification = [
                'message' => 'Category Updated Successfully',
                'alert-type' => 'success'
            ];

            return redirect()->route('all.category')->with($notification);
        } catch (\Exception $e) {
            $notification = [
                'message' => 'Error: ' . $e->getMessage(),
                'alert-type' => 'error'
            ];

            return back()->with($notification)->withInput();
        }
    }

    public function CategoryDelete($id)
    {
        try {
            $category = Category::findOrFail($id);

            // Delete image if exists
            if ($category->category_image && file_exists(public_path($category->category_image))) {
                unlink(public_path($category->category_image));
            }

            $category->delete();

            $notification = [
                'message' => 'Category Deleted Successfully',
                'alert-type' => 'success'
            ];

            return redirect()->route('all.category')->with($notification);
        } catch (\Exception $e) {
            $notification = [
                'message' => 'Error: ' . $e->getMessage(),
                'alert-type' => 'error'
            ];

            return back()->with($notification)->withInput();
        }
    }
}
