<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AboutPageSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Throwable;

class AboutPageController extends Controller
{
    public function edit()
    {
        $about = AboutPageSetting::query()->first();

        if (!$about) {
            $about = AboutPageSetting::query()->create([
                'hero_title' => 'เกี่ยวกับเรา',
                'hero_subtitle' => 'แพลตฟอร์มรับซื้อสินค้ามือสองที่สะดวก รวดเร็ว และเชื่อถือได้',
                'hero_background_color' => '#DFF3EA',

                'about_section_title' => 'เกี่ยวกับเรา',
                'about_company_title' => 'FinFin Phone.com',
                'about_description' => 'เป็นผู้ให้บริการแพลตฟอร์มออนไลน์ รับซื้อโทรศัพท์ทุกรุ่น ทุกยี่ห้อ iPad Macbook มือสองผ่านทางเว็บไซต์ โดยการันตีให้ราคาสูง รับซื้อเครื่องทุกสภาพ ลูกค้าสามารถเช็คราคาสินค้าที่จะขาย รู้ราคาภายใน 1 นาที และสามารถทำการขายได้ทันที โดยมีเจ้าหน้าที่ไปรับสินค้าถึงหน้าบ้าน หรือสถานที่ที่ลูกค้าสะดวกนัดหมาย เมื่อพนักงานตรวจเช็ครับสินค้า ทางบริษัทจ่ายเงินให้ลูกค้าทันที ฟรีค่าบริการ',

                'why_choose_title' => 'ทำไมถึงเลือกเรา',
                'why_choose_description' => 'ขายโทรศัพท์ได้ง่ายๆ ราบรื่น ตั้งแต่การตรวจสอบสภาพโทรศัพท์ฟรี ไปจนถึงการบริการถึงบ้านที่สะดวกรวดเร็วที่สุด',

                'feature_1_title' => 'ขั้นตอนง่าย',
                'feature_1_description' => '',

                'feature_2_title' => 'เชื่อถือได้และปลอดภัย',
                'feature_2_description' => '',

                'feature_3_title' => 'ราคาดีที่สุดสำหรับคุณ',
                'feature_3_description' => '',

                'feature_4_title' => 'ชำระเงินด่วน',
                'feature_4_description' => '',

                'footer_company_name' => 'Cashkub Co., Ltd.',
                'footer_company_description' => 'รับซื้อโทรศัพท์ทุกรุ่น พร้อมบริการถึงที่ให้ราคาดีที่คุณต้องถูกใจ',

                'contact_phone' => '0812345678',
                'contact_email' => 'hello@example.com',
                'social_facebook' => '#',
                'social_instagram' => '#',
                'social_line' => '#',
                'social_youtube' => '#',
            ]);
        }

        return view('admin.about-page.form', compact('about'));
    }

    public function update(Request $request)
    {
        $about = AboutPageSetting::query()->first();

        if (!$about) {
            $about = new AboutPageSetting();
        }

        $validated = $request->validate([
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_subtitle' => ['nullable', 'string', 'max:255'],
            'hero_background_color' => ['nullable', 'string', 'max:50'],

            'about_section_title' => ['nullable', 'string', 'max:255'],
            'about_company_title' => ['nullable', 'string', 'max:255'],
            'about_description' => ['nullable', 'string'],

            'why_choose_title' => ['nullable', 'string', 'max:255'],
            'why_choose_description' => ['nullable', 'string'],

            'feature_1_title' => ['nullable', 'string', 'max:255'],
            'feature_1_description' => ['nullable', 'string'],

            'feature_2_title' => ['nullable', 'string', 'max:255'],
            'feature_2_description' => ['nullable', 'string'],

            'feature_3_title' => ['nullable', 'string', 'max:255'],
            'feature_3_description' => ['nullable', 'string'],

            'feature_4_title' => ['nullable', 'string', 'max:255'],
            'feature_4_description' => ['nullable', 'string'],

            'hero_banner_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'about_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],

            'footer_company_name' => ['nullable', 'string', 'max:255'],
            'footer_company_description' => ['nullable', 'string'],

            'contact_phone' => ['nullable', 'string', 'max:100'],
            'contact_email' => ['nullable', 'string', 'max:255'],
            'social_facebook' => ['nullable', 'string', 'max:255'],
            'social_instagram' => ['nullable', 'string', 'max:255'],
            'social_line' => ['nullable', 'string', 'max:255'],
            'social_youtube' => ['nullable', 'string', 'max:255'],
        ], [
            'hero_banner_image.image' => 'รูป Hero Banner ต้องเป็นไฟล์รูปภาพเท่านั้น',
            'hero_banner_image.mimes' => 'รูป Hero Banner รองรับเฉพาะ jpg, jpeg, png, webp',
            'hero_banner_image.max' => 'รูป Hero Banner ต้องมีขนาดไม่เกิน 4MB',

            'about_image.image' => 'รูปเกี่ยวกับเราต้องเป็นไฟล์รูปภาพเท่านั้น',
            'about_image.mimes' => 'รูปเกี่ยวกับเรารองรับเฉพาะ jpg, jpeg, png, webp',
            'about_image.max' => 'รูปเกี่ยวกับเราต้องมีขนาดไม่เกิน 4MB',
        ]);

        try {
            if ($request->hasFile('hero_banner_image')) {
                if (!empty($about->hero_banner_image) && Storage::disk('public')->exists($about->hero_banner_image)) {
                    Storage::disk('public')->delete($about->hero_banner_image);
                }

                $validated['hero_banner_image'] = $request->file('hero_banner_image')
                    ->store('about-page', 'public');
            } else {
                unset($validated['hero_banner_image']);
            }

            if ($request->hasFile('about_image')) {
                if (!empty($about->about_image) && Storage::disk('public')->exists($about->about_image)) {
                    Storage::disk('public')->delete($about->about_image);
                }

                $validated['about_image'] = $request->file('about_image')
                    ->store('about-page', 'public');
            } else {
                unset($validated['about_image']);
            }

            $about->fill($validated);
            $about->save();

            return redirect()
                ->route('admin.about-page.edit')
                ->with('success', 'อัปเดตข้อมูลหน้าเกี่ยวกับเราเรียบร้อยแล้ว');
        } catch (Throwable $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'ไม่สามารถอัปเดตข้อมูลหน้าเกี่ยวกับเราได้: ' . $e->getMessage());
        }
    }
}
