@extends('layouts.app')

@section('title', 'เกี่ยวกับเรา | Cashkub')

@section('content')
    @php
        $about = $about ?? null;

        $getAboutImageUrl = function ($path, $fallback) {
            if (empty($path)) {
                return asset($fallback);
            }

            if (strpos($path, 'assets/') === 0) {
                return asset($path);
            }

            return asset('storage/' . $path);
        };

        $heroTitle = $about->hero_title ?? 'เกี่ยวกับเรา';
        $heroBg = $about->hero_background_color ?? '#DFF3EA';

        $heroBannerImage = $getAboutImageUrl(
            $about->hero_banner_image ?? null,
            'assets/media/hero/hero-silder-img1.png',
        );

        $aboutImage = $getAboutImageUrl($about->about_image ?? null, 'assets/media/image-02.png');

        $aboutSectionTitle = $about->about_section_title ?? 'เกี่ยวกับเรา';
        $aboutCompanyTitle = $about->about_company_title ?? 'FinFin Phone.com';
        $aboutDescription = $about->about_description ?? '';

        $whyChooseTitle = $about->why_choose_title ?? 'ทำไมถึงเลือกเรา';
        $whyChooseDescription =
            $about->why_choose_description ??
            'ขายโทรศัพท์ได้ง่ายๆ ราบรื่น ตั้งแต่การตรวจสอบสภาพโทรศัพท์ฟรี ไปจนถึงการบริการถึงบ้านที่สะดวกรวดเร็วที่สุด';
    @endphp

    {{-- HERO SECTION --}}
    <section id="hero-slider" class="relative overflow-hidden" style="background-color: {{ $heroBg }};">
        <div class="relative w-full">
            <div class="relative w-full h-[220px] sm:h-[320px] md:h-[420px] lg:h-[560px] xl:h-[680px]">
                <img src="{{ $heroBannerImage }}" alt="{{ $heroTitle }}"
                    class="absolute inset-0 w-full h-full object-cover object-center select-none pointer-events-none">
            </div>
        </div>
    </section>

    {{-- ABOUT SECTION --}}
    <section id="sell-estimate-section" class="container mx-auto px-4 sm:px-6" style="max-width: 1140px;">
        <div class="bg-white rounded-3xl shadow-lg p-6 sm:p-8 md:p-10 -mt-16 sm:-mt-20 md:-mt-[10rem] relative z-10">

            <h2
                class="leading-snug font-medium mb-6 text-[26px] md:text-[26px] lg:text-[38px] text-[#285F43] text-center sm:mb-8">
                {{ $aboutSectionTitle }}
            </h2>

            <div class="p-6">
                <img src="{{ $aboutImage }}" alt="{{ $aboutCompanyTitle }}" class="mx-auto mb-4 rounded-2xl">

                <h3 class="text-xl font-semibold mb-2">
                    {{ $aboutCompanyTitle }}
                </h3>

                @if (!empty($aboutDescription))
                    <p class="hidden sm:block text-sm text-gray-600 text-left leading-8 whitespace-pre-line">
                        {{ $aboutDescription }}
                    </p>
                @else
                    <p class="hidden sm:block text-sm text-gray-600 text-left leading-8">
                        ยังไม่มีรายละเอียดเกี่ยวกับบริษัท
                    </p>
                @endif
            </div>
        </div>
    </section>

    {{-- WHY US --}}
    <section class="bg-white py-12">
        <h2 class="font-medium mb-6 text-[26px] md:text-[26px] lg:text-[38px] text-[#285F43] text-center">
            {{ $whyChooseTitle }}
        </h2>

        @if (!empty($whyChooseDescription))
            <p class="text-center text-[#285F43] mb-12 text-xl font-light p-3">
                {{ $whyChooseDescription }}
            </p>
        @endif

        <div class="container mx-auto grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8 text-center px-6"
            style="max-width: 1140px;">
            {{-- ITEM 1 --}}
            <div class="flex flex-col items-center">
                <div class="whyus-circle mb-3">
                    <svg class="whyus-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M20 7L10 17l-5-5" stroke="currentColor" stroke-width="2.8" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>

                <p class="text-[#285F43] font-medium">
                    {{ $about->feature_1_title ?? 'ขั้นตอนง่าย' }}
                </p>

                @if (!empty($about->feature_1_description))
                    <p class="mt-2 text-[#6B7280] text-sm leading-6 max-w-[230px]">
                        {{ $about->feature_1_description }}
                    </p>
                @endif
            </div>

            {{-- ITEM 2 --}}
            <div class="flex flex-col items-center">
                <div class="whyus-circle mb-3">
                    <svg class="whyus-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M12 2l7 4v6c0 5-3.5 9.4-7 10-3.5-.6-7-5-7-10V6l7-4z" stroke="currentColor"
                            stroke-width="2.2" stroke-linejoin="round" />
                        <path d="M9.5 12.2l1.7 1.7 3.6-4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>

                <p class="text-[#285F43] font-medium">
                    {{ $about->feature_2_title ?? 'เชื่อถือได้และปลอดภัย' }}
                </p>

                @if (!empty($about->feature_2_description))
                    <p class="mt-2 text-[#6B7280] text-sm leading-6 max-w-[230px]">
                        {{ $about->feature_2_description }}
                    </p>
                @endif
            </div>

            {{-- ITEM 3 --}}
            <div class="flex flex-col items-center">
                <div class="whyus-circle mb-3">
                    <svg class="whyus-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path
                            d="M12 21s-7-4.6-9.2-9C1 8.4 3.2 6 6 6c1.7 0 3.1.9 4 2.1C10.9 6.9 12.3 6 14 6c2.8 0 5 2.4 3.2 6-2.2 4.4-9.2 9-9.2 9z"
                            stroke="currentColor" stroke-width="2.2" stroke-linejoin="round" />
                    </svg>
                </div>

                <p class="text-[#285F43] font-medium">
                    {{ $about->feature_3_title ?? 'ราคาดีที่สุดสำหรับคุณ' }}
                </p>

                @if (!empty($about->feature_3_description))
                    <p class="mt-2 text-[#6B7280] text-sm leading-6 max-w-[230px]">
                        {{ $about->feature_3_description }}
                    </p>
                @endif
            </div>

            {{-- ITEM 4 --}}
            <div class="flex flex-col items-center">
                <div class="whyus-circle mb-3">
                    <svg class="whyus-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M7 2h10v20l-2-1-2 1-2-1-2 1-2-1-2 1V2z" stroke="currentColor" stroke-width="2.2"
                            stroke-linejoin="round" />
                        <path d="M9 7h6M9 11h6M9 15h4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" />
                        <path d="M18.5 10.5v4.2" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" />
                        <path d="M17.2 11.8h2.6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" />
                    </svg>
                </div>

                <p class="text-[#285F43] font-medium">
                    {{ $about->feature_4_title ?? 'ชำระเงินด่วน' }}
                </p>

                @if (!empty($about->feature_4_description))
                    <p class="mt-2 text-[#6B7280] text-sm leading-6 max-w-[230px]">
                        {{ $about->feature_4_description }}
                    </p>
                @endif
            </div>
        </div>
    </section>
@endsection
