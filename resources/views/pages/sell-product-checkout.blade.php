@extends('layouts.app')

@section('title', 'ยืนยันการขาย | Cashkub')

<style>
    .sell-method-card {
        min-height: 125px;
    }

    @media (min-width: 768px) {
        .sell-method-card {
            min-height: 230px;
        }
    }
</style>

@section('content')
@php
$pickupMethods = $pickupMethods ?? [];
$storeBranches = $storeBranches ?? collect();
$transitStationsByLine = $transitStationsByLine ?? [];
$parcelSettings = $parcelSettings ?? collect();
$requiredDocuments = $requiredDocuments ?? collect();

$defaultPickupMethod = old('pickup_method', $pickupMethods[0]['key'] ?? '');
$defaultPickupPanel = old('pickup_panel', $pickupMethods[0]['panel_key'] ?? 'store');

$productImage = $productImage ?? 'assets/media/hero/hero-phone-right.png';

$estimatedPrice = (float) ($estimatedPrice ?? 0);
$basePrice = (float) ($basePrice ?? 0);
$deductTotal = (float) ($deductTotal ?? 0);
$priceAfterDeduct = (float) ($priceAfterDeduct ?? 0);
$bonusAmount = (float) ($bonusAmount ?? 0);

$bonusCodes = $bonusCodes ?? [];
$canUseBonusCode = $canUseBonusCode ?? false;
$selectedBonusCodeId = old('bonus_code_id', $bonusCode->id ?? '');

$summaryTitle = $summaryTitle ?? trim(($brand ?? '') . ' ' . ($model ?? '') . ' ' . ($storage ?? ''));
$summaryText = $summaryText ?? '-';

$minPickupDate = now()->format('Y-m-d');
$maxPickupDate = now()->addDays(30)->format('Y-m-d');

$times = $times ?? ['เวลา'];
$serviceTimeSlots = $serviceTimeSlots ?? collect();

$provinces = $provinces ?? [];
$districts = $districts ?? [];
$serviceAreas = $serviceAreas ?? collect();
$serviceProvinces = $serviceProvinces ?? [];
@endphp

<section class="min-h-screen bg-[#F5F7F6]">


    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10" style="max-width: 1140px;">
        <div class="mb-8 lg:mb-12">
            <div class="w-full px-4 sm:px-0">
                <div class="flex items-start justify-center gap-2 sm:gap-4">

                    {{-- Step 1 --}}
                    <div class="flex min-w-0 flex-1 flex-col items-center text-center">
                        <div
                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#10A36A] text-white shadow-sm
                                   sm:h-11 sm:w-11">
                            <svg class="h-4 w-4 sm:h-5 sm:w-5" viewBox="0 0 24 24" fill="none">
                                <path d="M20 7L10 17l-5-5"
                                    stroke="currentColor"
                                    stroke-width="2.4"
                                    stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </div>

                        <span
                            class="mt-2 block text-[11px] font-bold leading-tight text-[#13885C]
                                   sm:text-[15px] sm:leading-snug">
                            ประเมินราคา
                        </span>
                    </div>

                    {{-- Line 1 --}}
                    <div class="mt-4 h-px w-8 shrink-0 bg-[#D7E3DD] sm:mt-5 sm:w-24"></div>

                    {{-- Step 2 --}}
                    <div class="flex min-w-0 flex-1 flex-col items-center text-center">
                        <div
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#10A36A] text-white text-base font-bold shadow-md
                                   sm:h-12 sm:w-12 sm:text-xl">
                            <svg class="h-4 w-4 sm:h-5 sm:w-5" viewBox="0 0 24 24" fill="none">
                                <path d="M20 7L10 17l-5-5"
                                    stroke="currentColor"
                                    stroke-width="2.4"
                                    stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </div>

                        <span
                            class="mt-2 block text-[11px] font-bold leading-tight text-[#13885C]
                                   sm:text-[15px] sm:leading-snug">
                            เลือกข้อมูลเครื่อง
                        </span>
                    </div>

                    {{-- Line 2 --}}
                    <div class="mt-4 h-px w-8 shrink-0 bg-[#D7E3DD] sm:mt-5 sm:w-24"></div>

                    {{-- Step 3 --}}
                    <div class="flex min-w-0 flex-1 flex-col items-center text-center">
                        <div
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#10A36A] text-white text-base font-bold shadow-md
                                   sm:h-12 sm:w-12 sm:text-xl">
                            3
                        </div>

                        <span
                            class="mt-2 block text-[11px] font-bold leading-tight text-[#13885C]
                                   sm:text-[15px] sm:leading-snug">
                            ยืนยัน
                        </span>
                    </div>

                </div>
            </div>
        </div>

        <div id="checkoutMainGrid" class="grid grid-cols-1 xl:grid-cols-12 gap-6 xl:gap-8 items-start overflow-visible">
            <div class="xl:col-span-8">
                <div class="mb-7 lg:mb-8">
                    <div class="text-[#16A36C] text-[20px] mb-3">
                        ขั้นตอนสุดท้าย
                    </div>

                    <h2 class="font-medium mb-6 text-[26px] md:text-[26px] lg:text-[38px]">
                        เลือกวิธีขายที่สะดวก
                        <br class="hidden sm:block">
                        และยืนยันข้อมูลของคุณ
                    </h2>

                    <p class="mt-5 text-[#7A8793] text-[17px] sm:text-[18px] leading-8 max-w-[760px]">
                        เลือกช่องทางรับซื้อที่เหมาะกับคุณ พร้อมกรอกข้อมูลติดต่อเพื่อให้ทีมงานดำเนินการต่อได้อย่างรวดเร็ว
                    </p>
                </div>

                <div
                    class="rounded-[20px] border border-[#E6ECE8] bg-white shadow-[0_20px_60px_rgba(15,23,42,0.06)] overflow-hidden">
                    <form method="POST" action="{{ route('sell.product.orders.store') }}" id="pickupTabsForm">
                        @csrf

                        <input type="hidden" name="pickup_panel" id="pickupPanelInput"
                            value="{{ $defaultPickupPanel }}">

                        <input type="hidden" name="mobile_product_category_id"
                            value="{{ $mobileProductCategoryId ?? '' }}">
                        <input type="hidden" name="mobile_brand_id" value="{{ $mobileBrandId ?? '' }}">
                        <input type="hidden" name="mobile_model_id" value="{{ $mobileModelId ?? '' }}">
                        <input type="hidden" name="mobile_model_price_id" value="{{ $mobileModelPriceId ?? '' }}">

                        <input type="hidden" name="category" value="{{ $category ?? '' }}">
                        <input type="hidden" name="brand" value="{{ $brand ?? '' }}">
                        <input type="hidden" name="model" value="{{ $model ?? '' }}">
                        <input type="hidden" name="storage" value="{{ $storage ?? '' }}">

                        <input type="hidden" name="base_price" value="{{ $basePrice }}">
                        <input type="hidden" name="deduct_total" value="{{ $deductTotal }}">
                        <input type="hidden" name="price_after_deduct" id="priceAfterDeductHidden"
                            value="{{ $priceAfterDeduct }}">
                        <input type="hidden" name="bonus_code_id" id="bonusCodeIdHidden"
                            value="{{ $selectedBonusCodeId }}">
                        <input type="hidden" name="bonus_amount" id="bonusAmountHidden" value="{{ $bonusAmount }}">
                        <input type="hidden" name="estimated_price" id="estimatedPriceHidden"
                            value="{{ $estimatedPrice }}">

                        @if (!empty($selectedValues['answers']))
                        @foreach ($selectedValues['answers'] as $answer)
                        @if (!empty($answer['option_id']))
                        <input type="hidden" name="selected_option_ids[]"
                            value="{{ $answer['option_id'] }}">
                        @endif
                        @endforeach
                        @endif

                        <div class="px-5 sm:px-7 lg:px-9 py-6 border-b border-[#EEF2EF] bg-[#FCFDFC]">
                            <div class="flex items-start sm:items-center justify-between gap-4 flex-col sm:flex-row">
                                <div>
                                    <p class="text-[#8A97A2] text-[13px] font-medium mb-1">
                                        รายละเอียดการขาย
                                    </p>
                                    <h2 class="text-[#111827] text-[22px] sm:text-[28px] font-bold leading-tight">
                                        เลือกสถานที่รับซื้อ
                                    </h2>
                                </div>

                                <div
                                    class="inline-flex items-center rounded-full bg-[#F3F6F4] border border-[#E3EBE6] text-[#5C6B65] text-[13px] font-semibold px-4 h-10">
                                    กรอกข้อมูลให้ครบก่อนยืนยัน
                                </div>
                            </div>
                        </div>

                        <div class="px-5 sm:px-7 lg:px-9 py-6">
                            <div class="mb-8">
                                <div class="text-[#111827] text-[18px] font-bold mb-4">
                                    วิธีรับซื้อ
                                </div>

                                @if (!empty($pickupMethods) && count($pickupMethods) > 0)
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5" id="pickupTabs">
                                    @foreach ($pickupMethods as $method)
                                    @php
                                    $methodKey = $method['key'] ?? '';
                                    $panelKey = $method['panel_key'] ?? 'store';
                                    $isRecommended = $panelKey === 'store';
                                    $isChecked =
                                    $defaultPickupMethod === $methodKey ||
                                    $defaultPickupPanel === $panelKey;
                                    @endphp

                                    <label class="relative block cursor-pointer group">
                                        <input type="radio" name="pickup_method"
                                            value="{{ $methodKey }}" data-panel-key="{{ $panelKey }}"
                                            class="peer sr-only pickup-tab-input"
                                            {{ $isChecked ? 'checked' : '' }}>

                                        <span
                                            class="sell-method-card relative block min-h-[156px] rounded-[24px] border border-[#E3EBE6] bg-white px-6 py-6 transition-all duration-200
                                                        hover:border-[#10A36A] hover:shadow-[0_16px_35px_rgba(15,23,42,0.08)]
                                                        peer-checked:border-[#10A36A]
                                                        peer-checked:shadow-[inset_0_0_0_1px_#10A36A,0_18px_40px_rgba(16,163,106,0.12)]
                                                        peer-checked:bg-[#FCFFFD]">

                                            @if ($isRecommended)
                                            <span
                                                class="absolute -top-3 left-6 md:left-1/2 md:-translate-x-1/2 bg-[#00A36C] text-white text-[10px] font-black uppercase px-3 py-1 rounded-full tracking-widest">
                                                แนะนำ
                                            </span>
                                            @endif

                                            <span class="flex items-center gap-4 md:block">
                                                <span
                                                    class="w-11 h-11 md:w-12 md:h-12 rounded-xl flex items-center justify-center shrink-0 md:mb-6 bg-[#EEF4F8] text-[#8AA0B3] transition-all duration-200 group-hover:bg-[#EAF7F1] group-hover:text-[#10A36A]">
                                                    @if ($panelKey === 'store')
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="28"
                                                        height="28" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <circle cx="18.5" cy="17.5" r="3.5">
                                                        </circle>
                                                        <circle cx="5.5" cy="17.5" r="3.5">
                                                        </circle>
                                                        <circle cx="15" cy="5" r="1">
                                                        </circle>
                                                        <path d="M12 17.5V14l-3-3 4-3 2 3h2"></path>
                                                    </svg>
                                                    @elseif ($panelKey === 'bts_mrt')
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="27"
                                                        height="27" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M4 6h16"></path>
                                                        <path d="M6 6v9a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V6">
                                                        </path>
                                                        <path d="M8 18l-2 3"></path>
                                                        <path d="M16 18l2 3"></path>
                                                        <path d="M9 10h6"></path>
                                                        <path d="M9 14h6"></path>
                                                    </svg>
                                                    @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="27"
                                                        height="27" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="m21 16-9 5-9-5V8l9-5 9 5v8Z"></path>
                                                        <path d="m3.3 7.3 8.7 5 8.7-5"></path>
                                                        <path d="M12 22V12"></path>
                                                    </svg>
                                                    @endif
                                                </span>

                                                <span class="flex-1 min-w-0">
                                                    <span
                                                        class="block text-[17px] md:text-[21px] font-extrabold text-[#111827] leading-tight">
                                                        {{ $method['label'] ?? '-' }}
                                                    </span>

                                                    @if (!empty($method['description']))
                                                    <span
                                                        class="mt-2 block text-[13px] text-[#7A8793] leading-6">
                                                        {{ $method['description'] }}
                                                    </span>
                                                    @endif

                                                    <span
                                                        class="mt-3 hidden items-center text-[#10A36A] peer-checked:flex">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18"
                                                            height="18" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2.3"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <circle cx="12" cy="12" r="10">
                                                            </circle>
                                                            <path d="m9 12 2 2 4-4"></path>
                                                        </svg>
                                                    </span>
                                                </span>
                                            </span>
                                        </span>
                                    </label>
                                    @endforeach
                                </div>
                                @else
                                <div
                                    class="rounded-[18px] border border-[#E7EEEA] bg-[#F9FBFA] p-5 text-[#7A8793]">
                                    ยังไม่ได้ตั้งค่าวิธีรับซื้อ
                                </div>
                                @endif
                            </div>

                            {{-- รับซื้อถึงที่ --}}
                            <div class="pickup-panel space-y-7 md:space-y-8 {{ $defaultPickupPanel === 'store' ? '' : 'hidden' }}"
                                data-panel="store">
                                <div class="rounded-[18px] border border-[#E7EEEA] bg-[#FCFDFC] p-5 sm:p-6">
                                    <div class="flex items-start gap-4 mb-6">
                                        <div
                                            class="w-12 h-12 rounded-full bg-[#EAF7F1] text-[#10A36A] flex items-center justify-center shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path
                                                    d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M4 21C4 17.6863 7.58172 15 12 15C16.4183 15 20 17.6863 20 21"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3
                                                class="text-[20px] sm:text-[22px] font-bold text-[#111827] leading-tight">
                                                ข้อมูลผู้ขาย
                                            </h3>
                                            <p class="text-[14px] text-[#8A97A2] mt-1">
                                                กรอกข้อมูลเพื่อติดต่อกลับและนัดรับสินค้า
                                            </p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-6 mb-5">
                                        <div>
                                            <label class="block text-[15px] font-semibold text-[#111827] mb-3">
                                                ชื่อ-นามสกุล <span class="text-[#E05454]">*</span>
                                            </label>
                                            <input type="text" name="fullname_store"
                                                value="{{ old('fullname_store') }}" placeholder="ชื่อ-นามสกุล"
                                                required
                                                class="w-full h-[56px] rounded-[16px] border border-[#DCE6E0] bg-white px-4 text-[15px] text-[#111827] placeholder:text-[#9AA7B2] focus:outline-none focus:ring-2 focus:ring-[#10A36A]/20 focus:border-[#10A36A] transition">
                                        </div>

                                        <div>
                                            <label class="block text-[15px] font-semibold text-[#111827] mb-3">
                                                หมายเลขโทรศัพท์ <span class="text-[#E05454]">*</span>
                                            </label>
                                            <input type="text" name="phone_store"
                                                value="{{ old('phone_store') }}" placeholder="0812345678" required
                                                class="w-full h-[56px] rounded-[16px] border border-[#DCE6E0] bg-white px-4 text-[15px] text-[#111827] placeholder:text-[#9AA7B2] focus:outline-none focus:ring-2 focus:ring-[#10A36A]/20 focus:border-[#10A36A] transition">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-6">
                                        <div>
                                            <label class="block text-[15px] font-semibold text-[#111827] mb-3">
                                                Line ID
                                            </label>
                                            <input type="text" name="line_id_store"
                                                value="{{ old('line_id_store') }}" placeholder="Line ID"
                                                class="w-full h-[56px] rounded-[16px] border border-[#DCE6E0] bg-white px-4 text-[15px] text-[#111827] placeholder:text-[#9AA7B2] focus:outline-none focus:ring-2 focus:ring-[#10A36A]/20 focus:border-[#10A36A] transition">
                                        </div>

                                        <div>
                                            <label class="block text-[15px] font-semibold text-[#111827] mb-3">
                                                อีเมล
                                            </label>
                                            <input type="email" name="email_store"
                                                value="{{ old('email_store') }}" placeholder="example@email.com"
                                                class="w-full h-[56px] rounded-[16px] border border-[#DCE6E0] bg-white px-4 text-[15px] text-[#111827] placeholder:text-[#9AA7B2] focus:outline-none focus:ring-2 focus:ring-[#10A36A]/20 focus:border-[#10A36A] transition">
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded-[18px] border border-[#E7EEEA] bg-[#FCFDFC] p-5 sm:p-6">
                                    <div class="flex items-start gap-4 mb-6">
                                        <div
                                            class="w-12 h-12 rounded-full bg-[#EAF7F1] text-[#10A36A] flex items-center justify-center shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path d="M12 21s7-4.5 7-11a7 7 0 1 0-14 0c0 6.5 7 11 7 11Z"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <circle cx="12" cy="10" r="2.5" stroke="currentColor"
                                                    stroke-width="2" />
                                            </svg>
                                        </div>

                                        <div>
                                            <h3
                                                class="text-[20px] sm:text-[22px] font-bold text-[#111827] leading-tight">
                                                พื้นที่รับซื้อถึงที่
                                            </h3>
                                            <p class="text-[14px] text-[#8A97A2] mt-1">
                                                เลือกเฉพาะจังหวัดและเขต/อำเภอที่เปิดให้บริการรับซื้อถึงที่
                                            </p>
                                        </div>
                                    </div>

                                    <div class="mb-5">
                                        <label class="block text-[15px] font-semibold text-[#111827] mb-2">
                                            ที่อยู่ผู้ขาย <span class="text-[#E05454]">*</span>
                                            <span class="text-[#E05454] font-medium ml-1 text-[13px]">
                                                ต้องการขายสินค้ามือ 1 หรือสินค้าจำนวนมาก กรุณาติดต่อ 098-950-9222
                                            </span>
                                        </label>
                                        <textarea name="address_store" rows="4" placeholder="บ้านเลขที่ อาคาร หมู่บ้าน ถนน หรือรายละเอียดจุดนัดรับ"
                                            class="w-full rounded-[16px] border border-[#DCE6E0] bg-white px-4 py-4 text-[15px] text-[#111827] placeholder:text-[#9AA7B2] focus:outline-none focus:ring-2 focus:ring-[#10A36A]/20 focus:border-[#10A36A] transition resize-none">{{ old('address_store') }}</textarea>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-6">
                                        <div>
                                            <label class="block text-[15px] font-semibold text-[#111827] mb-3">
                                                จังหวัดที่ให้บริการ <span class="text-[#E05454]">*</span>
                                            </label>
                                            <select name="province_store" id="provinceStoreSelect"
                                                class="w-full h-[56px] rounded-[16px] border border-[#DCE6E0] bg-white px-4 text-[15px] text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#10A36A]/20 focus:border-[#10A36A] transition">
                                                <option value="">เลือกจังหวัด</option>

                                                @foreach ($serviceProvinces ?? [] as $province)
                                                <option value="{{ $province }}"
                                                    {{ old('province_store') === $province ? 'selected' : '' }}>
                                                    {{ $province }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-[15px] font-semibold text-[#111827] mb-3">
                                                เขต / อำเภอที่ให้บริการ <span class="text-[#E05454]">*</span>
                                            </label>
                                            <select name="district_store" id="districtStoreSelect"
                                                data-old-value="{{ old('district_store') }}"
                                                class="w-full h-[56px] rounded-[16px] border border-[#DCE6E0] bg-white px-4 text-[15px] text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#10A36A]/20 focus:border-[#10A36A] transition">
                                                <option value="">เลือกเขต / อำเภอ</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div id="serviceAreaNotice"
                                        class="hidden mt-4 rounded-[14px] border border-[#F7D7A8] bg-[#FFF8ED] px-4 py-3 text-[14px] leading-7 text-[#9A5B00]">
                                        ไม่พบพื้นที่ให้บริการสำหรับจังหวัดที่เลือก
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-6">
                                    <div>
                                        <label class="block text-[15px] font-semibold text-[#111827] mb-3">
                                            วันนัดหมาย
                                        </label>
                                        <input type="date" name="pickup_date_store" id="pickupDateStoreInput"
                                            min="{{ $minPickupDate }}" max="{{ $maxPickupDate }}"
                                            value="{{ old('pickup_date_store') }}"
                                            class="w-full h-[56px] rounded-[16px] border border-[#DCE6E0] bg-[#FCFDFC] px-4 text-[15px] text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#10A36A]/20 focus:border-[#10A36A] transition">
                                        <p class="mt-2 text-[12px] text-[#8A97A2]">
                                            เลือกได้ตั้งแต่ {{ $minPickupDate }} ถึง {{ $maxPickupDate }}
                                        </p>
                                    </div>

                                    <div>
                                        <label class="block text-[15px] font-semibold text-[#111827] mb-3">
                                            ช่วงเวลา
                                        </label>
                                        <select name="pickup_time_store" id="pickupTimeStoreSelect"
                                            class="w-full h-[56px] rounded-[16px] border border-[#DCE6E0] bg-[#FCFDFC] px-4 text-[15px] text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#10A36A]/20 focus:border-[#10A36A] transition">
                                            @foreach ($times as $time)
                                            <option value="{{ $time }}"
                                                {{ old('pickup_time_store') === $time ? 'selected' : '' }}>
                                                {{ $time }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- BTS/MRT --}}
                            <div class="pickup-panel space-y-7 md:space-y-8 {{ $defaultPickupPanel === 'bts_mrt' ? '' : 'hidden' }}"
                                data-panel="bts_mrt">
                                <div class="rounded-[18px] border border-[#E7EEEA] bg-[#FCFDFC] p-5 sm:p-6">
                                    <div class="flex items-start gap-4 mb-6">
                                        <div
                                            class="w-12 h-12 rounded-full bg-[#EAF7F1] text-[#10A36A] flex items-center justify-center shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path
                                                    d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M4 21C4 17.6863 7.58172 15 12 15C16.4183 15 20 17.6863 20 21"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3
                                                class="text-[20px] sm:text-[22px] font-bold text-[#111827] leading-tight">
                                                ข้อมูลผู้ขาย
                                            </h3>
                                            <p class="text-[14px] text-[#8A97A2] mt-1">
                                                กรอกข้อมูลเพื่อติดต่อกลับและนัดหมาย
                                            </p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-6 mb-5">
                                        <div>
                                            <label class="block text-[15px] font-semibold text-[#111827] mb-3">
                                                ชื่อ-นามสกุล <span class="text-[#E05454]">*</span>
                                            </label>
                                            <input type="text" name="fullname_bts"
                                                value="{{ old('fullname_bts') }}" placeholder="ชื่อ-นามสกุล" required
                                                class="w-full h-[56px] rounded-[16px] border border-[#DCE6E0] bg-white px-4 text-[15px] text-[#111827] placeholder:text-[#9AA7B2] focus:outline-none focus:ring-2 focus:ring-[#10A36A]/20 focus:border-[#10A36A] transition">
                                        </div>

                                        <div>
                                            <label class="block text-[15px] font-semibold text-[#111827] mb-3">
                                                หมายเลขโทรศัพท์ <span class="text-[#E05454]">*</span>
                                            </label>
                                            <input type="text" name="phone_bts" value="{{ old('phone_bts') }}"
                                                placeholder="0812345678" required
                                                class="w-full h-[56px] rounded-[16px] border border-[#DCE6E0] bg-white px-4 text-[15px] text-[#111827] placeholder:text-[#9AA7B2] focus:outline-none focus:ring-2 focus:ring-[#10A36A]/20 focus:border-[#10A36A] transition">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-6">
                                        <div>
                                            <label class="block text-[15px] font-semibold text-[#111827] mb-3">
                                                Line ID
                                            </label>
                                            <input type="text" name="line_id_bts"
                                                value="{{ old('line_id_bts') }}" placeholder="Line ID"
                                                class="w-full h-[56px] rounded-[16px] border border-[#DCE6E0] bg-white px-4 text-[15px] text-[#111827] placeholder:text-[#9AA7B2] focus:outline-none focus:ring-2 focus:ring-[#10A36A]/20 focus:border-[#10A36A] transition">
                                        </div>

                                        <div>
                                            <label class="block text-[15px] font-semibold text-[#111827] mb-3">
                                                อีเมล
                                            </label>
                                            <input type="email" name="email_bts" value="{{ old('email_bts') }}"
                                                placeholder="example@email.com"
                                                class="w-full h-[56px] rounded-[16px] border border-[#DCE6E0] bg-white px-4 text-[15px] text-[#111827] placeholder:text-[#9AA7B2] focus:outline-none focus:ring-2 focus:ring-[#10A36A]/20 focus:border-[#10A36A] transition">
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-6">
                                    <div>
                                        <label class="block text-[15px] font-semibold text-[#111827] mb-3">
                                            สายรถไฟ BTS/MRT
                                        </label>
                                        <select name="transit_line" id="transitLineSelect"
                                            class="w-full h-[56px] rounded-[16px] border border-[#DCE6E0] bg-[#FCFDFC] px-4 text-[15px] text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#10A36A]/20 focus:border-[#10A36A] transition">
                                            <option value="">เลือกสายรถไฟ</option>
                                            @foreach ($transitStationsByLine as $line)
                                            @php
                                            $lineName =
                                            is_array($line['line_name'] ?? null) ||
                                            is_object($line['line_name'] ?? null)
                                            ? 'ไม่ระบุสายรถไฟฟ้า'
                                            : $line['line_name'] ?? 'ไม่ระบุสายรถไฟฟ้า';
                                            @endphp

                                            <option value="{{ $lineName }}"
                                                {{ old('transit_line') === $lineName ? 'selected' : '' }}>
                                                {{ $lineName }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-[15px] font-semibold text-[#111827] mb-3">
                                            สถานี
                                        </label>
                                        <select name="transit_station_id" id="transitStationSelect"
                                            data-old-value="{{ old('transit_station_id') }}"
                                            class="w-full h-[56px] rounded-[16px] border border-[#DCE6E0] bg-[#FCFDFC] px-4 text-[15px] text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#10A36A]/20 focus:border-[#10A36A] transition">
                                            <option value="">ระบุสถานี</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-6">
                                    <div>
                                        <label class="block text-[15px] font-semibold text-[#111827] mb-3">
                                            วันนัดหมาย
                                        </label>
                                        <input type="date" name="pickup_date_bts" id="pickupDateBtsInput"
                                            min="{{ $minPickupDate }}" max="{{ $maxPickupDate }}"
                                            value="{{ old('pickup_date_bts') }}"
                                            class="w-full h-[56px] rounded-[16px] border border-[#DCE6E0] bg-[#FCFDFC] px-4 text-[15px] text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#10A36A]/20 focus:border-[#10A36A] transition">
                                        <p class="mt-2 text-[12px] text-[#8A97A2]">
                                            เลือกได้ตั้งแต่ {{ $minPickupDate }} ถึง {{ $maxPickupDate }}
                                        </p>
                                    </div>

                                    <div>
                                        <label class="block text-[15px] font-semibold text-[#111827] mb-3">
                                            ช่วงเวลา
                                        </label>
                                        <select name="pickup_time_bts" id="pickupTimeBtsSelect"
                                            class="w-full h-[56px] rounded-[16px] border border-[#DCE6E0] bg-[#FCFDFC] px-4 text-[15px] text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#10A36A]/20 focus:border-[#10A36A] transition">
                                            @foreach ($times as $time)
                                            <option value="{{ $time }}"
                                                {{ old('pickup_time_bts') === $time ? 'selected' : '' }}>
                                                {{ $time }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- EMS --}}
                            <div class="pickup-panel space-y-7 md:space-y-8 {{ $defaultPickupPanel === 'ems' ? '' : 'hidden' }}"
                                data-panel="ems">
                                <div class="rounded-[18px] border border-[#E7EEEA] bg-[#FCFDFC] p-5 sm:p-6">
                                    <div class="flex items-start gap-4 mb-6">
                                        <div
                                            class="w-12 h-12 rounded-full bg-[#EAF7F1] text-[#10A36A] flex items-center justify-center shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path
                                                    d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M4 21C4 17.6863 7.58172 15 12 15C16.4183 15 20 17.6863 20 21"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3
                                                class="text-[20px] sm:text-[22px] font-bold text-[#111827] leading-tight">
                                                ข้อมูลผู้ขาย
                                            </h3>
                                            <p class="text-[14px] text-[#8A97A2] mt-1">
                                                กรอกข้อมูลผู้ส่งสินค้าให้ครบถ้วน
                                            </p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-6 mb-5">
                                        <div>
                                            <label class="block text-[15px] font-semibold text-[#111827] mb-3">
                                                ชื่อ-นามสกุล <span class="text-[#E05454]">*</span>
                                            </label>
                                            <input type="text" name="fullname_ems"
                                                value="{{ old('fullname_ems') }}" placeholder="ชื่อ-นามสกุล" required
                                                class="w-full h-[56px] rounded-[16px] border border-[#DCE6E0] bg-white px-4 text-[15px] text-[#111827] placeholder:text-[#9AA7B2] focus:outline-none focus:ring-2 focus:ring-[#10A36A]/20 focus:border-[#10A36A] transition">
                                        </div>

                                        <div>
                                            <label class="block text-[15px] font-semibold text-[#111827] mb-3">
                                                หมายเลขโทรศัพท์ <span class="text-[#E05454]">*</span>
                                            </label>
                                            <input type="text" name="phone_ems" value="{{ old('phone_ems') }}"
                                                placeholder="0812345678" required
                                                class="w-full h-[56px] rounded-[16px] border border-[#DCE6E0] bg-white px-4 text-[15px] text-[#111827] placeholder:text-[#9AA7B2] focus:outline-none focus:ring-2 focus:ring-[#10A36A]/20 focus:border-[#10A36A] transition">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-6">
                                        <div>
                                            <label class="block text-[15px] font-semibold text-[#111827] mb-3">
                                                Line ID
                                            </label>
                                            <input type="text" name="line_id_ems"
                                                value="{{ old('line_id_ems') }}" placeholder="Line ID"
                                                class="w-full h-[56px] rounded-[16px] border border-[#DCE6E0] bg-white px-4 text-[15px] text-[#111827] placeholder:text-[#9AA7B2] focus:outline-none focus:ring-2 focus:ring-[#10A36A]/20 focus:border-[#10A36A] transition">
                                        </div>

                                        <div>
                                            <label class="block text-[15px] font-semibold text-[#111827] mb-3">
                                                อีเมล
                                            </label>
                                            <input type="email" name="email_ems" value="{{ old('email_ems') }}"
                                                placeholder="อีเมล"
                                                class="w-full h-[56px] rounded-[16px] border border-[#DCE6E0] bg-white px-4 text-[15px] text-[#111827] placeholder:text-[#9AA7B2] focus:outline-none focus:ring-2 focus:ring-[#10A36A]/20 focus:border-[#10A36A] transition">
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded-[18px] border border-[#E7EEEA] bg-[#F9FBFA] p-5">
                                    <div class="text-[15px] leading-8 text-[#111827]">
                                        <p class="font-semibold">ส่งสินค้ามาที่</p>

                                        @forelse ($parcelSettings as $parcelSetting)
                                        @php
                                        $parcelName =
                                        $parcelSetting->name ??
                                        ($parcelSetting->title ??
                                        ($parcelSetting->branch_name ?? 'สำนักงานใหญ่'));
                                        $parcelAddress =
                                        $parcelSetting->address ??
                                        ($parcelSetting->full_address ?? '-');
                                        $parcelPhone =
                                        $parcelSetting->phone ??
                                        ($parcelSetting->tel ??
                                        ($parcelSetting->contact_phone ?? null));
                                        @endphp

                                        <div class="mb-4">
                                            {{-- <p>{{ $parcelName }}</p> --}}
                                            <p>{{ $parcelAddress }}</p>

                                            {{-- @if (!empty($parcelPhone))
                                                        <p>{{ $parcelPhone }}</p>
                                            @endif

                                            @if (!empty($parcelSetting->description))
                                            <p class="text-[#66727D]">{{ $parcelSetting->description }}</p>
                                            @endif --}}
                                        </div>
                                        @empty
                                        <p>ยังไม่ได้ตั้งค่าศูนย์รับสินค้า</p>
                                        @endforelse
                                    </div>
                                </div>

                                <div
                                    class="rounded-[18px] border border-[#E7EEEA] bg-[#F9FBFA] p-5 text-[15px] leading-8 text-[#111827]">
                                    <p class="font-semibold">เอกสารที่ส่งมาพร้อมเครื่อง</p>

                                    @forelse ($requiredDocuments as $document)
                                    @php
                                    $documentName =
                                    $document->name ??
                                    ($document->title ?? ($document->document_name ?? '-'));
                                    @endphp

                                    <p>- {{ $documentName }}</p>

                                    @if (!empty($document->description))
                                    <p class="text-[#66727D] pl-4">{{ $document->description }}</p>
                                    @endif
                                    @empty
                                    <p>- ยังไม่ได้ตั้งค่าเอกสารที่ต้องใช้</p>
                                    @endforelse
                                </div>

                                @foreach ($parcelSettings as $parcelSetting)
                                @if (!empty($parcelSetting->remark) || !empty($parcelSetting->note))
                                <div
                                    class="rounded-[18px] border border-[#E7EEEA] bg-[#F9FBFA] p-5 text-[15px] leading-8 text-[#111827]">
                                    {{ $parcelSetting->remark ?? $parcelSetting->note }}
                                </div>
                                @endif
                                @endforeach

                                <div class="text-[15px] leading-8 text-[#111827] pb-2 border-b border-[#E7EEEA]">
                                    หากสินค้าถึงบริษัท จะติดต่อกลับลูกค้าโดยเร็วที่สุด
                                </div>
                            </div>

                            {{-- โค้ดบวกราคา / ส่วนลด --}}
                            <div class="mt-8 rounded-[20px] border border-[#E5ECE8] bg-[#F9FBFA] p-5 sm:p-6">
                                <div class="flex items-start gap-4 mb-5">
                                    <div
                                        class="w-12 h-12 rounded-full bg-[#EAF7F1] text-[#10A36A] flex items-center justify-center shrink-0">
                                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none">
                                            <path d="M7 8.5V7.8C7 6.81 7.81 6 8.8 6h6.4C16.19 6 17 6.81 17 7.8v.7"
                                                stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                                            <path
                                                d="M5.5 10.2C5.5 9.54 6.04 9 6.7 9h10.6c.66 0 1.2.54 1.2 1.2v7.1c0 .66-.54 1.2-1.2 1.2H6.7c-.66 0-1.2-.54-1.2-1.2v-7.1Z"
                                                stroke="currentColor" stroke-width="1.8" />
                                            <path d="M9 12.5h6" stroke="currentColor" stroke-width="1.8"
                                                stroke-linecap="round" />
                                            <path d="M12 9v7" stroke="currentColor" stroke-width="1.8"
                                                stroke-linecap="round" />
                                        </svg>
                                    </div>

                                    <div>
                                        <h3 class="text-[20px] sm:text-[22px] font-bold text-[#111827] leading-tight">
                                            โค้ดบวกราคา
                                        </h3>
                                        <p class="text-[14px] text-[#8A97A2] mt-1">
                                            เลือกโค้ดเพื่อบวกราคาเพิ่มจากราคาประเมินหลังหักตามสภาพ
                                        </p>
                                    </div>
                                </div>

                                @if ($canUseBonusCode)
                                @if (!empty($bonusCodes) && count($bonusCodes) > 0)
                                <select id="bonusCodeSelect"
                                    class="w-full h-[56px] rounded-[16px] border border-[#DCE6E0] bg-white px-4 text-[15px] text-[#111827] focus:outline-none focus:ring-2 focus:ring-[#10A36A]/20 focus:border-[#10A36A] transition">
                                    <option value="">ไม่ใช้โค้ดบวกราคา</option>

                                    @foreach ($bonusCodes as $bonusCodeItem)
                                    <option value="{{ $bonusCodeItem['id'] }}"
                                        {{ (string) $selectedBonusCodeId === (string) $bonusCodeItem['id'] ? 'selected' : '' }}>
                                        {{ $bonusCodeItem['code'] }} - {{ $bonusCodeItem['name'] }}
                                    </option>
                                    @endforeach
                                </select>

                                <p id="bonusCodeDescriptionText"
                                    class="mt-3 text-[13px] leading-6 text-[#7A8793]">
                                    เลือกโค้ดเพื่อบวกราคาเพิ่มจากราคาประเมินหลังหักตามสภาพ
                                </p>

                                <div id="bonusInlineResultBox"
                                    class="hidden mt-4 rounded-[16px] border border-[#D7F0E4] bg-white px-4 py-4">
                                    <div class="flex items-center justify-between gap-4 text-[15px] mb-2">
                                        <div class="text-[#66727D]">ราคาหลังหักตามสภาพ</div>
                                        <div class="font-bold text-[#111827]">
                                            ฿{{ number_format($priceAfterDeduct, 0) }}
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between gap-4 text-[15px] mb-2">
                                        <div class="text-[#66727D]">บวกจากโค้ด</div>
                                        <div id="bonusInlineAmountText" class="font-bold text-[#10A36A]">
                                            +฿0
                                        </div>
                                    </div>

                                    <div class="border-t border-[#E9EFEB] my-3"></div>

                                    <div class="flex items-center justify-between gap-4">
                                        <div class="text-[#111827] font-bold">ราคาประเมินสุทธิ</div>
                                        <div id="bonusInlineFinalPriceText"
                                            class="text-[#10A36A] text-[22px] font-extrabold">
                                            ฿{{ number_format($estimatedPrice, 0) }}
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div
                                    class="rounded-[16px] bg-white border border-[#E5ECE8] px-4 py-4 text-[14px] leading-7 text-[#7A8793]">
                                    ยังไม่มีโค้ดบวกราคาที่ใช้ได้สำหรับบัญชีนี้
                                </div>
                                @endif
                                @else
                                <div
                                    class="rounded-[16px] bg-white border border-[#E5ECE8] px-4 py-4 text-[14px] leading-7 text-[#7A8793]">
                                    โค้ดบวกราคาใช้ได้เฉพาะลูกค้าที่เข้าสู่ระบบเท่านั้น
                                </div>
                                @endif
                            </div>

                            <div class="pt-5 border-t border-[#EEF2EF] mt-8">
                                <div class="flex justify-center">
                                    <label
                                        class="inline-flex items-start gap-3 cursor-pointer max-w-[720px] rounded-[18px] border border-[#E5ECE8] bg-[#FCFDFC] px-4 sm:px-5 py-4">
                                        <input type="checkbox" name="accept_terms" id="acceptTermsCheckbox"
                                            value="1"
                                            class="mt-1 h-4 w-4 rounded border-[#C9D7D0] text-[#10A36A] focus:ring-[#10A36A]">
                                        <span class="text-[15px] leading-7 text-[#111827]">
                                            ฉันยอมรับข้อตกลงและเงื่อนไข
                                            <a href="javascript:void(0)"
                                                class="text-[#10A36A] font-semibold underline underline-offset-2">
                                                การรับซื้อสินค้าของทาง Cashkub
                                            </a>
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <div class="pt-5 text-center">
                                <button type="button" id="confirmSellOrderBtn" disabled
                                    class="inline-flex items-center justify-center w-full sm:w-[320px] h-[58px] rounded-full bg-[#10A36A] hover:bg-[#0E8C5B] text-white text-[18px] font-bold shadow-[0_12px_30px_rgba(16,163,106,0.22)] transition duration-200 disabled:bg-[#A7B7AF] disabled:hover:bg-[#A7B7AF] disabled:cursor-not-allowed disabled:shadow-none disabled:opacity-70">
                                    ยืนยันการขาย
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <aside class="xl:col-span-4 xl:self-start xl:sticky xl:top-[96px]">
                <div id="checkoutSummarySticky" class="summary-follow-box space-y-4">
                    <div
                        class="rounded-[20px] border border-[#E6ECE8] bg-white shadow-[0_20px_60px_rgba(15,23,42,0.06)] overflow-hidden">
                        <div class="px-5 sm:px-6 pt-6 pb-4">
                            <h3 class="text-[#16A36C] text-[24px] font-extrabold tracking-tight text-center">
                                สรุปรายการ
                            </h3>
                        </div>

                        <div class="px-5 sm:px-6 pb-6">
                            <div class="rounded-[20px] border border-[#E5ECE8] bg-[#FCFDFC] p-4 sm:p-5">
                                <div class="text-[#111827] text-[16px] sm:text-[17px] mb-4">
                                    อุปกรณ์ที่คุณจะขาย (1)
                                </div>

                                <div class="flex items-start gap-4">
                                    <div
                                        class="w-[106px] h-[106px] rounded-[22px] bg-[#F4F7F5] border border-[#E1E8E4] flex items-center justify-center overflow-hidden shrink-0">
                                        <img src="{{ asset($productImage) }}" alt="{{ $summaryTitle }}"
                                            class="w-[84px] h-[84px] object-contain">
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <h4
                                            class="text-[#0F172A] font-extrabold text-[18px] sm:text-[20px] leading-tight">
                                            {{ $summaryTitle }}
                                        </h4>

                                        <div class="mt-3 text-[#95A1AB] text-[13px] font-medium">
                                            ราคาประเมินเบื้องต้น
                                        </div>

                                        <div
                                            class="mt-2 inline-flex items-center gap-2 text-[#D08A00] text-[12px] font-semibold">
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none">
                                                <circle cx="12" cy="12" r="8.5" stroke="currentColor"
                                                    stroke-width="1.8" />
                                                <path d="M12 8v4l2.5 1.5" stroke="currentColor" stroke-width="1.8"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            ราคานี้มีผลเบื้องต้นจากข้อมูลที่เลือก
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5 rounded-[24px] border border-[#E5ECE8] bg-white overflow-hidden">
                                <div
                                    class="px-5 py-4 border-b border-[#EEF2EF] flex items-center justify-between gap-4">
                                    <div class="text-[#66727D] text-[15px] font-medium">
                                        ราคาประเมินรวม (Subtotal)
                                    </div>
                                    <div id="checkoutSubtotalPriceText"
                                        class="text-[#111827] text-[24px] font-extrabold">
                                        ฿{{ number_format($estimatedPrice, 0) }}
                                    </div>
                                </div>

                                <div class="px-5 py-4 space-y-3">
                                    <div class="flex items-center justify-between gap-3 text-[15px]">
                                        <div class="inline-flex items-center gap-2 text-[#66727D]">
                                            <svg class="w-4 h-4 text-[#94A3B8]" viewBox="0 0 24 24" fill="none">
                                                <path d="M7 7.5 12 4l5 3.5v9L12 20l-5-3.5v-9Z" stroke="currentColor"
                                                    stroke-width="1.8" stroke-linejoin="round" />
                                                <path d="M7 7.5 12 11l5-3.5M12 11v9" stroke="currentColor"
                                                    stroke-width="1.8" stroke-linejoin="round" />
                                            </svg>
                                            วิธีจัดส่ง / นัดรับ
                                        </div>
                                        <div class="text-[#10A36A] font-bold">
                                            เลือกในฟอร์ม
                                        </div>
                                    </div>

                                    <div id="checkoutBonusRow"
                                        class="hidden flex items-center justify-between gap-3 text-[15px]">
                                        <div class="inline-flex items-center gap-2 text-[#66727D]">
                                            โบนัสโค้ด
                                        </div>
                                        <div id="checkoutBonusAmountText" class="text-[#10A36A] font-bold">
                                            +฿0
                                        </div>
                                    </div>

                                    <div class="text-[15px]">
                                        <div class="text-[#66727D] font-medium mb-2">
                                            สรุปสภาพตามที่เลือก
                                        </div>
                                        <div
                                            class="rounded-[14px] border border-[#E5ECE8] bg-[#F9FBFA] px-4 py-3 text-[#66727D] leading-7">
                                            {{ $summaryText }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5 rounded-[24px] bg-[#F7FBF9] border border-[#E3ECE7] p-5">
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
                                            หมายเหตุสำคัญ
                                        </div>
                                        <p class="mt-2 text-[#66727D] text-[14px] leading-7">
                                            ราคานี้เป็นเพียงการประเมินเบื้องต้นจากข้อมูลที่คุณเลือก
                                            หากตรวจสอบเครื่องจริงแล้วสภาพไม่ตรง ระบบอาจมีการปรับราคาอีกครั้ง
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    select {
        appearance: auto;
        -webkit-appearance: menulist;
        -moz-appearance: menulist;
    }

    select.w-full,
    input.w-full,
    textarea.w-full {
        box-sizing: border-box;
    }

    select.w-full {
        display: block;
        width: 100%;
        min-height: 56px;
        line-height: 56px;
    }

    select.w-full:focus,
    input.w-full:focus,
    textarea.w-full:focus {
        outline: none;
    }

    input[type="date"]::-webkit-calendar-picker-indicator {
        cursor: pointer;
    }

    .ts-dropdown .option.disabled,
    .ts-dropdown .option.disabled.active,
    .ts-dropdown .option[data-disabled="true"],
    .ts-dropdown .option[data-disabled="true"].active,
    .ts-dropdown .option[aria-disabled="true"],
    .ts-dropdown .option[aria-disabled="true"].active {
        color: #9CA3AF !important;
        background: #F3F4F6 !important;
        cursor: not-allowed !important;
        opacity: 0.65 !important;
        pointer-events: none !important;
    }

    select option:disabled {
        color: #9CA3AF;
        background: #F3F4F6;
    }

    @media (min-width: 1280px) {
        #checkoutMainGrid {
            align-items: flex-start !important;
            overflow: visible !important;
        }

        #checkoutMainGrid>aside {
            position: sticky !important;
            top: 96px !important;
            align-self: flex-start !important;
            height: fit-content !important;
            z-index: 30;
        }

        .summary-follow-box {
            position: static !important;
            max-height: none !important;
            overflow: visible !important;
            transform: none !important;
            transition: none !important;
        }
    }

    @media (max-width: 1279px) {
        #checkoutMainGrid>aside {
            position: static !important;
        }

        .summary-follow-box {
            position: static !important;
            max-height: none !important;
            overflow: visible !important;
            transform: none !important;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabInputs = document.querySelectorAll('.pickup-tab-input');
        const panels = document.querySelectorAll('.pickup-panel');
        const selectedPickupMethodText = document.getElementById('selectedPickupMethodText');
        const pickupPanelInput = document.getElementById('pickupPanelInput');

        const serviceAreas = @json($serviceAreas ?? []);
        const provinceStoreSelect = document.getElementById('provinceStoreSelect');
        const districtStoreSelect = document.getElementById('districtStoreSelect');
        const serviceAreaNotice = document.getElementById('serviceAreaNotice');

        const transitLineSelect = document.getElementById('transitLineSelect');
        const transitStationSelect = document.getElementById('transitStationSelect');
        const transitStationsByLine = @json($transitStationsByLine ?? []);

        const storeBranchSelect = document.getElementById('storeBranchSelect');
        const storeBranchInfo = document.getElementById('storeBranchInfo');

        const checkoutForm = document.getElementById('pickupTabsForm');
        const confirmSellOrderBtn = document.getElementById('confirmSellOrderBtn');
        const acceptTermsCheckbox = document.getElementById('acceptTermsCheckbox');

        const bonusCodes = @json($bonusCodes ?? []);
        const canUseBonusCode = @json($canUseBonusCode ?? false);
        const priceAfterDeduct = Number(@json((float)($priceAfterDeduct ?? 0)));

        const bonusCodeSelect = document.getElementById('bonusCodeSelect');
        const bonusCodeIdHidden = document.getElementById('bonusCodeIdHidden');
        const bonusAmountHidden = document.getElementById('bonusAmountHidden');
        const estimatedPriceHidden = document.getElementById('estimatedPriceHidden');

        const checkoutEstimatedPriceText = document.getElementById('checkoutEstimatedPriceText');
        const checkoutSubtotalPriceText = document.getElementById('checkoutSubtotalPriceText');
        const checkoutBonusRow = document.getElementById('checkoutBonusRow');
        const checkoutBonusAmountText = document.getElementById('checkoutBonusAmountText');
        const bonusCodeDescriptionText = document.getElementById('bonusCodeDescriptionText');
        const bonusInlineResultBox = document.getElementById('bonusInlineResultBox');
        const bonusInlineAmountText = document.getElementById('bonusInlineAmountText');
        const bonusInlineFinalPriceText = document.getElementById('bonusInlineFinalPriceText');

        let transitLineTom = null;
        let transitStationTom = null;
        let storeBranchTom = null;
        let provinceStoreTom = null;
        let districtStoreTom = null;
        let pickupTimeStoreTom = null;
        let pickupTimeBtsTom = null;

        function formatBaht(amount) {
            return '฿' + Number(amount || 0).toLocaleString('th-TH', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
        }

        function getSelectedBonusCode() {
            if (!canUseBonusCode) {
                return null;
            }

            if (!bonusCodeSelect || !bonusCodeSelect.value) {
                return null;
            }

            return bonusCodes.find(function(item) {
                return String(item.id) === String(bonusCodeSelect.value);
            }) || null;
        }

        function calculateBonusAmount() {
            const selectedBonusCode = getSelectedBonusCode();

            if (!selectedBonusCode) {
                return 0;
            }

            if (selectedBonusCode.bonus_type === 'percent') {
                let bonus = (priceAfterDeduct * Number(selectedBonusCode.bonus_value || 0)) / 100;

                if (
                    selectedBonusCode.max_bonus_amount !== null &&
                    selectedBonusCode.max_bonus_amount !== undefined
                ) {
                    bonus = Math.min(bonus, Number(selectedBonusCode.max_bonus_amount || 0));
                }

                return Math.max(0, bonus);
            }

            return Math.max(0, Number(selectedBonusCode.bonus_value || 0));
        }

        function updateCheckoutBonusPrice() {
            const selectedBonusCode = getSelectedBonusCode();
            const bonusAmount = calculateBonusAmount();
            const finalPrice = priceAfterDeduct + bonusAmount;

            if (bonusCodeIdHidden) {
                bonusCodeIdHidden.value = selectedBonusCode ? selectedBonusCode.id : '';
            }

            if (bonusAmountHidden) {
                bonusAmountHidden.value = bonusAmount.toFixed(2);
            }

            if (estimatedPriceHidden) {
                estimatedPriceHidden.value = finalPrice.toFixed(2);
            }

            if (checkoutEstimatedPriceText) {
                checkoutEstimatedPriceText.textContent = formatBaht(finalPrice);
            }

            if (checkoutSubtotalPriceText) {
                checkoutSubtotalPriceText.textContent = formatBaht(finalPrice);
            }

            if (checkoutBonusRow) {
                checkoutBonusRow.classList.toggle('hidden', bonusAmount <= 0);
            }

            if (checkoutBonusAmountText) {
                checkoutBonusAmountText.textContent = '+' + formatBaht(bonusAmount);
            }

            if (bonusInlineResultBox) {
                bonusInlineResultBox.classList.toggle('hidden', !selectedBonusCode);
            }

            if (bonusInlineAmountText) {
                bonusInlineAmountText.textContent = '+' + formatBaht(bonusAmount);
            }

            if (bonusInlineFinalPriceText) {
                bonusInlineFinalPriceText.textContent = formatBaht(finalPrice);
            }

            if (bonusCodeDescriptionText) {
                if (selectedBonusCode && selectedBonusCode.description) {
                    bonusCodeDescriptionText.textContent = selectedBonusCode.description;
                } else if (selectedBonusCode) {
                    bonusCodeDescriptionText.textContent = 'โค้ดนี้จะบวกราคาเพิ่มหลังคำนวณราคาตามสภาพเครื่อง';
                } else {
                    bonusCodeDescriptionText.textContent =
                        'เลือกโค้ดเพื่อบวกราคาเพิ่มจากราคาประเมินหลังหักตามสภาพ';
                }
            }
        }

        function createSearchableSelect(selector, placeholder) {
            /*
            |--------------------------------------------------------------------------
            | Native Select Mode
            |--------------------------------------------------------------------------
            | ไม่ใช้ TomSelect ในหน้านี้ เพื่อไม่ให้เกิดกล่อง select ซ้อนกัน
            | คืนค่า null เพื่อให้โค้ดด้านล่างใช้ event change ของ select ปกติแทน
            */
            return null;
        }

        function getCheckedMethod() {
            return document.querySelector('.pickup-tab-input:checked');
        }

        function getActivePanel() {
            const checked = getCheckedMethod();

            return checked ? (checked.dataset.panelKey || 'store') : 'store';
        }

        function togglePanels() {
            const checked = getCheckedMethod();

            if (!checked) {
                return;
            }

            const activePanel = checked.dataset.panelKey || 'store';

            if (pickupPanelInput) {
                pickupPanelInput.value = activePanel;
            }

            panels.forEach(function(panel) {
                panel.classList.toggle('hidden', panel.dataset.panel !== activePanel);
            });

            updateSelectedPickupText();
        }

        function updateSelectedPickupText() {
            const checked = getCheckedMethod();

            if (!checked || !selectedPickupMethodText) {
                return;
            }

            const label = checked.closest('label')?.innerText || checked.value;

            selectedPickupMethodText.textContent = label.trim().replace(/\s+/g, ' ');
        }

        function getUniqueDistrictsByProvince(provinceName) {
            return serviceAreas
                .filter(function(item) {
                    return String(item.province || '') === String(provinceName || '');
                })
                .map(function(item) {
                    return item.district;
                })
                .filter(function(value, index, self) {
                    return value && self.indexOf(value) === index;
                })
                .sort(function(a, b) {
                    return String(a).localeCompare(String(b), 'th');
                });
        }

        function renderStoreDistrictsByProvince(provinceName) {
            if (!districtStoreSelect) {
                return;
            }

            const oldValue = districtStoreSelect.dataset.oldValue || '';
            const currentValue = districtStoreSelect.value || oldValue;
            const districts = getUniqueDistrictsByProvince(provinceName);

            if (districtStoreSelect.tomselect) {
                const tom = districtStoreSelect.tomselect;

                tom.clear(true);
                tom.clearOptions();

                tom.addOption({
                    value: '',
                    text: 'เลือกเขต / อำเภอ'
                });

                districts.forEach(function(district) {
                    tom.addOption({
                        value: district,
                        text: district
                    });
                });

                tom.refreshOptions(false);

                if (currentValue && districts.includes(currentValue)) {
                    tom.setValue(currentValue, true);
                } else {
                    tom.clear(true);
                }
            } else {
                districtStoreSelect.innerHTML = '<option value="">เลือกเขต / อำเภอ</option>';

                districts.forEach(function(district) {
                    const option = document.createElement('option');

                    option.value = district;
                    option.textContent = district;

                    if (String(currentValue) === String(district)) {
                        option.selected = true;
                    }

                    districtStoreSelect.appendChild(option);
                });
            }

            if (serviceAreaNotice) {
                serviceAreaNotice.classList.toggle('hidden', !provinceName || districts.length > 0);
            }
        }

        function renderTransitStations(lineName) {
            if (!transitStationSelect) {
                return;
            }

            const oldValue = transitStationSelect.dataset.oldValue || '';

            if (transitStationTom) {
                transitStationTom.clear(true);
                transitStationTom.clearOptions();
                transitStationTom.addOption({
                    value: '',
                    text: 'ระบุสถานี'
                });
            } else {
                transitStationSelect.innerHTML = '<option value="">ระบุสถานี</option>';
            }

            const selectedLine = transitStationsByLine.find(function(item) {
                return String(item.line_name) === String(lineName);
            });

            if (selectedLine && Array.isArray(selectedLine.stations)) {
                selectedLine.stations.forEach(function(station) {
                    if (transitStationTom) {
                        transitStationTom.addOption({
                            value: String(station.id),
                            text: station.name
                        });
                    } else {
                        const option = document.createElement('option');

                        option.value = station.id;
                        option.textContent = station.name;

                        if (String(oldValue) === String(station.id)) {
                            option.selected = true;
                        }

                        transitStationSelect.appendChild(option);
                    }
                });
            }

            if (transitStationTom) {
                transitStationTom.refreshOptions(false);

                if (oldValue) {
                    transitStationTom.setValue(String(oldValue), true);
                }
            }
        }

        function updateStoreBranchInfo() {
            if (!storeBranchSelect || !storeBranchInfo) {
                return;
            }

            const selectedValue = storeBranchTom ? storeBranchTom.getValue() : storeBranchSelect.value;

            const selectedOption = Array.from(storeBranchSelect.options).find(function(option) {
                return String(option.value) === String(selectedValue);
            });

            if (!selectedOption || !selectedOption.value) {
                storeBranchInfo.classList.add('hidden');
                storeBranchInfo.innerHTML = '';
                return;
            }

            const address = selectedOption.dataset.address || '-';
            const province = selectedOption.dataset.province || '-';
            const district = selectedOption.dataset.district || '-';

            storeBranchInfo.classList.remove('hidden');
            storeBranchInfo.innerHTML =
                '<div><b>ที่อยู่สาขา:</b> ' + address + '</div>' +
                '<div><b>จังหวัด:</b> ' + province + '</div>' +
                '<div><b>เขต/อำเภอ:</b> ' + district + '</div>';
        }

        function getInputValue(name) {
            const input = checkoutForm ? checkoutForm.querySelector('[name="' + name + '"]') : null;

            return input ? String(input.value || '').trim() : '';
        }

        function getSelectedOptionText(selector) {
            const select = document.querySelector(selector);

            if (!select) {
                return '';
            }

            if (select.tomselect) {
                const value = select.tomselect.getValue();
                const option = select.tomselect.options[value];

                return option ? option.text : '';
            }

            const selectedOption = select.options[select.selectedIndex];

            return selectedOption ? selectedOption.textContent.trim() : '';
        }

        function normalizeTimeRangeText(timeText) {
            return String(timeText || '').replace(/\./g, ':');
        }

        function getTimeRangeStartMinutes(timeText) {
            if (!timeText || timeText === 'เวลา') {
                return null;
            }

            const normalizedTimeText = normalizeTimeRangeText(timeText);
            const match = normalizedTimeText.match(/^(\d{2}):(\d{2})\s*-\s*(\d{2}):(\d{2})$/);

            if (!match) {
                return null;
            }

            const startHour = parseInt(match[1], 10);
            const startMinute = parseInt(match[2], 10);

            return (startHour * 60) + startMinute;
        }

        function isTodayDate(dateValue) {
            if (!dateValue) {
                return false;
            }

            const today = new Date();
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const dd = String(today.getDate()).padStart(2, '0');

            return dateValue === `${yyyy}-${mm}-${dd}`;
        }

        function getCurrentMinutes() {
            const now = new Date();

            return (now.getHours() * 60) + now.getMinutes();
        }

        function refreshTimeSelectByDate(dateInputId, timeSelectId) {
            const dateInput = document.getElementById(dateInputId);
            const timeSelect = document.getElementById(timeSelectId);

            if (!dateInput || !timeSelect) {
                return;
            }

            const selectedDate = dateInput.value;
            const todaySelected = isTodayDate(selectedDate);
            const currentMinutes = getCurrentMinutes();

            Array.from(timeSelect.options).forEach(function(option) {
                const value = option.value;

                if (!value || value === 'เวลา') {
                    option.disabled = false;
                    return;
                }

                const startMinutes = getTimeRangeStartMinutes(value);

                if (startMinutes === null) {
                    option.disabled = false;
                    return;
                }

                option.disabled = todaySelected && startMinutes <= currentMinutes;
            });

            const currentValue = timeSelect.value;
            const selectedOption = Array.from(timeSelect.options).find(function(option) {
                return String(option.value) === String(currentValue);
            });

            if (selectedOption && selectedOption.disabled) {
                timeSelect.value = '';

                if (timeSelect.tomselect) {
                    timeSelect.tomselect.clear(true);
                }
            }

            if (timeSelect.tomselect) {
                const tom = timeSelect.tomselect;

                Array.from(timeSelect.options).forEach(function(option, index) {
                    const value = option.value;

                    const optionData = {
                        value: value,
                        text: option.textContent.trim(),
                        disabled: option.disabled,
                        $order: index + 1
                    };

                    if (tom.options[value]) {
                        tom.updateOption(value, optionData);
                    } else {
                        tom.addOption(optionData);
                    }
                });

                const tomValue = tom.getValue();
                const tomOption = tom.options[tomValue];

                if (tomOption && tomOption.disabled) {
                    tom.clear(true);
                    timeSelect.value = '';
                }

                tom.refreshOptions(false);
                tom.refreshItems();
            }
        }

        function validateBeforeConfirm() {
            const panel = getActivePanel();

            if (panel === 'store') {
                if (!getInputValue('fullname_store')) return 'กรุณากรอกชื่อ-นามสกุล';
                if (!getInputValue('phone_store')) return 'กรุณากรอกหมายเลขโทรศัพท์';
                if (!getInputValue('address_store')) return 'กรุณากรอกที่อยู่';
                if (!getInputValue('province_store')) return 'กรุณาเลือกจังหวัด';
                if (!getInputValue('district_store')) return 'กรุณาเลือกเขต / อำเภอ';
                if (!getInputValue('pickup_date_store')) return 'กรุณาเลือกวันนัดหมาย';

                if (!getInputValue('pickup_time_store') || getInputValue('pickup_time_store') === 'เวลา') {
                    return 'กรุณาเลือกช่วงเวลา';
                }

                if (isTodayDate(getInputValue('pickup_date_store'))) {
                    const selectedStartMinutes = getTimeRangeStartMinutes(getInputValue('pickup_time_store'));

                    if (selectedStartMinutes !== null && selectedStartMinutes <= getCurrentMinutes()) {
                        return 'กรุณาเลือกช่วงเวลาถัดไป เนื่องจากช่วงเวลานี้เลยเวลาหรืออยู่ในช่วงเวลาปัจจุบันแล้ว';
                    }
                }
            }

            if (panel === 'bts_mrt') {
                if (!getInputValue('fullname_bts')) return 'กรุณากรอกชื่อ-นามสกุล';
                if (!getInputValue('phone_bts')) return 'กรุณากรอกหมายเลขโทรศัพท์';
                if (!getInputValue('transit_line')) return 'กรุณาเลือกสายรถไฟ';
                if (!getInputValue('transit_station_id')) return 'กรุณาเลือกสถานี';
                if (!getInputValue('pickup_date_bts')) return 'กรุณาเลือกวันนัดหมาย';

                if (!getInputValue('pickup_time_bts') || getInputValue('pickup_time_bts') === 'เวลา') {
                    return 'กรุณาเลือกช่วงเวลา';
                }

                if (isTodayDate(getInputValue('pickup_date_bts'))) {
                    const selectedStartMinutes = getTimeRangeStartMinutes(getInputValue('pickup_time_bts'));

                    if (selectedStartMinutes !== null && selectedStartMinutes <= getCurrentMinutes()) {
                        return 'กรุณาเลือกช่วงเวลาถัดไป เนื่องจากช่วงเวลานี้เลยเวลาหรืออยู่ในช่วงเวลาปัจจุบันแล้ว';
                    }
                }
            }

            if (panel === 'ems') {
                if (!getInputValue('fullname_ems')) return 'กรุณากรอกชื่อ-นามสกุล';
                if (!getInputValue('phone_ems')) return 'กรุณากรอกหมายเลขโทรศัพท์';
            }

            const acceptTerms = acceptTermsCheckbox || (checkoutForm ? checkoutForm.querySelector(
                '[name="accept_terms"]') : null);

            if (acceptTerms && !acceptTerms.checked) {
                return 'กรุณายอมรับข้อตกลงและเงื่อนไขก่อนยืนยันการขาย';
            }

            return '';
        }

        function buildConfirmSummaryHtml() {
            const panel = getActivePanel();

            const summaryTitle = @json($summaryTitle ?? '-');
            const summaryText = @json($summaryText ?? '-');
            const estimatedPrice = estimatedPriceHidden ?
                Number(estimatedPriceHidden.value || 0).toLocaleString('th-TH', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }) :
                @json(number_format((float)($estimatedPrice ?? 0), 0));

            const selectedBonusCode = getSelectedBonusCode();
            const bonusAmount = calculateBonusAmount();

            let methodName = 'รับซื้อถึงที่';
            let customerName = '';
            let customerPhone = '';
            let customerLineId = '';
            let customerEmail = '';
            let locationText = '';
            let pickupDate = '';
            let pickupTime = '';

            if (panel === 'store') {
                methodName = 'รับซื้อถึงที่';
                customerName = getInputValue('fullname_store');
                customerPhone = getInputValue('phone_store');
                customerLineId = getInputValue('line_id_store');
                customerEmail = getInputValue('email_store');
                locationText =
                    'ที่อยู่รับซื้อ: ' + (getInputValue('address_store') || '-') +
                    '<br>จังหวัด/เขต: ' + (getInputValue('province_store') || '-') + ' / ' + (getInputValue(
                        'district_store') || '-');
                pickupDate = getInputValue('pickup_date_store');
                pickupTime = getInputValue('pickup_time_store');
            }

            if (panel === 'bts_mrt') {
                methodName = 'รับซื้อตาม BTS/MRT';
                customerName = getInputValue('fullname_bts');
                customerPhone = getInputValue('phone_bts');
                customerLineId = getInputValue('line_id_bts');
                customerEmail = getInputValue('email_bts');
                locationText =
                    'สายรถไฟ: ' + (getInputValue('transit_line') || '-') +
                    '<br>สถานี: ' + (getSelectedOptionText('#transitStationSelect') || '-');
                pickupDate = getInputValue('pickup_date_bts');
                pickupTime = getInputValue('pickup_time_bts');
            }

            if (panel === 'ems') {
                methodName = 'จัดส่งพัสดุมาที่ศูนย์ใหญ่';
                customerName = getInputValue('fullname_ems');
                customerPhone = getInputValue('phone_ems');
                customerLineId = getInputValue('line_id_ems');
                customerEmail = getInputValue('email_ems');
                locationText = 'ลูกค้าจัดส่งพัสดุมาที่ศูนย์ใหญ่';
                pickupDate = '-';
                pickupTime = '-';
            }

            const bonusLine = selectedBonusCode && bonusAmount > 0 ?
                `<br><b>โค้ดบวกราคา:</b> ${selectedBonusCode.code} / +${formatBaht(bonusAmount)}` :
                '';

            return `
                <div style="text-align:left;font-size:14px;line-height:1.8">
                    <div style="padding:12px;border:1px solid #E5ECE8;border-radius:14px;margin-bottom:12px;background:#F9FBFA">
                        <b>สินค้า:</b> ${summaryTitle}<br>
                        <b>สภาพที่เลือก:</b> ${summaryText}${bonusLine}<br>
                        <b>ราคาประเมิน:</b> <span style="color:#10A36A;font-weight:700">฿${estimatedPrice}</span>
                    </div>

                    <div style="padding:12px;border:1px solid #E5ECE8;border-radius:14px;margin-bottom:12px;background:#FFFFFF">
                        <b>วิธีขาย:</b> ${methodName}<br>
                        <b>ชื่อ:</b> ${customerName}<br>
                        <b>เบอร์โทร:</b> ${customerPhone}<br>
                        <b>Line ID:</b> ${customerLineId || '-'}<br>
                        <b>อีเมล:</b> ${customerEmail || '-'}<br>
                        <b>รายละเอียดสถานที่:</b><br>${locationText}<br>
                        <b>วันที่นัดหมาย:</b> ${pickupDate}<br>
                        <b>ช่วงเวลา:</b> ${pickupTime}
                    </div>

                    <div style="font-size:12px;color:#66727D">
                        กรุณาตรวจสอบข้อมูลให้ถูกต้องก่อนยืนยัน หลังยืนยันแล้วระบบจะบันทึกคำสั่งขายและทีมงานจะติดต่อกลับ
                    </div>
                </div>
            `;
        }

        tabInputs.forEach(function(input) {
            input.addEventListener('change', togglePanels);
        });

        if (transitLineSelect) {
            transitLineTom = createSearchableSelect('#transitLineSelect', 'ค้นหาสายรถไฟ');
            transitStationTom = createSearchableSelect('#transitStationSelect', 'ค้นหาสถานี');

            if (transitLineTom) {
                transitLineTom.on('change', function(value) {
                    renderTransitStations(value);
                });

                renderTransitStations(transitLineTom.getValue());
            } else {
                transitLineSelect.addEventListener('change', function() {
                    renderTransitStations(this.value);
                });

                renderTransitStations(transitLineSelect.value);
            }
        }

        if (storeBranchSelect) {
            storeBranchTom = createSearchableSelect('#storeBranchSelect', 'ค้นหาสาขา');

            if (storeBranchTom) {
                storeBranchTom.on('change', updateStoreBranchInfo);
            } else {
                storeBranchSelect.addEventListener('change', updateStoreBranchInfo);
            }

            updateStoreBranchInfo();
        }

        provinceStoreTom = createSearchableSelect('#provinceStoreSelect', 'ค้นหาจังหวัด');
        districtStoreTom = createSearchableSelect('#districtStoreSelect', 'ค้นหาเขต / อำเภอ');
        pickupTimeStoreTom = createSearchableSelect('#pickupTimeStoreSelect', 'ค้นหาช่วงเวลา');
        pickupTimeBtsTom = createSearchableSelect('#pickupTimeBtsSelect', 'ค้นหาช่วงเวลา');

        if (provinceStoreSelect) {
            if (provinceStoreTom) {
                provinceStoreTom.on('change', function(value) {
                    renderStoreDistrictsByProvince(value);
                });

                renderStoreDistrictsByProvince(provinceStoreTom.getValue());
            } else {
                provinceStoreSelect.addEventListener('change', function() {
                    renderStoreDistrictsByProvince(this.value);
                });

                renderStoreDistrictsByProvince(provinceStoreSelect.value);
            }
        }

        const pickupDateStoreInput = document.getElementById('pickupDateStoreInput');
        const pickupDateBtsInput = document.getElementById('pickupDateBtsInput');

        if (pickupDateStoreInput) {
            pickupDateStoreInput.addEventListener('change', function() {
                refreshTimeSelectByDate('pickupDateStoreInput', 'pickupTimeStoreSelect');
            });

            refreshTimeSelectByDate('pickupDateStoreInput', 'pickupTimeStoreSelect');
        }

        if (pickupDateBtsInput) {
            pickupDateBtsInput.addEventListener('change', function() {
                refreshTimeSelectByDate('pickupDateBtsInput', 'pickupTimeBtsSelect');
            });

            refreshTimeSelectByDate('pickupDateBtsInput', 'pickupTimeBtsSelect');
        }

        function updateConfirmSellOrderButton() {
            if (!confirmSellOrderBtn || !acceptTermsCheckbox) {
                return;
            }

            confirmSellOrderBtn.disabled = !acceptTermsCheckbox.checked;
        }

        if (acceptTermsCheckbox) {
            acceptTermsCheckbox.addEventListener('change', function() {
                updateConfirmSellOrderButton();
            });

            updateConfirmSellOrderButton();
        }

        if (bonusCodeSelect) {
            bonusCodeSelect.addEventListener('change', function() {
                updateCheckoutBonusPrice();
            });

            updateCheckoutBonusPrice();
        }

        if (confirmSellOrderBtn && checkoutForm) {
            confirmSellOrderBtn.addEventListener('click', function() {
                const errorMessage = validateBeforeConfirm();

                if (errorMessage) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'กรอกข้อมูลไม่ครบ',
                        text: errorMessage,
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#10A36A'
                    });

                    return;
                }

                Swal.fire({
                    title: 'ยืนยันคำสั่งขาย',
                    html: buildConfirmSummaryHtml(),
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'ยืนยันรายการ',
                    cancelButtonText: 'กลับไปแก้ไข',
                    confirmButtonColor: '#10A36A',
                    cancelButtonColor: '#94A3B8',
                    width: 720
                }).then(function(result) {
                    if (result.isConfirmed) {
                        confirmSellOrderBtn.disabled = true;
                        confirmSellOrderBtn.textContent = 'กำลังบันทึก...';
                        checkoutForm.submit();
                    }
                });
            });
        }

        togglePanels();
    });
</script>
@endsection