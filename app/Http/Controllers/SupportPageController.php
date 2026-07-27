<?php

namespace App\Http\Controllers;

use App\Models\SupportPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class SupportPageController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.support-pages.edit', ['slug' => 'cancel-selling']);
    }

    public function edit($slug = null)
    {
        $slug = $slug ?: 'cancel-selling';

        $supportPage = SupportPage::query()
            ->with([
                'sections' => function ($query) {
                    $query->with([
                        'items' => function ($itemQuery) {
                            $itemQuery->orderBy('sort_order', 'asc')
                                ->orderBy('id', 'asc');
                        },
                    ])
                        ->orderBy('sort_order', 'asc')
                        ->orderBy('id', 'asc');
                },
            ])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('admin.support-pages.form', compact('supportPage'));
    }

    public function update(Request $request, $slug)
    {
        $supportPage = SupportPage::query()
            ->where('slug', $slug)
            ->firstOrFail();

        $request->validate([
            'menu_label' => ['nullable', 'string', 'max:255'],
            'page_title' => ['nullable', 'string', 'max:255'],
            'breadcrumb_title' => ['nullable', 'string', 'max:255'],

            'badge_text' => ['nullable', 'string', 'max:255'],
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_description' => ['nullable', 'string'],
            'primary_button_text' => ['nullable', 'string', 'max:255'],
            'primary_button_url' => ['nullable', 'string', 'max:255'],
            'secondary_button_text' => ['nullable', 'string', 'max:255'],
            'secondary_button_url' => ['nullable', 'string', 'max:255'],

            'contact_title' => ['nullable', 'string', 'max:255'],
            'contact_description' => ['nullable', 'string'],
            'contact_phone_label' => ['nullable', 'string', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:100'],
            'contact_email_label' => ['nullable', 'string', 'max:255'],
            'contact_email' => ['nullable', 'string', 'max:255'],
            'contact_time_label' => ['nullable', 'string', 'max:255'],
            'contact_time' => ['nullable', 'string', 'max:255'],

            'note_icon' => ['nullable', 'string', 'max:100'],
            'note_title' => ['nullable', 'string', 'max:255'],
            'note_description' => ['nullable', 'string'],
            'call_button_text' => ['nullable', 'string', 'max:255'],
            'call_button_url' => ['nullable', 'string', 'max:255'],

            'status' => ['required', 'in:0,1'],
            'sort_order' => ['nullable', 'integer', 'min:0'],

            'sections' => ['nullable', 'array'],
            'sections.*.id' => ['required', 'integer'],
            'sections.*.section_key' => ['nullable', 'string', 'max:100'],
            'sections.*.label' => ['nullable', 'string', 'max:255'],
            'sections.*.title' => ['nullable', 'string', 'max:255'],
            'sections.*.description' => ['nullable', 'string'],
            'sections.*.layout_type' => ['nullable', 'string', 'max:100'],
            'sections.*.status' => ['nullable', 'in:0,1'],
            'sections.*.sort_order' => ['nullable', 'integer', 'min:0'],

            'sections.*.items' => ['nullable', 'array'],
            'sections.*.items.*.id' => ['required', 'integer'],
            'sections.*.items.*.item_no' => ['nullable', 'string', 'max:50'],
            'sections.*.items.*.icon' => ['nullable', 'string', 'max:100'],
            'sections.*.items.*.title' => ['nullable', 'string', 'max:255'],
            'sections.*.items.*.description' => ['nullable', 'string'],
            'sections.*.items.*.link_text' => ['nullable', 'string', 'max:255'],
            'sections.*.items.*.link_url' => ['nullable', 'string', 'max:255'],
            'sections.*.items.*.status' => ['nullable', 'in:0,1'],
            'sections.*.items.*.sort_order' => ['nullable', 'integer', 'min:0'],
        ], [
            'status.required' => 'กรุณาเลือกสถานะหน้า',
            'status.in' => 'สถานะหน้าไม่ถูกต้อง',
            'sort_order.integer' => 'ลำดับหน้าต้องเป็นตัวเลข',
            'sections.*.id.required' => 'ไม่พบรหัส Section เดิม จึงไม่สามารถบันทึกได้',
            'sections.*.items.*.id.required' => 'ไม่พบรหัสรายการเดิม จึงไม่สามารถบันทึกได้',
            'sections.*.section_key.max' => 'Section Key ต้องไม่เกิน 100 ตัวอักษร',
            'sections.*.title.max' => 'หัวข้อ Section ต้องไม่เกิน 255 ตัวอักษร',
            'sections.*.items.*.title.max' => 'หัวข้อรายการต้องไม่เกิน 255 ตัวอักษร',
        ]);

        DB::beginTransaction();

        try {
            $supportPage->update([
                'menu_label' => $request->menu_label,
                'page_title' => $request->page_title,
                'breadcrumb_title' => $request->breadcrumb_title,

                'badge_text' => $request->badge_text,
                'hero_title' => $request->hero_title,
                'hero_description' => $request->hero_description,
                'primary_button_text' => $request->primary_button_text,
                'primary_button_url' => $request->primary_button_url,
                'secondary_button_text' => $request->secondary_button_text,
                'secondary_button_url' => $request->secondary_button_url,

                'contact_title' => $request->contact_title,
                'contact_description' => $request->contact_description,
                'contact_phone_label' => $request->contact_phone_label,
                'contact_phone' => $request->contact_phone,
                'contact_email_label' => $request->contact_email_label,
                'contact_email' => $request->contact_email,
                'contact_time_label' => $request->contact_time_label,
                'contact_time' => $request->contact_time,

                'note_icon' => $request->note_icon,
                'note_title' => $request->note_title,
                'note_description' => $request->note_description,
                'call_button_text' => $request->call_button_text,
                'call_button_url' => $request->call_button_url,

                'status' => (int) $request->status,
                'sort_order' => (int) ($request->sort_order ?? 0),
            ]);

            foreach (($request->sections ?? []) as $sectionRow) {
                $sectionId = (int) ($sectionRow['id'] ?? 0);

                if ($sectionId <= 0) {
                    continue;
                }

                $section = $supportPage->sections()
                    ->where('id', $sectionId)
                    ->first();

                if (!$section) {
                    continue;
                }

                $section->update([
                    'section_key' => $sectionRow['section_key'] ?? null,
                    'label' => $sectionRow['label'] ?? null,
                    'title' => $sectionRow['title'] ?? null,
                    'description' => $sectionRow['description'] ?? null,
                    'layout_type' => $sectionRow['layout_type'] ?? null,
                    'status' => (int) ($sectionRow['status'] ?? 1),
                    'sort_order' => (int) ($sectionRow['sort_order'] ?? 0),
                ]);

                foreach (($sectionRow['items'] ?? []) as $itemRow) {
                    $itemId = (int) ($itemRow['id'] ?? 0);

                    if ($itemId <= 0) {
                        continue;
                    }

                    $item = $section->items()
                        ->where('id', $itemId)
                        ->first();

                    if (!$item) {
                        continue;
                    }

                    $item->update([
                        'item_no' => $itemRow['item_no'] ?? null,
                        'icon' => $itemRow['icon'] ?? null,
                        'title' => $itemRow['title'] ?? null,
                        'description' => $itemRow['description'] ?? null,
                        'link_text' => $itemRow['link_text'] ?? null,
                        'link_url' => $itemRow['link_url'] ?? null,
                        'status' => (int) ($itemRow['status'] ?? 1),
                        'sort_order' => (int) ($itemRow['sort_order'] ?? 0),
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.support-pages.edit', ['slug' => $supportPage->slug])
                ->with('success', 'บันทึกข้อมูลเรียบร้อยแล้ว');
        } catch (Throwable $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'ไม่สามารถบันทึกข้อมูลได้: ' . $e->getMessage());
        }
    }
}
