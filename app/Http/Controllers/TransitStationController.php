<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TransitLine;
use App\Models\TransitStation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransitStationController extends Controller
{
    public function index()
    {
        $stations = TransitStation::query()
            ->with('line')
            ->orderBy('line_id', 'asc')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.transit-stations.index', compact('stations'));
    }

    public function create()
    {
        $mode = 'create';

        $station = new TransitStation();
        $station->is_active = 1;
        $station->sort_order = 0;

        $lines = TransitLine::query()
            ->where('is_active', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.transit-stations.form', compact('mode', 'station', 'lines'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'line_id' => ['required', 'exists:transit_lines,id'],
            'station_code' => ['nullable', 'string', 'max:50'],
            'name_th' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'province_name' => ['nullable', 'string', 'max:255'],
            'district_name' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'in:0,1'],
        ], [
            'line_id.required' => 'กรุณาเลือกสายรถไฟฟ้า',
            'line_id.exists' => 'ไม่พบข้อมูลสายรถไฟฟ้า',
            'name_th.required' => 'กรุณากรอกชื่อสถานีภาษาไทย',
        ]);

        DB::beginTransaction();

        try {
            TransitStation::create([
                'line_id' => $request->line_id,
                'station_code' => $request->station_code,
                'name_th' => $request->name_th,
                'name_en' => $request->name_en,
                'province_name' => $request->province_name,
                'district_name' => $request->district_name,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'sort_order' => $request->sort_order ?? 0,
                'is_active' => $request->is_active ?? 0,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.transit-stations.index')
                ->with('success', 'เพิ่มข้อมูลสถานีเรียบร้อยแล้ว');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Create transit station error', [
                'message' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'ไม่สามารถเพิ่มข้อมูลสถานีได้');
        }
    }

    public function edit($id)
    {
        $mode = 'edit';

        $station = TransitStation::query()
            ->findOrFail($id);

        $lines = TransitLine::query()
            ->where('is_active', 1)
            ->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.transit-stations.form', compact('mode', 'station', 'lines'));
    }

    public function update(Request $request, $id)
    {
        $station = TransitStation::query()
            ->findOrFail($id);

        $request->validate([
            'line_id' => ['required', 'exists:transit_lines,id'],
            'station_code' => ['nullable', 'string', 'max:50'],
            'name_th' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'province_name' => ['nullable', 'string', 'max:255'],
            'district_name' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'in:0,1'],
        ], [
            'line_id.required' => 'กรุณาเลือกสายรถไฟฟ้า',
            'line_id.exists' => 'ไม่พบข้อมูลสายรถไฟฟ้า',
            'name_th.required' => 'กรุณากรอกชื่อสถานีภาษาไทย',
        ]);

        DB::beginTransaction();

        try {
            $station->update([
                'line_id' => $request->line_id,
                'station_code' => $request->station_code,
                'name_th' => $request->name_th,
                'name_en' => $request->name_en,
                'province_name' => $request->province_name,
                'district_name' => $request->district_name,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'sort_order' => $request->sort_order ?? 0,
                'is_active' => $request->is_active ?? 0,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.transit-stations.index')
                ->with('success', 'บันทึกข้อมูลสถานีเรียบร้อยแล้ว');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Update transit station error', [
                'id' => $id,
                'message' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'ไม่สามารถบันทึกข้อมูลสถานีได้');
        }
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => ['required', 'exists:transit_stations,id'],
        ]);

        DB::beginTransaction();

        try {
            $station = TransitStation::query()
                ->findOrFail($request->id);

            $station->delete();

            DB::commit();

            return redirect()
                ->route('admin.transit-stations.index')
                ->with('success', 'ลบข้อมูลสถานีเรียบร้อยแล้ว');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Delete transit station error', [
                'id' => $request->id,
                'message' => $e->getMessage(),
            ]);

            return back()
                ->with('error', 'ไม่สามารถลบข้อมูลสถานีได้');
        }
    }
}