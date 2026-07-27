<?php

namespace App\Http\Controllers;

use App\Models\AboutSince;
use App\Models\ContactIndustry;
use App\Models\ContactTopic;
use App\Models\News;
use App\Models\Odm;
use App\Models\Oem;
use App\Models\ProductsFinished;
use App\Models\ProductsLeather;
use App\Models\ProductsRiver;
use App\Models\ProductsRochelle;
use App\Models\WorkFactory;
use App\Models\WorkFarm;
use App\Models\WorkTannery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\ValidationException;

class AddSettingController extends Controller
{
    
    public function workFactoryAdd(Request $request)
    {
        $request->validate([
            'title' => 'required',
            // 'detail' => 'required',
        ]);

        WorkFactory::create([
            'title' => $request->title,
            'detail' => $request->detail,
        ]);

        return redirect()->back()->with('success', 'เพิ่มข้อมูลสำเร็จ');
    }
    public function workFarmAdd(Request $request)
    {
        $request->validate([
            'title' => 'required',
            // 'detail' => 'required',
        ]);

        WorkFarm::create([
            'title' => $request->title,
            'detail' => $request->detail,
        ]);

        return redirect()->back()->with('success', 'เพิ่มข้อมูลสำเร็จ');
    }
    public function workTanneryAdd(Request $request)
    {
        $request->validate([
            'title' => 'required',
            // 'detail' => 'required',
        ]);

        WorkTannery::create([
            'title' => $request->title,
            'detail' => $request->detail,
        ]);

        return redirect()->back()->with('success', 'เพิ่มข้อมูลสำเร็จ');
    }
    public function oemAdd(Request $request)
    {
        $oem = new Oem();
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
        $oem->save();

        return redirect('admin/products-oem')->with('success', 'เพิ่มข้อมูลใหม่เรียบร้อยแล้ว');
    }
    public function odmAdd(Request $request)
    {
        $odm = new Odm();
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
        $odm->save();

        return redirect('admin/products-odm')->with('success', 'เพิ่มข้อมูลใหม่เรียบร้อยแล้ว');
    }
    public function newsAdd(Request $request)
    {
        $news = new News();
        $news->title = $request->title;
        $news->detail = $request->detail;
        $news->date = $request->date;
        $news->type = $request->type;
        $news->url = $request->url;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/odm/');
            $file->move($destinationPath, $filename);
            $news->image = 'image/odm/' . $filename;
        }
        $news->save();

        return redirect('admin/news')->with('success', 'เพิ่มข้อมูลใหม่เรียบร้อยแล้ว');
    }
    public function leatherProductsAdd(Request $request)
    {
        $request->validate([
            'image' => 'required',
            'name' => 'required',
            'detail' => 'required',
        ]);

        $leather = new ProductsLeather();
        $leather->name = $request->name;
        $leather->detail = $request->detail;
        $leather->url = $request->url;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/products/leather/');
            $file->move($destinationPath, $filename);
            $leather->image = 'image/products/leather/' . $filename;
        }
        $leather->save();

        return redirect()->back()->with('success', 'เพิ่มข้อมูลสำเร็จ');
    }
    public function finishedProductsAdd(Request $request)
    {
        $request->validate([
            'image' => 'required',
            'name' => 'required',
            'detail' => 'required',
        ]);

        $finished = new ProductsFinished();
        $finished->name = $request->name;
        $finished->detail = $request->detail;
        $finished->url = $request->url;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/products/finished/');
            $file->move($destinationPath, $filename);
            $finished->image = 'image/products/finished/' . $filename;
        }
        $finished->save();

        return redirect()->back()->with('success', 'เพิ่มข้อมูลสำเร็จ');
    }
    public function riverProductsAdd(Request $request)
    {
        $request->validate([
            'image' => 'required',
            'name' => 'required',
            'detail' => 'required',
        ]);

        $river = new ProductsRiver();
        $river->name = $request->name;
        $river->detail = $request->detail;
        $river->url = $request->url;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/products/river/');
            $file->move($destinationPath, $filename);
            $river->image = 'image/products/river/' . $filename;
        }
        $river->save();

        return redirect()->back()->with('success', 'เพิ่มข้อมูลสำเร็จ');
    }
    public function rochelleProductsAdd(Request $request)
    {
        $request->validate([
            'image' => 'required',
            'name' => 'required',
            'detail' => 'required',
        ]);

        $rochelle = new ProductsRochelle();
        $rochelle->name = $request->name;
        $rochelle->detail = $request->detail;
        $rochelle->url = $request->url;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('image/products/rochelle/');
            $file->move($destinationPath, $filename);
            $rochelle->image = 'image/products/rochelle/' . $filename;
        }
        $rochelle->save();

        return redirect()->back()->with('success', 'เพิ่มข้อมูลสำเร็จ');
    }
    public function contactIndustryAdd(Request $request)
    {
        $request->validate([
            'name' => 'required',
            // 'detail' => 'required',
        ]);

        ContactIndustry::create([
            'name' => $request->name,
            // 'detail' => $request->detail,
        ]);

        return redirect()->back()->with('success', 'เพิ่มข้อมูลสำเร็จ');
    }
    public function contactTopicAdd(Request $request)
    {
        $request->validate([
            'name' => 'required',
            // 'detail' => 'required',
        ]);

        ContactTopic::create([
            'name' => $request->name,
            // 'detail' => $request->detail,
        ]);

        return redirect()->back()->with('success', 'เพิ่มข้อมูลสำเร็จ');
    }
    public function contactFormAdd(Request $request)
    {
        $request->validate([
            'name' => 'required',
            // 'detail' => 'required',
        ]);

        ContactTopic::create([
            'name' => $request->name,
            'email_1' => $request->email_1,
            'email_2' => $request->email_2,
            // 'detail' => $request->detail,
        ]);

        return redirect()->back()->with('success', 'เพิ่มข้อมูลสำเร็จ');
    }

}
