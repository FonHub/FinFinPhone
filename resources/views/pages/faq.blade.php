@extends('layouts.app')

@section('title', 'คำถามที่พบบ่อย | Cashkub')

@section('content')
{{-- HERO SECTION --}}
{{-- HERO SECTION --}}
@php
$banner = $banner ?? collect();
@endphp

@if ($banner->count() > 0)
<section id="hero-slider" class="relative overflow-hidden bg-[#DFF3EA]">
    <div class="relative w-full">
        <div class="overflow-hidden">
            <div id="hero-slider-track"
                class="flex transition-transform duration-500 ease-in-out will-change-transform">
                @foreach ($banner as $index => $slide)
                <div class="hero-slide min-w-full shrink-0">
                    <div class="relative w-full h-[220px] sm:h-[320px] md:h-[420px] lg:h-[560px] xl:h-[680px]">
                        <img src="{{ asset('storage/' . $slide->desktop_image) }}"
                            alt="FAQ Banner {{ $index + 1 }}"
                            class="hidden sm:block absolute inset-0 w-full h-full object-cover object-center select-none pointer-events-none">

                        <img src="{{ asset('storage/' . $slide->mobile_image) }}"
                            alt="FAQ Banner Mobile {{ $index + 1 }}"
                            class="block sm:hidden absolute inset-0 w-full h-full object-cover object-center select-none pointer-events-none">
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        @if ($banner->count() > 1)
        <button type="button" id="hero-prev" aria-label="Previous slide"
            class="hidden md:flex absolute left-4 lg:left-6 top-1/2 -translate-y-1/2 z-20 items-center justify-center w-12 h-12 rounded-full bg-white/85 hover:bg-white shadow-md transition">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M15 18l-6-6 6-6" stroke="#285F43" stroke-width="2.5" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </button>

        <button type="button" id="hero-next" aria-label="Next slide"
            class="hidden md:flex absolute right-4 lg:right-6 top-1/2 -translate-y-1/2 z-20 items-center justify-center w-12 h-12 rounded-full bg-white/85 hover:bg-white shadow-md transition">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M9 6l6 6-6 6" stroke="#285F43" stroke-width="2.5" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </button>

        <div id="hero-dots"
            class="absolute bottom-4 md:bottom-6 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2">
            @foreach ($banner as $index => $slide)
            <button type="button"
                class="hero-dot {{ $index === 0 ? 'is-active' : '' }} transition-all duration-300 rounded-full {{ $index === 0 ? 'w-8 h-2.5 bg-white' : 'w-2.5 h-2.5 bg-white/50' }}"
                data-index="{{ $index }}" aria-label="Go to slide {{ $index + 1 }}">
            </button>
            @endforeach
        </div>
        @endif
    </div>
</section>

<script>
    (function() {
        const slider = document.getElementById('hero-slider');
        const track = document.getElementById('hero-slider-track');
        const prevBtn = document.getElementById('hero-prev');
        const nextBtn = document.getElementById('hero-next');
        const dots = Array.from(document.querySelectorAll('#hero-dots .hero-dot'));

        if (!slider || !track) return;

        const slides = Array.from(track.querySelectorAll('.hero-slide'));
        const total = slides.length;

        if (total <= 1) return;

        let current = 0;
        let autoplay = null;
        const AUTOPLAY_MS = 4500;

        let startX = 0;
        let currentX = 0;
        let isDragging = false;

        function updateDots() {
            dots.forEach((dot, i) => {
                const active = i === current;
                dot.className = active ?
                    'hero-dot is-active transition-all duration-300 rounded-full w-8 h-2.5 bg-white' :
                    'hero-dot transition-all duration-300 rounded-full w-2.5 h-2.5 bg-white/50';
            });
        }

        function updateSlider(index) {
            current = (index + total) % total;
            track.style.transform = `translateX(-${current * 100}%)`;
            updateDots();
        }

        function nextSlide() {
            updateSlider(current + 1);
        }

        function prevSlide() {
            updateSlider(current - 1);
        }

        function startAutoplay() {
            stopAutoplay();
            autoplay = setInterval(nextSlide, AUTOPLAY_MS);
        }

        function stopAutoplay() {
            if (autoplay) {
                clearInterval(autoplay);
                autoplay = null;
            }
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function() {
                nextSlide();
                startAutoplay();
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', function() {
                prevSlide();
                startAutoplay();
            });
        }

        dots.forEach((dot) => {
            dot.addEventListener('click', function() {
                const index = parseInt(this.dataset.index || '0', 10);
                updateSlider(index);
                startAutoplay();
            });
        });

        slider.addEventListener('mouseenter', stopAutoplay);
        slider.addEventListener('mouseleave', startAutoplay);

        slider.addEventListener('touchstart', function(e) {
            if (!e.touches || !e.touches.length) return;

            isDragging = true;
            startX = e.touches[0].clientX;
            currentX = startX;
            stopAutoplay();
        }, {
            passive: true
        });

        slider.addEventListener('touchmove', function(e) {
            if (!isDragging || !e.touches || !e.touches.length) return;

            currentX = e.touches[0].clientX;
        }, {
            passive: true
        });

        slider.addEventListener('touchend', function() {
            if (!isDragging) return;

            isDragging = false;

            const diff = currentX - startX;

            if (Math.abs(diff) > 50) {
                if (diff < 0) {
                    nextSlide();
                } else {
                    prevSlide();
                }
            }

            startAutoplay();
        }, {
            passive: true
        });

        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                stopAutoplay();
            } else {
                startAutoplay();
            }
        });

        updateSlider(0);
        startAutoplay();
    })();
</script>
@else
<section class="relative overflow-hidden bg-[#DFF3EA]">
    <div
        class="relative w-full h-[220px] sm:h-[320px] md:h-[420px] lg:h-[560px] xl:h-[680px] flex items-center justify-center">
        <div class="text-center px-4">
            <h1 class="text-[#285F43] text-[32px] sm:text-[44px] lg:text-[56px] font-bold">
                คำถามที่พบบ่อย
            </h1>
            <p class="mt-3 text-[#5B6B63] text-[16px] sm:text-[18px]">
                รวมคำถามและคำตอบเกี่ยวกับการขายสินค้า
            </p>
        </div>
    </div>
</section>
@endif

<section id="sell-estimate-section" class="container mx-auto px-4 sm:px-6" style="max-width: 1140px;">
    <div class="bg-white rounded-3xl shadow-lg p-6 sm:p-8 md:p-10 -mt-16 sm:-mt-20 md:-mt-[10rem] relative z-10">
        <div class="max-w-[1080px] mx-auto">
            <h2 class="leading-tight font-medium text-[26px] md:text-[30px] lg:text-[42px] text-[#285F43] text-center">
                คำถามที่พบบ่อย
            </h2>

            <p
                class="text-center text-[#5B6B63] mt-3 mb-8 sm:mb-10 text-[15px] sm:text-[16px] md:text-[18px] leading-relaxed">
                เลือกประเภทสินค้า แล้วดูคำถามที่พบบ่อยตามหมวดหมู่
            </p>

            @if ($faqCategories->count() > 0)
            <div class="mb-8 overflow-x-auto">
                <div class="faq-tabs flex items-center gap-3 min-w-max sm:min-w-0 sm:flex-wrap sm:justify-center">
                    @foreach ($faqCategories as $index => $category)
                    <button type="button"
                        class="faq-tab-btn inline-flex items-center justify-center rounded-full border px-5 h-12 text-[14px] sm:text-[15px] font-semibold transition
                                    {{ $index === 0 ? 'border-[#285F43] bg-[#285F43] text-white shadow-md' : 'border-[#D9E6DE] bg-white text-[#285F43] hover:bg-[#F3FAF6]' }}"
                        data-target="faq-category-{{ $category->id }}">
                        {{ $category->category_name }}
                    </button>
                    @endforeach
                </div>
            </div>

            <div class="faq-panels">
                @foreach ($faqCategories as $index => $category)
                <div id="faq-category-{{ $category->id }}"
                    class="faq-panel {{ $index === 0 ? '' : 'hidden' }}">
                    <div class="mb-5 flex items-center justify-between gap-4 flex-col sm:flex-row">
                        <div>
                            <h3 class="text-[#285F43] font-bold text-[22px] sm:text-[26px] leading-tight">
                                {{ $category->category_name }}
                            </h3>

                            <p class="mt-2 text-[#6B7280] text-[14px] sm:text-[15px]">
                                ทั้งหมด {{ number_format($category->questions->count()) }} คำถาม
                            </p>
                        </div>
                    </div>

                    <div class="divide-y divide-[#D9E6DE] border-t border-b border-[#D9E6DE]">
                        @foreach ($category->questions as $faq)
                        <details class="group py-1">
                            <summary
                                class="list-none cursor-pointer select-none flex items-center justify-between gap-4 py-5 sm:py-6">
                                <span
                                    class="text-left text-[#1F2937] font-medium text-[17px] sm:text-[19px] md:text-[22px] leading-[1.45] pr-2">
                                    {{ $faq->question }}
                                </span>

                                <span class="shrink-0 relative w-6 h-6 sm:w-7 sm:h-7">
                                    <span
                                        class="absolute left-1/2 top-1/2 w-4 sm:w-5 h-[1.8px] bg-[#285F43] rounded-full -translate-x-1/2 -translate-y-1/2"></span>
                                    <span
                                        class="absolute left-1/2 top-1/2 w-[1.8px] h-4 sm:h-5 bg-[#285F43] rounded-full -translate-x-1/2 -translate-y-1/2 transition-transform duration-300 group-open:scale-y-0"></span>
                                </span>
                            </summary>

                            <div class="pb-5 sm:pb-6 pr-8 sm:pr-12">
                                @if (($faq->question_type ?? 'general') === 'model_specific')
                                @if (!empty($faq->model_answers) && $faq->model_answers->count() > 0)
                                <div class="space-y-3">
                                    @foreach ($faq->model_answers as $modelAnswer)
                                    <div
                                        class="rounded-[18px] border border-[#E5ECE8] bg-[#F9FBFA] px-4 py-4">
                                        <div
                                            class="text-[#285F43] text-[15px] sm:text-[16px] font-bold leading-7">
                                            {{ $modelAnswer->model_title }}
                                        </div>

                                        <p
                                            class="mt-1 text-[#4B5563] text-[15px] sm:text-[16px] md:text-[17px] leading-8">
                                            {{ $modelAnswer->answer }}
                                        </p>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <p
                                    class="text-[#9CA3AF] text-[15px] sm:text-[16px] md:text-[17px] leading-8">
                                    ยังไม่มีคำตอบสำหรับโมเดลสินค้า
                                </p>
                                @endif
                                @else
                                <p
                                    class="text-[#4B5563] text-[15px] sm:text-[16px] md:text-[17px] leading-8">
                                    {{ $faq->general_answer }}
                                </p>
                                @endif
                            </div>
                        </details>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="rounded-[24px] border border-[#E5ECE8] bg-[#F9FBFA] px-6 py-12 text-center">
                <div class="text-[#285F43] text-[22px] font-bold">
                    ยังไม่มีคำถามที่พบบ่อย
                </div>
                <p class="mt-3 text-[#6B7280] text-[15px] leading-7">
                    เมื่อมีการเพิ่มคำถามในระบบ รายการจะแสดงที่หน้านี้
                </p>
            </div>
            @endif
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = Array.from(document.querySelectorAll('.faq-tab-btn'));
        const panels = Array.from(document.querySelectorAll('.faq-panel'));

        function activateTab(targetId) {
            tabButtons.forEach(function(button) {
                const isActive = button.dataset.target === targetId;

                if (isActive) {
                    button.classList.remove('border-[#D9E6DE]', 'bg-white', 'text-[#285F43]',
                        'hover:bg-[#F3FAF6]');
                    button.classList.add('border-[#285F43]', 'bg-[#285F43]', 'text-white', 'shadow-md');
                } else {
                    button.classList.remove('border-[#285F43]', 'bg-[#285F43]', 'text-white',
                        'shadow-md');
                    button.classList.add('border-[#D9E6DE]', 'bg-white', 'text-[#285F43]',
                        'hover:bg-[#F3FAF6]');
                }
            });

            panels.forEach(function(panel) {
                panel.classList.toggle('hidden', panel.id !== targetId);
            });
        }

        tabButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                activateTab(this.dataset.target);
            });
        });

        if (tabButtons.length > 0) {
            activateTab(tabButtons[0].dataset.target);
        }
    });
</script>

{{-- WHY US --}}
<section class="py-32">
    <h2 class="font-medium mb-6 text-[26px] md:text-[26px] lg:text-[38px] text-[#1d1d1f] text-center">
        ทำไมถึงเลือกเรา
    </h2>

    <p class="text-center text-[#1d1d1f] mb-12 text-xl font-light p-3">
        ขายโทรศัพท์ได้ง่ายๆ ราบรื่น ตั้งแต่การตรวจสอบสภาพโทรศัพท์ฟรี ไปจนถึงการบริการถึงบ้านที่สะดวกรวดเร็วที่สุด
    </p>

    <div class="container mx-auto grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8 text-center px-6" style="max-width: 1140px;">
        <div class="flex flex-col items-center">
            <div class="whyus-circle mb-3">
                <svg class="whyus-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M20 7L10 17l-5-5" stroke="currentColor" stroke-width="2.8" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </div>
            <p class="text-[#1d1d1f] font-medium">ขั้นตอนง่าย</p>
        </div>

        <div class="flex flex-col items-center">
            <div class="whyus-circle mb-3">
                <svg class="whyus-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M12 2l7 4v6c0 5-3.5 9.4-7 10-3.5-.6-7-5-7-10V6l7-4z" stroke="currentColor"
                        stroke-width="2.2" stroke-linejoin="round" />
                    <path d="M9.5 12.2l1.7 1.7 3.6-4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </div>
            <p class="text-[#1d1d1f] font-medium">เชื่อถือได้และปลอดภัย</p>
        </div>

        <div class="flex flex-col items-center">
            <div class="whyus-circle mb-3">
                <svg class="whyus-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path
                        d="M12 21s-7-4.6-9.2-9C1 8.4 3.2 6 6 6c1.7 0 3.1.9 4 2.1C10.9 6.9 12.3 6 14 6c2.8 0 5 2.4 3.2 6-2.2 4.4-9.2 9-9.2 9z"
                        stroke="currentColor" stroke-width="2.2" stroke-linejoin="round" />
                </svg>
            </div>
            <p class="text-[#1d1d1f] font-medium">ราคาดีที่สุดสำหรับคุณ</p>
        </div>

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
            <p class="text-[#1d1d1f] font-medium">ชำระเงินด่วน</p>
        </div>
    </div>
</section>
@endsection