@extends('layouts.app')

@section('title', 'โปรไฟล์ | Cashkub')

@section('content')
@php
$purchaseSellHistory = [
[
'date' => '10 เม.ย. 2026',
'type' => 'ขาย',
'title' => 'iPhone 15 Pro Max 256GB',
'status' => 'สำเร็จ',
'amount' => '฿28,500',
],
[
'date' => '03 เม.ย. 2026',
'type' => 'ซื้อ',
'title' => 'iPad Air 5 64GB',
'status' => 'สำเร็จ',
'amount' => '฿16,900',
],
[
'date' => '22 มี.ค. 2026',
'type' => 'ขาย',
'title' => 'iPhone 13 128GB',
'status' => 'รอตรวจสอบ',
'amount' => '฿12,300',
],
[
'date' => '14 มี.ค. 2026',
'type' => 'ซื้อ',
'title' => 'AirPods Pro 2',
'status' => 'สำเร็จ',
'amount' => '฿6,490',
],
];

$reviews = [
[
'service' => 'บริการรับซื้อถึงบ้าน',
'score' => 5,
'date' => '08 เม.ย. 2026',
'comment' => 'บริการดีมาก ติดต่อรวดเร็ว นัดหมายง่าย และให้ข้อมูลชัดเจน',
],
[
'service' => 'ประเมินราคาสินค้า',
'score' => 4,
'date' => '25 มี.ค. 2026',
'comment' => 'หน้าเว็บใช้งานง่าย ประเมินราคาได้ไว เข้าใจไม่ยาก',
],
];
@endphp

<section class="min-h-screen bg-[#F5F7F6]">
    <div class="border-b border-[#E5ECE8] bg-white/90 backdrop-blur-sm">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="h-[82px] flex items-center justify-between">
                <a href="{{ url('/') }}"
                    class="inline-flex items-center gap-2 text-[#6B7280] hover:text-[#285F43] transition text-[15px] font-medium">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                        <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    กลับหน้าแรก
                </a>

                <div class="text-center">
                    <div class="text-[#1FA36E] font-extrabold text-[18px] sm:text-[20px] tracking-tight">
                        Cashkub
                    </div>
                </div>

                <div class="w-[140px] hidden sm:block"></div>
            </div>
        </div>
    </div>

    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10">
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 xl:gap-8 items-start">
            <div class="xl:col-span-4">
                <div class="xl:sticky xl:top-6 space-y-4">
                    <div class="rounded-[24px] border border-[#E6ECE8] bg-white shadow-[0_20px_60px_rgba(15,23,42,0.06)] overflow-hidden">
                        <div class="px-6 sm:px-7 py-7 text-center">
                            <div class="w-24 h-24 mx-auto rounded-full bg-[#EAF7F1] text-[#10A36A] flex items-center justify-center text-[34px] font-extrabold">
                                N
                            </div>

                            <h1 class="mt-4 text-[#111827] text-[28px] font-bold">
                                Natanon
                            </h1>

                            <p class="mt-1 text-[#66727D] text-[15px]">
                                [email protected]
                            </p>
                            <p class="mt-1 text-[#66727D] text-[15px]">
                                08x-xxx-1234
                            </p>

                            <div class="mt-5 inline-flex items-center rounded-full bg-[#EAF7F1] text-[#13885C] text-[13px] font-bold px-4 h-10">
                                Mock-up Profile
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[24px] border border-[#E6ECE8] bg-white shadow-[0_20px_60px_rgba(15,23,42,0.06)] overflow-hidden">
                        <div class="px-5 sm:px-6 py-5 border-b border-[#EEF2EF]">
                            <div class="text-[#111827] text-[18px] font-bold">
                                เมนูโปรไฟล์
                            </div>
                        </div>

                        <div class="px-5 sm:px-6 py-5 space-y-3">
                            <a href="#history"
                                class="flex items-center justify-between rounded-[16px] border border-[#DDE7E1] bg-[#F7FBF9] px-4 py-4 text-[#111827] font-semibold hover:border-[#10A36A] transition">
                                <span>ประวัติซื้อ-ขาย</span>
                                <span class="text-[#10A36A]">→</span>
                            </a>

                            <a href="#reviews"
                                class="flex items-center justify-between rounded-[16px] border border-[#DDE7E1] bg-[#F7FBF9] px-4 py-4 text-[#111827] font-semibold hover:border-[#10A36A] transition">
                                <span>การส่งรีวิว</span>
                                <span class="text-[#10A36A]">→</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="xl:col-span-8 space-y-6">
                <div id="history" class="rounded-[28px] border border-[#E6ECE8] bg-white shadow-[0_20px_60px_rgba(15,23,42,0.06)] overflow-hidden">
                    <div class="px-6 sm:px-8 py-6 border-b border-[#EEF2EF] bg-[linear-gradient(135deg,#F7FCF9_0%,#FFFFFF_45%,#F2FAF6_100%)]">
                        <div class="text-[#16A36C] text-[18px] mb-2">ประวัติซื้อ-ขาย</div>
                        <h2 class="text-[#111827] text-[28px] sm:text-[32px] font-bold leading-tight">
                            รายการย้อนหลัง
                        </h2>
                    </div>

                    <div class="px-6 sm:px-8 py-6">
                        <div class="space-y-4">
                            @foreach($purchaseSellHistory as $item)
                            <div class="rounded-[20px] border border-[#E5ECE8] bg-[#FCFDFC] p-5">
                                <div class="flex items-start justify-between gap-4 flex-col md:flex-row">
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-3 flex-wrap">
                                            <span class="inline-flex items-center rounded-full px-3 h-8 text-[12px] font-bold {{ $item['type'] === 'ขาย' ? 'bg-[#EAF7F1] text-[#13885C]' : 'bg-[#EEF4FF] text-[#3B82F6]' }}">
                                                {{ $item['type'] }}
                                            </span>

                                            <span class="inline-flex items-center rounded-full px-3 h-8 text-[12px] font-bold {{ $item['status'] === 'สำเร็จ' ? 'bg-[#EAF7F1] text-[#13885C]' : 'bg-[#FFF7E8] text-[#D08A00]' }}">
                                                {{ $item['status'] }}
                                            </span>
                                        </div>

                                        <div class="mt-3 text-[#111827] text-[18px] font-bold leading-7">
                                            {{ $item['title'] }}
                                        </div>

                                        <div class="mt-2 text-[#66727D] text-[14px]">
                                            วันที่ทำรายการ: {{ $item['date'] }}
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <div class="text-[#8A97A2] text-[13px] font-medium">
                                            จำนวนเงิน
                                        </div>
                                        <div class="mt-1 text-[#10A36A] text-[24px] font-extrabold tracking-tight">
                                            {{ $item['amount'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div id="reviews" class="rounded-[28px] border border-[#E6ECE8] bg-white shadow-[0_20px_60px_rgba(15,23,42,0.06)] overflow-hidden">
                    <div class="px-6 sm:px-8 py-6 border-b border-[#EEF2EF]">
                        <div class="text-[#16A36C] text-[18px] mb-2">การส่งรีวิว</div>
                        <h2 class="text-[#111827] text-[28px] sm:text-[32px] font-bold leading-tight">
                            รีวิวของคุณ
                        </h2>
                    </div>

                    <div class="px-6 sm:px-8 py-6">
                        <div class="rounded-[22px] border border-[#E8EFEB] bg-[#F9FBFA] p-5 sm:p-6 mb-6">
                            <div class="text-[#111827] text-[20px] font-bold">
                                ส่งรีวิวใหม่
                            </div>

                            <form method="POST" action="#" class="mt-5 space-y-4">
                                @csrf

                                <div>
                                    <label class="block text-[#111827] text-[15px] font-semibold mb-2">
                                        หัวข้อบริการ
                                    </label>
                                    <select class="w-full h-14 rounded-[16px] border border-[#DCE6E0] bg-white px-4 text-[15px] text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#10A36A]/20 focus:border-[#10A36A]">
                                        <option>เลือกหัวข้อรีวิว</option>
                                        <option>บริการรับซื้อถึงบ้าน</option>
                                        <option>ประเมินราคาสินค้า</option>
                                        <option>บริการหลังการขาย</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[#111827] text-[15px] font-semibold mb-2">
                                        คะแนน
                                    </label>
                                    <div class="flex items-center gap-2 text-[#F59E0B]">
                                        <span>★</span>
                                        <span>★</span>
                                        <span>★</span>
                                        <span>★</span>
                                        <span>★</span>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[#111827] text-[15px] font-semibold mb-2">
                                        ข้อความรีวิว
                                    </label>
                                    <textarea
                                        rows="5"
                                        placeholder="เขียนรีวิวของคุณ"
                                        class="w-full rounded-[16px] border border-[#DCE6E0] bg-white px-4 py-4 text-[15px] text-[#111827] placeholder:text-[#9AA7B2] focus:outline-none focus:ring-2 focus:ring-[#10A36A]/20 focus:border-[#10A36A]"></textarea>
                                </div>

                                <button
                                    type="submit"
                                    class="inline-flex items-center justify-center rounded-full bg-[#10A36A] hover:bg-[#0E8C5B] text-white text-[15px] font-bold px-6 h-12 shadow-[0_12px_30px_rgba(16,163,106,0.22)] transition">
                                    ส่งรีวิว
                                </button>
                            </form>
                        </div>

                        <div class="space-y-4">
                            @foreach($reviews as $review)
                            <div class="rounded-[20px] border border-[#E5ECE8] bg-[#FCFDFC] p-5">
                                <div class="flex items-start justify-between gap-4 flex-col sm:flex-row">
                                    <div>
                                        <div class="text-[#111827] text-[18px] font-bold">
                                            {{ $review['service'] }}
                                        </div>
                                        <div class="mt-2 text-[#F59E0B] text-[18px]">
                                            @for($i = 1; $i <= 5; $i++)
                                                {{ $i <= $review['score'] ? '★' : '☆' }}
                                                @endfor
                                                </div>
                                                <p class="mt-3 text-[#66727D] text-[15px] leading-7">
                                                    {{ $review['comment'] }}
                                                </p>
                                        </div>

                                        <div class="text-[#8A97A2] text-[14px] whitespace-nowrap">
                                            {{ $review['date'] }}
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>
@endsection