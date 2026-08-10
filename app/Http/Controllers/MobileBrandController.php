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
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view(
            'admin.mobile_brands.index',
            compact('brands')
        );
    }

    public function create()
    {
        $brand = new MobileBrand();

        $nextSortOrder = ((int) MobileBrand::query()
            ->max('sort_order')) + 1;

        return view('admin.mobile_brands.form', [
            'brand' => $brand,
            'mode' => 'create',
            'nextSortOrder' => $nextSortOrder,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                'unique:mobile_brands,name',
            ],

            'sort_order' => [
                'required',
                'integer',
                'min:1',
            ],

            'status' => [
                'nullable',
                'in:0,1',
            ],
        ], [
            'name.required' =>
                'กรุณากรอกชื่อแบรนด์',

            'name.max' =>
                'ชื่อแบรนด์ต้องไม่เกิน 100 ตัวอักษร',

            'name.unique' =>
                'ชื่อแบรนด์นี้มีอยู่ในระบบแล้ว',

            'sort_order.required' =>
                'กรุณากรอกลำดับการแสดงผล',

            'sort_order.integer' =>
                'ลำดับการแสดงผลต้องเป็นตัวเลข',

            'sort_order.min' =>
                'ลำดับการแสดงผลต้องเริ่มตั้งแต่ 1',

            'status.in' =>
                'สถานะไม่ถูกต้อง',
        ]);

        $sortOrder = (int) $validated['sort_order'];

        DB::beginTransaction();

        try {
            /*
            |--------------------------------------------------------------------------
            | เปิดตำแหน่งลำดับใหม่
            |--------------------------------------------------------------------------
            */

            MobileBrand::query()
                ->where(
                    'sort_order',
                    '>=',
                    $sortOrder
                )
                ->increment('sort_order');

            /*
            |--------------------------------------------------------------------------
            | สร้างแบรนด์
            |--------------------------------------------------------------------------
            */

            MobileBrand::query()->create([
                'name' => trim(
                    $validated['name']
                ),

                'sort_order' =>
                    $sortOrder,

                'status' => (int) $request->input(
                    'status',
                    1
                ),
            ]);

            DB::commit();

            return redirect('admin/mobile-brands')
                ->with(
                    'success',
                    'เพิ่มแบรนด์สินค้าเรียบร้อยแล้ว'
                );
        } catch (Throwable $e) {
            DB::rollBack();

            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'ไม่สามารถเพิ่มแบรนด์สินค้าได้: '
                    . $e->getMessage()
                );
        }
    }

    public function edit($id)
    {
        $brand = MobileBrand::query()
            ->findOrFail((int) $id);

        return view('admin.mobile_brands.form', [
            'brand' => $brand,
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, $id)
    {
        $brand = MobileBrand::query()
            ->findOrFail((int) $id);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',

                Rule::unique(
                    'mobile_brands',
                    'name'
                )->ignore($brand->id),
            ],

            'sort_order' => [
                'required',
                'integer',
                'min:1',
            ],

            'status' => [
                'nullable',
                'in:0,1',
            ],
        ], [
            'name.required' =>
                'กรุณากรอกชื่อแบรนด์',

            'name.max' =>
                'ชื่อแบรนด์ต้องไม่เกิน 100 ตัวอักษร',

            'name.unique' =>
                'ชื่อแบรนด์นี้มีอยู่ในระบบแล้ว',

            'sort_order.required' =>
                'กรุณากรอกลำดับการแสดงผล',

            'sort_order.integer' =>
                'ลำดับการแสดงผลต้องเป็นตัวเลข',

            'sort_order.min' =>
                'ลำดับการแสดงผลต้องเริ่มตั้งแต่ 1',

            'status.in' =>
                'สถานะไม่ถูกต้อง',
        ]);

        $oldSortOrder = max(
            1,
            (int) $brand->sort_order
        );

        $newSortOrder =
            (int) $validated['sort_order'];

        DB::beginTransaction();

        try {
            /*
            |--------------------------------------------------------------------------
            | ย้ายลำดับขึ้น
            |--------------------------------------------------------------------------
            */

            if ($newSortOrder < $oldSortOrder) {
                MobileBrand::query()
                    ->where(
                        'id',
                        '!=',
                        $brand->id
                    )
                    ->whereBetween(
                        'sort_order',
                        [
                            $newSortOrder,
                            $oldSortOrder - 1,
                        ]
                    )
                    ->increment('sort_order');
            }

            /*
            |--------------------------------------------------------------------------
            | ย้ายลำดับลง
            |--------------------------------------------------------------------------
            */

            if ($newSortOrder > $oldSortOrder) {
                MobileBrand::query()
                    ->where(
                        'id',
                        '!=',
                        $brand->id
                    )
                    ->whereBetween(
                        'sort_order',
                        [
                            $oldSortOrder + 1,
                            $newSortOrder,
                        ]
                    )
                    ->decrement('sort_order');
            }

            /*
            |--------------------------------------------------------------------------
            | อัปเดตแบรนด์
            |--------------------------------------------------------------------------
            */

            $brand->update([
                'name' =>
                    trim($validated['name']),

                'sort_order' =>
                    $newSortOrder,

                'status' => (int) $request->input(
                    'status',
                    1
                ),
            ]);

            DB::commit();

            return redirect('admin/mobile-brands')
                ->with(
                    'success',
                    'แก้ไขแบรนด์สินค้าเรียบร้อยแล้ว'
                );
        } catch (Throwable $e) {
            DB::rollBack();

            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'ไม่สามารถแก้ไขแบรนด์สินค้าได้: '
                    . $e->getMessage()
                );
        }
    }

    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'id' => [
                'required',
                'integer',
                'exists:mobile_brands,id',
            ],
        ], [
            'id.required' =>
                'ไม่พบรหัสข้อมูลที่ต้องการลบ',

            'id.integer' =>
                'รหัสข้อมูลไม่ถูกต้อง',

            'id.exists' =>
                'ไม่พบข้อมูลแบรนด์สินค้า',
        ]);

        DB::beginTransaction();

        try {
            $brand = MobileBrand::query()
                ->findOrFail(
                    (int) $validated['id']
                );

            $deletedSortOrder = max(
                1,
                (int) $brand->sort_order
            );

            $brandId = (int) $brand->id;

            /*
            |--------------------------------------------------------------------------
            | ลบแบรนด์
            |--------------------------------------------------------------------------
            */

            $brand->delete();

            /*
            |--------------------------------------------------------------------------
            | ปิดช่องว่างของลำดับ
            |--------------------------------------------------------------------------
            */

            MobileBrand::query()
                ->where(
                    'id',
                    '!=',
                    $brandId
                )
                ->where(
                    'sort_order',
                    '>',
                    $deletedSortOrder
                )
                ->decrement('sort_order');

            DB::commit();

            return redirect('admin/mobile-brands')
                ->with(
                    'success',
                    'ลบแบรนด์สินค้าเรียบร้อยแล้ว'
                );
        } catch (Throwable $e) {
            DB::rollBack();

            report($e);

            return redirect()
                ->back()
                ->with(
                    'error',
                    'ไม่สามารถลบแบรนด์สินค้าได้: '
                    . $e->getMessage()
                );
        }
    }
}