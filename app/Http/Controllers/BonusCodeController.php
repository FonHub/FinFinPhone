<?php

namespace App\Http\Controllers;

use App\Models\BonusCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Throwable;

class BonusCodeController extends Controller
{
    public function index()
    {
        $codes = BonusCode::query()
            ->orderByDesc('id')
            ->get();

        return view('admin.bonus_codes.index', [
            'codes' => $codes,
        ]);
    }

    public function create()
    {
        $code = new BonusCode();

        return view('admin.bonus_codes.form', [
            'code' => $code,
            'mode' => 'create',
            'action' => url('admin/bonus-codes/store'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:100', 'unique:bonus_codes,code'],
            'name' => ['required', 'string', 'max:255'],
            'bonus_type' => [
                'required',
                Rule::in([
                    BonusCode::TYPE_FIXED,
                    BonusCode::TYPE_PERCENT,
                ]),
            ],
            'bonus_value' => ['required', 'numeric', 'min:0'],
            'max_bonus_amount' => ['nullable', 'numeric', 'min:0'],
            'min_estimate_price' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'per_user_limit' => ['nullable', 'integer', 'min:1'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:0,1'],
        ], [
            'code.required' => 'กรุณากรอกรหัสโค้ด',
            'code.unique' => 'รหัสโค้ดนี้มีอยู่แล้ว',
            'name.required' => 'กรุณากรอกชื่อโค้ด',
            'bonus_type.required' => 'กรุณาเลือกประเภทการบวกเพิ่ม',
            'bonus_type.in' => 'ประเภทการบวกเพิ่มไม่ถูกต้อง',
            'bonus_value.required' => 'กรุณากรอกมูลค่าที่บวกเพิ่ม',
            'bonus_value.numeric' => 'มูลค่าที่บวกเพิ่มต้องเป็นตัวเลข',
            'max_bonus_amount.numeric' => 'เพดานบวกเพิ่มต้องเป็นตัวเลข',
            'min_estimate_price.numeric' => 'ราคาประเมินขั้นต่ำต้องเป็นตัวเลข',
            'usage_limit.integer' => 'จำนวนครั้งที่ใช้ได้ทั้งหมดต้องเป็นตัวเลขจำนวนเต็ม',
            'usage_limit.min' => 'จำนวนครั้งที่ใช้ได้ทั้งหมดต้องมากกว่า 0',
            'per_user_limit.integer' => 'จำนวนครั้งต่อผู้ใช้ต้องเป็นตัวเลขจำนวนเต็ม',
            'per_user_limit.min' => 'จำนวนครั้งต่อผู้ใช้ต้องมากกว่า 0',
            'ends_at.after_or_equal' => 'วันหมดอายุต้องไม่น้อยกว่าวันเริ่มใช้งาน',
            'status.required' => 'กรุณาเลือกสถานะ',
        ]);

        DB::beginTransaction();

        try {
            BonusCode::query()->create([
                'code' => strtoupper(trim($validated['code'])),
                'name' => trim($validated['name']),
                'bonus_type' => $validated['bonus_type'],
                'bonus_value' => $validated['bonus_value'],
                'max_bonus_amount' => $validated['bonus_type'] === BonusCode::TYPE_PERCENT
                    ? ($validated['max_bonus_amount'] ?? null)
                    : null,
                'min_estimate_price' => $validated['min_estimate_price'] ?? 0,
                'usage_limit' => $validated['usage_limit'] ?? null,
                'per_user_limit' => $validated['per_user_limit'] ?? null,
                'used_count' => 0,
                'starts_at' => $validated['starts_at'] ?? null,
                'ends_at' => $validated['ends_at'] ?? null,
                'description' => $validated['description'] ?? null,
                'status' => (int) $validated['status'],
            ]);

            DB::commit();

            return redirect('admin/bonus-codes')
                ->with('success', 'เพิ่มโค้ดบวกราคาเรียบร้อยแล้ว');
        } catch (Throwable $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'ไม่สามารถเพิ่มโค้ดได้: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $code = BonusCode::query()->findOrFail($id);

        return view('admin.bonus_codes.form', [
            'code' => $code,
            'mode' => 'edit',
            'action' => url('admin/bonus-codes/' . $code->id . '/update'),
        ]);
    }

    public function update(Request $request, $id)
    {
        $code = BonusCode::query()->findOrFail($id);

        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('bonus_codes', 'code')->ignore($code->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'bonus_type' => [
                'required',
                Rule::in([
                    BonusCode::TYPE_FIXED,
                    BonusCode::TYPE_PERCENT,
                ]),
            ],
            'bonus_value' => ['required', 'numeric', 'min:0'],
            'max_bonus_amount' => ['nullable', 'numeric', 'min:0'],
            'min_estimate_price' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'per_user_limit' => ['nullable', 'integer', 'min:1'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:0,1'],
        ], [
            'code.required' => 'กรุณากรอกรหัสโค้ด',
            'code.unique' => 'รหัสโค้ดนี้มีอยู่แล้ว',
            'name.required' => 'กรุณากรอกชื่อโค้ด',
            'bonus_type.required' => 'กรุณาเลือกประเภทการบวกเพิ่ม',
            'bonus_type.in' => 'ประเภทการบวกเพิ่มไม่ถูกต้อง',
            'bonus_value.required' => 'กรุณากรอกมูลค่าที่บวกเพิ่ม',
            'bonus_value.numeric' => 'มูลค่าที่บวกเพิ่มต้องเป็นตัวเลข',
            'max_bonus_amount.numeric' => 'เพดานบวกเพิ่มต้องเป็นตัวเลข',
            'min_estimate_price.numeric' => 'ราคาประเมินขั้นต่ำต้องเป็นตัวเลข',
            'usage_limit.integer' => 'จำนวนครั้งที่ใช้ได้ทั้งหมดต้องเป็นตัวเลขจำนวนเต็ม',
            'usage_limit.min' => 'จำนวนครั้งที่ใช้ได้ทั้งหมดต้องมากกว่า 0',
            'per_user_limit.integer' => 'จำนวนครั้งต่อผู้ใช้ต้องเป็นตัวเลขจำนวนเต็ม',
            'per_user_limit.min' => 'จำนวนครั้งต่อผู้ใช้ต้องมากกว่า 0',
            'ends_at.after_or_equal' => 'วันหมดอายุต้องไม่น้อยกว่าวันเริ่มใช้งาน',
            'status.required' => 'กรุณาเลือกสถานะ',
        ]);

        DB::beginTransaction();

        try {
            $code->update([
                'code' => strtoupper(trim($validated['code'])),
                'name' => trim($validated['name']),
                'bonus_type' => $validated['bonus_type'],
                'bonus_value' => $validated['bonus_value'],
                'max_bonus_amount' => $validated['bonus_type'] === BonusCode::TYPE_PERCENT
                    ? ($validated['max_bonus_amount'] ?? null)
                    : null,
                'min_estimate_price' => $validated['min_estimate_price'] ?? 0,
                'usage_limit' => $validated['usage_limit'] ?? null,
                'per_user_limit' => $validated['per_user_limit'] ?? null,
                'starts_at' => $validated['starts_at'] ?? null,
                'ends_at' => $validated['ends_at'] ?? null,
                'description' => $validated['description'] ?? null,
                'status' => (int) $validated['status'],
            ]);

            DB::commit();

            return redirect('admin/bonus-codes')
                ->with('success', 'แก้ไขโค้ดบวกราคาเรียบร้อยแล้ว');
        } catch (Throwable $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'ไม่สามารถแก้ไขโค้ดได้: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer', 'exists:bonus_codes,id'],
        ], [
            'id.required' => 'ไม่พบข้อมูลที่ต้องการลบ',
            'id.exists' => 'ไม่พบโค้ดที่ต้องการลบ',
        ]);

        DB::beginTransaction();

        try {
            $code = BonusCode::query()->findOrFail($request->id);
            $code->delete();

            DB::commit();

            return redirect('admin/bonus-codes')
                ->with('success', 'ลบโค้ดบวกราคาเรียบร้อยแล้ว');
        } catch (Throwable $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'ไม่สามารถลบโค้ดได้: ' . $e->getMessage());
        }
    }
}