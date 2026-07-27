<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ServiceArea;
use App\Models\ServiceTimeSlot;
use App\Models\ThaiDistrict;
use App\Models\ThaiProvince;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class BranchController extends Controller
{
    public function index()
    {
        $provinces = ThaiProvince::query()
            ->with([
                'districts' => function ($query) {
                    $query->where('is_active', 1)
                        ->orderBy('sort_order', 'asc')
                        ->orderBy('name_th', 'asc');
                }
            ])
            ->where('is_active', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('name_th', 'asc')
            ->get();

        $serviceAreas = ServiceArea::query()
            ->with(['provinceData', 'districtData'])
            ->leftJoin('thai_provinces as provinces', 'provinces.id', '=', 'service_areas.thai_province_id')
            ->leftJoin('thai_districts as districts', 'districts.id', '=', 'service_areas.thai_district_id')
            ->select('service_areas.*')
            ->orderBy('provinces.sort_order', 'asc')
            ->orderBy('provinces.name_th', 'asc')
            ->orderBy('service_areas.is_all_districts', 'desc')
            ->orderBy('districts.sort_order', 'asc')
            ->orderBy('districts.name_th', 'asc')
            ->get();

        $serviceTimeSlots = ServiceTimeSlot::query()
            ->orderBy('sort_order', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        return view('admin.branches.index', compact(
            'provinces',
            'serviceAreas',
            'serviceTimeSlots'
        ));
    }

    public function create()
    {
        return redirect()->route('admin.branches.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'thai_province_id' => ['required', 'integer', 'exists:thai_provinces,id'],
            'district_mode' => ['required', Rule::in(['all', 'selected'])],
            'district_ids' => ['nullable', 'array'],
            'district_ids.*' => ['nullable', 'integer', 'exists:thai_districts,id'],
        ], [
            'thai_province_id.required' => 'กรุณาเลือกจังหวัด',
            'thai_province_id.exists' => 'ไม่พบจังหวัดที่เลือก',
            'district_mode.required' => 'กรุณาเลือกรูปแบบพื้นที่ให้บริการ',
            'district_mode.in' => 'รูปแบบพื้นที่ให้บริการไม่ถูกต้อง',
            'district_ids.array' => 'รูปแบบอำเภอไม่ถูกต้อง',
            'district_ids.*.exists' => 'ไม่พบอำเภอที่เลือก',
        ]);

        $province = ThaiProvince::query()
            ->where('is_active', 1)
            ->findOrFail($request->thai_province_id);

        $districtMode = $request->input('district_mode');

        $districtIds = collect($request->input('district_ids', []))
            ->filter()
            ->map(function ($id) {
                return (int) $id;
            })
            ->unique()
            ->values();

        if ($districtMode === 'selected' && $districtIds->count() <= 0) {
            return back()
                ->withInput()
                ->with('error', 'กรุณาเลือกอำเภออย่างน้อย 1 รายการ');
        }

        if ($districtMode === 'selected') {
            $validDistrictCount = ThaiDistrict::query()
                ->where('thai_province_id', $province->id)
                ->where('is_active', 1)
                ->whereIn('id', $districtIds->toArray())
                ->count();

            if ($validDistrictCount !== $districtIds->count()) {
                return back()
                    ->withInput()
                    ->with('error', 'อำเภอที่เลือกไม่ตรงกับจังหวัด หรืออำเภอถูกปิดใช้งาน');
            }
        }

        DB::beginTransaction();

        try {
            if ($districtMode === 'all') {
                ServiceArea::query()
                    ->where('thai_province_id', $province->id)
                    ->delete();

                ServiceArea::query()->create([
                    'thai_province_id' => $province->id,
                    'thai_district_id' => null,
                    'is_all_districts' => 1,
                    'province' => $province->name_th,
                    'district' => 'ทุกอำเภอ',
                ]);

                DB::commit();

                return redirect()
                    ->route('admin.branches.index')
                    ->with('success', 'เพิ่มพื้นที่ให้บริการทั้งจังหวัดเรียบร้อยแล้ว');
            }

            ServiceArea::query()
                ->where('thai_province_id', $province->id)
                ->where(function ($query) use ($districtIds) {
                    $query->where('is_all_districts', 1)
                        ->orWhereNull('thai_district_id')
                        ->orWhereNotIn('thai_district_id', $districtIds->toArray());
                })
                ->delete();

            $districts = ThaiDistrict::query()
                ->where('thai_province_id', $province->id)
                ->whereIn('id', $districtIds->toArray())
                ->get();

            foreach ($districts as $district) {
                ServiceArea::query()->updateOrCreate(
                    [
                        'thai_province_id' => $province->id,
                        'thai_district_id' => $district->id,
                    ],
                    [
                        'is_all_districts' => 0,
                        'province' => $province->name_th,
                        'district' => $district->name_th,
                    ]
                );
            }

            DB::commit();

            return redirect()
                ->route('admin.branches.index')
                ->with('success', 'เพิ่มพื้นที่ให้บริการเฉพาะอำเภอเรียบร้อยแล้ว');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Create service area error', [
                'message' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'ไม่สามารถเพิ่มพื้นที่ให้บริการได้: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        return redirect()->route('admin.branches.index');
    }

    public function update(Request $request, $id)
    {
        return $this->store($request);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => ['required', 'exists:service_areas,id'],
        ], [
            'id.required' => 'ไม่พบข้อมูลที่ต้องการลบ',
            'id.exists' => 'ไม่พบพื้นที่ให้บริการ',
        ]);

        DB::beginTransaction();

        try {
            $serviceArea = ServiceArea::query()
                ->findOrFail($request->id);

            $serviceArea->delete();

            DB::commit();

            return redirect()
                ->route('admin.branches.index')
                ->with('success', 'ลบพื้นที่ให้บริการเรียบร้อยแล้ว');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Delete service area error', [
                'id' => $request->id,
                'message' => $e->getMessage(),
            ]);

            return back()
                ->with('error', 'ไม่สามารถลบพื้นที่ให้บริการได้');
        }
    }

    public function storeTimeSlot(Request $request)
    {
        $request->validate([
            'label' => ['nullable', 'string', 'max:100'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'in:0,1'],
        ], [
            'start_time.required' => 'กรุณากรอกเวลาเริ่มต้น',
            'start_time.date_format' => 'รูปแบบเวลาเริ่มต้นไม่ถูกต้อง',
            'end_time.required' => 'กรุณากรอกเวลาสิ้นสุด',
            'end_time.date_format' => 'รูปแบบเวลาสิ้นสุดไม่ถูกต้อง',
            'end_time.after' => 'เวลาสิ้นสุดต้องมากกว่าเวลาเริ่มต้น',
        ]);

        DB::beginTransaction();

        try {
            $startTime = $request->start_time . ':00';
            $endTime = $request->end_time . ':00';

            $exists = ServiceTimeSlot::query()
                ->where('start_time', $startTime)
                ->where('end_time', $endTime)
                ->exists();

            if ($exists) {
                DB::rollBack();

                return back()
                    ->withInput()
                    ->with('error', 'ช่วงเวลานี้มีอยู่แล้ว');
            }

            $label = trim((string) $request->label);

            if ($label === '') {
                $label = $request->start_time . ' - ' . $request->end_time;
            }

            ServiceTimeSlot::query()->create([
                'label' => $label,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'sort_order' => $request->sort_order ?? 0,
                'is_active' => $request->is_active ?? 1,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.branches.index')
                ->with('success', 'เพิ่มช่วงเวลารับบริการเรียบร้อยแล้ว');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Create service time slot error', [
                'message' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'ไม่สามารถเพิ่มช่วงเวลารับบริการได้');
        }
    }

    public function updateTimeSlot(Request $request, $id)
    {
        $serviceTimeSlot = ServiceTimeSlot::query()
            ->findOrFail($id);

        $request->validate([
            'label' => ['nullable', 'string', 'max:100'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'in:0,1'],
        ], [
            'start_time.required' => 'กรุณากรอกเวลาเริ่มต้น',
            'start_time.date_format' => 'รูปแบบเวลาเริ่มต้นไม่ถูกต้อง',
            'end_time.required' => 'กรุณากรอกเวลาสิ้นสุด',
            'end_time.date_format' => 'รูปแบบเวลาสิ้นสุดไม่ถูกต้อง',
            'end_time.after' => 'เวลาสิ้นสุดต้องมากกว่าเวลาเริ่มต้น',
        ]);

        DB::beginTransaction();

        try {
            $startTime = $request->start_time . ':00';
            $endTime = $request->end_time . ':00';

            $exists = ServiceTimeSlot::query()
                ->where('start_time', $startTime)
                ->where('end_time', $endTime)
                ->where('id', '!=', $serviceTimeSlot->id)
                ->exists();

            if ($exists) {
                DB::rollBack();

                return back()
                    ->withInput()
                    ->with('error', 'ช่วงเวลานี้มีอยู่แล้ว');
            }

            $label = trim((string) $request->label);

            if ($label === '') {
                $label = $request->start_time . ' - ' . $request->end_time;
            }

            $serviceTimeSlot->update([
                'label' => $label,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'sort_order' => $request->sort_order ?? 0,
                'is_active' => $request->is_active ?? 1,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.branches.index')
                ->with('success', 'บันทึกช่วงเวลารับบริการเรียบร้อยแล้ว');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Update service time slot error', [
                'id' => $id,
                'message' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'ไม่สามารถบันทึกช่วงเวลารับบริการได้');
        }
    }

    public function deleteTimeSlot(Request $request)
    {
        $request->validate([
            'id' => ['required', 'exists:service_time_slots,id'],
        ]);

        DB::beginTransaction();

        try {
            $serviceTimeSlot = ServiceTimeSlot::query()
                ->findOrFail($request->id);

            $serviceTimeSlot->delete();

            DB::commit();

            return redirect()
                ->route('admin.branches.index')
                ->with('success', 'ลบช่วงเวลารับบริการเรียบร้อยแล้ว');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Delete service time slot error', [
                'id' => $request->id,
                'message' => $e->getMessage(),
            ]);

            return back()
                ->with('error', 'ไม่สามารถลบช่วงเวลารับบริการได้');
        }
    }
}
