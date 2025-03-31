<?php

namespace App\Http\Controllers\Backend\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;

class SubcategoryController extends Controller
{
    public function AllSubCategory()
    {

        $subcategories = SubCategory::with(['category' => function ($query) {
            $query->select('id', 'category_name', 'status');
        }])
            ->whereHas('category', function ($query) {
                $query->where('status', 'active');
            })
            ->select('id', 'category_id', 'subcategory_name', 'subcategory_slug', 'status')
            ->latest()
            ->get();

        return view('backend.category.all_sub_category', compact('subcategories'));
    }
    public function SubCategoryAdd()
    {

        $categories = Category::where('status', 'active')
            ->select('id', 'category_name')
            ->orderBy('category_name')
            ->get();

        return view('backend.category.add_sub_category', compact('categories'));
    }

    public function SubCategoryStore(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id,status,active',
            'subcategory_name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'unique:sub_categories,subcategory_name',
                'regex:/^[a-zA-Z0-9\s-]+$/'
            ],
        ], [
            'category_id.exists' => 'Selected category is invalid or inactive',
            'subcategory_name.regex' => 'Subcategory name can only contain letters, numbers, spaces and hyphens'
        ]);


        SubCategory::create([
            'category_id' => $request->category_id,
            'subcategory_name' => trim($request->subcategory_name),
            'subcategory_slug' => strtolower(str_replace(' ', '-', $request->subcategory_name)),
            'status' => 'active'
        ]);

        return redirect()
            ->route('all.subcategory')
            ->with('success', 'Subcategory Added Successfully');
    }

    public function SubCategoryEdit($id)
    {
        $subcategory = SubCategory::findOrFail($id);
        $categories = Category::where('status', 'active')
            ->orderBy('category_name')
            ->get();
        return view('backend.category.edit_sub_category', compact('categories', 'subcategory'));
    }

    public function SubCategoryUpdate(Request $request, $id)
    {
        $subcategory = SubCategory::findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:categories,id,status,active',
            'subcategory_name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'unique:sub_categories,subcategory_name,' . $subcategory->id,
                'regex:/^[a-zA-Z0-9\s-]+$/'
            ],
            'status' => 'required|in:active,inactive',
        ]);


        $subcategory->update([
            'category_id' => $request->category_id,
            'subcategory_name' => trim($request->subcategory_name),
            'subcategory_slug' => strtolower(str_replace(' ', '-', $request->subcategory_name)),
            'status' => $request->status,
        ]);

        $notification = [
            'message' => 'Subcategory Updated Successfully',
            'alert-type' => 'success'
        ];

        return redirect()
            ->route('all.subcategory')
            ->with($notification);
    }
    public function SubCategoryDelete($id)
    {

        $subcategory = SubCategory::findOrFail($id);

        // Check if subcategory has related products
        // if ($subcategory->products()->exists()) {
        //     return back()->with('error', 'Cannot delete subcategory with associated products');
        // }

        $subcategory->delete();
        $notification = [
            'message' => 'Subcategory Deleted Successfully',
            'alert-type' => 'success'
        ];
        return redirect()
            ->route('all.subcategory')
            ->with($notification);
    }

    /**
     * Get subcategories by category ID for AJAX request
     */
    public function getSubcategories($category_id)
    {
        $subcategories = SubCategory::where('category_id', $category_id)
            ->where('status', 'active')
            ->select('id', 'subcategory_name')
            ->get();

        return response()->json($subcategories);
    }
}
