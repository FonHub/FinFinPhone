@extends('layouts.app')

@section('title', 'โปรไฟล์ | Cashkub')

@section('content')
@php
$userName = $user->name ?? 'สมาชิก';
$userEmail = $user->email ?? '-';
$userPhone = $user->phone ?? '-';
$userLineId = $user->line_id ?? '-';
$initial = mb_substr($userName, 0, 1, 'UTF-8');

$sellOrders = $sellOrders ?? collect();
$reviews = $reviews ?? collect();
$reviewableOrders = $reviewableOrders ?? collect();

$profileStats = $profileStats ?? [
'sell_order_count' => 0,
'completed_order_count' => 0,
'total_sell_amount' => 0,
];

function profileStatusClass($status)
{
if (in_array($status, ['completed', 'success'])) {
return 'bg-[#EAF7F1] text-[#13885C]';
}

if (in_array($status, ['confirmed', 'approved', 'processing'])) {
return 'bg-[#EEF4FF] text-[#3B82F6]';
}

if (in_array($status, ['cancelled', 'canceled', 'rejected'])) {
return 'bg-[#FFF1F1] text-[#C0392B]';
}

return 'bg-[#FFF7E8] text-[#D08A00]';
}
@endphp

<section class="min-h-screen bg-[#F5F7F6]">

    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10" style="max-width: 1140px;">
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 xl:gap-8 items-start">
            <div class="xl:col-span-4">
                <div class="xl:sticky xl:top-6 space-y-4">
                    <div
                        class="rounded-[24px] border border-[#E6ECE8] bg-white shadow-[0_20px_60px_rgba(15,23,42,0.06)] overflow-hidden">
                        <div class="px-3 sm:px-2 py-2 text-center">


                            <h1 class="mt-1 text-[#111827] text-[28px] font-bold">
                                {{ $userName }}
                            </h1>

                            <p class="mt-1 text-[#66727D] text-[15px]">
                                {{ $userEmail }}
                            </p>

                            <p class="mt-1 text-[#66727D] text-[15px]">
                                {{ $userPhone }}
                            </p>

                            @if ($userLineId !== '-')
                            <p class="mt-1 text-[#66727D] text-[15px]">
                                Line ID: {{ $userLineId }}
                            </p>
                            @endif


                        </div>
                    </div>

                    <div
                        class="rounded-[24px] border border-[#E6ECE8] bg-white shadow-[0_20px_60px_rgba(15,23,42,0.06)] overflow-hidden">
                        <div class="px-5 sm:px-6 py-5 border-b border-[#EEF2EF]">
                            <div class="text-[#111827] text-[18px] font-bold">
                                สรุปบัญชี
                            </div>
                        </div>

                        <div class="px-5 sm:px-6 py-5 space-y-3">
                            <div class="rounded-[16px] border border-[#E5ECE8] bg-[#FCFDFC] p-4">
                                <div class="text-[#8A97A2] text-[13px] font-semibold">
                                    คำสั่งขายทั้งหมด
                                </div>
                                <div class="mt-1 text-[#111827] text-[24px] font-extrabold">
                                    {{ number_format($profileStats['sell_order_count']) }}
                                </div>
                            </div>

                            <div class="rounded-[16px] border border-[#E5ECE8] bg-[#FCFDFC] p-4">
                                <div class="text-[#8A97A2] text-[13px] font-semibold">
                                    คำสั่งขายสำเร็จ
                                </div>
                                <div class="mt-1 text-[#10A36A] text-[24px] font-extrabold">
                                    {{ number_format($profileStats['completed_order_count']) }}
                                </div>
                            </div>

                            <div class="rounded-[16px] border border-[#E5ECE8] bg-[#FCFDFC] p-4">
                                <div class="text-[#8A97A2] text-[13px] font-semibold">
                                    ยอดขายรวมโดยประมาณ
                                </div>
                                <div class="mt-1 text-[#10A36A] text-[24px] font-extrabold">
                                    ฿{{ number_format($profileStats['total_sell_amount'], 0) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="rounded-[24px] border border-[#E6ECE8] bg-white shadow-[0_20px_60px_rgba(15,23,42,0.06)] overflow-hidden">
                        <div class="px-5 sm:px-6 py-5 border-b border-[#EEF2EF]">
                            <div class="text-[#111827] text-[18px] font-bold">
                                เมนูโปรไฟล์
                            </div>
                        </div>

                        <div class="px-5 sm:px-6 py-5 space-y-3">
                            <button type="button"
                                class="profile-menu-btn w-full flex items-center justify-between rounded-[16px] border border-[#10A36A] bg-[#EAF7F1] px-4 py-4 text-[#13885C] font-semibold transition"
                                data-target="history">
                                <span>ประวัติคำสั่งขาย</span>
                                <span>→</span>
                            </button>

                            <button type="button"
                                class="profile-menu-btn w-full flex items-center justify-between rounded-[16px] border border-[#DDE7E1] bg-[#F7FBF9] px-4 py-4 text-[#111827] font-semibold hover:border-[#10A36A] transition"
                                data-target="reviews">
                                <span>การส่งรีวิว</span>
                                <span>→</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="xl:col-span-8 space-y-6">
                <div id="history"
                    class="profile-content rounded-[28px] border border-[#E6ECE8] bg-white shadow-[0_20px_60px_rgba(15,23,42,0.06)] overflow-hidden">
                    <div
                        class="px-6 sm:px-8 py-6 border-b border-[#EEF2EF] bg-[linear-gradient(135deg,#F7FCF9_0%,#FFFFFF_45%,#F2FAF6_100%)]">
                        <div class="text-[#16A36C] text-[18px] mb-2">
                            ประวัติคำสั่งขาย
                        </div>
                        <h2 class="text-[#111827] text-[28px] sm:text-[32px] font-bold leading-tight">
                            รายการย้อนหลัง
                        </h2>
                        <p class="mt-2 text-[#66727D] text-[14px] leading-7">
                            ราคาที่แสดงจะใช้ราคาที่แอดมินยืนยัน หากยังไม่มีราคายืนยันจะแสดงราคาประเมินเดิม
                        </p>
                    </div>

                    <div class="px-6 sm:px-8 py-6">
                        @if ($sellOrders->count() > 0)
                        <div class="space-y-4">
                            @foreach ($sellOrders as $order)
                            <div class="rounded-[20px] border border-[#E5ECE8] bg-[#FCFDFC] p-5">
                                <div class="flex items-start justify-between gap-4 flex-col md:flex-row">
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-3 flex-wrap">
                                            <span
                                                class="inline-flex items-center rounded-full px-3 h-8 text-[12px] font-bold bg-[#EAF7F1] text-[#13885C]">
                                                ขาย
                                            </span>

                                            <span
                                                class="inline-flex items-center rounded-full px-3 h-8 text-[12px] font-bold {{ profileStatusClass($order->status) }}">
                                                {{ $order->status_label }}
                                            </span>

                                            <span
                                                class="inline-flex items-center rounded-full px-3 h-8 text-[12px] font-bold bg-[#F3F6F4] text-[#66727D]">
                                                {{ $order->pickup_label }}
                                            </span>
                                        </div>

                                        <div class="mt-3 text-[#111827] text-[18px] font-bold leading-7">
                                            {{ $order->title ?: '-' }}
                                        </div>

                                        <div class="mt-2 text-[#66727D] text-[14px] leading-7">
                                            เลขที่คำสั่งขาย:
                                            <span class="font-bold text-[#111827]">
                                                {{ $order->order_no }}
                                            </span>
                                        </div>

                                        <div class="mt-1 text-[#66727D] text-[14px]">
                                            วันที่ทำรายการ:
                                            {{ optional(\Carbon\Carbon::parse($order->created_at))->format('d/m/Y H:i') }}
                                        </div>

                                        <!-- @if (!empty($order->summary_text))
                                        <div
                                            class="mt-3 rounded-[14px] border border-[#E5ECE8] bg-white px-4 py-3 text-[#66727D] text-[13px] leading-6">
                                            {{ $order->summary_text }}
                                        </div>
                                        @endif -->
                                    </div>

                                    <div class="text-left md:text-right shrink-0">
                                        <div class="text-[#8A97A2] text-[13px] font-medium">
                                            ราคาที่แสดง
                                        </div>

                                        <div
                                            class="mt-1 text-[#10A36A] text-[24px] font-extrabold tracking-tight">
                                            ฿{{ number_format($order->display_price, 0) }}
                                        </div>

                                        @if ((float) $order->display_price !== (float) $order->original_price)
                                        <div class="mt-1 text-[#66727D] text-[12px] leading-5">
                                            ราคาประเมินเดิม:
                                            ฿{{ number_format($order->original_price, 0) }}
                                        </div>
                                        @else
                                        <div class="mt-1 text-[#66727D] text-[12px] leading-5">
                                            ยังไม่มีราคาแอดมินยืนยัน
                                        </div>
                                        @endif

                                        <a href="{{ route('sell.product.orders.success', $order->order_no) }}"
                                            class="mt-4 inline-flex items-center justify-center rounded-full border border-[#10A36A] text-[#10A36A] hover:bg-[#EAF7F1] px-4 h-10 text-[13px] font-bold transition">
                                            ดูรายละเอียด
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div
                            class="rounded-[22px] border border-[#E8EFEB] bg-[#F9FBFA] p-8 text-center text-[#66727D]">
                            <div class="text-[18px] font-bold text-[#111827]">
                                ยังไม่มีประวัติคำสั่งขาย
                            </div>
                            <p class="mt-2 text-[14px] leading-7">
                                เมื่อคุณส่งคำขอขายสินค้า รายการจะแสดงที่หน้านี้
                            </p>

                            <a href="{{ route('sell.product') }}"
                                class="mt-5 inline-flex items-center justify-center rounded-full bg-[#10A36A] hover:bg-[#0E8C5B] text-white text-[15px] font-bold px-6 h-12 shadow-[0_12px_30px_rgba(16,163,106,0.22)] transition">
                                เริ่มขายสินค้า
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <div id="reviews"
                    class="profile-content hidden rounded-[28px] border border-[#E6ECE8] bg-white shadow-[0_20px_60px_rgba(15,23,42,0.06)] overflow-hidden">
                    <div class="px-6 sm:px-8 py-6 border-b border-[#EEF2EF]">
                        <div class="text-[#16A36C] text-[18px] mb-2">
                            การส่งรีวิว
                        </div>
                        <h2 class="text-[#111827] text-[28px] sm:text-[32px] font-bold leading-tight">
                            รีวิวของคุณ
                        </h2>
                        <p class="mt-2 text-[#66727D] text-[14px] leading-7">
                            คุณสามารถรีวิวได้เฉพาะคำสั่งขายที่สำเร็จแล้ว และรีวิวได้เพียง 1 ครั้งต่อคำสั่งขาย
                        </p>
                    </div>

                    <div class="px-6 sm:px-8 py-6">
                        @if (session('success'))
                        <div
                            class="mb-5 rounded-[16px] border border-[#CDEADC] bg-[#EAF7F1] px-4 py-3 text-[#0E8C5B] text-[14px] leading-6">
                            {{ session('success') }}
                        </div>
                        @endif

                        @if (session('error'))
                        <div
                            class="mb-5 rounded-[16px] border border-[#F7CACA] bg-[#FFF1F1] px-4 py-3 text-[#C0392B] text-[14px] leading-6">
                            {{ session('error') }}
                        </div>
                        @endif

                        @if ($errors->any())
                        <div
                            class="mb-5 rounded-[16px] border border-[#F7CACA] bg-[#FFF1F1] px-4 py-3 text-[#C0392B] text-[14px] leading-6">
                            {{ $errors->first() }}
                        </div>
                        @endif

                        <div class="rounded-[22px] border border-[#E8EFEB] bg-[#F9FBFA] p-5 sm:p-6 mb-6">
                            <div class="flex items-start justify-between gap-4 flex-col sm:flex-row">
                                <div>
                                    <div class="text-[#111827] text-[20px] font-bold">
                                        ส่งรีวิวใหม่
                                    </div>
                                    <p class="mt-1 text-[#66727D] text-[14px] leading-7">
                                        เลือกคำสั่งขายที่สำเร็จแล้วเพื่อเขียนรีวิว
                                    </p>
                                </div>

                                <div
                                    class="inline-flex items-center rounded-full bg-[#EAF7F1] text-[#13885C] text-[13px] font-bold px-4 h-10">
                                    รีวิวได้ {{ number_format($reviewableOrders->count()) }} รายการ
                                </div>
                            </div>

                            @if ($reviewableOrders->count() > 0)
                            <form method="POST" action="{{ route('profile.reviews.store') }}"
                                enctype="multipart/form-data" class="mt-5 space-y-4">
                                @csrf

                                <div>
                                    <label class="block text-[#111827] text-[15px] font-semibold mb-2">
                                        คำสั่งขายที่ต้องการรีวิว <span class="text-[#E05454]">*</span>
                                    </label>
                                    <select name="sell_order_id"
                                        class="w-full h-14 rounded-[16px] border border-[#DCE6E0] bg-white px-4 text-[15px] text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#10A36A]/20 focus:border-[#10A36A]">
                                        <option value="">เลือกคำสั่งขาย</option>
                                        @foreach ($reviewableOrders as $order)
                                        <option value="{{ $order->id }}"
                                            {{ old('sell_order_id') == $order->id ? 'selected' : '' }}>
                                            {{ $order->order_no }} - {{ $order->title }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[#111827] text-[15px] font-semibold mb-2">
                                        คะแนน <span class="text-[#E05454]">*</span>
                                    </label>
                                    <select name="rating"
                                        class="w-full h-14 rounded-[16px] border border-[#DCE6E0] bg-white px-4 text-[15px] text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#10A36A]/20 focus:border-[#10A36A]">
                                        <option value="5" {{ old('rating', 5) == 5 ? 'selected' : '' }}>
                                            ★★★★★ 5 คะแนน
                                        </option>
                                        <option value="4" {{ old('rating') == 4 ? 'selected' : '' }}>
                                            ★★★★☆ 4 คะแนน
                                        </option>
                                        <option value="3" {{ old('rating') == 3 ? 'selected' : '' }}>
                                            ★★★☆☆ 3 คะแนน
                                        </option>
                                        <option value="2" {{ old('rating') == 2 ? 'selected' : '' }}>
                                            ★★☆☆☆ 2 คะแนน
                                        </option>
                                        <option value="1" {{ old('rating') == 1 ? 'selected' : '' }}>
                                            ★☆☆☆☆ 1 คะแนน
                                        </option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[#111827] text-[15px] font-semibold mb-2">
                                        หัวข้อรีวิว
                                    </label>
                                    <input type="text" name="title" value="{{ old('title') }}"
                                        placeholder="เช่น บริการดีมาก นัดรับรวดเร็ว"
                                        class="w-full h-14 rounded-[16px] border border-[#DCE6E0] bg-white px-4 text-[15px] text-[#111827] placeholder:text-[#9AA7B2] focus:outline-none focus:ring-2 focus:ring-[#10A36A]/20 focus:border-[#10A36A]">
                                </div>

                                <div>
                                    <label class="block text-[#111827] text-[15px] font-semibold mb-2">
                                        ข้อความรีวิว <span class="text-[#E05454]">*</span>
                                    </label>
                                    <textarea name="comment" rows="5" placeholder="เขียนรีวิวของคุณ"
                                        class="w-full rounded-[16px] border border-[#DCE6E0] bg-white px-4 py-4 text-[15px] text-[#111827] placeholder:text-[#9AA7B2] focus:outline-none focus:ring-2 focus:ring-[#10A36A]/20 focus:border-[#10A36A] resize-none">{{ old('comment') }}</textarea>
                                </div>

                                <div>
                                    <label class="block text-[#111827] text-[15px] font-semibold mb-2">
                                        รูปภาพรีวิว
                                    </label>
                                    <input type="file" name="image" accept="image/jpeg,image/png,image/webp"
                                        class="block w-full rounded-[16px] border border-[#DCE6E0] bg-white px-4 py-3 text-[15px] text-[#111827] file:mr-4 file:rounded-full file:border-0 file:bg-[#EAF7F1] file:px-4 file:py-2 file:text-[#13885C] file:font-bold">
                                    <p class="mt-2 text-[12px] text-[#8A97A2]">
                                        รองรับ jpg, jpeg, png, webp ขนาดไม่เกิน 4MB
                                    </p>
                                </div>

                                <button type="submit"
                                    class="inline-flex items-center justify-center rounded-full bg-[#10A36A] hover:bg-[#0E8C5B] text-white text-[15px] font-bold px-6 h-12 shadow-[0_12px_30px_rgba(16,163,106,0.22)] transition">
                                    ส่งรีวิว
                                </button>
                            </form>
                            @else
                            <div
                                class="mt-5 rounded-[18px] border border-[#E5ECE8] bg-white px-5 py-6 text-center">
                                <div class="text-[#111827] text-[17px] font-bold">
                                    ยังไม่มีคำสั่งขายที่สามารถรีวิวได้
                                </div>
                                <p class="mt-2 text-[#66727D] text-[14px] leading-7">
                                    เมื่อคำสั่งขายของคุณสำเร็จ และยังไม่เคยรีวิว รายการจะปรากฏให้เลือกที่นี่
                                </p>
                            </div>
                            @endif
                        </div>

                        <div class="space-y-4">
                            <div class="text-[#111827] text-[20px] font-bold">
                                รีวิวที่ผ่านมา
                            </div>

                            @if ($reviews->count() > 0)
                            @foreach ($reviews as $review)
                            <div class="rounded-[20px] border border-[#E5ECE8] bg-[#FCFDFC] p-5">
                                <div class="flex items-start justify-between gap-4 flex-col sm:flex-row">
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-3 flex-wrap">
                                            <span
                                                class="inline-flex items-center rounded-full px-3 h-8 text-[12px] font-bold bg-[#EAF7F1] text-[#13885C]">
                                                {{ $review->order_no ?? '-' }}
                                            </span>

                                            <span
                                                class="inline-flex items-center rounded-full px-3 h-8 text-[12px] font-bold bg-[#FFF7E8] text-[#D08A00]">
                                                {{ str_repeat('★', (int) $review->rating) }}{{ str_repeat('☆', 5 - (int) $review->rating) }}
                                            </span>
                                        </div>

                                        <div class="mt-3 text-[#111827] text-[18px] font-bold leading-7">
                                            {{ $review->title ?: $review->order_title }}
                                        </div>

                                        <div class="mt-1 text-[#66727D] text-[14px] leading-7">
                                            {{ $review->order_title }}
                                        </div>

                                        <p class="mt-3 text-[#66727D] text-[15px] leading-7">
                                            {{ $review->comment }}
                                        </p>

                                        @if (!empty($review->image))
                                        <div class="mt-4">
                                            <img src="{{ asset('storage/' . $review->image) }}"
                                                alt="Review image"
                                                class="w-[120px] h-[120px] rounded-[18px] border border-[#E5ECE8] object-cover">
                                        </div>
                                        @endif
                                    </div>

                                    <div class="text-[#8A97A2] text-[14px] whitespace-nowrap">
                                        {{ optional(\Carbon\Carbon::parse($review->created_at))->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @else
                            <div
                                class="rounded-[22px] border border-[#E8EFEB] bg-[#F9FBFA] p-8 text-center text-[#66727D]">
                                <div class="text-[18px] font-bold text-[#111827]">
                                    ยังไม่มีรีวิว
                                </div>
                                <p class="mt-2 text-[14px] leading-7">
                                    เมื่อคุณส่งรีวิวแล้ว รายการจะแสดงที่นี่
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuButtons = document.querySelectorAll('.profile-menu-btn');
            const contents = document.querySelectorAll('.profile-content');

            function setActive(targetId) {
                contents.forEach(function(content) {
                    content.classList.toggle('hidden', content.id !== targetId);
                });

                menuButtons.forEach(function(button) {
                    const isActive = button.dataset.target === targetId;

                    if (isActive) {
                        button.classList.remove('border-[#DDE7E1]', 'bg-[#F7FBF9]', 'text-[#111827]');
                        button.classList.add('border-[#10A36A]', 'bg-[#EAF7F1]', 'text-[#13885C]');
                    } else {
                        button.classList.remove('border-[#10A36A]', 'bg-[#EAF7F1]', 'text-[#13885C]');
                        button.classList.add('border-[#DDE7E1]', 'bg-[#F7FBF9]', 'text-[#111827]');
                    }
                });
            }

            menuButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    setActive(this.dataset.target);
                });
            });

            setActive('history');
        });
    </script>
</section>
@endsection