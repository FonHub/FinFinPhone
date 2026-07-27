<?php

namespace App\Http\Controllers;

use App\Models\MobileModel;
use App\Models\MobileProductCategory;
use App\Models\ProductCategoryIcon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MobileProductCategoryController extends Controller
{
    public function index()
    {
        $categories = MobileProductCategory::query()
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        $icons = ProductCategoryIcon::query()
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get()
            ->keyBy('icon_key');

        return view('admin.mobile_product_categories.index', [
            'categories' => $categories,
            'icons' => $icons,
        ]);
    }

    public function create()
    {
        $category = new MobileProductCategory();

        $icons = ProductCategoryIcon::query()
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.mobile_product_categories.form', [
            'category' => $category,
            'icons' => $icons,
            'action' => url('admin/mobile-product-categories/store'),
            'isEdit' => false,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:150',
            'icon' => [
                'nullable',
                'string',
                'max:100',
                Rule::exists('product_category_icons', 'icon_key')->where(function ($query) {
                    return $query->where('status', 1);
                }),
            ],
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:0,1',
        ], [
            'category_name.required' => 'กรุณากรอกชื่อประเภทสินค้า',
            'category_name.max' => 'ชื่อประเภทสินค้าต้องไม่เกิน 150 ตัวอักษร',
            'icon.exists' => 'ไม่พบไอคอนที่เลือก',
            'sort_order.integer' => 'ลำดับต้องเป็นตัวเลข',
            'sort_order.min' => 'ลำดับต้องไม่น้อยกว่า 0',
            'status.required' => 'กรุณาเลือกสถานะ',
        ]);

        $categoryName = trim($request->category_name);

        $exists = MobileProductCategory::query()
            ->where('category_name', $categoryName)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'category_name' => 'ประเภทสินค้านี้มีอยู่แล้ว',
                ]);
        }

        $category = new MobileProductCategory();
        $category->category_name = $categoryName;
        $category->icon = $request->icon ?: null;
        $category->sort_order = $request->sort_order ?? 0;
        $category->status = $request->status;
        $category->save();

        return redirect('admin/mobile-product-categories')
            ->with('success', 'เพิ่มประเภทสินค้าเรียบร้อยแล้ว');
    }

    public function edit($id)
    {
        $category = MobileProductCategory::query()->findOrFail($id);

        $icons = ProductCategoryIcon::query()
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.mobile_product_categories.form', [
            'category' => $category,
            'icons' => $icons,
            'action' => url('admin/mobile-product-categories/' . $category->id . '/update'),
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, $id)
    {
        $category = MobileProductCategory::query()->findOrFail($id);

        $request->validate([
            'category_name' => 'required|string|max:150',
            'icon' => [
                'nullable',
                'string',
                'max:100',
                Rule::exists('product_category_icons', 'icon_key')->where(function ($query) {
                    return $query->where('status', 1);
                }),
            ],
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:0,1',
        ], [
            'category_name.required' => 'กรุณากรอกชื่อประเภทสินค้า',
            'category_name.max' => 'ชื่อประเภทสินค้าต้องไม่เกิน 150 ตัวอักษร',
            'icon.exists' => 'ไม่พบไอคอนที่เลือก',
            'sort_order.integer' => 'ลำดับต้องเป็นตัวเลข',
            'sort_order.min' => 'ลำดับต้องไม่น้อยกว่า 0',
            'status.required' => 'กรุณาเลือกสถานะ',
        ]);

        $categoryName = trim($request->category_name);

        $exists = MobileProductCategory::query()
            ->where('category_name', $categoryName)
            ->where('id', '!=', $category->id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'category_name' => 'ประเภทสินค้านี้มีอยู่แล้ว',
                ]);
        }

        $category->category_name = $categoryName;
        $category->icon = $request->icon ?: null;
        $category->sort_order = $request->sort_order ?? 0;
        $category->status = $request->status;
        $category->save();

        return redirect('admin/mobile-product-categories')
            ->with('success', 'แก้ไขประเภทสินค้าเรียบร้อยแล้ว');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:mobile_product_categories,id',
        ], [
            'id.required' => 'ไม่พบข้อมูลที่ต้องการลบ',
            'id.exists' => 'ไม่พบข้อมูลประเภทสินค้า',
        ]);

        $category = MobileProductCategory::query()->findOrFail($request->id);

        $hasModels = MobileModel::query()
            ->where('mobile_product_category_id', $category->id)
            ->exists();

        if ($hasModels) {
            return redirect()->back()
                ->with('error', 'ไม่สามารถลบประเภทสินค้าได้ เนื่องจากมีสินค้าอยู่ภายในประเภทนี้');
        }

        $category->delete();

        return redirect('admin/mobile-product-categories')
            ->with('success', 'ลบประเภทสินค้าเรียบร้อยแล้ว');
    }
}