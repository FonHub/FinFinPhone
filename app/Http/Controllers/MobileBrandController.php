<?php

namespace App\Http\Controllers;

use App\Models\MobileBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Throwable;

class MobileBrandController extends Controller
{
    public function index()
    {
        $brands = MobileBrand::query()
            ->orderByDesc('id')
            ->get();

        return view('admin.mobile_brands.index', compact('brands'));
    }

    public function create()
    {
        $brand = new MobileBrand();

        return view('admin.mobile_brands.form', [
            'brand' => $brand,
            'mode' => 'create',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:mobile_brands,name'],
            'status' => ['nullable', 'in:0,1'],
        ], [
            'name.required' => 'กรุณากรอกชื่อแบรนด์',
            'name.max' => 'ชื่อแบรนด์ต้องไม่เกิน 100 ตัวอักษร',
            'name.unique' => 'ชื่อแบรนด์นี้มีอยู่ในระบบแล้ว',
            'status.in' => 'สถานะไม่ถูกต้อง',
        ]);

        DB::beginTransaction();

        try {
            MobileBrand::create([
                'name' => trim($validated['name']),
                'status' => (int) $request->input('status', 1),
            ]);

            DB::commit();

            return redirect('admin/mobile-brands')
                ->with('success', 'เพิ่มแบรนด์สินค้าเรียบร้อยแล้ว');
        } catch (Throwable $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'ไม่สามารถเพิ่มแบรนด์สินค้าได้: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $brand = MobileBrand::query()->findOrFail($id);

        return view('admin.mobile_brands.form', [
            'brand' => $brand,
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, $id)
    {
        $brand = MobileBrand::query()->findOrFail($id);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('mobile_brands', 'name')->ignore($brand->id),
            ],
            'status' => ['nullable', 'in:0,1'],
        ], [
            'name.required' => 'กรุณากรอกชื่อแบรนด์',
            'name.max' => 'ชื่อแบรนด์ต้องไม่เกิน 100 ตัวอักษร',
            'name.unique' => 'ชื่อแบรนด์นี้มีอยู่ในระบบแล้ว',
            'status.in' => 'สถานะไม่ถูกต้อง',
        ]);

        DB::beginTransaction();

        try {
            $brand->update([
                'name' => trim($validated['name']),
                'status' => (int) $request->input('status', 1),
            ]);

            DB::commit();

            return redirect('admin/mobile-brands')
                ->with('success', 'แก้ไขแบรนด์สินค้าเรียบร้อยแล้ว');
        } catch (Throwable $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'ไม่สามารถแก้ไขแบรนด์สินค้าได้: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer', 'exists:mobile_brands,id'],
        ], [
            'id.required' => 'ไม่พบรหัสข้อมูลที่ต้องการลบ',
            'id.exists' => 'ไม่พบข้อมูลแบรนด์สินค้า',
        ]);

        DB::beginTransaction();

        try {
            $brand = MobileBrand::query()->findOrFail($request->id);
            $brand->delete();

            DB::commit();

            return redirect('admin/mobile-brands')
                ->with('success', 'ลบแบรนด์สินค้าเรียบร้อยแล้ว');
        } catch (Throwable $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'ไม่สามารถลบแบรนด์สินค้าได้: ' . $e->getMessage());
        }
    }
}