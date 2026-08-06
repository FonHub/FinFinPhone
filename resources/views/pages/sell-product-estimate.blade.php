@extends('layouts.app')

@section('title', 'ประเมินราคา | Cashkub')

@section('content')
    <style>
        .estimate-option-grid {
            display: grid;
            gap: 12px;
            align-items: stretch;
        }

        .estimate-option-grid.has-icon {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .estimate-option-grid.no-icon {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }

        /*
                |--------------------------------------------------------------------------
                | ทำให้ label และ card สูงเท่ากันทุกตัวในแถว
                |--------------------------------------------------------------------------
                */

        .estimate-option-grid>label {
            display: flex;
            height: 100%;
        }

        .estimate-option-card-icon {
            width: 100%;
            height: 100%;
            min-height: 150px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
        }

        /*
                |--------------------------------------------------------------------------
                | แสดงข้อความเต็ม ไม่ตัดคำ ไม่จำกัดจำนวนบรรทัด
                |--------------------------------------------------------------------------
                */

        .estimate-option-card-text {
            display: block;
            width: 100%;
            min-height: 48px;
            line-height: 22px;
            white-space: normal;
            overflow: visible;
            word-break: break-word;
            overflow-wrap: anywhere;
            text-align: center;
        }

        /*
                |--------------------------------------------------------------------------
                | ตัวเลือกที่ไม่มีไอคอน
                |--------------------------------------------------------------------------
                */

        .estimate-option-card-no-icon {
            width: 100%;
            height: 100%;
            min-height: 76px;
            display: flex;
            align-items: center;
            white-space: normal;
            word-break: break-word;
            overflow-wrap: anywhere;
        }

        @media (max-width: 640px) {
            .estimate-option-card-icon {
                min-height: 145px;
            }

            .estimate-option-card-text {
                min-height: 44px;
                line-height: 20px;
            }

            .estimate-option-card-no-icon {
                min-height: 72px;
            }
        }

        @media (min-width: 768px) {
            .estimate-option-grid.no-icon {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (min-width: 1024px) {
            .estimate-option-grid.has-icon {
                grid-template-columns: repeat(5, minmax(0, 1fr));
            }
        }
    </style>

    @php
        $modelDisplayTitle = $summaryTitle ?? trim(($brand ?? '') . ' ' . ($model ?? '') . ' ' . ($storage ?? ''));

        /*
            สำคัญ:
            $sections ต้องมาจาก Controller เท่านั้น
            ห้ามประกาศคำถาม static ใน Blade แล้ว
        */
        $sections = $sections ?? [];

        $sectionKeys = array_column($sections, 'key');
        $totalSteps = count($sections);

        $productImage = 'assets/media/hero/hero-phone-right.png';

        $estimatePriceValue = (float) ($estimatePrice ?? 0);
        $minPriceValue = (float) ($minPrice ?? 0);

        $issueIcons = [
            'none' => '
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-6 h-6">
                    <circle cx="12" cy="12" r="8.5" stroke-width="1.8" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8.5 12.2l2.2 2.2 4.9-5.2" />
                </svg>
            ',
            'touch' => '
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-6 h-6">
                    <rect x="6.5" y="3.5" width="11" height="17" rx="2.5" stroke-width="1.8" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 17.5h.01" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.5 9.5c.7-.7 1.5-1 2.5-1s1.8.3 2.5 1M8.2 7.2A5.8 5.8 0 0 1 12 5.8a5.8 5.8 0 0 1 3.8 1.4" />
                </svg>
            ',
            'connect' => '
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.8 8.5A15 15 0 0 1 12 5.5a15 15 0 0 1 9.2 3" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 12a9.5 9.5 0 0 1 12 0" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.2 15.2a5 5 0 0 1 5.6 0" />
                    <circle cx="12" cy="18.2" r="1.1" fill="currentColor" stroke="none" />
                </svg>
            ',
            'vibration' => '
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-6 h-6">
                    <rect x="8" y="4" width="8" height="16" rx="2.2" stroke-width="1.8" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.5 8.5v7M19.5 8.5v7M2.5 10.5v3M21.5 10.5v3" />
                </svg>
            ',
            'call' => '
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7.2 5.2l2 3.8c.3.6.2 1.2-.3 1.6l-1.1 1c1.1 2.2 2.8 3.9 5 5l1-1.1c.4-.5 1.1-.6 1.6-.3l3.8 2c.6.3.9 1 .7 1.7-.4 1.3-1.6 2.1-3 2.1C9.2 21 3 14.8 3 7.1c0-1.4.8-2.6 2.1-3 .7-.2 1.4.1 1.7 1.1z" />
                </svg>
            ',
            'face_scan' => '
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 4.5H6.8A2.3 2.3 0 0 0 4.5 6.8V8M16 4.5h1.2a2.3 2.3 0 0 1 2.3 2.3V8M19.5 16v1.2a2.3 2.3 0 0 1-2.3 2.3H16M8 19.5H6.8a2.3 2.3 0 0 1-2.3-2.3V16" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.5 10.3c.5-.6 1.3-1 2.5-1s2 .4 2.5 1M9.3 14.2c.7.8 1.6 1.3 2.7 1.3s2-.5 2.7-1.3" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h.01M15 12h.01" />
                </svg>
            ',
            'home' => '
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-6 h-6">
                    <rect x="7" y="3.5" width="10" height="17" rx="2.5" stroke-width="1.8" />
                    <circle cx="12" cy="16.8" r="1.4" stroke-width="1.8" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 6.8h4" />
                </svg>
            ',
            'display' => '
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-6 h-6">
                    <rect x="4" y="5" width="16" height="11" rx="2" stroke-width="1.8" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 20h6M12 16v4" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7.5 9.5h.01M10.5 9.5h.01M13.5 9.5h.01M16.5 9.5h.01" />
                </svg>
            ',
            'camera' => '
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-6 h-6">
                    <rect x="3.5" y="6.5" width="17" height="12" rx="2.5" stroke-width="1.8" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 6.5l1.2-2h5.6l1.2 2" />
                    <circle cx="12" cy="12.5" r="3.2" stroke-width="1.8" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M18 9h.01" />
                </svg>
            ',
            'sensor' => '
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-6 h-6">
                    <circle cx="12" cy="12" r="2.5" stroke-width="1.8" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v2.2M12 17.8V20M4 12h2.2M17.8 12H20M6.4 6.4l1.5 1.5M16.1 16.1l1.5 1.5M17.6 6.4l-1.5 1.5M7.9 16.1l-1.5 1.5" />
                </svg>
            ',
            'button' => '
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-6 h-6">
                    <rect x="7" y="3.5" width="10" height="17" rx="2.5" stroke-width="1.8" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.8 8.5h1.2M17.8 11.5h1.2M5 10h1.2" />
                </svg>
            ',
            'speaker' => '
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 14.5h3.2l4.3 3V6.5l-4.3 3H5z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.5 10a3 3 0 0 1 0 4" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.8 8a6 6 0 0 1 0 8" />
                </svg>
            ',
            'mic' => '
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-6 h-6">
                    <rect x="9" y="4" width="6" height="10" rx="3" stroke-width="1.8" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6.5 11.5a5.5 5.5 0 0 0 11 0M12 17v3M9 20h6" />
                </svg>
            ',
            'charge' => '
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 7V4M15 7V4" />
                    <rect x="7" y="7" width="10" height="7" rx="1.8" stroke-width="1.8" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 18h4M12 14v4" />
                </svg>
            ',
            'sim' => '
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 4.5h6l3 3v12a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2v-13a2 2 0 0 1 2-2z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.5 10.5h5M9.5 14h5" />
                </svg>
            ',
            'other' => '
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-6 h-6">
                    <circle cx="12" cy="12" r="8" stroke-width="1.8" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8.2a2.4 2.4 0 0 1 2.4 2.2c0 1.5-1.8 2-2.4 3.1M12 16.8h.01" />
                </svg>
            ',
        ];
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
                                    <path d="M20 7L10 17l-5-5" stroke="currentColor" stroke-width="2.4"
                                        stroke-linecap="round" stroke-linejoin="round" />
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
                                2
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
                        <div class="flex min-w-0 flex-1 flex-col items-center text-center opacity-45">
                            <div
                                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-[#C9D4CE] bg-white text-[#95A39B] text-sm font-bold
                           sm:h-11 sm:w-11 sm:text-base">
                                3
                            </div>

                            <span
                                class="mt-2 block text-[11px] font-bold leading-tight text-[#95A39B]
                           sm:text-[15px] sm:leading-snug">
                                ยืนยัน
                            </span>
                        </div>

                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 xl:gap-8 items-start">
                <div class="xl:col-span-8">
                    <div class="mb-7 lg:mb-8">
                        <div class="text-[#16A36C] text-[20px] mb-3">
                            ขั้นตอนการประเมิน
                        </div>

                        <h2 class="font-medium mb-6 text-[26px] md:text-[26px] lg:text-[38px]">
                            เลือกข้อมูลสภาพเครื่อง
                            <br class="hidden sm:block">
                            ที่ตรงกับอุปกรณ์ของคุณ
                        </h2>

                        <p class="mt-5 text-[#7A8793] text-[17px] sm:text-[16px] leading-8 max-w-[760px]">
                            กรุณาเลือกข้อมูลให้ใกล้เคียงกับสภาพใช้งานจริงมากที่สุด
                            เพื่อให้ราคาประเมินเบื้องต้นแม่นยำยิ่งขึ้น
                        </p>
                    </div>

                    <div class="mb-5 flex items-center gap-4 flex-wrap">
                        <div
                            class="inline-flex items-center rounded-full bg-[#EAF7F1] text-[#13885C] text-[13px] font-bold px-4 h-10">
                            <span id="progressText">0 / {{ $totalSteps }}</span>
                        </div>

                        <div class="w-full sm:w-[280px] h-[8px] rounded-full bg-[#E4ECE8] overflow-hidden">
                            <div id="progressBar" class="h-full w-0 rounded-full bg-[#10A36A] transition-all duration-300">
                            </div>
                        </div>
                    </div>

                    <div id="estimate-app" data-total-steps="{{ (int) $totalSteps }}"
                        data-section-keys='@json($sectionKeys)'>
                    </div>

                    <form method="POST" action="{{ route('sell.product.checkout') }}" id="estimateForm"
                        class="rounded-[20px] border border-[#E6ECE8] bg-white shadow-[0_20px_60px_rgba(15,23,42,0.06)] overflow-hidden">
                        @csrf

                        <input type="hidden" name="mobile_product_category_id"
                            value="{{ $mobileProductCategoryId ?? '' }}">
                        <input type="hidden" name="mobile_brand_id" value="{{ $mobileBrandId ?? '' }}">
                        <input type="hidden" name="mobile_model_id" value="{{ $mobileModelId ?? '' }}">
                        <input type="hidden" name="mobile_model_price_id" value="{{ $mobileModelPriceId ?? '' }}">

                        <input type="hidden" name="category" value="{{ $category ?? '' }}">
                        <input type="hidden" name="brand" value="{{ $brand ?? '' }}">
                        <input type="hidden" name="model" value="{{ $model ?? '' }}">
                        <input type="hidden" name="storage" value="{{ $storage ?? '' }}">

                        <input type="hidden" name="estimate_price" value="{{ $estimatePriceValue }}">
                        <input type="hidden" name="min_price" value="{{ $minPriceValue }}">

                        <input type="hidden" name="deduct_total" id="deductTotalHidden" value="0">
                        <input type="hidden" name="price_after_deduct" id="priceAfterDeductHidden" value="0">
                        <input type="hidden" name="final_estimate_price" id="finalEstimatePriceHidden" value="0">

                        <div class="px-5 sm:px-7 lg:px-9 py-6 border-b border-[#EEF2EF] bg-[#FCFDFC]">
                            <div class="flex items-start sm:items-center justify-between gap-4 flex-col sm:flex-row">
                                <div>
                                    <p class="text-[#8A97A2] text-[13px] font-medium mb-1">
                                        อุปกรณ์ที่กำลังประเมิน
                                    </p>
                                    <h2 class="text-[#111827] text-[22px] sm:text-[28px] font-bold leading-tight">
                                        {{ $modelDisplayTitle }}
                                    </h2>
                                </div>

                                <div
                                    class="inline-flex items-center rounded-full bg-[#F3F6F4] border border-[#E3EBE6] text-[#5C6B65] text-[13px] font-semibold px-4 h-10">
                                    กรอกข้อมูลเพื่อประเมินราคาเบื้องต้น
                                </div>
                            </div>
                        </div>

                        <div class="px-5 sm:px-7 lg:px-9 py-2" id="estimateAccordion">
                            <div class="border-b border-[#EEF2EF]">
                                <div class="w-full flex items-center justify-between gap-4 py-5 text-left">
                                    <div class="pr-4 flex-1 min-w-0">
                                        <div class="flex items-center gap-3 flex-wrap">
                                            <h3 class="text-[#111827] text-[19px] sm:text-[21px] font-bold leading-tight">
                                                1. Model
                                            </h3>
                                        </div>

                                        <div class="mt-3 rounded-[12px] border border-[#DCE6E0] bg-[#FCFDFC] px-4 py-4">
                                            <div class="text-[13px] text-[#8A97A2] font-medium mb-1">
                                                อุปกรณ์ที่เลือก
                                            </div>

                                            <div class="text-[#111827] text-[17px] sm:text-[19px] font-bold leading-tight">
                                                {{ $modelDisplayTitle }}
                                            </div>

                                            <div class="mt-2 text-[13px] text-[#6B7280] leading-6">
                                                ประเภท: {{ $category ?? '-' }} /
                                                แบรนด์: {{ $brand ?? '-' }} /
                                                รุ่น: {{ $model ?? '-' }} /
                                                ความจุ: {{ $storage ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if (!empty($sections) && count($sections) > 0)
                                @foreach ($sections as $index => $section)
                                    @php
                                        $answerType = $section['answer_type'] ?? 'single';
                                        $isMultiple = $answerType === 'multiple';
                                    @endphp

                                    <div class="accordion-item border-b border-[#EEF2EF] {{ $index === 0 ? '' : 'hidden' }}"
                                        data-accordion-item data-section-key="{{ $section['key'] }}">
                                        <button type="button"
                                            class="w-full flex items-center justify-between gap-4 py-5 text-left accordion-trigger"
                                            data-accordion-trigger aria-expanded="{{ $index === 0 ? 'true' : 'false' }}">
                                            <div class="pr-4 flex-1 min-w-0">
                                                <div class="flex items-center gap-3 flex-wrap">
                                                    <h3
                                                        class="text-[#111827] text-[19px] sm:text-[21px] font-bold leading-tight">
                                                        {{ $section['title'] }}
                                                    </h3>

                                                    @if ($isMultiple)
                                                        <span
                                                            class="inline-flex items-center rounded-full bg-[#F3F6F4] text-[#6B7280] px-3 h-8 text-[12px] font-bold">
                                                            เลือกได้หลายข้อ
                                                        </span>
                                                    @endif
                                                </div>

                                                @if (!empty($section['description']))
                                                    <p class="text-sm text-[#7A8793] mt-2">
                                                        {{ $section['description'] }}
                                                    </p>
                                                @endif

                                                <div class="selected-preview hidden mt-2 text-[14px] sm:text-[15px] text-[#6B7280] leading-6"
                                                    data-selected-preview="{{ $section['key'] }}">
                                                </div>
                                            </div>

                                            <span class="relative block w-5 h-5 shrink-0">
                                                <span
                                                    class="absolute left-1/2 top-1/2 w-4 h-[1.8px] bg-[#6B7280] rounded-full -translate-x-1/2 -translate-y-1/2"></span>
                                                <span
                                                    class="accordion-vertical absolute left-1/2 top-1/2 h-4 w-[1.8px] bg-[#6B7280] rounded-full -translate-x-1/2 -translate-y-1/2 transition duration-300 {{ $index === 0 ? 'scale-y-0' : '' }}"></span>
                                            </span>
                                        </button>

                                        <div class="accordion-content overflow-hidden transition-all duration-300 {{ $index === 0 ? 'pb-6' : 'max-h-0' }}"
                                            data-accordion-content
                                            @if ($index !== 0) style="max-height:0px;" @endif>

                                            @php
                                                $sectionHasIcon = collect($section['fields'] ?? [])->contains(function (
                                                    $field,
                                                ) use ($issueIcons) {
                                                    $iconKey = $field['icon_key'] ?? null;

                                                    return !empty($iconKey) && array_key_exists($iconKey, $issueIcons);
                                                });
                                            @endphp

                                            <div
                                                class="estimate-option-grid {{ $sectionHasIcon ? 'has-icon' : 'no-icon' }}">
                                                @foreach ($section['fields'] as $field)
                                                    @php
                                                        $iconKey = $field['icon_key'] ?? null;
                                                        $hasIcon =
                                                            !empty($iconKey) && array_key_exists($iconKey, $issueIcons);
                                                    @endphp

                                                    <label class="cursor-pointer">
                                                        <input type="{{ $isMultiple ? 'checkbox' : 'radio' }}"
                                                            name="answers[{{ $section['key'] }}]{{ $isMultiple ? '[]' : '' }}"
                                                            value="{{ $field['id'] }}"
                                                            class="peer sr-only section-answer"
                                                            data-step-group="{{ $section['key'] }}"
                                                            data-option-id="{{ $field['id'] }}"
                                                            data-grade-master-id="{{ $field['grade_master_id'] ?? '' }}"
                                                            data-deduct-price="{{ $field['deduct_price'] ?? 0 }}">

                                                        @if ($hasIcon)
                                                            <span
                                                                class="estimate-option-card-icon gap-3 rounded-[14px] border border-[#DCE6E0] bg-[#FCFDFC] px-3 py-4 text-center text-[12px] sm:text-[14px] text-[#111827] transition duration-200 hover:border-[#10A36A] hover:bg-[#F7FCF9] peer-checked:border-[#10A36A] peer-checked:bg-[#ECF8F2] peer-checked:shadow-[inset_0_0_0_1px_#10A36A]">
                                                                <span
                                                                    class="w-9 h-9 sm:w-12 sm:h-12 rounded-full bg-[#F1F7F4] text-[#10A36A] flex items-center justify-center shrink-0 transition duration-200">
                                                                    {!! $issueIcons[$iconKey] !!}
                                                                </span>

                                                                <span class="estimate-option-card-text font-semibold">
                                                                    {{ $field['label'] }}
                                                                </span>
                                                            </span>
                                                        @else
                                                            <span
                                                                class="estimate-option-card-no-icon rounded-[10px] border border-[#DCE6E0] bg-[#FCFDFC] px-4 py-3 text-[15px] text-[#111827] leading-6 transition duration-200 hover:border-[#10A36A] hover:bg-[#F7FCF9] peer-checked:border-[#10A36A] peer-checked:bg-[#ECF8F2] peer-checked:shadow-[inset_0_0_0_1px_#10A36A]">
                                                                {{ $field['label'] }}
                                                            </span>
                                                        @endif
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="py-8 text-center text-[#7A8793]">
                                    ยังไม่มีชุดคำถามสำหรับสินค้านี้ กรุณาติดต่อผู้ดูแลระบบ
                                </div>
                            @endif
                        </div>

                        <div class="px-5 sm:px-7 lg:px-9 pt-1 pb-8 sm:pb-9">
                            <div id="icloudNoticeBox"
                                class="hidden rounded-[10px] border border-[#E7EEEA] bg-[#F9FBFA] px-4 py-4 sm:px-5">
                                <label class="inline-flex items-start gap-3 cursor-pointer">
                                    <input type="checkbox" name="icloud_notice_ack" id="icloudNoticeAck" value="1"
                                        class="mt-1 h-4 w-4 rounded border-[#C9D7D0] text-[#10A36A] focus:ring-[#10A36A]">
                                    <span class="text-sm text-[#66727D] leading-7">
                                        เครื่องไม่ติดล็อค iCloud และไม่ติดระบบค้นหาอุปกรณ์
                                        จะช่วยให้การประเมินและรับซื้อทำได้ง่ายขึ้น
                                    </span>
                                </label>
                            </div>

                            <div class="mt-8 flex justify-center">
                                <button type="submit" id="estimateSubmitBtn" disabled
                                    class="inline-flex items-center justify-center rounded-full bg-[#10A36A] hover:bg-[#0E8C5B] text-white text-[17px] font-bold px-8 h-14 min-w-[240px] shadow-[0_12px_30px_rgba(16,163,106,0.22)] transition duration-200 disabled:bg-[#A7B7AF] disabled:hover:bg-[#A7B7AF] disabled:cursor-not-allowed disabled:shadow-none disabled:opacity-70">
                                    ประเมินราคา
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <aside class="xl:col-span-4">
                    <div class="xl:sticky xl:top-6 space-y-4">
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
                                            class="w-[106px] h-[106px] rounded-[10px] bg-[#F4F7F5] border border-[#E1E8E4] flex items-center justify-center overflow-hidden shrink-0">
                                            <img src="{{ asset($productImage) }}" alt="{{ $modelDisplayTitle }}"
                                                class="w-[84px] h-[84px] object-contain">
                                        </div>

                                        <div class="min-w-0 flex-1">
                                            <h4
                                                class="text-[#0F172A] font-extrabold text-[18px] sm:text-[20px] leading-tight">
                                                {{ $modelDisplayTitle }}
                                            </h4>

                                            {{-- <div class="mt-3 text-[#95A1AB] text-[13px] font-medium">
                                                ราคาประเมินหลังคำนวณ
                                            </div>

                                            <div id="finalPriceText"
                                                class="hidden mt-1 text-[#10A36A] text-[22px] sm:text-[24px] font-extrabold tracking-tight">
                                                ฿0
                                            </div>

                                            <div id="priceBreakdownText"
                                                class="hidden mt-2 text-[#66727D] text-[13px] leading-6">
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-5 rounded-[20px] bg-[#F7FBF9] border border-[#E3ECE7] p-5">
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
                                                ราคาจริงจะสรุปหลังตรวจสอบข้อมูลและสภาพเครื่องครบถ้วน
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const accordionItems = Array.from(document.querySelectorAll('[data-accordion-item]'));
            const progressText = document.getElementById('progressText');
            const progressBar = document.getElementById('progressBar');
            const appContainer = document.getElementById('estimate-app');
            const icloudNoticeBox = document.getElementById('icloudNoticeBox');
            const icloudNoticeAck = document.getElementById('icloudNoticeAck');
            const estimateSubmitBtn = document.getElementById('estimateSubmitBtn');
            const estimateForm = document.getElementById('estimateForm');

            const basePrice = Number(@json($estimatePriceValue));
            const minPrice = Number(@json($minPriceValue));

            const deductTotalHidden = document.getElementById('deductTotalHidden');
            const priceAfterDeductHidden = document.getElementById('priceAfterDeductHidden');
            const finalEstimatePriceHidden = document.getElementById('finalEstimatePriceHidden');

            const priceWaitingText = document.getElementById('priceWaitingText');
            const finalPriceText = document.getElementById('finalPriceText');
            const sidebarFinalPriceText = document.getElementById('sidebarFinalPriceText');
            const priceBreakdownText = document.getElementById('priceBreakdownText');

            if (!appContainer) return;

            const totalSteps = parseInt(appContainer.dataset.totalSteps || '0', 10);
            const sectionGroups = JSON.parse(appContainer.dataset.sectionKeys || '[]');
            const allGroups = sectionGroups;

            function formatBaht(amount) {
                return '฿' + Number(amount || 0).toLocaleString('th-TH', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                });
            }

            function getTrigger(item) {
                return item ? item.querySelector('[data-accordion-trigger]') : null;
            }

            function getContent(item) {
                return item ? item.querySelector('[data-accordion-content]') : null;
            }

            function getVertical(item) {
                return item ? item.querySelector('.accordion-vertical') : null;
            }

            function setContentHeight(content, expanded) {
                if (!content) return;

                if (expanded) {
                    content.classList.remove('max-h-0');
                    content.classList.add('pb-6');
                    content.style.maxHeight = content.scrollHeight + 'px';
                } else {
                    content.classList.remove('pb-6');
                    content.classList.add('max-h-0');
                    content.style.maxHeight = '0px';
                }
            }

            function isGroupAnswered(group) {
                return document.querySelectorAll('input[data-step-group="' + group + '"]:checked').length > 0;
            }

            function getGroupSelectedText(group) {
                const checked = Array.from(document.querySelectorAll('input[data-step-group="' + group +
                    '"]:checked'));

                return checked.map(function(input) {
                    const labelText = input.closest('label')?.innerText || input.value || '';
                    return labelText.trim().replace(/\s+/g, ' ');
                }).filter(Boolean).join(', ');
            }

            function updateSelectedPreview(group) {
                const preview = document.querySelector('[data-selected-preview="' + group + '"]');

                if (!preview) return;

                const answered = isGroupAnswered(group);
                const text = getGroupSelectedText(group);

                if (answered && text) {
                    preview.textContent = text;
                    preview.classList.remove('hidden');
                } else {
                    preview.textContent = '';
                    preview.classList.add('hidden');
                }
            }

            function updateAllSelectedPreviews() {
                allGroups.forEach(function(group) {
                    updateSelectedPreview(group);
                });
            }

            function calculateDeductTotal() {
                let deductTotal = 0;

                document.querySelectorAll('.section-answer:checked').forEach(function(input) {
                    deductTotal += Number(input.dataset.deductPrice || 0);
                });

                return deductTotal;
            }

            function isAllAnswered() {
                if (totalSteps <= 0) return false;

                return allGroups.every(function(group) {
                    return isGroupAnswered(group);
                });
            }

            function resetPriceDisplay() {
                if (priceWaitingText) {
                    priceWaitingText.classList.remove('hidden');
                    priceWaitingText.textContent = totalSteps <= 0 ?
                        'ยังไม่มีชุดคำถามสำหรับสินค้านี้' :
                        'จะแสดงหลังเลือกข้อมูลครบ';
                }

                if (finalPriceText) {
                    finalPriceText.classList.add('hidden');
                    finalPriceText.textContent = '฿0';
                }

                if (sidebarFinalPriceText) {
                    sidebarFinalPriceText.textContent = totalSteps <= 0 ?
                        'ยังไม่มีชุดคำถาม' :
                        'จะแสดงหลังเลือกข้อมูลครบ';
                    sidebarFinalPriceText.className = 'text-[#66727D] text-[15px] font-bold';
                }

                if (priceBreakdownText) {
                    priceBreakdownText.classList.add('hidden');
                    priceBreakdownText.textContent = '';
                }

                if (deductTotalHidden) deductTotalHidden.value = '0';
                if (priceAfterDeductHidden) priceAfterDeductHidden.value = '0';
                if (finalEstimatePriceHidden) finalEstimatePriceHidden.value = '0';
            }

            function updateCalculatedPrice() {
                if (!isAllAnswered()) {
                    resetPriceDisplay();
                    return;
                }

                const deductTotal = calculateDeductTotal();
                const rawAfterDeduct = basePrice - deductTotal;
                const priceAfterDeduct = Math.max(rawAfterDeduct, minPrice);
                const finalPrice = priceAfterDeduct;

                if (priceWaitingText) {
                    priceWaitingText.classList.add('hidden');
                }

                if (finalPriceText) {
                    finalPriceText.classList.remove('hidden');
                    finalPriceText.textContent = formatBaht(finalPrice);
                }

                if (sidebarFinalPriceText) {
                    sidebarFinalPriceText.textContent = formatBaht(finalPrice);
                    sidebarFinalPriceText.className = 'text-[#111827] text-[24px] font-extrabold';
                }

                if (priceBreakdownText) {
                    priceBreakdownText.classList.remove('hidden');
                    priceBreakdownText.textContent =
                        'ราคาตั้งต้น ' + formatBaht(basePrice) +
                        ' / หักตามสภาพ ' + formatBaht(deductTotal) +
                        ' / ราคาหลังหัก ' + formatBaht(priceAfterDeduct);
                }

                if (deductTotalHidden) deductTotalHidden.value = deductTotal.toFixed(2);
                if (priceAfterDeductHidden) priceAfterDeductHidden.value = priceAfterDeduct.toFixed(2);
                if (finalEstimatePriceHidden) finalEstimatePriceHidden.value = finalPrice.toFixed(2);

                updateEstimateSubmitButton();
            }

            function updateSectionStatus() {
                let answered = 0;

                allGroups.forEach(function(group) {
                    if (isGroupAnswered(group)) {
                        answered++;
                    }
                });

                if (progressText) {
                    progressText.textContent = answered + ' / ' + totalSteps;
                }

                if (progressBar) {
                    progressBar.style.width = totalSteps > 0 ? ((answered / totalSteps) * 100) + '%' : '0%';
                }

                updateIcloudNoticeVisibility(answered);
            }

            function updateEstimateSubmitButton() {
                if (!estimateSubmitBtn) return;

                const allAnsweredNow = isAllAnswered();
                const icloudAccepted = icloudNoticeAck ? icloudNoticeAck.checked : false;

                estimateSubmitBtn.disabled = !(allAnsweredNow && icloudAccepted);
            }

            function updateIcloudNoticeVisibility(answeredCount) {
                if (!icloudNoticeBox) return;

                const isAllAnsweredNow = totalSteps > 0 && answeredCount >= totalSteps;

                icloudNoticeBox.classList.toggle('hidden', !isAllAnsweredNow);

                if (!isAllAnsweredNow && icloudNoticeAck) {
                    icloudNoticeAck.checked = false;
                }

                updateEstimateSubmitButton();
            }

            function getCurrentOpenItem() {
                return accordionItems.find(function(item) {
                    const trigger = getTrigger(item);

                    return !item.classList.contains('hidden') &&
                        trigger &&
                        trigger.getAttribute('aria-expanded') === 'true';
                }) || null;
            }

            function openAccordion(itemToOpen) {
                accordionItems.forEach(function(item) {
                    if (item.classList.contains('hidden')) return;

                    const trigger = getTrigger(item);
                    const content = getContent(item);
                    const vertical = getVertical(item);
                    const isTarget = item === itemToOpen;

                    if (trigger) {
                        trigger.setAttribute('aria-expanded', isTarget ? 'true' : 'false');
                    }

                    setContentHeight(content, isTarget);

                    if (vertical) {
                        vertical.classList.toggle('scale-y-0', isTarget);
                    }
                });
            }

            function closeAccordion(item) {
                const trigger = getTrigger(item);
                const content = getContent(item);
                const vertical = getVertical(item);

                if (trigger) {
                    trigger.setAttribute('aria-expanded', 'false');
                }

                setContentHeight(content, false);

                if (vertical) {
                    vertical.classList.remove('scale-y-0');
                }
            }

            function updateVisibleSteps() {
                let foundFirstUnanswered = false;

                accordionItems.forEach(function(item) {
                    const key = item.dataset.sectionKey;
                    const answered = isGroupAnswered(key);

                    if (answered) {
                        item.classList.remove('hidden');
                        closeAccordion(item);
                        return;
                    }

                    if (!foundFirstUnanswered) {
                        item.classList.remove('hidden');
                        foundFirstUnanswered = true;
                    } else {
                        item.classList.add('hidden');
                        closeAccordion(item);
                    }
                });
            }

            function openFirstUnansweredVisibleStep() {
                const nextItem = accordionItems.find(function(item) {
                    return !item.classList.contains('hidden') && !isGroupAnswered(item.dataset.sectionKey);
                });

                if (nextItem) {
                    openAccordion(nextItem);

                    setTimeout(function() {
                        nextItem.scrollIntoView({
                            behavior: 'smooth',
                            block: 'nearest'
                        });
                    }, 120);
                }
            }

            function refreshAccordionFlow() {
                updateSectionStatus();
                updateAllSelectedPreviews();
                updateVisibleSteps();
                openFirstUnansweredVisibleStep();
                updateCalculatedPrice();
            }

            accordionItems.forEach(function(item) {
                const trigger = getTrigger(item);

                if (!trigger) return;

                trigger.addEventListener('click', function() {
                    if (item.classList.contains('hidden')) return;

                    const key = item.dataset.sectionKey;
                    const isExpanded = trigger.getAttribute('aria-expanded') === 'true';

                    if (isExpanded) {
                        closeAccordion(item);
                    } else {
                        openAccordion(item);
                    }

                    if (!isGroupAnswered(key)) {
                        openAccordion(item);
                    }
                });
            });

            document.querySelectorAll('.section-answer').forEach(function(input) {
                input.addEventListener('change', function() {
                    const currentItem = input.closest('[data-accordion-item]');
                    const isMultiple = input.type === 'checkbox';

                    if (isMultiple) {
                        updateSectionStatus();
                        updateAllSelectedPreviews();
                        updateVisibleSteps();

                        if (currentItem) {
                            currentItem.classList.remove('hidden');

                            const trigger = getTrigger(currentItem);
                            const content = getContent(currentItem);
                            const vertical = getVertical(currentItem);

                            if (trigger) {
                                trigger.setAttribute('aria-expanded', 'true');
                            }

                            if (content) {
                                content.classList.remove('max-h-0');
                                content.classList.add('pb-6');
                                content.style.maxHeight = content.scrollHeight + 'px';
                            }

                            if (vertical) {
                                vertical.classList.add('scale-y-0');
                            }
                        }

                        updateCalculatedPrice();
                        return;
                    }

                    refreshAccordionFlow();
                });
            });

            if (icloudNoticeAck) {
                icloudNoticeAck.addEventListener('change', function() {
                    updateEstimateSubmitButton();
                });
            }

            if (estimateForm) {
                estimateForm.addEventListener('submit', function(event) {
                    if (!isAllAnswered()) {
                        event.preventDefault();
                        alert('กรุณาเลือกข้อมูลสภาพเครื่องให้ครบก่อนประเมินราคา');
                        return;
                    }

                    if (!icloudNoticeAck || !icloudNoticeAck.checked) {
                        event.preventDefault();
                        alert(
                            'กรุณาติ๊กยืนยันว่าเครื่องไม่ติดล็อค iCloud และไม่ติดระบบค้นหาอุปกรณ์ก่อนประเมินราคา'
                        );
                    }
                });
            }

            window.addEventListener('resize', function() {
                const openedItem = getCurrentOpenItem();

                if (!openedItem) return;

                const content = getContent(openedItem);

                if (content) {
                    content.style.maxHeight = content.scrollHeight + 'px';
                }
            });

            updateSectionStatus();
            updateAllSelectedPreviews();
            updateVisibleSteps();
            openFirstUnansweredVisibleStep();
            updateCalculatedPrice();
        });
    </script>
@endsection
