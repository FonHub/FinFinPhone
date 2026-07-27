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
use App\Models\User;
use App\Models\UserRecipient;
use App\Models\WorkFactory;
use App\Models\WorkFarm;
use App\Models\WorkTannery;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;


class DeleteSettingController extends Controller
{
    // public function deleteUser(Request $request)
    // {
    //     $user = User::find($request->id);
    //     if (!$user) {
    //         return redirect()->back()->withErrors(['msg' => 'ไม่พบผู้ใช้นี้']);
    //     }
    //     $user->delete();
    //     return redirect()->back()->with('success', 'ลบผู้ใช้เรียบร้อยแล้ว');
    // }
    public function legacySinceDelete(Request $request)
    {
        $since = AboutSince::find($request->id);
        if (!$since) {
            return redirect()->back()->withErrors(['msg' => 'ไม่พบข้อมูล']);
        }
        $since->delete();
        return redirect()->back()->with('success', 'ลบข้อมูลเรียบร้อยแล้ว');
    }
    public function workFactoryDelete(Request $request)
    {
        $data = WorkFactory::find($request->id);
        if (!$data) {
            return redirect()->back()->withErrors(['msg' => 'ไม่พบข้อมูล']);
        }
        $data->delete();
        return redirect()->back()->with('success', 'ลบข้อมูลเรียบร้อยแล้ว');
    }
    public function workFarmDelete(Request $request)
    {
        $data = WorkFarm::find($request->id);
        if (!$data) {
            return redirect()->back()->withErrors(['msg' => 'ไม่พบข้อมูล']);
        }
        $data->delete();
        return redirect()->back()->with('success', 'ลบข้อมูลเรียบร้อยแล้ว');
    }

    public function workTanneryDelete(Request $request)
    {
        $data = WorkTannery::find($request->id);
        if (!$data) {
            return redirect()->back()->withErrors(['msg' => 'ไม่พบข้อมูล']);
        }
        $data->delete();
        return redirect()->back()->with('success', 'ลบข้อมูลเรียบร้อยแล้ว');
    }
    public function oemDelete(Request $request)
    {
        $data = Oem::find($request->id);
        if (!$data) {
            return redirect()->back()->withErrors(['msg' => 'ไม่พบข้อมูล']);
        }
        $data->delete();
        return redirect()->back()->with('success', 'ลบข้อมูลเรียบร้อยแล้ว');
    }
    public function odmDelete(Request $request)
    {
        $data = Odm::find($request->id);
        if (!$data) {
            return redirect()->back()->withErrors(['msg' => 'ไม่พบข้อมูล']);
        }
        $data->delete();
        return redirect()->back()->with('success', 'ลบข้อมูลเรียบร้อยแล้ว');
    }
    public function newsDelete(Request $request)
    {
        $data = News::find($request->id);
        if (!$data) {
            return redirect()->back()->withErrors(['msg' => 'ไม่พบข้อมูล']);
        }
        $data->delete();
        return redirect()->back()->with('success', 'ลบข้อมูลเรียบร้อยแล้ว');
    }
    public function leatherProductsDelete(Request $request)
    {
        $data = ProductsLeather::find($request->id);
        if (!$data) {
            return redirect()->back()->withErrors(['msg' => 'ไม่พบข้อมูล']);
        }
        if (!empty($data->image) && file_exists(public_path($data->image))) {
            unlink(public_path($data->image));
        }
        $data->delete();

        return redirect()->back()->with('success', 'ลบข้อมูลเรียบร้อยแล้ว');
    }
    public function finishedProductsDelete(Request $request)
    {
        $data = ProductsFinished::find($request->id);
        if (!$data) {
            return redirect()->back()->withErrors(['msg' => 'ไม่พบข้อมูล']);
        }
        if (!empty($data->image) && file_exists(public_path($data->image))) {
            unlink(public_path($data->image));
        }
        $data->delete();

        return redirect()->back()->with('success', 'ลบข้อมูลเรียบร้อยแล้ว');
    }
    public function riverProductsDelete(Request $request)
    {
        $data = ProductsRiver::find($request->id);
        if (!$data) {
            return redirect()->back()->withErrors(['msg' => 'ไม่พบข้อมูล']);
        }
        if (!empty($data->image) && file_exists(public_path($data->image))) {
            unlink(public_path($data->image));
        }
        $data->delete();

        return redirect()->back()->with('success', 'ลบข้อมูลเรียบร้อยแล้ว');
    }
    public function rochelleProductsDelete(Request $request)
    {
        $data = ProductsRochelle::find($request->id);
        if (!$data) {
            return redirect()->back()->withErrors(['msg' => 'ไม่พบข้อมูล']);
        }
        if (!empty($data->image) && file_exists(public_path($data->image))) {
            unlink(public_path($data->image));
        }
        $data->delete();

        return redirect()->back()->with('success', 'ลบข้อมูลเรียบร้อยแล้ว');
    }
    public function contactTopicDelete(Request $request)
    {
        $data = ContactTopic::find($request->id);
        if (!$data) {
            return redirect()->back()->withErrors(['msg' => 'ไม่พบข้อมูล']);
        }
        $data->delete();
        return redirect()->back()->with('success', 'ลบข้อมูลเรียบร้อยแล้ว');
    }
    public function contactIndustryDelete(Request $request)
    {
        $data = ContactIndustry::find($request->id);
        if (!$data) {
            return redirect()->back()->withErrors(['msg' => 'ไม่พบข้อมูล']);
        }
        $data->delete();
        return redirect()->back()->with('success', 'ลบข้อมูลเรียบร้อยแล้ว');
    }
}
