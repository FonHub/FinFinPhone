@extends('layouts.app')

@section('title', 'ส่งคำขอขายสินค้าเรียบร้อยแล้ว | Cashkub')

@section('content')
    @php
        $statusLabels = [
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

        $pickupPanelLabels = [
            'store' => 'รับซื้อถึงที่',
            'bts_mrt' => 'รับซื้อตาม BTS/MRT',
            'ems' => 'จัดส่ง EMS',
        ];

        $statusLabel = $statusLabels[$order->status] ?? $order->status;
        $pickupPanelLabel = $pickupPanelLabels[$order->pickup_panel] ?? ($order->sell_method_name ?? '-');

        $finalPrice = (float) ($order->final_estimate_price ?? 0);
        $bonusAmount = (float) ($order->bonus_amount ?? 0);
        $estimateBeforeBonus = max(0, $finalPrice - $bonusAmount);
    @endphp

    <section class="min-h-screen bg-[#F5F7F6]">
        

        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14">
            <div class="max-w-[820px] mx-auto text-center mb-8">
                <div
                    class="w-20 h-20 rounded-full bg-[#EAF7F1] text-[#10A36A] flex items-center justify-center mx-auto mb-5 shadow-sm">
                    <svg class="w-10 h-10" viewBox="0 0 24 24" fill="none">
                        <path d="M20 7L10 17l-5-5" stroke="currentColor" stroke-width="2.7" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>

                <h1 class="text-[#111827] text-[28px] sm:text-[36px] lg:text-[42px] font-extrabold leading-tight">
                    ส่งคำขอขายสินค้าเรียบร้อยแล้ว
                </h1>


            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
                <div class="lg:col-span-7 space-y-6">
                    <div
                        class="rounded-[24px] border border-[#E6ECE8] bg-white shadow-[0_20px_60px_rgba(15,23,42,0.06)] overflow-hidden">
                        <div class="px-5 sm:px-7 py-5 border-b border-[#EEF2EF] bg-[#FCFDFC]">
                            <div class="flex items-start sm:items-center justify-between gap-4 flex-col sm:flex-row">
                                <div>
                                    <div class="text-[#8A97A2] text-[13px] font-semibold mb-1">
                                        เลขที่คำสั่งขาย
                                    </div>
                                    <div class="text-[#111827] text-[24px] sm:text-[30px] font-extrabold">
                                        {{ $order->order_no }}
                                    </div>
                                </div>

                                <div
                                    class="inline-flex items-center rounded-full bg-[#EAF7F1] text-[#0E8C5B] border border-[#CDEADC] px-4 h-10 text-[14px] font-bold">
                                    {{ $statusLabel }}
                                </div>
                            </div>
                            <p class="mt-2 text-[#66727D] text-[12px] sm:text-[12px] leading-8">
                                ทีมงานได้รับข้อมูลของคุณแล้ว และจะติดต่อกลับตามข้อมูลที่ระบุไว้
                            </p>
                            <div class="mt-2 rounded-[14px] border border-[#F3D47A] bg-[#FFF7D6] px-4 py-3">
                                <div class="flex items-start gap-3">
                                    <div
                                        class="w-7 h-7 rounded-full bg-white border border-[#F59E0B] text-[#F59E0B] flex items-center justify-center shrink-0 mt-[2px]">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                            <path d="M12 10v6" stroke="currentColor" stroke-width="2.4"
                                                stroke-linecap="round" />
                                            <path d="M12 7h.01" stroke="currentColor" stroke-width="3"
                                                stroke-linecap="round" />
                                            <circle cx="12" cy="12" r="9" stroke="currentColor"
                                                stroke-width="2" />
                                        </svg>
                                    </div>

                                    <div class="text-[#5B4630] text-[13px] sm:text-[14px] leading-6">
                                        <span class="font-bold">หากมีข้อสงสัยติดต่อทันที</span>
                                        กรุณาติดต่อแอดมิน
                                        <span class="font-bold whitespace-nowrap">📞 098-950-9222</span>
                                        ในช่วงเวลาทำการ
                                        <span class="font-bold whitespace-nowrap">9.00 - 20.00</span>
                                        เพื่อยืนยันข้อมูลหมายเลขอ้างอิง
                                        <span class="font-bold text-[#111827] whitespace-nowrap">
                                            {{ $order->order_no }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="px-5 sm:px-7 py-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="rounded-[18px] border border-[#E7EEEA] bg-[#F9FBFA] p-5">
                                    <div class="text-[#8A97A2] text-[13px] font-semibold mb-2">
                                        สินค้าที่ขาย
                                    </div>

                                    <div class="text-[#111827] text-[18px] font-extrabold leading-7">
                                        {{ $order->summary_title ?? '-' }}
                                    </div>

                                    <div class="mt-3 text-[#66727D] text-[14px] leading-7">
                                        ประเภท: {{ $order->category_name ?? '-' }}<br>
                                        แบรนด์: {{ $order->brand_name ?? '-' }}<br>
                                        รุ่น: {{ $order->model_name ?? '-' }}<br>
                                        ความจุ: {{ $order->capacity ?? '-' }}
                                    </div>
                                </div>

                                <div class="rounded-[18px] border border-[#E7EEEA] bg-[#F9FBFA] p-5">
                                    <div class="text-[#8A97A2] text-[13px] font-semibold mb-2">
                                        วิธีรับซื้อ
                                    </div>

                                    <div class="text-[#111827] text-[18px] font-extrabold leading-7">
                                        {{ $pickupPanelLabel }}
                                    </div>

                                    <div class="mt-3 text-[#66727D] text-[14px] leading-7">
                                        วันที่นัดหมาย:
                                        {{ !empty($order->pickup_date) ? \Carbon\Carbon::parse($order->pickup_date)->format('d/m/Y') : '-' }}
                                        <br>
                                        ช่วงเวลา: {{ $order->pickup_time ?? '-' }}
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 rounded-[18px] border border-[#E7EEEA] bg-white p-5">
                                <div class="text-[#111827] text-[18px] font-extrabold mb-4">
                                    ข้อมูลผู้ขาย
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-[15px] leading-7">
                                    <div>
                                        <div class="text-[#8A97A2] text-[13px] font-semibold">
                                            ชื่อ-นามสกุล
                                        </div>
                                        <div class="text-[#111827] font-semibold">
                                            {{ $pickupDetail->fullname ?? ($order->customer_name ?? '-') }}
                                        </div>
                                    </div>

                                    <div>
                                        <div class="text-[#8A97A2] text-[13px] font-semibold">
                                            เบอร์โทรศัพท์
                                        </div>
                                        <div class="text-[#111827] font-semibold">
                                            {{ $pickupDetail->phone ?? ($order->customer_phone ?? '-') }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-[#8A97A2] text-[13px] font-semibold">
                                            Line ID
                                        </div>
                                        <div class="text-[#111827] font-semibold">
                                            {{ $pickupDetail->line_id ?? ($order->line_id ?? '-') }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-[#8A97A2] text-[13px] font-semibold">
                                            อีเมล
                                        </div>
                                        <div class="text-[#111827] font-semibold">
                                            {{ $pickupDetail->email ?? ($order->customer_email ?? '-') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 rounded-[18px] border border-[#E7EEEA] bg-white p-5">
                                <div class="text-[#111827] text-[18px] font-extrabold mb-4">
                                    รายละเอียดสถานที่ / การจัดส่ง
                                </div>

                                @if (($order->pickup_panel ?? '') === 'store')
                                    <div class="text-[15px] leading-8 text-[#111827]">
                                        {{-- <div>
                                            <span class="text-[#66727D]">สาขา:</span>
                                            {{ $pickupDetail->branch_name ?? '-' }}
                                        </div>
                                        <div>
                                            <span class="text-[#66727D]">ที่อยู่สาขา:</span>
                                            {{ $pickupDetail->branch_address ?? '-' }}
                                        </div> --}}
                                        <div>
                                            <span class="text-[#66727D]">ที่อยู่รับซื้อ:</span>
                                            {{ $pickupDetail->customer_address ?? '-' }}
                                        </div>
                                        <div>
                                            <span class="text-[#66727D]">จังหวัด / เขต:</span>
                                            {{ $pickupDetail->province ?? '-' }} / {{ $pickupDetail->district ?? '-' }}
                                        </div>
                                    </div>
                                @elseif (($order->pickup_panel ?? '') === 'bts_mrt')
                                    <div class="text-[15px] leading-8 text-[#111827]">
                                        <div>
                                            <span class="text-[#66727D]">สายรถไฟฟ้า:</span>
                                            {{ $pickupDetail->transit_line_name ?? '-' }}
                                        </div>
                                        <div>
                                            <span class="text-[#66727D]">สถานี:</span>
                                            {{ $pickupDetail->transit_station_name ?? '-' }}
                                        </div>
                                        <div>
                                            <span class="text-[#66727D]">รหัสสถานี:</span>
                                            {{ $pickupDetail->transit_station_code ?? '-' }}
                                        </div>
                                    </div>
                                @elseif (($order->pickup_panel ?? '') === 'ems')
                                    <div class="text-[15px] leading-8 text-[#111827]">
                                        <div>
                                            <span class="text-[#66727D]">ที่อยู่ผู้ส่ง:</span>
                                            {{ $pickupDetail->sender_address ?? '-' }}
                                        </div>

                                        <div class="mt-4 pt-4 border-t border-[#EEF2EF]">
                                            <div class="font-bold">ส่งสินค้ามาที่</div>
                                            <div>{{ $pickupDetail->parcel_receiver_name ?? '-' }}</div>
                                            <div>{{ $pickupDetail->parcel_receiver_address ?? '-' }}</div>
                                            <div>{{ $pickupDetail->parcel_receiver_phone ?? '-' }}</div>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-[15px] text-[#66727D]">
                                        -
                                    </div>
                                @endif
                            </div>

                            <!-- @if ($answers && $answers->count() > 0)
                                <div class="mt-6 rounded-[18px] border border-[#E7EEEA] bg-white p-5">
                                    <div class="text-[#111827] text-[18px] font-extrabold mb-4">
                                        คำตอบประเมินสภาพเครื่อง
                                    </div>

                                    <div class="space-y-3">
                                        @foreach ($answers as $answer)
                                            <div class="rounded-[14px] border border-[#E7EEEA] bg-[#F9FBFA] px-4 py-3">
                                                <div class="text-[#111827] font-bold text-[15px]">
                                                    {{ $answer->question_title ?? '-' }}
                                                </div>

                                                <div class="mt-1 text-[#66727D] text-[14px] leading-7">
                                                    {{ $answer->option_title ?? '-' }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif -->
                        </div>
                    </div>
                </div>

                <aside class="lg:col-span-5">
                    <div class="lg:sticky lg:top-6 space-y-6">
                        <div
                            class="rounded-[24px] border border-[#E6ECE8] bg-white shadow-[0_20px_60px_rgba(15,23,42,0.06)] overflow-hidden">
                            <div class="px-5 sm:px-6 pt-6 pb-4">
                                <h3 class="text-[#16A36C] text-[24px] font-extrabold tracking-tight text-center">
                                    สรุปราคาประเมิน
                                </h3>
                            </div>

                            <div class="px-5 sm:px-6 pb-6">
                                <div class="rounded-[22px] border border-[#E5ECE8] bg-[#FCFDFC] p-5">
                                    <div class="flex items-center justify-between gap-4 py-3 border-b border-[#E9EFEB]">
                                        <div class="text-[#66727D] text-[15px]">
                                            ราคาประเมิน
                                        </div>
                                        <div class="text-[#111827] font-bold">
                                            ฿{{ number_format($estimateBeforeBonus, 0) }}
                                        </div>
                                    </div>

                                    @if ($bonusAmount > 0)
                                        <div
                                            class="flex items-center justify-between gap-4 py-3 border-b border-[#E9EFEB]">
                                            <div class="text-[#66727D] text-[15px]">
                                                โบนัสโค้ด
                                                @if (!empty($order->bonus_code))
                                                    <span class="text-[#10A36A]">
                                                        ({{ $order->bonus_code }})
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-[#10A36A] font-bold">
                                                +฿{{ number_format($bonusAmount, 0) }}
                                            </div>
                                        </div>
                                    @endif

                                    <div class="flex items-center justify-between gap-4 pt-5">
                                        <div>
                                            <div class="text-[#111827] text-[16px] font-bold">
                                                ราคาประเมินสุทธิ
                                            </div>
                                            <div class="text-[#8A97A2] text-[12px] mt-1">
                                                อาจเปลี่ยนแปลงหลังตรวจเครื่องจริง
                                            </div>
                                        </div>

                                        <div class="text-[#10A36A] text-[30px] font-extrabold">
                                            ฿{{ number_format($finalPrice, 0) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-5 rounded-[22px] bg-[#F7FBF9] border border-[#E3ECE7] p-5">
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-[#EAF7F1] text-[#10A36A] flex items-center justify-center shrink-0">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                                                <path
                                                    d="M12 8v4m0 4h.01M4.93 19h14.14c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.2 16c-.77 1.33.19 3 1.73 3Z"
                                                    stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </div>

                                        <div>
                                            <div class="text-[#111827] font-bold text-[16px]">
                                                ขั้นตอนถัดไป
                                            </div>
                                            <p class="mt-2 text-[#66727D] text-[14px] leading-7">
                                                ทีมงานจะตรวจสอบข้อมูลและติดต่อกลับตามเบอร์โทรศัพท์ที่คุณแจ้งไว้
                                                กรุณาเตรียมสินค้าและเอกสารให้พร้อมตามวิธีรับซื้อที่เลือก
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <a href="{{ route('sell.product') }}"
                                        class="inline-flex items-center justify-center h-[52px] rounded-full border border-[#DCE6E0] bg-white text-[#285F43] font-bold hover:bg-[#F9FBFA] transition">
                                        ขายสินค้าเพิ่ม
                                    </a>

                                    <a href="{{ url('/') }}"
                                        class="inline-flex items-center justify-center h-[52px] rounded-full bg-[#10A36A] hover:bg-[#0E8C5B] text-white font-bold transition shadow-[0_10px_24px_rgba(16,163,106,0.22)]">
                                        กลับหน้าแรก
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
