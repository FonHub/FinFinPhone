<?php

namespace App\Http\Controllers;

use App\Models\AboutOurValue;
use App\Models\AboutSince;
use App\Models\AboutUsCulture;
use App\Models\AboutUsLegacy;
use App\Models\CapabilitiesFactory;
use App\Models\CapabilitiesFarm;
use App\Models\CapabilitiesTannery;
use App\Models\ContactIndustry;
use App\Models\ContactTopic;
use App\Models\ContactUs;
use App\Models\Finished;
use App\Models\Home;
use App\Models\Leather;
use App\Models\News;
use App\Models\Odm;
use App\Models\Oem;
use App\Models\ProductsFinished;
use App\Models\ProductsLeather;
use App\Models\ProductsRiver;
use App\Models\ProductsRochelle;
use App\Models\River;
use App\Models\Rochelle;
use App\Models\SaltedSK;
use App\Models\Sustainability;
use App\Models\TopMenu;
use App\Models\WorkFactory;
use App\Models\WorkFarm;
use App\Models\WorkTannery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EditSettingController extends Controller
{
    // public function editUser(Request $request)
    // {
    //     $user = User::find($request->id);
    //     // $user->title = $request->title;
    //     $user->name = $request->name;
    //     $user->position = $request->position;
    //     $user->email = $request->email;
    //     // $user->password = $request->password;
    //     $user->save();
    //     return redirect('/user');
    // }

    public function homeEdit(Request $request)
    {
        $request->validate([
            // 'video' => 'required|mimes:mp4,mov,avi,wmv|max:102400', // 100MB
        ]);

        $home = Home::first();

        if ($request->hasFile('video')) {
            $file = $request->file('video');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('videos'), $fileName); // เก็บที่ public/videos/
            $home->video = 'videos/' . $fileName;
        }

        $home->text = $request->text;
        $home->text_1 = $request->text_1;
        $home->text_2 = $request->text_2;

        if ($request->hasFile('image_1')) {
            $file = $request->file('image_1');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/home/');
            $file->move($destinationPath, $filename);
            $home->image_1 = 'image/home/' . $filename;
        }
        if ($request->hasFile('image_2')) {
            $file = $request->file('image_2');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/home/');
            $file->move($destinationPath, $filename);
            $home->image_2 = 'image/home/' . $filename;
        }
        if ($request->hasFile('image_3')) {
            $file = $request->file('image_3');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/home/');
            $file->move($destinationPath, $filename);
            $home->image_3 = 'image/home/' . $filename;
        }
        if ($request->hasFile('image_4')) {
            $file = $request->file('image_4');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/home/');
            $file->move($destinationPath, $filename);
            $home->image_4 = 'image/home/' . $filename;
        }

        // =========================
        // ✅ บันทึก 3 ชุดใหม่ (text)
        // =========================
        $home->top_text_1 = $request->top_text_1;
        $home->link_text_1 = $request->link_text_1;
        $home->link_url_1 = $request->link_url_1;
        $home->bottom_text_1 = $request->bottom_text_1;

        $home->top_text_2 = $request->top_text_2;
        $home->link_text_2 = $request->link_text_2;
        $home->link_url_2 = $request->link_url_2;
        $home->bottom_text_2 = $request->bottom_text_2;

        $home->top_text_3 = $request->top_text_3;
        $home->link_url_3 = $request->link_url_3;
        $home->link_text_3 = $request->link_text_3;
        $home->bottom_text_3 = $request->bottom_text_3;

        // =========================
        // ✅ ลบรูปเดิม (ถ้าติ๊ก remove)
        // =========================
        if ($request->input('remove_image_1') == '1') {
            if (!empty($home->image_path_1) && file_exists(public_path($home->image_path_1))) {
                @unlink(public_path($home->image_path_1));
            }
            $home->image_path_1 = null;
        }
        if ($request->input('remove_image_2') == '1') {
            if (!empty($home->image_path_2) && file_exists(public_path($home->image_path_2))) {
                @unlink(public_path($home->image_path_2));
            }
            $home->image_path_2 = null;
        }
        if ($request->input('remove_image_3') == '1') {
            if (!empty($home->image_path_3) && file_exists(public_path($home->image_path_3))) {
                @unlink(public_path($home->image_path_3));
            }
            $home->image_path_3 = null;
        }

        // =========================
        // ✅ อัปโหลดรูปใหม่ (image_path_1..3)
        // =========================
        if ($request->hasFile('image_path_1')) {
            // ลบรูปเก่าก่อน (ถ้ามี)
            if (!empty($home->image_path_1) && file_exists(public_path($home->image_path_1))) {
                @unlink(public_path($home->image_path_1));
            }

            $file = $request->file('image_path_1');
            $filename = time() . '_1_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/home_cards/');
            if (!file_exists($destinationPath)) {
                @mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $filename);
            $home->image_path_1 = 'image/home_cards/' . $filename;
        }

        if ($request->hasFile('image_path_2')) {
            if (!empty($home->image_path_2) && file_exists(public_path($home->image_path_2))) {
                @unlink(public_path($home->image_path_2));
            }

            $file = $request->file('image_path_2');
            $filename = time() . '_2_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/home_cards/');
            if (!file_exists($destinationPath)) {
                @mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $filename);
            $home->image_path_2 = 'image/home_cards/' . $filename;
        }

        if ($request->hasFile('image_path_3')) {
            if (!empty($home->image_path_3) && file_exists(public_path($home->image_path_3))) {
                @unlink(public_path($home->image_path_3));
            }

            $file = $request->file('image_path_3');
            $filename = time() . '_3_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/home_cards/');
            if (!file_exists($destinationPath)) {
                @mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $filename);
            $home->image_path_3 = 'image/home_cards/' . $filename;
        }
        $home->user_id = session('login');
        $home->save();
        return redirect()->back()->with('success', 'แก้ไขข้อมูลสำเร็จ');
    }

    public function menu(Request $request)
    {

        $menu = TopMenu::first();
        if ($request->hasFile('about_us')) {
            $file = $request->file('about_us');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/menu/');
            $file->move($destinationPath, $filename);
            $menu->about_us = 'image/menu/' . $filename;
        }
        if ($request->hasFile('our_capabilities')) {
            $file = $request->file('our_capabilities');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/menu/');
            $file->move($destinationPath, $filename);
            $menu->our_capabilities = 'image/menu/' . $filename;
        }
        if ($request->hasFile('products')) {
            $file = $request->file('products');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/menu/');
            $file->move($destinationPath, $filename);
            $menu->products = 'image/menu/' . $filename;
        }
        if ($request->hasFile('brands')) {
            $file = $request->file('brands');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/menu/');
            $file->move($destinationPath, $filename);
            $menu->brands = 'image/menu/' . $filename;
        }
        $menu->user_id = session('login');
        $menu->save();
        return redirect()->back()->with('success', 'แก้ไขข้อมูลสำเร็จ');
    }
    public function legacyEdit(Request $request)
    {
        $about = AboutUsLegacy::first();
        $about->title_1 = $request->title_1;
        $about->detail_1 = $request->detail_1;
        $about->title_2 = $request->title_2;
        $about->detail_2 = $request->detail_2;
        $about->title_3 = $request->title_3;
        $about->detail_3 = $request->detail_3;
        $about->user_id = session('login');

        if ($request->hasFile('image_1')) {
            $file = $request->file('image_1');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/legacy/');
            $file->move($destinationPath, $filename);
            $about->image_1 = 'image/legacy/' . $filename;
        }
        if ($request->hasFile('image_2')) {
            $file = $request->file('image_2');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/legacy/');
            $file->move($destinationPath, $filename);
            $about->image_2 = 'image/legacy/' . $filename;
        }
        if ($request->hasFile('image_3')) {
            $file = $request->file('image_3');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/legacy/');
            $file->move($destinationPath, $filename);
            $about->image_3 = 'image/legacy/' . $filename;
        }

        $about->save();
        // return redirect('/about-us-legacy');
        return redirect()->back()->with('success', 'แก้ไขข้อมูลสำเร็จ');
    }
    public function legacySinceEdit(Request $request)
    {
        $since = AboutSince::where('id', $request->id)->first();
        // $since->year = $request->year;
        $since->title = $request->title;
        $since->detail = $request->detail;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/since/');
            $file->move($destinationPath, $filename);
            $since->image = 'image/since/' . $filename;
        }
        $since->user_id = session('login');
        $since->no = $request->no;

        $since->save();
        return redirect()->back()->with('success', 'แก้ไขข้อมูลสำเร็จ');
    }
    public function ourValueEdit(Request $request)
    {
        $about = AboutOurValue::first();
        $about->title_1 = $request->title_1;
        $about->detail_1 = $request->detail_1;
        $about->title_2 = $request->title_2;
        $about->detail_2 = $request->detail_2;
        $about->title_3 = $request->title_3;
        $about->detail_3 = $request->detail_3;
        $about->title_4 = $request->title_4;
        $about->detail_4 = $request->detail_4;
        $about->title_5 = $request->title_5;
        $about->detail_5 = $request->detail_5;
        $about->title_6 = $request->title_6;
        $about->detail_6 = $request->detail_6;
        $about->user_id = session('login');

        if ($request->hasFile('image_1')) {
            $file = $request->file('image_1');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/value/');
            $file->move($destinationPath, $filename);
            $about->image_1 = 'image/value/' . $filename;
        }
        if ($request->hasFile('image_2')) {
            $file = $request->file('image_2');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/value/');
            $file->move($destinationPath, $filename);
            $about->image_2 = 'image/value/' . $filename;
        }
        if ($request->hasFile('image_3')) {
            $file = $request->file('image_3');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/value/');
            $file->move($destinationPath, $filename);
            $about->image_3 = 'image/value/' . $filename;
        }
        if ($request->hasFile('image_4')) {
            $file = $request->file('image_4');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/value/');
            $file->move($destinationPath, $filename);
            $about->image_4 = 'image/value/' . $filename;
        }
        if ($request->hasFile('image_5')) {
            $file = $request->file('image_5');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/value/');
            $file->move($destinationPath, $filename);
            $about->image_5 = 'image/value/' . $filename;
        }
        if ($request->hasFile('image_6')) {
            $file = $request->file('image_6');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/value/');
            $file->move($destinationPath, $filename);
            $about->image_6 = 'image/value/' . $filename;
        }

        $about->save();
        return redirect()->back()->with('success', 'แก้ไขข้อมูลสำเร็จ');
    }
    public function cultureEdit(Request $request)
    {
        $about = AboutUsCulture::first();
        $about->title_1 = $request->title_1;
        $about->detail_1 = $request->detail_1;
        $about->title_2 = $request->title_2;
        $about->detail_2 = $request->detail_2;
        $about->title_3 = $request->title_3;
        $about->detail_3 = $request->detail_3;
        $about->title_4 = $request->title_4;
        $about->detail_4 = $request->detail_4;
        $about->title_5 = $request->title_5;
        $about->detail_5 = $request->detail_5;
        $about->title_6 = $request->title_6;
        $about->detail_6 = $request->detail_6;
        $about->title_7 = $request->title_7;
        $about->detail_7 = $request->detail_7;
        $about->title_8 = $request->title_8;
        $about->detail_8 = $request->detail_8;
        $about->user_id = session('login');

        if ($request->hasFile('image_1')) {
            $file = $request->file('image_1');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/culture/');
            $file->move($destinationPath, $filename);
            $about->image_1 = 'image/culture/' . $filename;
        }
        if ($request->hasFile('image_2')) {
            $file = $request->file('image_2');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/culture/');
            $file->move($destinationPath, $filename);
            $about->image_2 = 'image/culture/' . $filename;
        }
        if ($request->hasFile('image_3')) {
            $file = $request->file('image_3');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/culture/');
            $file->move($destinationPath, $filename);
            $about->image_3 = 'image/culture/' . $filename;
        }
        if ($request->hasFile('image_4')) {
            $file = $request->file('image_4');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/culture/');
            $file->move($destinationPath, $filename);
            $about->image_4 = 'image/culture/' . $filename;
        }
        if ($request->hasFile('image_5')) {
            $file = $request->file('image_5');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/culture/');
            $file->move($destinationPath, $filename);
            $about->image_5 = 'image/culture/' . $filename;
        }
        if ($request->hasFile('image_6')) {
            $file = $request->file('image_6');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/culture/');
            $file->move($destinationPath, $filename);
            $about->image_6 = 'image/culture/' . $filename;
        }
        if ($request->hasFile('image_7')) {
            $file = $request->file('image_7');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/culture/');
            $file->move($destinationPath, $filename);
            $about->image_7 = 'image/culture/' . $filename;
        }
        if ($request->hasFile('image_8')) {
            $file = $request->file('image_8');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/culture/');
            $file->move($destinationPath, $filename);
            $about->image_8 = 'image/culture/' . $filename;
        }

        $about->save();
        return redirect()->back()->with('success', 'แก้ไขข้อมูลสำเร็จ');
    }
    public function factoryEdit(Request $request)
    {
        $factory = CapabilitiesFactory::first();
        $factory->banner_title = $request->banner_title;
        $factory->banner_detail = $request->banner_detail;
        $factory->banner_footer = $request->banner_footer;
        $factory->title_1 = $request->title_1;
        $factory->detail_1 = $request->detail_1;
        $factory->title_2 = $request->title_2;
        $factory->detail_2 = $request->detail_2;
        $factory->product_services = $request->product_services;
        $factory->user_id = session('login');

        if ($request->hasFile('banner_image')) {
            $file = $request->file('banner_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/factory/');
            $file->move($destinationPath, $filename);
            $factory->banner_image = 'image/factory/' . $filename;
        }
        if ($request->hasFile('image_1')) {
            $file = $request->file('image_1');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/factory/');
            $file->move($destinationPath, $filename);
            $factory->image_1 = 'image/factory/' . $filename;
        }
        if ($request->hasFile('image_2')) {
            $file = $request->file('image_2');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/factory/');
            $file->move($destinationPath, $filename);
            $factory->image_2 = 'image/factory/' . $filename;
        }

        $factory->save();
        return redirect()->back()->with('success', 'แก้ไขข้อมูลสำเร็จ');
    }
    public function workFactoryEdit(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'title' => 'required',
            // 'detail' => 'required',
            'no' => 'required|integer|min:0',
        ]);

        $data = WorkFactory::findOrFail($request->id);

        $data->update([
            'title' => $request->title,
            'detail' => $request->detail,
            'user_id' => session('login'),
            'no' => $request->no,


        ]);

        return redirect()->back()->with('success', 'แก้ไขข้อมูลสำเร็จ');
    }

    public function farmEdit(Request $request)
    {
        $farm = CapabilitiesFarm::first();
        $farm->banner_title = $request->banner_title;
        $farm->banner_detail = $request->banner_detail;
        $farm->banner_footer = $request->banner_footer;
        $farm->title_1 = $request->title_1;
        $farm->detail_1 = $request->detail_1;
        $farm->title_2 = $request->title_2;
        $farm->detail_2 = $request->detail_2;
        $farm->title_3 = $request->title_3;
        $farm->detail_3 = $request->detail_3;
        $farm->product_services = $request->product_services;
        $farm->user_id = session('login');

        if ($request->hasFile('banner_image')) {
            $file = $request->file('banner_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/farm/');
            $file->move($destinationPath, $filename);
            $farm->banner_image = 'image/farm/' . $filename;
        }
        if ($request->hasFile('image_1')) {
            $file = $request->file('image_1');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/farm/');
            $file->move($destinationPath, $filename);
            $farm->image_1 = 'image/farm/' . $filename;
        }
        if ($request->hasFile('image_2')) {
            $file = $request->file('image_2');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('videos/farm/');
            $file->move($destinationPath, $filename);
            $farm->image_2 = 'videos/farm/' . $filename;
        }
        if ($request->hasFile('image_3')) {
            $file = $request->file('image_3');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('videos/farm/');
            $file->move($destinationPath, $filename);
            $farm->image_3 = 'videos/farm/' . $filename;
        }


        $farm->save();
        return redirect('admin/capabilities-farm');
    }
    public function workFarmEdit(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'title' => 'required',
            // 'detail' => 'required',
            'no' => 'required|integer|min:0',
        ]);

        $data = WorkFarm::findOrFail($request->id);

        $data->update([
            'title' => $request->title,
            'detail' => $request->detail,
            'user_id' => session('login'),
            'no' => $request->no,

        ]);

        return redirect()->back()->with('success', 'แก้ไขข้อมูลสำเร็จ');
    }
    public function tanneryEdit(Request $request)
    {
        $tannery = CapabilitiesTannery::first();
        $tannery->banner_title = $request->banner_title;
        $tannery->banner_detail = $request->banner_detail;
        $tannery->banner_footer = $request->banner_footer;
        $tannery->title_1 = $request->title_1;
        $tannery->detail_1 = $request->detail_1;
        $tannery->title_2 = $request->title_2;
        $tannery->detail_2 = $request->detail_2;
        $tannery->title_3 = $request->title_3;
        $tannery->detail_3 = $request->detail_3;
        $tannery->product_services = $request->product_services;
        $tannery->user_id = session('login');

        if ($request->hasFile('banner_image')) {
            $file = $request->file('banner_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/tannery/');
            $file->move($destinationPath, $filename);
            $tannery->banner_image = 'image/tannery/' . $filename;
        }
        if ($request->hasFile('image_1')) {
            $file = $request->file('image_1');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/tannery/');
            $file->move($destinationPath, $filename);
            $tannery->image_1 = 'image/tannery/' . $filename;
        }
        if ($request->hasFile('image_2')) {
            $file = $request->file('image_2');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/tannery/');
            $file->move($destinationPath, $filename);
            $tannery->image_2 = 'image/tannery/' . $filename;
        }
        if ($request->hasFile('image_3')) {
            $file = $request->file('image_3');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/tannery/');
            $file->move($destinationPath, $filename);
            $tannery->image_3 = 'image/tannery/' . $filename;
        }

        $tannery->save();
        return redirect('admin/capabilities-tannery');
    }
    public function workTanneryEdit(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'title' => 'required',
            // 'detail' => 'required',
            'no' => 'required|integer|min:0',
        ]);

        $data = WorkTannery::findOrFail($request->id);

        $data->update([
            'title' => $request->title,
            'detail' => $request->detail,
            'user_id' => session('login'),
            'no' => $request->no,

        ]);

        return redirect()->back()->with('success', 'แก้ไขข้อมูลสำเร็จ');
    }

    public function leatherEdit(Request $request)
    {
        $leather = Leather::first();
        $leather->banner_title = $request->banner_title;
        $leather->banner_detail = $request->banner_detail;
        $leather->footer_detail = $request->footer_detail;
        $leather->title_1 = $request->title_1;
        $leather->detail_1 = $request->detail_1;
        $leather->title_2 = $request->title_2;
        $leather->detail_2 = $request->detail_2;
        $leather->title_3 = $request->title_3;
        $leather->detail_3 = $request->detail_3;
        $leather->title_4 = $request->title_4;
        $leather->detail_4 = $request->detail_4;
        $leather->title_5 = $request->title_5;
        $leather->detail_5 = $request->detail_5;
        $leather->title_6 = $request->title_6;
        $leather->detail_6 = $request->detail_6;
        $leather->title_7 = $request->title_7;
        $leather->detail_7 = $request->detail_7;
        $leather->title_8 = $request->title_8;
        $leather->detail_8 = $request->detail_8;
        $leather->title_9 = $request->title_9;
        $leather->detail_9 = $request->detail_9;
        $leather->title_10 = $request->title_10;
        $leather->detail_10 = $request->detail_10;
        $leather->title_11 = $request->title_11;
        $leather->detail_11 = $request->detail_11;
        $leather->title_12 = $request->title_12;
        $leather->detail_12 = $request->detail_12;
        $leather->title_13 = $request->title_13;
        $leather->detail_13 = $request->detail_13;
        $leather->title_14 = $request->title_14;
        $leather->detail_14 = $request->detail_14;
        $leather->user_id = session('login');

        if ($request->hasFile('footer_image')) {
            $file = $request->file('footer_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/leather/');
            $file->move($destinationPath, $filename);
            $leather->footer_image = 'image/leather/' . $filename;
        }
        if ($request->hasFile('banner_image')) {
            $file = $request->file('banner_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/leather/');
            $file->move($destinationPath, $filename);
            $leather->banner_image = 'image/leather/' . $filename;
        }
        if ($request->hasFile('image_1')) {
            $file = $request->file('image_1');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/leather/');
            $file->move($destinationPath, $filename);
            $leather->image_1 = 'image/leather/' . $filename;
        }
        if ($request->hasFile('image_2')) {
            $file = $request->file('image_2');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/leather/');
            $file->move($destinationPath, $filename);
            $leather->image_2 = 'image/leather/' . $filename;
        }
        if ($request->hasFile('image_3')) {
            $file = $request->file('image_3');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/leather/');
            $file->move($destinationPath, $filename);
            $leather->image_3 = 'image/leather/' . $filename;
        }
        if ($request->hasFile('image_4')) {
            $file = $request->file('image_4');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/leather/');
            $file->move($destinationPath, $filename);
            $leather->image_4 = 'image/leather/' . $filename;
        }
        if ($request->hasFile('image_5')) {
            $file = $request->file('image_5');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/leather/');
            $file->move($destinationPath, $filename);
            $leather->image_5 = 'image/leather/' . $filename;
        }
        if ($request->hasFile('image_6')) {
            $file = $request->file('image_6');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/leather/');
            $file->move($destinationPath, $filename);
            $leather->image_6 = 'image/leather/' . $filename;
        }
        if ($request->hasFile('image_7')) {
            $file = $request->file('image_7');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/leather/');
            $file->move($destinationPath, $filename);
            $leather->image_7 = 'image/leather/' . $filename;
        }
        if ($request->hasFile('image_8')) {
            $file = $request->file('image_8');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/leather/');
            $file->move($destinationPath, $filename);
            $leather->image_8 = 'image/leather/' . $filename;
        }
        if ($request->hasFile('image_9')) {
            $file = $request->file('image_9');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/leather/');
            $file->move($destinationPath, $filename);
            $leather->image_9 = 'image/leather/' . $filename;
        }
        if ($request->hasFile('image_10')) {
            $file = $request->file('image_10');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/leather/');
            $file->move($destinationPath, $filename);
            $leather->image_10 = 'image/leather/' . $filename;
        }
        if ($request->hasFile('image_11')) {
            $file = $request->file('image_11');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/leather/');
            $file->move($destinationPath, $filename);
            $leather->image_11 = 'image/leather/' . $filename;
        }
        if ($request->hasFile('image_12')) {
            $file = $request->file('image_12');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/leather/');
            $file->move($destinationPath, $filename);
            $leather->image_12 = 'image/leather/' . $filename;
        }
        if ($request->hasFile('image_13')) {
            $file = $request->file('image_13');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/leather/');
            $file->move($destinationPath, $filename);
            $leather->image_13 = 'image/leather/' . $filename;
        }
        if ($request->hasFile('image_14')) {
            $file = $request->file('image_14');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/leather/');
            $file->move($destinationPath, $filename);
            $leather->image_14 = 'image/leather/' . $filename;
        }

        $leather->type_catalog = $request->type_catalog;
        if ($request->type_catalog == 1) {
            if ($request->hasFile('catalog_file')) {
                $file = $request->file('catalog_file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $destinationPath = public_path('file/leather/catalog/');
                $file->move($destinationPath, $filename);
                $leather->catalog_file = 'file/leather/catalog/' . $filename;
            }
        } elseif ($request->type_catalog == 2) {
            $leather->catalog_url = $request->catalog_url;
        }

        $leather->save();
        return redirect()->back()->with('success', 'แก้ไขข้อมูลสำเร็จ');
    }
    public function saltedEdit(Request $request)
    {
        $salted = SaltedSK::first();
        $salted->title_1 = $request->title_1;
        $salted->detail_1 = $request->detail_1;
        $salted->title_2 = $request->title_2;
        $salted->detail_2 = $request->detail_2;
        $salted->title_3 = $request->title_3;
        $salted->detail_3 = $request->detail_3;
        $salted->title_4 = $request->title_4;
        $salted->detail_4 = $request->detail_4;
        $salted->user_id = session('login');

        if ($request->hasFile('image_1')) {
            $file = $request->file('image_1');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/saltedSK/');
            $file->move($destinationPath, $filename);
            $salted->image_1 = 'image/saltedSK/' . $filename;
        }
        if ($request->hasFile('image_2')) {
            $file = $request->file('image_2');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/saltedSK/');
            $file->move($destinationPath, $filename);
            $salted->image_2 = 'image/saltedSK/' . $filename;
        }
        if ($request->hasFile('image_3')) {
            $file = $request->file('image_3');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/saltedSK/');
            $file->move($destinationPath, $filename);
            $salted->image_3 = 'image/saltedSK/' . $filename;
        }
        if ($request->hasFile('image_4')) {
            $file = $request->file('image_4');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/saltedSK/');
            $file->move($destinationPath, $filename);
            $salted->image_4 = 'image/saltedSK/' . $filename;
        }


        $salted->save();
        return redirect()->back()->with('success', 'แก้ไขข้อมูลสำเร็จ');
    }
    public function finishedEdit(Request $request)
    {
        $finished = Finished::first();
        $finished->banner_title = $request->banner_title;
        $finished->banner_detail = $request->banner_detail;
        $finished->title = $request->title;
        $finished->detail = $request->detail;
        $finished->footer_title = $request->footer_title;
        $finished->footer_detail = $request->footer_detail;
        $finished->user_id = session('login');

        if ($request->hasFile('banner_image')) {
            $file = $request->file('banner_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/finished/');
            $file->move($destinationPath, $filename);
            $finished->banner_image = 'image/finished/' . $filename;
        }
        if ($request->hasFile('footer_image')) {
            $file = $request->file('footer_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/finished/');
            $file->move($destinationPath, $filename);
            $finished->footer_image = 'image/finished/' . $filename;
        }


        $finished->save();
        return redirect()->back()->with('success', 'แก้ไขข้อมูลสำเร็จ');
    }
    public function oemEdit(Request $request)
    {
        $oem = Oem::where('id', $request->id)->first();
        // $since->year = $request->year;
        $oem->title = $request->title;
        $oem->detail = $request->detail;
        $oem->type_catalog = $request->type_catalog;
        if ($request->type_catalog == 1) {
            if ($request->hasFile('catalog_file')) {
                $file = $request->file('catalog_file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $destinationPath = public_path('file/oem-catalog/');
                $file->move($destinationPath, $filename);
                $oem->catalog_file = 'file/oem-catalog/' . $filename;
            }
        } elseif ($request->type_catalog == 2) {
            $oem->catalog_url = $request->catalog_url;
        }
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/oem/');
            $file->move($destinationPath, $filename);
            $oem->image = 'image/oem/' . $filename;
        }
        $oem->user_id = session('login');
        $oem->no = $request->no;

        $oem->save();
        return redirect()->back()->with('success', 'แก้ไขข้อมูลสำเร็จ');
    }
    public function odmEdit(Request $request)
    {
        $odm = Odm::where('id', $request->id)->first();
        // $since->year = $request->year;
        $odm->title = $request->title;
        $odm->detail = $request->detail;
        $odm->type_catalog = $request->type_catalog;
        if ($request->type_catalog == 1) {
            if ($request->hasFile('catalog_file')) {
                $file = $request->file('catalog_file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $destinationPath = public_path('file/odm-catalog/');
                $file->move($destinationPath, $filename);
                $odm->catalog_file = 'file/odm-catalog/' . $filename;
            }
        } elseif ($request->type_catalog == 2) {
            $odm->catalog_url = $request->catalog_url;
        }
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/odm/');
            $file->move($destinationPath, $filename);
            $odm->image = 'image/odm/' . $filename;
        }
        $odm->user_id = session('login');
        $odm->no = $request->no;
        $odm->save();
        return redirect()->back()->with('success', 'แก้ไขข้อมูลสำเร็จ');
    }
    public function sustainabilityEdit(Request $request)
    {
        $sustainability = Sustainability::first();
        $sustainability->title_1 = $request->title_1;
        $sustainability->detail_1 = $request->detail_1;
        $sustainability->title_2 = $request->title_2;
        $sustainability->detail_2 = $request->detail_2;
        $sustainability->title_3 = $request->title_3;
        $sustainability->detail_3 = $request->detail_3;

        if ($request->hasFile('banner_image')) {
            $file = $request->file('banner_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/sustainability/');
            $file->move($destinationPath, $filename);
            $sustainability->banner_image = 'image/sustainability/' . $filename;
        }
        if ($request->hasFile('image_1')) {
            $file = $request->file('image_1');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/sustainability/');
            $file->move($destinationPath, $filename);
            $sustainability->image_1 = 'image/sustainability/' . $filename;
        }
        if ($request->hasFile('image_2')) {
            $file = $request->file('image_2');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/sustainability/');
            $file->move($destinationPath, $filename);
            $sustainability->image_2 = 'image/sustainability/' . $filename;
        }
        if ($request->hasFile('image_3')) {
            $file = $request->file('image_3');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/sustainability/');
            $file->move($destinationPath, $filename);
            $sustainability->image_3 = 'image/sustainability/' . $filename;
        }
        $sustainability->user_id = session('login');

        $sustainability->save();
        return redirect()->back()->with('success', 'แก้ไขข้อมูลสำเร็จ');
    }
    public function newsEdit(Request $request)
    {
        $news = News::first();
        $news->title = $request->title;
        $news->detail = $request->detail;
        $news->date = $request->date;
        $news->type = $request->type;
        $news->url = $request->url;
        $news->user_id = session('login');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/odm/');
            $file->move($destinationPath, $filename);
            $news->image = 'image/odm/' . $filename;
        }
        $news->save();

        return redirect()->back()->with('success', 'แก้ไขข้อมูลสำเร็จ');
    }
    public function contactEdit(Request $request)
    {
        $news = ContactUs::first();
        $news->title = $request->title;
        $news->detail = $request->detail;
        $news->name_company = $request->name_company;
        $news->address = $request->address;
        $news->open = $request->open;
        $news->phone = $request->phone;
        $news->facebook = $request->facebook;
        $news->line = $request->line;
        $news->whatsapp = $request->whatsapp;
        $news->map = $request->map;
        $news->user_id = session('login');

        if ($request->hasFile('banner_image')) {
            $file = $request->file('banner_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/contactUs/');
            $file->move($destinationPath, $filename);
            $news->banner_image = 'image/contactUs/' . $filename;
        }
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/contactUs/');
            $file->move($destinationPath, $filename);
            $news->logo = 'image/contactUs/' . $filename;
        }
        if ($request->hasFile('logo_footer')) {
            $file = $request->file('logo_footer');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/contactUs/');
            $file->move($destinationPath, $filename);
            $news->logo_footer = 'image/contactUs/' . $filename;
        }
        $news->save();

        return redirect()->back()->with('success', 'แก้ไขข้อมูลสำเร็จ');
    }
    public function leatherProductsEdit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);

        $leather = ProductsLeather::findOrFail($request->id);
        $leather->name = $request->name;
        $leather->detail = $request->detail;
        $leather->url = $request->url;
        $leather->user_id = session('login');

        if ($request->hasFile('image')) {
            if (!empty($leather->image) && file_exists(public_path($leather->image))) {
                unlink(public_path($leather->image));
            }
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/products/leather/');
            $file->move($destinationPath, $filename);

            $leather->image = 'image/products/leather/' . $filename;
        }

        $leather->save();

        return redirect()->back()->with('success', 'แก้ไขข้อมูลสำเร็จ');
    }
    public function finishedProductsEdit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);

        $finished = ProductsFinished::findOrFail($request->id);
        $finished->name = $request->name;
        $finished->detail = $request->detail;
        $finished->url = $request->url;
        $finished->user_id = session('login');

        if ($request->hasFile('image')) {
            if (!empty($finished->image) && file_exists(public_path($finished->image))) {
                unlink(public_path($finished->image));
            }
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/products/finished/');
            $file->move($destinationPath, $filename);

            $finished->image = 'image/products/finished/' . $filename;
        }

        $finished->save();

        return redirect()->back()->with('success', 'แก้ไขข้อมูลสำเร็จ');
    }

    public function riverEdit(Request $request)
    {
        $river = River::first();
        $river->title = $request->title;
        $river->detail = $request->detail;
        $river->title_1 = $request->title_1;
        $river->detail_1 = $request->detail_1;
        $river->detail_2 = $request->detail_2;
        $river->user_id = session('login');

        if ($request->hasFile('banner_1')) {
            $file = $request->file('banner_1');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/river/');
            $file->move($destinationPath, $filename);
            $river->banner_1 = 'image/river/' . $filename;
        }
        if ($request->hasFile('banner_2')) {
            $file = $request->file('banner_2');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/river/');
            $file->move($destinationPath, $filename);
            $river->banner_2 = 'image/river/' . $filename;
        }
        if ($request->hasFile('image_1')) {
            $file = $request->file('image_1');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/river/');
            $file->move($destinationPath, $filename);
            $river->image_1 = 'image/river/' . $filename;
        }
        if ($request->hasFile('image_2')) {
            $file = $request->file('image_2');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/river/');
            $file->move($destinationPath, $filename);
            $river->image_2 = 'image/river/' . $filename;
        }


        $river->save();
        return redirect()->back()->with('success', 'แก้ไขข้อมูลสำเร็จ');
    }
    public function riverProductsEdit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);

        $river = ProductsRiver::findOrFail($request->id);
        $river->name = $request->name;
        $river->detail = $request->detail;
        $river->url = $request->url;
        $river->user_id = session('login');

        if ($request->hasFile('image')) {
            if (!empty($river->image) && file_exists(public_path($river->image))) {
                unlink(public_path($river->image));
            }
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/products/river/');
            $file->move($destinationPath, $filename);

            $river->image = 'image/products/river/' . $filename;
        }

        $river->save();

        return redirect()->back()->with('success', 'แก้ไขข้อมูลสำเร็จ');
    }
    public function rochelleEdit(Request $request)
    {
        $rochelle = Rochelle::first();

        $rochelle->title = $request->title;
        $rochelle->detail = $request->detail;
        $rochelle->title_1 = $request->title_1;
        $rochelle->detail_1 = $request->detail_1;
        $rochelle->detail_2 = $request->detail_2;
        $rochelle->user_id = session('login');

        $fields = ['banner_1', 'banner_2', 'image_1', 'image_2'];

        foreach ($fields as $field) {
            if ($request->hasFile($field)) {
                // ลบไฟล์เก่า
                if (!empty($rochelle->$field) && file_exists(public_path($rochelle->$field))) {
                    unlink(public_path($rochelle->$field));
                }

                $file = $request->file($field);
                $filename = time() . '_' . preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $file->getClientOriginalName());
                $file->move(public_path('image/rochelle/'), $filename);
                $rochelle->$field = 'image/rochelle/' . $filename;
            }
        }

        $rochelle->save();

        return redirect()->back()->with('success', 'แก้ไขข้อมูลสำเร็จ');
    }

    public function rochelleProductsEdit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);

        $rochelle = ProductsRochelle::findOrFail($request->id);
        $rochelle->name = $request->name;
        $rochelle->detail = $request->detail;
        $rochelle->url = $request->url;
        $rochelle->user_id = session('login');

        if ($request->hasFile('image')) {
            if (!empty($rochelle->image) && file_exists(public_path($rochelle->image))) {
                unlink(public_path($rochelle->image));
            }
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/products/rochelle/');
            $file->move($destinationPath, $filename);

            $rochelle->image = 'image/products/rochelle/' . $filename;
        }

        $rochelle->save();

        return redirect()->back()->with('success', 'แก้ไขข้อมูลสำเร็จ');
    }
    public function contactTopicEdit(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'name' => 'required',
            // 'detail' => 'required',
        ]);

        $data = ContactTopic::findOrFail($request->id);

        $data->update([
            'name' => $request->name,
            'user_id' => session('login'),

            // 'detail' => $request->detail,
        ]);

        return redirect()->back()->with('success', 'แก้ไขข้อมูลสำเร็จ');
    }

    public function contactIndustryEdit(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'name' => 'required',
            // 'detail' => 'required',
        ]);

        $data = ContactIndustry::findOrFail($request->id);

        $data->update([
            'name' => $request->name,
            'email_1' => $request->email_1,
            'email_2' => $request->email_2,
            'user_id' => session('login'),

            // 'detail' => $request->detail,
        ]);

        return redirect()->back()->with('success', 'แก้ไขข้อมูลสำเร็จ');
    }
}
