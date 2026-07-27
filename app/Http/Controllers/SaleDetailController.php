<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SaleDetailSection;
use App\Models\SaleDetailTab;
use App\Models\SaleDetailStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SaleDetailController extends Controller
{
    public function edit()
    {
        $saleDetail = SaleDetailSection::with(['tabs.steps'])
            ->where('page_key', 'product_sale_detail')
            ->first();

        if (!$saleDetail) {
            $saleDetail = SaleDetailSection::create([
                'page_key' => 'product_sale_detail',
                'title' => 'รายละเอียดการขายสินค้า',
                'sub_title' => null,
                'status' => 'active',
            ]);

            $pickupTab = SaleDetailTab::create([
                'sale_detail_section_id' => $saleDetail->id,
                'tab_key' => 'pickup',
                'name' => 'รับถึงที่',
                'sort_order' => 1,
                'status' => 'active',
            ]);

            $deliveryTab = SaleDetailTab::create([
                'sale_detail_section_id' => $saleDetail->id,
                'tab_key' => 'delivery',
                'name' => 'ส่งพัสดุ',
                'sort_order' => 2,
                'status' => 'active',
            ]);

            SaleDetailStep::insert([
                [
                    'sale_detail_tab_id' => $pickupTab->id,
                    'step_label' => 'ขั้นตอนที่ 1',
                    'title' => 'ดูแลการแลกอุปกรณ์ของคุณ',
                    'description' => 'รายละเอียดขั้นตอน',
                    'sort_order' => 1,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'sale_detail_tab_id' => $pickupTab->id,
                    'step_label' => 'ขั้นตอนที่ 2',
                    'title' => 'เตรียมอุปกรณ์พร้อมแลก',
                    'description' => 'รายละเอียดขั้นตอน',
                    'sort_order' => 2,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'sale_detail_tab_id' => $pickupTab->id,
                    'step_label' => 'ขั้นตอนที่ 3',
                    'title' => 'ส่งพนักงานไปบริษัท',
                    'description' => 'รายละเอียดขั้นตอน',
                    'sort_order' => 3,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'sale_detail_tab_id' => $deliveryTab->id,
                    'step_label' => 'ขั้นตอนที่ 1',
                    'title' => 'ดูแลการแลกอุปกรณ์ของคุณ',
                    'description' => 'รายละเอียดขั้นตอน',
                    'sort_order' => 1,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'sale_detail_tab_id' => $deliveryTab->id,
                    'step_label' => 'ขั้นตอนที่ 2',
                    'title' => 'เตรียมอุปกรณ์พร้อมแลก',
                    'description' => 'รายละเอียดขั้นตอน',
                    'sort_order' => 2,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'sale_detail_tab_id' => $deliveryTab->id,
                    'step_label' => 'ขั้นตอนที่ 3',
                    'title' => 'ส่งพนักงานไปบริษัท',
                    'description' => 'รายละเอียดขั้นตอน',
                    'sort_order' => 3,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);

            $saleDetail->load(['tabs.steps']);
        }

        return view('admin.sale_detail.form', compact('saleDetail'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'id' => ['nullable', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'sub_title' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:active,inactive'],
            'tabs' => ['required', 'array'],
            'tabs.*.id' => ['nullable', 'integer'],
            'tabs.*.tab_key' => ['nullable', 'string', 'max:100'],
            'tabs.*.name' => ['required', 'string', 'max:255'],
            'tabs.*.sort_order' => ['nullable', 'integer'],
            'tabs.*.status' => ['required', 'in:active,inactive'],
            'tabs.*.steps' => ['nullable', 'array'],
            'tabs.*.steps.*.id' => ['nullable', 'integer'],
            'tabs.*.steps.*.step_label' => ['nullable', 'string', 'max:255'],
            'tabs.*.steps.*.title' => ['nullable', 'string', 'max:255'],
            'tabs.*.steps.*.description' => ['nullable', 'string'],
            'tabs.*.steps.*.sort_order' => ['nullable', 'integer'],
            'tabs.*.steps.*.status' => ['required', 'in:active,inactive'],
            'tabs.*.steps.*.image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'tabs.*.steps.*.old_image' => ['nullable', 'string'],
        ]);

        DB::beginTransaction();

        try {
            $saleDetail = SaleDetailSection::updateOrCreate(
                [
                    'id' => $request->id,
                ],
                [
                    'page_key' => 'product_sale_detail',
                    'title' => $request->title,
                    'sub_title' => $request->sub_title,
                    'status' => $request->status,
                ]
            );

            $oldTabIds = SaleDetailTab::where('sale_detail_section_id', $saleDetail->id)->pluck('id')->toArray();
            $keepTabIds = [];

            foreach ($request->tabs as $tabIndex => $tabData) {
                $tab = SaleDetailTab::updateOrCreate(
                    [
                        'id' => $tabData['id'] ?? null,
                    ],
                    [
                        'sale_detail_section_id' => $saleDetail->id,
                        'tab_key' => $tabData['tab_key'] ?? null,
                        'name' => $tabData['name'],
                        'sort_order' => $tabData['sort_order'] ?? ($tabIndex + 1),
                        'status' => $tabData['status'],
                    ]
                );

                $keepTabIds[] = $tab->id;

                $oldStepIds = SaleDetailStep::where('sale_detail_tab_id', $tab->id)->pluck('id')->toArray();
                $keepStepIds = [];

                if (!empty($tabData['steps'])) {
                    foreach ($tabData['steps'] as $stepIndex => $stepData) {
                        $imagePath = $stepData['old_image'] ?? null;

                        if ($request->hasFile("tabs.$tabIndex.steps.$stepIndex.image")) {
                            if (!empty($imagePath) && Storage::disk('public')->exists($imagePath)) {
                                Storage::disk('public')->delete($imagePath);
                            }

                            $file = $request->file("tabs.$tabIndex.steps.$stepIndex.image");
                            $fileName = 'sale-detail/' . date('Y/m') . '/' . Str::uuid() . '.' . $file->getClientOriginalExtension();
                            Storage::disk('public')->put($fileName, file_get_contents($file));
                            $imagePath = $fileName;
                        }

                        $step = SaleDetailStep::updateOrCreate(
                            [
                                'id' => $stepData['id'] ?? null,
                            ],
                            [
                                'sale_detail_tab_id' => $tab->id,
                                'step_label' => $stepData['step_label'] ?? null,
                                'title' => $stepData['title'] ?? null,
                                'description' => $stepData['description'] ?? null,
                                'image' => $imagePath,
                                'sort_order' => $stepData['sort_order'] ?? ($stepIndex + 1),
                                'status' => $stepData['status'],
                            ]
                        );

                        $keepStepIds[] = $step->id;
                    }
                }

                $deleteStepIds = array_diff($oldStepIds, $keepStepIds);
                if (!empty($deleteStepIds)) {
                    $deleteSteps = SaleDetailStep::whereIn('id', $deleteStepIds)->get();
                    foreach ($deleteSteps as $deleteStep) {
                        if (!empty($deleteStep->image) && Storage::disk('public')->exists($deleteStep->image)) {
                            Storage::disk('public')->delete($deleteStep->image);
                        }
                        $deleteStep->delete();
                    }
                }
            }

            $deleteTabIds = array_diff($oldTabIds, $keepTabIds);
            if (!empty($deleteTabIds)) {
                $deleteTabs = SaleDetailTab::with('steps')->whereIn('id', $deleteTabIds)->get();

                foreach ($deleteTabs as $deleteTab) {
                    foreach ($deleteTab->steps as $deleteStep) {
                        if (!empty($deleteStep->image) && Storage::disk('public')->exists($deleteStep->image)) {
                            Storage::disk('public')->delete($deleteStep->image);
                        }
                    }
                    $deleteTab->delete();
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'บันทึกข้อมูลรายละเอียดการขายสินค้าเรียบร้อยแล้ว');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }
}