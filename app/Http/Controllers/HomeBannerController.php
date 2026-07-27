<?php

namespace App\Http\Controllers;

use App\Models\HomeBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Throwable;

class HomeBannerController extends Controller
{
    public function index()
    {
        $banners = HomeBanner::query()
            ->orderByDesc('id')
            ->get();

        return view('admin.home-banner.index', compact('banners'));
    }

    public function form($id = null)
    {
        $banner = $id ? HomeBanner::query()->findOrFail($id) : new HomeBanner();

        return view('admin.home-banner.form', [
            'banner' => $banner,
            'mode' => $id ? 'edit' : 'create',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'desktop_image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'mobile_image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ], [
            'desktop_image.required' => 'กรุณาอัปโหลดรูปแบนเนอร์สำหรับเดสก์ท็อป',
            'desktop_image.image' => 'ไฟล์รูปเดสก์ท็อปไม่ถูกต้อง',
            'desktop_image.mimes' => 'รูปเดสก์ท็อปรองรับเฉพาะ jpg, jpeg, png, webp',
            'mobile_image.required' => 'กรุณาอัปโหลดรูปแบนเนอร์สำหรับมือถือ',
            'mobile_image.image' => 'ไฟล์รูปมือถือไม่ถูกต้อง',
            'mobile_image.mimes' => 'รูปมือถือรองรับเฉพาะ jpg, jpeg, png, webp',
        ]);

        try {
            $desktopImage = $request->file('desktop_image')->store('home-banners', 'public');
            $mobileImage = $request->file('mobile_image')->store('home-banners', 'public');

            HomeBanner::query()->create([
                'desktop_image' => $desktopImage,
                'mobile_image' => $mobileImage,
            ]);

            return redirect()->route('admin.home-banner.index')
                ->with('success', 'เพิ่มแบนเนอร์เรียบร้อยแล้ว');
        } catch (Throwable $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'ไม่สามารถเพิ่มแบนเนอร์ได้: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $banner = HomeBanner::query()->findOrFail($id);

        $validated = $request->validate([
            'desktop_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'mobile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ], [
            'desktop_image.image' => 'ไฟล์รูปเดสก์ท็อปไม่ถูกต้อง',
            'desktop_image.mimes' => 'รูปเดสก์ท็อปรองรับเฉพาะ jpg, jpeg, png, webp',
            'mobile_image.image' => 'ไฟล์รูปมือถือไม่ถูกต้อง',
            'mobile_image.mimes' => 'รูปมือถือรองรับเฉพาะ jpg, jpeg, png, webp',
        ]);

        try {
            $data = [];

            if ($request->hasFile('desktop_image')) {
                if (!empty($banner->desktop_image) && Storage::disk('public')->exists($banner->desktop_image)) {
                    Storage::disk('public')->delete($banner->desktop_image);
                }

                $data['desktop_image'] = $request->file('desktop_image')->store('home-banners', 'public');
            }

            if ($request->hasFile('mobile_image')) {
                if (!empty($banner->mobile_image) && Storage::disk('public')->exists($banner->mobile_image)) {
                    Storage::disk('public')->delete($banner->mobile_image);
                }

                $data['mobile_image'] = $request->file('mobile_image')->store('home-banners', 'public');
            }

            $banner->update($data);

            return redirect()->route('admin.home-banner.index')
                ->with('success', 'แก้ไขแบนเนอร์เรียบร้อยแล้ว');
        } catch (Throwable $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'ไม่สามารถแก้ไขแบนเนอร์ได้: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer', 'exists:home_banners,id'],
        ], [
            'id.required' => 'ไม่พบรหัสแบนเนอร์',
            'id.integer' => 'รหัสแบนเนอร์ไม่ถูกต้อง',
            'id.exists' => 'ไม่พบข้อมูลแบนเนอร์',
        ]);

        try {
            $banner = HomeBanner::query()->findOrFail($request->id);

            if (!empty($banner->desktop_image) && Storage::disk('public')->exists($banner->desktop_image)) {
                Storage::disk('public')->delete($banner->desktop_image);
            }

            if (!empty($banner->mobile_image) && Storage::disk('public')->exists($banner->mobile_image)) {
                Storage::disk('public')->delete($banner->mobile_image);
            }

            $banner->delete();

            return redirect()->route('admin.home-banner.index')
                ->with('success', 'ลบแบนเนอร์เรียบร้อยแล้ว');
        } catch (Throwable $e) {
            return redirect()->back()
                ->with('error', 'ไม่สามารถลบแบนเนอร์ได้: ' . $e->getMessage());
        }
    }
}