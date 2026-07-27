<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TransitLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransitLineController extends Controller
{
    public function index()
    {
        $lines = TransitLine::query()
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.transit-lines.index', compact('lines'));
    }

    public function create()
    {
        $mode = 'create';

        $line = new TransitLine();
        $line->is_active = 1;
        $line->sort_order = 0;

        return view('admin.transit-lines.form', compact('mode', 'line'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:transit_lines,code'],
            'name' => ['required', 'string', 'max:255'],
            'operator_name' => ['nullable', 'string', 'max:255'],
            'line_color' => ['nullable', 'string', 'max:50'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'in:0,1'],
        ], [
            'code.required' => 'กรุณากรอกรหัสสายรถไฟฟ้า',
            'code.unique' => 'รหัสสายรถไฟฟ้านี้มีอยู่แล้ว',
            'name.required' => 'กรุณากรอกชื่อสายรถไฟฟ้า',
        ]);

        DB::beginTransaction();

        try {
            TransitLine::create([
                'code' => $request->code,
                'name' => $request->name,
                'operator_name' => $request->operator_name,
                'line_color' => $request->line_color,
                'sort_order' => $request->sort_order ?? 0,
                'is_active' => $request->is_active ?? 0,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.transit-lines.index')
                ->with('success', 'เพิ่มข้อมูลสายรถไฟฟ้าเรียบร้อยแล้ว');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Create transit line error', [
                'message' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'ไม่สามารถเพิ่มข้อมูลสายรถไฟฟ้าได้');
        }
    }

    public function edit($id)
    {
        $mode = 'edit';

        $line = TransitLine::query()
            ->findOrFail($id);

        return view('admin.transit-lines.form', compact('mode', 'line'));
    }

    public function update(Request $request, $id)
    {
        $line = TransitLine::query()
            ->findOrFail($id);

        $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:transit_lines,code,' . $line->id],
            'name' => ['required', 'string', 'max:255'],
            'operator_name' => ['nullable', 'string', 'max:255'],
            'line_color' => ['nullable', 'string', 'max:50'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'in:0,1'],
        ], [
            'code.required' => 'กรุณากรอกรหัสสายรถไฟฟ้า',
            'code.unique' => 'รหัสสายรถไฟฟ้านี้มีอยู่แล้ว',
            'name.required' => 'กรุณากรอกชื่อสายรถไฟฟ้า',
        ]);

        DB::beginTransaction();

        try {
            $line->update([
                'code' => $request->code,
                'name' => $request->name,
                'operator_name' => $request->operator_name,
                'line_color' => $request->line_color,
                'sort_order' => $request->sort_order ?? 0,
                'is_active' => $request->is_active ?? 0,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.transit-lines.index')
                ->with('success', 'บันทึกข้อมูลสายรถไฟฟ้าเรียบร้อยแล้ว');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Update transit line error', [
                'id' => $id,
                'message' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'ไม่สามารถบันทึกข้อมูลสายรถไฟฟ้าได้');
        }
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => ['required', 'exists:transit_lines,id'],
        ]);

        DB::beginTransaction();

        try {
            $line = TransitLine::query()
                ->withCount('stations')
                ->findOrFail($request->id);

            if ($line->stations_count > 0) {
                return back()->with('error', 'ไม่สามารถลบได้ เนื่องจากมีสถานีอยู่ในสายนี้');
            }

            $line->delete();

            DB::commit();

            return redirect()
                ->route('admin.transit-lines.index')
                ->with('success', 'ลบข้อมูลสายรถไฟฟ้าเรียบร้อยแล้ว');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Delete transit line error', [
                'id' => $request->id,
                'message' => $e->getMessage(),
            ]);

            return back()
                ->with('error', 'ไม่สามารถลบข้อมูลสายรถไฟฟ้าได้');
        }
    }
}