<?php

namespace App\Http\Controllers;

use App\Models\GradeMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class GradeMasterController extends Controller
{
    public function index()
    {
        $grades = GradeMaster::query()
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.grade_masters.index', compact('grades'));
    }

    public function create()
    {
        $grade = new GradeMaster();

        return view('admin.grade_masters.form', [
            'grade' => $grade,
            'mode' => 'create',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'grade_code' => ['required', 'string', 'max:50'],
            'grade_name' => ['required', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'in:0,1'],
        ], [
            'grade_code.required' => 'กรุณากรอกรหัสเกรด',
            'grade_code.max' => 'รหัสเกรดต้องไม่เกิน 50 ตัวอักษร',
            'grade_name.required' => 'กรุณากรอกชื่อเกรด',
            'grade_name.max' => 'ชื่อเกรดต้องไม่เกิน 100 ตัวอักษร',
            'sort_order.integer' => 'ลำดับการแสดงต้องเป็นตัวเลข',
        ]);

        $gradeCode = strtoupper(trim($validated['grade_code']));

        $exists = GradeMaster::query()
            ->whereRaw('UPPER(grade_code) = ?', [$gradeCode])
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'รหัสเกรดนี้มีอยู่ในระบบแล้ว');
        }

        DB::beginTransaction();

        try {
            GradeMaster::create([
                'grade_code' => $gradeCode,
                'grade_name' => trim($validated['grade_name']),
                'sort_order' => (int) ($validated['sort_order'] ?? 0),
                'status' => (int) $request->input('status', 1),
            ]);

            DB::commit();

            return redirect('admin/grade-masters')
                ->with('success', 'เพิ่มเกรดพื้นฐานเรียบร้อยแล้ว');
        } catch (Throwable $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'ไม่สามารถเพิ่มเกรดพื้นฐานได้: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $grade = GradeMaster::query()->findOrFail($id);

        return view('admin.grade_masters.form', [
            'grade' => $grade,
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, $id)
    {
        $grade = GradeMaster::query()->findOrFail($id);

        $validated = $request->validate([
            'grade_code' => ['required', 'string', 'max:50'],
            'grade_name' => ['required', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'in:0,1'],
        ], [
            'grade_code.required' => 'กรุณากรอกรหัสเกรด',
            'grade_code.max' => 'รหัสเกรดต้องไม่เกิน 50 ตัวอักษร',
            'grade_name.required' => 'กรุณากรอกชื่อเกรด',
            'grade_name.max' => 'ชื่อเกรดต้องไม่เกิน 100 ตัวอักษร',
            'sort_order.integer' => 'ลำดับการแสดงต้องเป็นตัวเลข',
        ]);

        $gradeCode = strtoupper(trim($validated['grade_code']));

        $exists = GradeMaster::query()
            ->whereRaw('UPPER(grade_code) = ?', [$gradeCode])
            ->where('id', '!=', $grade->id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'รหัสเกรดนี้มีอยู่ในระบบแล้ว');
        }

        DB::beginTransaction();

        try {
            $grade->update([
                'grade_code' => $gradeCode,
                'grade_name' => trim($validated['grade_name']),
                'sort_order' => (int) ($validated['sort_order'] ?? 0),
                'status' => (int) $request->input('status', 1),
            ]);

            DB::commit();

            return redirect('admin/grade-masters')
                ->with('success', 'แก้ไขเกรดพื้นฐานเรียบร้อยแล้ว');
        } catch (Throwable $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'ไม่สามารถแก้ไขเกรดพื้นฐานได้: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer', 'exists:grade_masters,id'],
        ], [
            'id.required' => 'ไม่พบรหัสข้อมูลที่ต้องการลบ',
            'id.integer' => 'รหัสข้อมูลไม่ถูกต้อง',
            'id.exists' => 'ไม่พบข้อมูลเกรดพื้นฐาน',
        ]);

        DB::beginTransaction();

        try {
            $grade = GradeMaster::query()->findOrFail($request->id);
            $grade->delete();

            DB::commit();

            return redirect('admin/grade-masters')
                ->with('success', 'ลบเกรดพื้นฐานเรียบร้อยแล้ว');
        } catch (Throwable $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'ไม่สามารถลบเกรดพื้นฐานได้: ' . $e->getMessage());
        }
    }
}