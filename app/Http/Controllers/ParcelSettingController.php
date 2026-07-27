<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SellMethod;
use App\Models\SellMethodParcelSetting;
use App\Models\SellMethodRequiredDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ParcelSettingController extends Controller
{
    public function edit()
    {
        $sellMethod = SellMethod::query()
            ->where('key', 'parcel')
            ->firstOrFail();

        $parcelSetting = SellMethodParcelSetting::query()
            ->where('sell_method_id', $sellMethod->id)
            ->first();

        if (!$parcelSetting) {
            $parcelSetting = new SellMethodParcelSetting();
            $parcelSetting->sell_method_id = $sellMethod->id;
            $parcelSetting->is_active = 1;
        }

        $documents = SellMethodRequiredDocument::query()
            ->where('sell_method_id', $sellMethod->id)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.parcel-setting.form', compact(
            'sellMethod',
            'parcelSetting',
            'documents'
        ));
    }

    public function update(Request $request)
    {
        $request->validate([
            'receiver_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['required', 'string'],
            'subdistrict' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'province' => ['nullable', 'string', 'max:255'],
            'postcode' => ['nullable', 'string', 'max:20'],
            'remark' => ['nullable', 'string'],
            'is_active' => ['nullable', 'in:0,1'],
        ], [
            'receiver_name.required' => 'กรุณากรอกชื่อผู้รับ / ชื่อศูนย์',
            'address.required' => 'กรุณากรอกที่อยู่จัดส่ง',
        ]);

        DB::beginTransaction();

        try {
            $sellMethod = SellMethod::query()
                ->where('key', 'parcel')
                ->firstOrFail();

            SellMethodParcelSetting::updateOrCreate(
                [
                    'sell_method_id' => $sellMethod->id,
                ],
                [
                    'receiver_name' => $request->receiver_name,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'subdistrict' => $request->subdistrict,
                    'district' => $request->district,
                    'province' => $request->province,
                    'postcode' => $request->postcode,
                    'remark' => $request->remark,
                    'is_active' => $request->is_active ?? 0,
                ]
            );

            DB::commit();

            return redirect()
                ->route('admin.parcel-setting.edit')
                ->with('success', 'บันทึกข้อมูลศูนย์ใหญ่รับพัสดุเรียบร้อยแล้ว');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Update parcel setting error', [
                'message' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'ไม่สามารถบันทึกข้อมูลได้');
        }
    }

    public function storeDocument(Request $request)
    {
        $request->validate([
            'document_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_required' => ['nullable', 'in:0,1'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'in:0,1'],
        ], [
            'document_name.required' => 'กรุณากรอกชื่อเอกสาร',
        ]);

        DB::beginTransaction();

        try {
            $sellMethod = SellMethod::query()
                ->where('key', 'parcel')
                ->firstOrFail();

            SellMethodRequiredDocument::create([
                'sell_method_id' => $sellMethod->id,
                'document_name' => $request->document_name,
                'description' => $request->description,
                'is_required' => $request->is_required ?? 0,
                'sort_order' => $request->sort_order ?? 0,
                'is_active' => $request->is_active ?? 0,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.parcel-setting.edit')
                ->with('success', 'เพิ่มเอกสารเรียบร้อยแล้ว');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Create parcel document error', [
                'message' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'ไม่สามารถเพิ่มเอกสารได้');
        }
    }

    public function updateDocument(Request $request, $id)
    {
        $document = SellMethodRequiredDocument::query()
            ->findOrFail($id);

        $request->validate([
            'document_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_required' => ['nullable', 'in:0,1'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'in:0,1'],
        ], [
            'document_name.required' => 'กรุณากรอกชื่อเอกสาร',
        ]);

        DB::beginTransaction();

        try {
            $document->update([
                'document_name' => $request->document_name,
                'description' => $request->description,
                'is_required' => $request->is_required ?? 0,
                'sort_order' => $request->sort_order ?? 0,
                'is_active' => $request->is_active ?? 0,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.parcel-setting.edit')
                ->with('success', 'บันทึกเอกสารเรียบร้อยแล้ว');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Update parcel document error', [
                'id' => $id,
                'message' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'ไม่สามารถบันทึกเอกสารได้');
        }
    }

    public function deleteDocument(Request $request)
    {
        $request->validate([
            'id' => ['required', 'exists:sell_method_required_documents,id'],
        ]);

        DB::beginTransaction();

        try {
            $document = SellMethodRequiredDocument::query()
                ->findOrFail($request->id);

            $document->delete();

            DB::commit();

            return redirect()
                ->route('admin.parcel-setting.edit')
                ->with('success', 'ลบเอกสารเรียบร้อยแล้ว');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Delete parcel document error', [
                'id' => $request->id,
                'message' => $e->getMessage(),
            ]);

            return back()
                ->with('error', 'ไม่สามารถลบเอกสารได้');
        }
    }
}
