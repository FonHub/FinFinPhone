<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::query()
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.news', compact('news'));
    }

    public function formNews($id = null)
    {
        $news = null;

        if ($id) {
            $news = News::query()->findOrFail($id);
        }

        return view('admin.form_news', compact('news'));
    }

    public function saveNews(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'detail' => ['nullable', 'string'],
            'short_description' => ['nullable', 'string'],
            'status' => ['required', 'in:0,1'],
            'published_at' => ['nullable', 'date'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string'],
            'og_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'canonical_url' => ['nullable', 'string', 'max:255'],
            'seo_robots' => ['nullable', 'string', 'max:100'],
        ], [
            'title.required' => 'กรุณากรอกหัวข้อ',
            'status.required' => 'กรุณาเลือกสถานะ',
            'status.in' => 'สถานะไม่ถูกต้อง',
            'published_at.date' => 'รูปแบบวันที่เผยแพร่ไม่ถูกต้อง',
            'image.image' => 'รูปภาพหลักต้องเป็นไฟล์รูปภาพเท่านั้น',
            'image.mimes' => 'รูปภาพหลักรองรับเฉพาะ jpg, jpeg, png, webp',
            'image.max' => 'รูปภาพหลักต้องมีขนาดไม่เกิน 4MB',
            'og_image.image' => 'OG Image ต้องเป็นไฟล์รูปภาพเท่านั้น',
            'og_image.mimes' => 'OG Image รองรับเฉพาะ jpg, jpeg, png, webp',
            'og_image.max' => 'OG Image ต้องมีขนาดไม่เกิน 4MB',
        ]);

        try {
            if ($request->id) {
                $news = News::query()->findOrFail($request->id);
                $news->updated_by = $this->getAdminId();
            } else {
                $news = new News();
                $news->created_by = $this->getAdminId();
                $news->updated_by = $this->getAdminId();
            }

            $slugSource = $request->filled('slug')
                ? $request->slug
                : $request->title;

            $news->title = $request->title;
            $news->slug = $this->makeUniqueSlug($slugSource, $news->id ?? null);
            $news->detail = $request->detail;
            $news->short_description = $request->short_description;
            $news->status = (int) $request->status;
            $news->published_at = $request->published_at;
            $news->meta_title = $request->meta_title;
            $news->meta_description = $request->meta_description;
            $news->meta_keywords = $request->meta_keywords;
            $news->canonical_url = $request->canonical_url;
            $news->seo_robots = $request->seo_robots ?: 'index,follow';

            if ($request->hasFile('image')) {
                if (!empty($news->image) && Storage::disk('public')->exists($news->image)) {
                    Storage::disk('public')->delete($news->image);
                }

                $news->image = $request->file('image')->store('news', 'public');
            }

            if ($request->hasFile('og_image')) {
                if (!empty($news->og_image) && Storage::disk('public')->exists($news->og_image)) {
                    Storage::disk('public')->delete($news->og_image);
                }

                $news->og_image = $request->file('og_image')->store('news/og', 'public');
            }

            $news->save();

            return redirect('admin/news')
                ->with('success', $request->id ? 'แก้ไขข้อมูลเรียบร้อย' : 'เพิ่มข้อมูลเรียบร้อย');
        } catch (Throwable $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'ไม่สามารถบันทึกข้อมูลได้: ' . $e->getMessage());
        }
    }

    public function deleteNews(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer', 'exists:news,id'],
        ]);

        try {
            $news = News::query()->findOrFail($request->id);

            if (!empty($news->image) && Storage::disk('public')->exists($news->image)) {
                Storage::disk('public')->delete($news->image);
            }

            if (!empty($news->og_image) && Storage::disk('public')->exists($news->og_image)) {
                Storage::disk('public')->delete($news->og_image);
            }

            $news->delete();

            return redirect('admin/news')
                ->with('success', 'ลบข้อมูลเรียบร้อย');
        } catch (Throwable $e) {
            return redirect()
                ->back()
                ->with('error', 'ไม่สามารถลบข้อมูลได้: ' . $e->getMessage());
        }
    }

    public function uploadEditorImage(Request $request)
    {
        $request->validate([
            'file' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
        ]);

        $path = $request->file('file')->store('news/editor', 'public');

        return response()->json([
            'location' => asset('storage/' . $path),
        ]);
    }

    private function makeUniqueSlug($value, $ignoreId = null)
    {
        $slug = Str::slug($value);

        if (empty($slug)) {
            $slug = 'news-' . now()->format('YmdHis');
        }

        $baseSlug = $slug;
        $counter = 1;

        while (
            News::query()
            ->where('slug', $slug)
            ->when($ignoreId, function ($query) use ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            })
            ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function getAdminId()
    {
        if (config('auth.guards.admin') && Auth::guard('admin')->check()) {
            return Auth::guard('admin')->id();
        }

        if (Auth::check()) {
            return Auth::id();
        }

        return null;
    }
}
