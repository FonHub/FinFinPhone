<?php

namespace App\Http\Controllers;

use App\Models\SellOrderReview;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class AdminSellOrderController extends Controller
{
    public function index(Request $request)
    {
        $statuses = $this->statusOptions();
        $pickupPanels = $this->pickupPanelOptions();

        $query = DB::table('sell_orders')
            ->select([
                'sell_orders.id',
                'sell_orders.order_no',
                'sell_orders.customer_name',
                'sell_orders.customer_phone',
                'sell_orders.customer_email',
                'sell_orders.summary_title',
                'sell_orders.final_estimate_price',
                'sell_orders.pickup_panel',
                'sell_orders.pickup_date',
                'sell_orders.pickup_time',
                'sell_orders.status',
                'sell_orders.created_at',
            ]);

        if ($request->filled('keyword')) {
            $keyword = trim($request->keyword);

            $query->where(function ($q) use ($keyword) {
                $q->where('sell_orders.order_no', 'like', '%' . $keyword . '%')
                    ->orWhere('sell_orders.customer_name', 'like', '%' . $keyword . '%')
                    ->orWhere('sell_orders.customer_phone', 'like', '%' . $keyword . '%')
                    ->orWhere('sell_orders.customer_email', 'like', '%' . $keyword . '%')
                    ->orWhere('sell_orders.summary_title', 'like', '%' . $keyword . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('sell_orders.status', $request->status);
        }

        if ($request->filled('pickup_panel')) {
            $query->where('sell_orders.pickup_panel', $request->pickup_panel);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('sell_orders.created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('sell_orders.created_at', '<=', $request->date_to);
        }

        $orders = $query
            ->orderBy('sell_orders.id', 'desc')
            ->paginate(20)
            ->withQueryString();

        $summary = [
            'total' => DB::table('sell_orders')->count(),
            'pending' => DB::table('sell_orders')->where('status', 'pending')->count(),
            'inspecting' => DB::table('sell_orders')->where('status', 'inspecting')->count(),
            'completed' => DB::table('sell_orders')->where('status', 'completed')->count(),
        ];

        return view('admin.sell_orders.index', [
            'orders' => $orders,
            'statuses' => $statuses,
            'pickupPanels' => $pickupPanels,
            'summary' => $summary,
        ]);
    }

    public function show($id)
    {
        $order = DB::table('sell_orders')
            ->where('id', $id)
            ->first();

        if (!$order) {
            abort(404);
        }

        $pickupDetail = DB::table('sell_order_pickup_details')
            ->where('sell_order_id', $order->id)
            ->first();

        $answers = DB::table('sell_order_answers')
            ->where('sell_order_id', $order->id)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $requiredDocuments = DB::table('sell_order_required_documents')
            ->where('sell_order_id', $order->id)
            ->orderBy('id', 'asc')
            ->get();

        $files = DB::table('sell_order_files')
            ->where('sell_order_id', $order->id)
            ->orderBy('id', 'desc')
            ->get();

        $histories = DB::table('sell_order_status_histories')
            ->where('sell_order_id', $order->id)
            ->orderBy('id', 'desc')
            ->get();

        $review = SellOrderReview::query()
            ->where('sell_order_id', $order->id)
            ->first();

        return view('admin.sell_orders.show', [
            'order' => $order,
            'pickupDetail' => $pickupDetail,
            'answers' => $answers,
            'requiredDocuments' => $requiredDocuments,
            'files' => $files,
            'histories' => $histories,
            'review' => $review,
            'statuses' => $this->statusOptions(),
            'pickupPanels' => $this->pickupPanelOptions(),
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $statuses = array_keys($this->statusOptions());

        $request->validate([
            'status' => ['required', 'in:' . implode(',', $statuses)],
            'note' => ['nullable', 'string'],
            'admin_note' => ['nullable', 'string'],
        ], [
            'status.required' => 'กรุณาเลือกสถานะ',
            'status.in' => 'สถานะไม่ถูกต้อง',
        ]);

        $order = DB::table('sell_orders')
            ->where('id', $id)
            ->first();

        if (!$order) {
            abort(404);
        }

        DB::beginTransaction();

        try {
            $oldStatus = $order->status;
            $newStatus = $request->status;

            DB::table('sell_orders')
                ->where('id', $order->id)
                ->update([
                    'status' => $newStatus,
                    'admin_note' => $request->admin_note,
                    'updated_at' => now(),
                ]);

            DB::table('sell_order_status_histories')->insert([
                'sell_order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'changed_by_admin_id' => $this->getAdminId(),
                'changed_by_user_id' => null,
                'note' => $request->note,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'อัปเดตสถานะเรียบร้อยแล้ว');
        } catch (Throwable $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'ไม่สามารถอัปเดตสถานะได้: ' . $e->getMessage());
        }
    }

    public function updatePrice(Request $request, $id)
    {
        $request->validate([
            'final_estimate_price' => ['required', 'numeric', 'min:0'],
            'price_adjustment_note' => ['nullable', 'string'],
        ], [
            'final_estimate_price.required' => 'กรุณากรอกราคาประเมินใหม่',
            'final_estimate_price.numeric' => 'ราคาประเมินต้องเป็นตัวเลข',
            'final_estimate_price.min' => 'ราคาประเมินต้องไม่น้อยกว่า 0',
        ]);

        $order = DB::table('sell_orders')
            ->where('id', $id)
            ->first();

        if (!$order) {
            abort(404);
        }

        DB::beginTransaction();

        try {
            $firstOriginalPrice = !is_null($order->original_final_estimate_price ?? null)
                ? (float) $order->original_final_estimate_price
                : (float) ($order->final_estimate_price ?? 0);

            $oldPrice = !is_null($order->admin_adjusted_price ?? null) && (float) $order->admin_adjusted_price > 0
                ? (float) $order->admin_adjusted_price
                : (float) ($order->final_estimate_price ?? 0);

            $newPrice = (float) $request->final_estimate_price;

            $oldStatus = $order->status;
            $newStatus = $order->status;

            if (!in_array($order->status, ['completed', 'cancelled', 'canceled'], true)) {
                $newStatus = 'price_adjusted';
            }

            DB::table('sell_orders')
                ->where('id', $order->id)
                ->update([
                    'original_final_estimate_price' => $firstOriginalPrice,
                    'admin_adjusted_price' => $newPrice,
                    'final_estimate_price' => $newPrice,
                    'price_adjustment_note' => $request->price_adjustment_note,
                    'price_adjusted_by_admin_id' => $this->getAdminId(),
                    'price_adjusted_at' => now(),
                    'status' => $newStatus,
                    'updated_at' => now(),
                ]);

            DB::table('sell_order_status_histories')->insert([
                'sell_order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'changed_by_admin_id' => $this->getAdminId(),
                'changed_by_user_id' => null,
                'note' => 'แอดมินปรับราคาประเมินจาก ฿' . number_format($oldPrice, 0) . ' เป็น ฿' . number_format($newPrice, 0)
                    . (!empty($request->price_adjustment_note) ? ' | หมายเหตุ: ' . $request->price_adjustment_note : ''),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'ปรับราคาประเมินเรียบร้อยแล้ว');
        } catch (Throwable $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'ไม่สามารถปรับราคาประเมินได้: ' . $e->getMessage());
        }
    }

    public function storeReview(Request $request, $id)
    {
        $order = DB::table('sell_orders')
            ->where('id', $id)
            ->first();

        if (!$order) {
            abort(404);
        }

        $existingReview = SellOrderReview::query()
            ->where('sell_order_id', $order->id)
            ->first();

        if ($existingReview) {
            return redirect()
                ->back()
                ->with('error', 'คำสั่งขายนี้มีรีวิวแล้ว ไม่สามารถเพิ่มซ้ำได้');
        }

        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title' => ['nullable', 'string', 'max:255'],
            'comment' => ['required', 'string', 'max:2000'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:50'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'is_displayed' => ['nullable', 'in:0,1'],
            'is_active' => ['nullable', 'in:0,1'],
        ], [
            'rating.required' => 'กรุณาเลือกคะแนนรีวิว',
            'rating.integer' => 'คะแนนรีวิวไม่ถูกต้อง',
            'rating.min' => 'คะแนนรีวิวต้องไม่น้อยกว่า 1',
            'rating.max' => 'คะแนนรีวิวต้องไม่เกิน 5',
            'comment.required' => 'กรุณากรอกข้อความรีวิว',
            'image.image' => 'ไฟล์ต้องเป็นรูปภาพเท่านั้น',
            'image.mimes' => 'รองรับเฉพาะไฟล์ jpg, jpeg, png, webp',
            'image.max' => 'ขนาดรูปภาพต้องไม่เกิน 4MB',
        ]);

        DB::beginTransaction();

        try {
            $imagePath = null;

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('sell-order-reviews', 'public');
            }

            $adminId = $this->getAdminId();

            SellOrderReview::create([
                'sell_order_id' => $order->id,
                'user_id' => $order->user_id ?? null,
                'reviewed_by_type' => 'admin',
                'reviewed_by_admin_id' => $adminId,

                'customer_name' => $request->filled('customer_name')
                    ? trim($request->customer_name)
                    : ($order->customer_name ?? null),

                'customer_phone' => $request->filled('customer_phone')
                    ? trim($request->customer_phone)
                    : ($order->customer_phone ?? null),

                'rating' => (int) $request->rating,
                'title' => $request->title,
                'comment' => $request->comment,
                'image' => $imagePath,

                'is_displayed' => (int) $request->input('is_displayed', 1),
                'is_active' => (int) $request->input('is_active', 1),
            ]);

            DB::table('sell_order_status_histories')->insert([
                'sell_order_id' => $order->id,
                'old_status' => $order->status ?? null,
                'new_status' => $order->status ?? null,
                'changed_by_admin_id' => $adminId,
                'changed_by_user_id' => null,
                'note' => 'แอดมินเพิ่มรีวิวให้คำสั่งขายนี้',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'เพิ่มรีวิวเรียบร้อยแล้ว');
        } catch (Throwable $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'ไม่สามารถเพิ่มรีวิวได้: ' . $e->getMessage());
        }
    }

    private function statusOptions()
    {
        return [
            'pending' => 'รอทีมงานติดต่อกลับ',
            'confirmed' => 'ยืนยันรายการแล้ว',
            'contacted' => 'ติดต่อผู้ขายแล้ว',
            'waiting_receive' => 'รอรับสินค้า',
            'received' => 'รับสินค้าแล้ว',
            'inspecting' => 'กำลังตรวจสอบสินค้า',
            'price_adjusted' => 'มีการปรับราคา',
            'completed' => 'ดำเนินการสำเร็จ',
            'cancelled' => 'ยกเลิก',
        ];
    }

    private function pickupPanelOptions()
    {
        return [
            'store' => 'รับซื้อถึงที่',
            'bts_mrt' => 'รับซื้อตาม BTS/MRT',
            'ems' => 'จัดส่ง EMS',
        ];
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
