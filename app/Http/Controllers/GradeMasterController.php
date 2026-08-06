<?php

namespace App\Http\Controllers;

use App\Models\GradeMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class GradeMasterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | รายการเกรด
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $grades = GradeMaster::query()
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view(
            'admin.grade_masters.index',
            compact('grades')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ฟอร์มเพิ่มเกรด
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $grade = new GradeMaster();

        $nextSortOrder = ((int) GradeMaster::query()
            ->max('sort_order')) + 1;

        return view('admin.grade_masters.form', [
            'grade' => $grade,
            'mode' => 'create',
            'nextSortOrder' => $nextSortOrder,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | บันทึกเกรดใหม่
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'grade_code' => [
                'required',
                'string',
                'max:50',
            ],

            'grade_name' => [
                'required',
                'string',
                'max:100',
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
            'grade_code.required' =>
            'กรุณากรอกรหัสเกรด',

            'grade_code.max' =>
            'รหัสเกรดต้องไม่เกิน 50 ตัวอักษร',

            'grade_name.required' =>
            'กรุณากรอกชื่อเกรด',

            'grade_name.max' =>
            'ชื่อเกรดต้องไม่เกิน 100 ตัวอักษร',

            'sort_order.required' =>
            'กรุณากรอกลำดับการแสดงผล',

            'sort_order.integer' =>
            'ลำดับการแสดงผลต้องเป็นตัวเลขจำนวนเต็ม',

            'sort_order.min' =>
            'ลำดับการแสดงผลต้องเริ่มตั้งแต่ 1',

            'status.in' =>
            'สถานะไม่ถูกต้อง',
        ]);

        $gradeCode = strtoupper(
            trim($validated['grade_code'])
        );

        $gradeName = trim(
            $validated['grade_name']
        );

        $sortOrder = (int) $validated['sort_order'];

        /*
        |--------------------------------------------------------------------------
        | ตรวจรหัสซ้ำ รวมข้อมูลที่ถูก Soft Delete
        |--------------------------------------------------------------------------
        */

        $existingGrade = GradeMaster::withTrashed()
            ->whereRaw(
                'UPPER(grade_code) = ?',
                [$gradeCode]
            )
            ->first();

        if ($existingGrade) {
            if ($existingGrade->trashed()) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        'รหัสเกรดนี้เคยถูกลบแล้ว กรุณากู้คืนข้อมูลเดิมหรือใช้รหัสเกรดอื่น'
                    );
            }

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'รหัสเกรดนี้มีอยู่ในระบบแล้ว'
                );
        }

        DB::beginTransaction();

        try {
            /*
            |--------------------------------------------------------------------------
            | เปิดตำแหน่งลำดับใหม่
            |--------------------------------------------------------------------------
            |
            | ตัวอย่าง:
            | เดิม 1, 2, 3
            | เพิ่มใหม่ที่ 2
            | จะกลายเป็น 1, ใหม่=2, เดิม2=3, เดิม3=4
            |
            */

            $this->openSortPosition($sortOrder);

            GradeMaster::query()->create([
                'grade_code' => $gradeCode,
                'grade_name' => $gradeName,
                'sort_order' => $sortOrder,
                'status' => (int) $request->input(
                    'status',
                    1
                ),
            ]);

            DB::commit();

            return redirect('admin/grade-masters')
                ->with(
                    'success',
                    'เพิ่มเกรดพื้นฐานเรียบร้อยแล้ว'
                );
        } catch (Throwable $e) {
            DB::rollBack();

            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'ไม่สามารถเพิ่มเกรดพื้นฐานได้: '
                        . $e->getMessage()
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | ฟอร์มแก้ไขเกรด
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $grade = GradeMaster::query()
            ->findOrFail((int) $id);

        return view('admin.grade_masters.form', [
            'grade' => $grade,
            'mode' => 'edit',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | อัปเดตเกรด
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $grade = GradeMaster::query()
            ->findOrFail((int) $id);

        $validated = $request->validate([
            'grade_code' => [
                'required',
                'string',
                'max:50',
            ],

            'grade_name' => [
                'required',
                'string',
                'max:100',
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
            'grade_code.required' =>
            'กรุณากรอกรหัสเกรด',

            'grade_code.max' =>
            'รหัสเกรดต้องไม่เกิน 50 ตัวอักษร',

            'grade_name.required' =>
            'กรุณากรอกชื่อเกรด',

            'grade_name.max' =>
            'ชื่อเกรดต้องไม่เกิน 100 ตัวอักษร',

            'sort_order.required' =>
            'กรุณากรอกลำดับการแสดงผล',

            'sort_order.integer' =>
            'ลำดับการแสดงผลต้องเป็นตัวเลขจำนวนเต็ม',

            'sort_order.min' =>
            'ลำดับการแสดงผลต้องเริ่มตั้งแต่ 1',

            'status.in' =>
            'สถานะไม่ถูกต้อง',
        ]);

        $gradeCode = strtoupper(
            trim($validated['grade_code'])
        );

        $gradeName = trim(
            $validated['grade_name']
        );

        $newSortOrder =
            (int) $validated['sort_order'];

        $oldSortOrder =
            max(1, (int) $grade->sort_order);

        /*
        |--------------------------------------------------------------------------
        | ตรวจรหัสซ้ำ รวมข้อมูลที่ถูก Soft Delete
        |--------------------------------------------------------------------------
        */

        $existingGrade = GradeMaster::withTrashed()
            ->whereRaw(
                'UPPER(grade_code) = ?',
                [$gradeCode]
            )
            ->where(
                'id',
                '!=',
                $grade->id
            )
            ->first();

        if ($existingGrade) {
            if ($existingGrade->trashed()) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        'รหัสเกรดนี้ตรงกับข้อมูลที่เคยถูกลบ กรุณาใช้รหัสเกรดอื่น'
                    );
            }

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'รหัสเกรดนี้มีอยู่ในระบบแล้ว'
                );
        }

        DB::beginTransaction();

        try {
            /*
            |--------------------------------------------------------------------------
            | ย้ายตำแหน่งลำดับ
            |--------------------------------------------------------------------------
            */

            $this->moveSortPosition(
                gradeId: (int) $grade->id,
                oldSortOrder: $oldSortOrder,
                newSortOrder: $newSortOrder
            );

            $grade->update([
                'grade_code' => $gradeCode,
                'grade_name' => $gradeName,
                'sort_order' => $newSortOrder,
                'status' => (int) $request->input(
                    'status',
                    1
                ),
            ]);

            DB::commit();

            return redirect('admin/grade-masters')
                ->with(
                    'success',
                    'แก้ไขเกรดพื้นฐานเรียบร้อยแล้ว'
                );
        } catch (Throwable $e) {
            DB::rollBack();

            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'ไม่สามารถแก้ไขเกรดพื้นฐานได้: '
                        . $e->getMessage()
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | ลบเกรด
    |--------------------------------------------------------------------------
    */

    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'id' => [
                'required',
                'integer',
                'exists:grade_masters,id',
            ],
        ], [
            'id.required' =>
            'ไม่พบรหัสข้อมูลที่ต้องการลบ',

            'id.integer' =>
            'รหัสข้อมูลไม่ถูกต้อง',

            'id.exists' =>
            'ไม่พบข้อมูลเกรดพื้นฐาน',
        ]);

        DB::beginTransaction();

        try {
            $grade = GradeMaster::query()
                ->findOrFail(
                    (int) $validated['id']
                );

            $deletedSortOrder = max(
                1,
                (int) $grade->sort_order
            );

            /*
            |--------------------------------------------------------------------------
            | ปิดสถานะก่อน Soft Delete
            |--------------------------------------------------------------------------
            */

            $grade->update([
                'status' => 0,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Soft Delete
            |--------------------------------------------------------------------------
            */

            $grade->delete();

            /*
            |--------------------------------------------------------------------------
            | ปิดช่องว่างของลำดับ
            |--------------------------------------------------------------------------
            |
            | ตัวอย่าง:
            | เดิม 1, 2, 3, 4
            | ลบลำดับ 2
            | จะเหลือ 1, 2, 3
            |
            */

            $this->closeSortPosition(
                deletedSortOrder: $deletedSortOrder,
                ignoreGradeId: (int) $grade->id
            );

            DB::commit();

            return redirect('admin/grade-masters')
                ->with(
                    'success',
                    'ลบเกรดพื้นฐานเรียบร้อยแล้ว'
                );
        } catch (Throwable $e) {
            DB::rollBack();

            report($e);

            return redirect()
                ->back()
                ->with(
                    'error',
                    'ไม่สามารถลบเกรดพื้นฐานได้: '
                        . $e->getMessage()
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | เปิดตำแหน่งลำดับใหม่
    |--------------------------------------------------------------------------
    */

    private function openSortPosition(
        int $sortOrder,
        ?int $ignoreGradeId = null
    ): void {
        GradeMaster::query()
            ->when(
                $ignoreGradeId,
                function ($query) use (
                    $ignoreGradeId
                ) {
                    $query->where(
                        'id',
                        '!=',
                        $ignoreGradeId
                    );
                }
            )
            ->where(
                'sort_order',
                '>=',
                $sortOrder
            )
            ->increment('sort_order');
    }

    /*
    |--------------------------------------------------------------------------
    | ย้ายตำแหน่งลำดับ
    |--------------------------------------------------------------------------
    */

    private function moveSortPosition(
        int $gradeId,
        int $oldSortOrder,
        int $newSortOrder
    ): void {
        if ($newSortOrder === $oldSortOrder) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | ย้ายขึ้น เช่น จากลำดับ 5 ไปลำดับ 2
        |--------------------------------------------------------------------------
        |
        | รายการลำดับ 2 ถึง 4 จะถูกขยับลง +1
        |
        */

        if ($newSortOrder < $oldSortOrder) {
            GradeMaster::query()
                ->where(
                    'id',
                    '!=',
                    $gradeId
                )
                ->whereBetween(
                    'sort_order',
                    [
                        $newSortOrder,
                        $oldSortOrder - 1,
                    ]
                )
                ->increment('sort_order');

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | ย้ายลง เช่น จากลำดับ 2 ไปลำดับ 5
        |--------------------------------------------------------------------------
        |
        | รายการลำดับ 3 ถึง 5 จะถูกขยับขึ้น -1
        |
        */

        GradeMaster::query()
            ->where(
                'id',
                '!=',
                $gradeId
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
    | ปิดช่องว่างของลำดับหลังลบ
    |--------------------------------------------------------------------------
    */

    private function closeSortPosition(
        int $deletedSortOrder,
        ?int $ignoreGradeId = null
    ): void {
        GradeMaster::query()
            ->when(
                $ignoreGradeId,
                function ($query) use (
                    $ignoreGradeId
                ) {
                    $query->where(
                        'id',
                        '!=',
                        $ignoreGradeId
                    );
                }
            )
            ->where(
                'sort_order',
                '>',
                $deletedSortOrder
            )
            ->decrement('sort_order');
    }
}
