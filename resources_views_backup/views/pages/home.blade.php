@extends('layouts.app')

@section('title', 'Fin Fin Phone')

@section('content')

{{-- Header --}}


{{-- HERO SECTION --}}
<section class="relative overflow-hidden bg-gradient-to-r from-[#DFF3EA] via-[#EAF7F1] to-[#CDEEDC]">
    {{-- Background glow / blur circles --}}
    <div class="absolute inset-0 pointer-events-none z-0">
        {{-- วงกลมซ้าย: เส้นคม + glow --}}
        <div class="absolute
            left-[-70px] bottom-[38px]
            sm:left-[-50px] sm:bottom-[46px]
            md:left-[40px] md:bottom-[54px]
            lg:left-[72px] lg:bottom-[62px]
            w-[180px] h-[180px] sm:w-[220px] sm:h-[220px] md:w-[260px] md:h-[260px] lg:w-[300px] lg:h-[300px]">

            {{-- glow ด้านใน/ด้านหลัง --}}
            <div class="absolute inset-0 rounded-full bg-[#219C6B]/25 blur-2xl"></div>

            {{-- glow ขาวช่วยให้ฟุ้งนุ่ม --}}
            <div class="absolute inset-[8%] rounded-full bg-white/25 blur-3xl"></div>


        </div>

        {{-- วงกลมขวา: เส้นคม + glow --}}
        <div class="absolute
            right-[-55px] top-[18px]
            sm:right-[-35px] sm:top-[22px]
            md:right-[36px] md:top-[28px]
            lg:right-[78px] lg:top-[34px]
            w-[180px] h-[180px] sm:w-[220px] sm:h-[220px] md:w-[250px] md:h-[250px] lg:w-[300px] lg:h-[300px]">

            {{-- glow ด้านใน/ด้านหลัง --}}
            <div class="absolute inset-0 rounded-full bg-[#219C6B]/20 blur-2xl"></div>

            {{-- glow ขาว --}}
            <div class="absolute inset-[8%] rounded-full bg-white/20 blur-3xl"></div>


        </div>
    </div>

    <div class="relative z-10 max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8
                min-h-[260px] sm:min-h-[340px] md:min-h-[430px] lg:min-h-[520px]">

        {{-- รูปซ้าย --}}
        <img
            src="{{ asset('assets/media/hero/hero-phone-left.png') }}"
            alt="โทรศัพท์มือถือ"
            class="pointer-events-none select-none absolute z-10
                   left-[-25px] bottom-[-10px]
                   w-[150px] sm:w-[210px] md:w-[320px] lg:w-[420px]
                   opacity-90 sm:opacity-95">

        {{-- รูปขวา --}}
        <img
            src="{{ asset('assets/media/hero/hero-phone-right.png') }}"
            alt="โทรศัพท์มือถือ"
            class="pointer-events-none select-none absolute z-10
                   right-[-20px] bottom-[-5px]
                   w-[120px] sm:w-[170px] md:w-[240px] lg:w-[320px]
                   opacity-85 sm:opacity-95">

        {{-- ข้อความกลาง --}}
        <div class="relative z-20 h-full flex items-center justify-center text-center pt-8 pb-6 sm:pt-20 sm:pb-12 md:pt-24 md:pb-16">
            <div class="max-w-[92%] sm:max-w-[760px] pt-4 sm:pt-16">
                <h1 class="text-[#1E5B3A] font-extrabold leading-[1.08]
                           text-[30px] sm:text-[42px] md:text-[56px] lg:text-[68px]">
                    รับซื้อ โทรศัพท์มือสอง
                    <br class="hidden sm:block">
                    <span class="block sm:inline">ทุกรุ่น ทุกยี่ห้อ</span>
                </h1>

                <p class="mt-5 sm:mt-4 md:mt-5 pt-5
                          text-[#111827] font-medium leading-snug
                          text-[16px] sm:text-[22px] md:text-[30px] lg:text-[34px]">
                    การันตีให้ราคาสูง ซื่อตรงต่อลูกค้า
                </p>
            </div>
        </div>
    </div>
</section>


{{-- CATEGORY + ESTIMATE --}}
{{-- CATEGORY --}}
@php
$categories = [
[
'key' => 'smartphone',
'name' => 'Smartphone',
'label_th' => 'iPhone',
'icon_default' => 'assets/media/icons/checked.gif',
'icon_active' => 'assets/media/icons/digital-creative-01.gif',
],
[
'key' => 'tablet',
'name' => 'Tablet',
'label_th' => 'Tablet',
'icon_default' => 'assets/media/icons/digital-creative.gif',
'icon_active' => 'assets/media/icons/checked-01.gif',
],
[
'key' => 'smartwatch',
'name' => 'Smartwatch',
'label_th' => 'Smartwatch',
'icon_default' => 'assets/media/icons/smartwatch.gif',
'icon_active' => 'assets/media/icons/smartwatch-01.gif',
],
[
'key' => 'macbook',
'name' => 'MacBook',
'label_th' => 'MacBook',
'icon_default' => 'assets/media/icons/slow.gif',
'icon_active' => 'assets/media/icons/slow-01.gif',
],
[
'key' => 'accessories',
'name' => 'Accessories',
'label_th' => 'Accessories',
'icon_default' => 'assets/media/icons/vr-control.gif',
'icon_active' => 'assets/media/icons/vr-control-01.gif',
],
];

$activeKey = 'smartphone'; // default active
@endphp
<section class="container mx-auto px-4 sm:px-6">
    <div class="bg-white rounded-3xl shadow-lg p-6 sm:p-8 md:p-10 -mt-16 sm:-mt-20 md:-mt-24 relative z-10">

        <h2 class="text-center text-[#285F43] leading-snug font-bold
                   text-[16px] sm:text-[22px] md:text-[30px] lg:text-[34px] mb-6 sm:mb-8">
            เลือกที่ต้องการขาย
        </h2>



        {{-- CATEGORY (SLIDER) --}}
        <div class="relative mb-8 sm:mb-10">
            {{-- viewport --}}
            <div class="overflow-hidden " id="cat-viewport">
                {{-- track --}}
                <div class="flex flex-nowrap transition-transform duration-300 ease-out my-8 mb-10" id="cat-track">
                    @foreach ($categories as $cat)
                    @php $isActive = $cat['key'] === $activeKey; @endphp

                    {{-- slide --}}
                    <div class="cat-slide px-2">
                        <button
                            type="button"
                            class="sell-category-card {{ $isActive ? 'is-active' : '' }}
                               relative rounded-2xl text-center cursor-pointer
                               h-[140px] sm:h-[160px] md:h-[240px]
                               px-3 py-4 sm:px-4 sm:py-5 md:px-4 md:py-6
                               flex flex-col items-center justify-center
                               focus:outline-none focus:ring-2 focus:ring-[#285F43]/30"
                            data-key="{{ $cat['key'] }}"
                            data-option="{{ $cat['label_th'] }}"
                            aria-pressed="{{ $isActive ? 'true' : 'false' }}">

                            {{-- ICON --}}
                            <div class="icon-wrap relative mb-3 sm:mb-4 w-10 h-10 sm:w-12 sm:h-12 md:w-14 md:h-14">
                                <img
                                    src="{{ asset($cat['icon_default']) }}"
                                    alt="{{ $cat['name'] }}"
                                    class="icon-default absolute inset-0 m-auto w-full h-full object-contain">

                                <img
                                    src="{{ asset($cat['icon_active']) }}"
                                    alt="{{ $cat['name'] }} active"
                                    class="icon-active absolute inset-0 m-auto w-full h-full object-contain">
                            </div>

                            {{-- TEXT --}}
                            <p class="card-text leading-tight font-semibold text-[14px] sm:text-[15px] md:text-[16px]">
                                {{ $cat['name'] }}
                            </p>
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- dots (เหมือนรูป) --}}
            <div class="flex justify-center gap-2 mt-3" id="cat-dots"></div>
        </div>



        <div id="estimate-form-wrap" class="relative z-50 transform-none">

            {{-- ESTIMATE FORM --}}
            <div class="w-3/4 mx-auto flex flex-col gap-3 sm:w-auto sm:flex-row sm:flex-wrap sm:gap-4 sm:justify-center sm:items-center">
                {{-- hidden input เผื่อ submit form --}}
                <input type="hidden" id="selectedCategoryKey" name="selected_category_key" value="{{ $activeKey }}">
                <div class="w-[260px]">
                    <select id="categorySelect" name="category_type"
                        class="border border-[#D1D5DB] rounded-full px-4 py-2 w-40 sm:w-full bg-white w-[260px] focus:outline-none focus:ring-2 focus:ring-[#285F43] focus:border-[#285F43]">
                        @foreach ($categories as $cat)
                        <option value="{{ $cat['key'] }}" {{ $cat['key'] === $activeKey ? 'selected' : '' }}>
                            {{ $cat['label_th'] }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="w-[260px]">
                    <select class="border border-[#D1D5DB] rounded-full px-4 py-2 w-40 sm:w-full bg-white w-[260px] focus:outline-none focus:ring-2 focus:ring-[#285F43] focus:border-[#285F43]">
                        <option>เลือกรุ่น</option>
                    </select>
                </div>
                <div class="w-[260px]">
                    <select class="border border-[#D1D5DB] rounded-full px-4 py-2 w-40 sm:w-full bg-white w-[260px] focus:outline-none focus:ring-2 focus:ring-[#285F43] focus:border-[#285F43]">
                        <option>เลือกความจุ</option>
                    </select>
                </div>

            </div>
            <div class="m-auto text-center my-5">
                <button type="button"
                    class="bg-[#3AAA5B] hover:bg-[#2E944C] text-white px-8 py-2 rounded-full shadow-md transition w-[260px]">
                    ประเมินราคา
                </button>
            </div>

        </div>


    </div>
</section>

<script>
    (function() {
        const viewport = document.getElementById('cat-viewport');
        const track = document.getElementById('cat-track');
        const dotsWrap = document.getElementById('cat-dots');

        if (!viewport || !track || !dotsWrap) return;

        const slides = Array.from(track.querySelectorAll('.cat-slide'));
        let index = 0;
        let perPage = 5;

        function calcPerPage() {
            const w = window.innerWidth;
            if (w >= 768) return 5; // md+: 5 (คง PC เดิม)
            return 3; // mobile: 3 (เหมือนรูป)
        }

        function pagesCount() {
            return Math.max(1, Math.ceil(slides.length / perPage));
        }

        function clampIndex(i) {
            const max = pagesCount() - 1;
            return Math.min(Math.max(i, 0), max);
        }

        function pageWidth() {
            return viewport.clientWidth;
        }

        function renderDots() {
            dotsWrap.innerHTML = '';
            const total = pagesCount();

            for (let i = 0; i < total; i++) {
                const dot = document.createElement('button');
                dot.type = 'button';
                dot.className = 'w-2 h-2 rounded-full ' + (i === index ? 'bg-gray-900' : 'bg-gray-300');
                dot.setAttribute('aria-label', 'Go to page ' + (i + 1));
                dot.addEventListener('click', () => go(i));
                dotsWrap.appendChild(dot);
            }
        }

        function updateDotsActive() {
            Array.from(dotsWrap.children).forEach((d, di) => {
                d.className = 'w-2 h-2 rounded-full ' + (di === index ? 'bg-gray-900' : 'bg-gray-300');
            });
        }

        function go(i) {
            index = clampIndex(i);
            const x = index * pageWidth();
            track.style.transform = `translateX(${-x}px)`;
            updateDotsActive();
        }

        function refresh() {
            perPage = calcPerPage();
            index = clampIndex(index);
            renderDots();
            go(index);
        }

        // swipe (มือถือ)
        let startX = 0;
        let dragging = false;

        viewport.addEventListener('touchstart', (e) => {
            dragging = true;
            startX = e.touches[0].clientX;
        }, {
            passive: true
        });

        viewport.addEventListener('touchend', (e) => {
            if (!dragging) return;
            dragging = false;

            const endX = (e.changedTouches && e.changedTouches[0]) ? e.changedTouches[0].clientX : startX;
            const diff = endX - startX;

            if (Math.abs(diff) < 30) return;

            if (diff < 0) go(index + 1); // swipe left -> next
            else go(index - 1); // swipe right -> prev
        }, {
            passive: true
        });

        window.addEventListener('resize', refresh);
        refresh();
    })();
</script>

{{-- SELL METHOD (APPLE-LIKE TABS + SPEC) --}}
<section class="py-32 text-center">
    <h2 class="font-medium mb-6" style="color:#285F43; font-size:35px;">
        สามารถขายได้ 2 วิธีง่ายๆ
    </h2>

    {{-- Segmented Tabs --}}
    <div class="flex justify-center">
        <div
            class="relative inline-flex items-center rounded-full bg-white p-1 pr-[10px] shadow-sm border border-gray-200"
            id="sell-method-seg"
            role="tablist"
            aria-label="Sell method">
            {{-- Sliding indicator (ตัวพื้นหลังเลื่อนตามปุ่ม) --}}
            <div
                id="sell-method-indicator"
                class="absolute top-1 bottom-1 left-1 rounded-full shadow transition-transform duration-300 ease-out"
                style="background:#285F43; width:0px; transform:translateX(0px);"
                aria-hidden="true"></div>

            <button
                type="button"
                class="sell-seg-btn relative z-10 rounded-full transition-colors duration-200"
                data-tab="pickup"
                role="tab"
                aria-selected="true"
                style="width:160px; height:50px; font-size:20px;">
                รับถึงที่
            </button>

            <button
                type="button"
                class="sell-seg-btn relative z-10 rounded-full transition-colors duration-200"
                data-tab="parcel"
                role="tab"
                aria-selected="false"
                style="width:160px; height:50px; font-size:20px;">
                ส่งพัสดุ
            </button>
        </div>
    </div>

    {{-- Panels --}}
    <div class="mt-12 container mx-auto px-6">
        {{-- Panel: Pickup --}}
        <div class="sell-panel" data-panel="pickup" role="tabpanel">
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl p-6 shadow border border-gray-100">
                    <img src="{{ asset('assets/media/image-02.png') }}" alt="Step 1" class="mx-auto mb-4  rounded-2xl">
                    <div class="text-sm text-gray-500 mb-1">ขั้นตอนที่ 1</div>
                    <h3 class="text-xl font-semibold mb-2">ดูแลการแลกอุปกรณ์ของคุณ</h3>
                    <p class="hidden sm:block text-sm text-gray-600 text-left">
                        อันดับแรก รับการประเมินราคาแลกอุปกรณ์ของคุณจากเว็บไซต์พาร์ทเนอร์ของเรา จากนั้นทำตามการแจ้งเตือนเมื่อคุณต้องการ แลกอุปกรณ์ กับพาร์ทเนอร์ของเราให้เสร็จสมบูรณ์</p>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow border border-gray-100">
                    <img src="{{ asset('assets/media/image-0222.png') }}" alt="Step 2" class="mx-auto mb-4  rounded-2xl">
                    <div class="text-sm text-gray-500 mb-1">ขั้นตอนที่ 2</div>
                    <h3 class="text-xl font-semibold mb-2">เตรียมอุปกรณ์พร้อมแลก</h3>
                    <p class="hidden sm:block text-sm text-gray-600 text-left">
                        อันดับแรก พาร์ทเนอร์การแลกอุปกรณ์ของเราจะส่งอีเมลยืนยันการแลก อุปกรณ์ให้คุณพร้อมคำแนะนำต่างๆ ในการสำรองข้อมูลส่วนตัวและเตรียมอุปกรณ์ของคุณให้พร้อม</p>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow border border-gray-100">
                    <img src="{{ asset('assets/media/image-20231.png') }}" alt="Step 3" class="mx-auto mb-4  rounded-2xl">
                    <div class="text-sm text-gray-500 mb-1">ขั้นตอนที่ 3</div>
                    <h3 class="text-xl font-semibold mb-2">ส่งพนักงานไปบริษัท</h3>
                    <p class="hidden sm:block text-sm text-gray-600 text-left">
                        อีเมลแจ้งยืนยันการแลกอุปกรณ์ของคุณ จะแจ้งรายละเอียดต่างๆ ในการส่งคืนอุปกรณ์อีกด้วย</p>
                </div>
            </div>
        </div>

        {{-- Panel: Parcel --}}
        <div class="sell-panel hidden" data-panel="parcel" role="tabpanel">
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl p-6 shadow border border-gray-100">
                    <img src="{{ asset('assets/media/image-20231.png') }}" alt="Step 1" class="mx-auto mb-4  rounded-2xl">
                    <div class="text-sm text-gray-500 mb-1">ขั้นตอนที่ 1</div>
                    <h3 class="text-xl font-semibold mb-2">ดูแลการแลกอุปกรณ์ของคุณ</h3>
                    <p class="hidden sm:block text-sm text-gray-600 text-left">
                        อันดับแรก รับการประเมินราคาแลกอุปกรณ์ของคุณจากเว็บไซต์พาร์ทเนอร์ของเรา จากนั้นทำตามการแจ้งเตือนเมื่อคุณต้องการ แลกอุปกรณ์ กับพาร์ทเนอร์ของเราให้เสร็จสมบูรณ์</p>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow border border-gray-100">
                    <img src="{{ asset('assets/media/image-0222.png') }}" alt="Step 2" class="mx-auto mb-4  rounded-2xl">
                    <div class="text-sm text-gray-500 mb-1">ขั้นตอนที่ 2</div>
                    <h3 class="text-xl font-semibold mb-2">เตรียมอุปกรณ์พร้อมแลก</h3>
                    <p class="hidden sm:block text-sm text-gray-600 text-left">
                        อันดับแรก พาร์ทเนอร์การแลกอุปกรณ์ของเราจะส่งอีเมลยืนยันการแลก อุปกรณ์ให้คุณพร้อมคำแนะนำต่างๆ ในการสำรองข้อมูลส่วนตัวและเตรียมอุปกรณ์ของคุณให้พร้อม</p>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow border border-gray-100">
                    <img src="{{ asset('assets/media/image-02.png') }}" alt="Step 3" class="mx-auto mb-4  rounded-2xl">
                    <div class="text-sm text-gray-500 mb-1">ขั้นตอนที่ 3</div>
                    <h3 class="text-xl font-semibold mb-2">ส่งพนักงานไปบริษัท</h3>
                    <p class="hidden sm:block text-sm text-gray-600 text-left">
                        อีเมลแจ้งยืนยันการแลกอุปกรณ์ของคุณ จะแจ้งรายละเอียดต่างๆ ในการส่งคืนอุปกรณ์อีกด้วย</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const seg = document.getElementById('sell-method-seg');
            if (!seg) return;

            const indicator = document.getElementById('sell-method-indicator');
            const btns = Array.from(seg.querySelectorAll('.sell-seg-btn'));
            const panels = Array.from(document.querySelectorAll('.sell-panel'));

            const GREEN = '#285F43';

            function animatePanelIn(panel) {
                panel.classList.remove('hidden');
                panel.style.opacity = '0';
                panel.style.transform = 'translateY(6px)';
                panel.style.transition = 'opacity 220ms ease, transform 220ms ease';
                requestAnimationFrame(() => {
                    panel.style.opacity = '1';
                    panel.style.transform = 'translateY(0px)';
                });
                setTimeout(() => {
                    panel.style.opacity = '';
                    panel.style.transform = '';
                    panel.style.transition = '';
                }, 260);
            }

            function setBtnStyle(btn, isActive) {
                if (isActive) {
                    // Active: bg green (via indicator behind), text white
                    btn.style.color = '#FFFFFF';
                    btn.style.background = 'transparent';
                } else {
                    // Inactive: bg white, text green
                    btn.style.color = GREEN;
                    // btn.style.background = '#FFFFFF';
                }
            }

            function setActive(tabName) {
                // Buttons
                btns.forEach((btn) => {
                    const isActive = btn.dataset.tab === tabName;
                    btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
                    setBtnStyle(btn, isActive);
                });

                // Panels
                panels.forEach((p) => {
                    const isTarget = p.dataset.panel === tabName;
                    if (isTarget) animatePanelIn(p);
                    else p.classList.add('hidden');
                });

                // Indicator position + width
                const activeBtn = btns.find(b => b.dataset.tab === tabName);
                if (!activeBtn) return;

                const segRect = seg.getBoundingClientRect();
                const btnRect = activeBtn.getBoundingClientRect();
                const left = btnRect.left - segRect.left;

                indicator.style.width = btnRect.width + 'px';
                indicator.style.transform = `translateX(${left}px)`;
                indicator.style.background = GREEN;
            }

            // Bind events
            btns.forEach((btn) => btn.addEventListener('click', () => setActive(btn.dataset.tab)));

            // Init + keep indicator accurate on resize
            setActive('pickup');
            window.addEventListener('resize', () => {
                const current = btns.find(b => b.getAttribute('aria-selected') === 'true')?.dataset.tab || 'pickup';
                setActive(current);
            });
        })();
    </script>
</section>

{{-- WHY US --}}
<section class="bg-white py-32">
    <h2 class="font-medium mb-6 text-center" style="color:#285F43; font-size:35px;">
        ทำไมถึงเลือกเรา
    </h2>

    <p class="text-center text-[#285F43] mb-12 text-xl font-light p-3">
        ขายโทรศัพท์ได้ง่ายๆ ราบรื่น ตั้งแต่การตรวจสอบสภาพโทรศัพท์ฟรี ไปจนถึงการบริการถึงบ้านที่สะดวกรวดเร็วที่สุด
    </p>

    <div class="container mx-auto grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8 text-center px-6">
        {{-- ITEM 1 --}}
        <div class="flex flex-col items-center">
            <div class="whyus-circle mb-3">
                {{-- ✅ Check icon (SVG) --}}
                <svg class="whyus-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M20 7L10 17l-5-5" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
            <p class="text-[#285F43] font-medium">ขั้นตอนง่าย</p>
        </div>

        {{-- ITEM 2 --}}
        <div class="flex flex-col items-center">
            <div class="whyus-circle mb-3">
                {{-- ✅ Shield icon (SVG) --}}
                <svg class="whyus-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M12 2l7 4v6c0 5-3.5 9.4-7 10-3.5-.6-7-5-7-10V6l7-4z"
                        stroke="currentColor" stroke-width="2.2" stroke-linejoin="round" />
                    <path d="M9.5 12.2l1.7 1.7 3.6-4"
                        stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
            <p class="text-[#285F43] font-medium">เชื่อถือได้และปลอดภัย</p>
        </div>

        {{-- ITEM 3 --}}
        <div class="flex flex-col items-center">
            <div class="whyus-circle mb-3">
                {{-- ✅ Heart icon (SVG) --}}
                <svg class="whyus-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M12 21s-7-4.6-9.2-9C1 8.4 3.2 6 6 6c1.7 0 3.1.9 4 2.1C10.9 6.9 12.3 6 14 6c2.8 0 5 2.4 3.2 6-2.2 4.4-9.2 9-9.2 9z"
                        stroke="currentColor" stroke-width="2.2" stroke-linejoin="round" />
                </svg>
            </div>
            <p class="text-[#285F43] font-medium">ราคาดีที่สุดสำหรับคุณ</p>
        </div>

        {{-- ITEM 4 --}}
        <div class="flex flex-col items-center">
            <div class="whyus-circle mb-3">
                {{-- ✅ Receipt / Quick pay icon (SVG) --}}
                <svg class="whyus-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M7 2h10v20l-2-1-2 1-2-1-2 1-2-1-2 1V2z"
                        stroke="currentColor" stroke-width="2.2" stroke-linejoin="round" />
                    <path d="M9 7h6M9 11h6M9 15h4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" />
                    <path d="M18.5 10.5v4.2" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" />
                    <path d="M17.2 11.8h2.6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" />
                </svg>
            </div>
            <p class="text-[#285F43] font-medium">ชำระเงินด่วน</p>
        </div>
    </div>


</section>

{{-- REVIEWS (CAROUSEL LIKE IMAGE) --}}
<section class="py-20 bg-gray-50">
    <h2 class="text-center font-medium mb-12" style="color:#285F43; font-size:35px;">
        รีวิวความประทับใจ
    </h2>

    <div class="relative container mx-auto px-6">
        {{-- Left arrow --}}
        <button type="button"
            class="review-nav review-prev hidden md:flex items-center justify-center absolute left-2 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white shadow border border-gray-200"
            aria-label="Previous">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                <path d="M15 18l-6-6 6-6" stroke="#285F43" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </button>

        {{-- Right arrow --}}
        <button type="button"
            class="review-nav review-next hidden md:flex items-center justify-center absolute right-2 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white shadow border border-gray-200"
            aria-label="Next">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                <path d="M9 6l6 6-6 6" stroke="#285F43" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </button>

        {{-- Viewport --}}
        <div class="overflow-hidden" id="review-viewport">
            {{-- Track --}}
            <div class="flex transition-transform duration-300 ease-out" id="review-track">
                @php
                // ตัวอย่าง data (คุณเปลี่ยนเป็นข้อมูลจริงได้)
                $reviews = [
                ['img' => 'assets/media/review/review-1.png', 'title' => 'พนักงาน บริการดีมาก', 'sub' => 'รวดเร็วทันใจ', 'by' => 'By 093xxx163', 'model' => 'ขายสินค้า iPhone 15 Pro', 'rate' => '5.0'],
                ['img' => 'assets/media/review/review-2.png', 'title' => 'พนักงาน บริการดีมาก', 'sub' => 'รวดเร็วทันใจ', 'by' => 'By 093xxx163', 'model' => 'ขายสินค้า iPhone 15 Pro', 'rate' => '5.0'],
                ['img' => 'assets/media/review/review-3.png', 'title' => 'พนักงาน บริการดีมาก', 'sub' => 'รวดเร็วทันใจ', 'by' => 'By 093xxx163', 'model' => 'ขายสินค้า iPhone 15 Pro', 'rate' => '5.0'],
                ['img' => 'assets/media/review/review-4.png', 'title' => 'พนักงาน บริการดีมาก', 'sub' => 'รวดเร็วทันใจ', 'by' => 'By 093xxx163', 'model' => 'ขายสินค้า iPhone 15 Pro', 'rate' => '5.0'],
                ['img' => 'assets/media/review/avatar.png', 'title' => 'พนักงาน บริการดีมาก', 'sub' => 'รวดเร็วทันใจ', 'by' => 'By 093xxx163', 'model' => 'ขายสินค้า iPhone 15 Pro', 'rate' => '5.0'],
                ['img' => 'assets/media/review/avatar.png', 'title' => 'พนักงาน บริการดีมาก', 'sub' => 'รวดเร็วทันใจ', 'by' => 'By 093xxx163', 'model' => 'ขายสินค้า iPhone 15 Pro', 'rate' => '5.0'],
                ];
                @endphp

                @foreach ($reviews as $idx => $r)
                <div class="review-slide basis-1/2 md:basis-1/2 lg:basis-1/4 shrink-0 px-2">
                    <div class="bg-white shadow-sm rounded-none">
                        {{-- รูป: โค้งเฉพาะด้านบน --}}
                        <div class="px-4 pt-4">
                            <img
                                src="{{ asset($r['img']) }}"
                                alt="Review {{ $idx+1 }}"
                                class="w-full object-cover rounded-t-2xl"
                                style="height:320px;">
                        </div>

                        <div class="px-4 pb-5 pt-3 text-left">
                            {{-- ดาว + คะแนน (เหมือนรูป) --}}
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-1">
                                    @for ($s=0; $s<5; $s++)
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="#F5B301" aria-hidden="true">
                                        <path d="M12 2l2.9 6.1 6.7.9-4.9 4.7 1.2 6.6L12 17.9 6.1 20.3l1.2-6.6L2.4 9l6.7-.9L12 2z" />
                                        </svg>
                                        @endfor
                                </div>
                                <div class="text-xl font-semibold text-gray-900">{{ $r['rate'] }}</div>
                            </div>

                            <div class="text-gray-900 font-semibold leading-tight">
                                {{ $r['title'] }}
                            </div>
                            <div class="text-gray-900 font-semibold leading-tight mb-2">
                                {{ $r['sub'] }}
                            </div>

                            <div class="text-sm text-gray-500">{{ $r['by'] }}</div>
                            <div class="text-sm text-gray-500">{{ $r['model'] }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Dots --}}
        <div class="flex justify-center gap-2 mt-8" id="review-dots"></div>
    </div>

   

    <script>
        (function() {
            const viewport = document.getElementById('review-viewport');
            const track = document.getElementById('review-track');
            const prev = document.querySelector('.review-prev');
            const next = document.querySelector('.review-next');
            const dotsWrap = document.getElementById('review-dots');

            if (!viewport || !track || !dotsWrap) return;

            const slides = Array.from(track.querySelectorAll('.review-slide'));
            let index = 0;
            let perPage = 4;

            // ====== AUTO PLAY SETTINGS ======
            const AUTO_PLAY = true;
            const AUTO_INTERVAL_MS = 3500; // ปรับความเร็วได้ (ms)
            let timer = null;
            let isHovering = false;

            function calcPerPage() {
                const w = window.innerWidth;
                if (w >= 1024) return 4;
                if (w >= 768) return 2;
                return 2;
            }

            function pagesCount() {
                return Math.max(1, Math.ceil(slides.length / perPage));
            }

            function clampIndex(i) {
                const max = pagesCount() - 1;
                return Math.min(Math.max(i, 0), max);
            }

            function slideWidth() {
                return viewport.clientWidth;
            }

            function renderDots() {
                dotsWrap.innerHTML = '';
                const total = pagesCount();

                for (let i = 0; i < total; i++) {
                    const dot = document.createElement('button');
                    dot.type = 'button';
                    dot.className = 'w-2 h-2 rounded-full ' + (i === index ? 'bg-gray-900' : 'bg-gray-300');
                    dot.setAttribute('aria-label', 'Go to page ' + (i + 1));
                    dot.addEventListener('click', () => {
                        go(i);
                        resetAutoplay();
                    });
                    dotsWrap.appendChild(dot);
                }
            }

            function updateNav() {
                const total = pagesCount();
                const showNav = window.innerWidth >= 768;

                if (prev) prev.classList.toggle('hidden', !showNav);
                if (next) next.classList.toggle('hidden', !showNav);

                if (prev) prev.disabled = (index === 0);
                if (next) next.disabled = (index >= total - 1);

                if (prev) prev.style.opacity = prev.disabled ? '0.35' : '1';
                if (next) next.style.opacity = next.disabled ? '0.35' : '1';
            }

            function updateDotsActive() {
                Array.from(dotsWrap.children).forEach((d, di) => {
                    d.className = 'w-2 h-2 rounded-full ' + (di === index ? 'bg-gray-900' : 'bg-gray-300');
                });
            }

            function go(i) {
                index = clampIndex(i);
                const x = index * slideWidth();
                track.style.transform = `translateX(${-x}px)`;
                updateDotsActive();
                updateNav();
            }

            function nextPage() {
                const total = pagesCount();
                const nextIdx = (index + 1) >= total ? 0 : (index + 1);
                go(nextIdx);
            }

            function startAutoplay() {
                if (!AUTO_PLAY) return;
                stopAutoplay();
                timer = setInterval(() => {
                    if (isHovering) return;
                    nextPage();
                }, AUTO_INTERVAL_MS);
            }

            function stopAutoplay() {
                if (timer) {
                    clearInterval(timer);
                    timer = null;
                }
            }

            function resetAutoplay() {
                if (!AUTO_PLAY) return;
                startAutoplay();
            }

            function refresh() {
                perPage = calcPerPage();
                index = clampIndex(index);
                renderDots();
                go(index);
                resetAutoplay();
            }

            // events
            if (prev) prev.addEventListener('click', () => {
                go(index - 1);
                resetAutoplay();
            });
            if (next) next.addEventListener('click', () => {
                go(index + 1);
                resetAutoplay();
            });

            // pause on hover
            viewport.addEventListener('mouseenter', () => {
                isHovering = true;
            });
            viewport.addEventListener('mouseleave', () => {
                isHovering = false;
            });

            // mobile touch: pause while dragging (ง่ายๆ)
            viewport.addEventListener('touchstart', () => {
                isHovering = true;
            }, {
                passive: true
            });
            viewport.addEventListener('touchend', () => {
                isHovering = false;
            }, {
                passive: true
            });

            window.addEventListener('resize', refresh);

            // init
            refresh();
            startAutoplay();
        })();
    </script>
</section>

{{-- Footer --}}


@endsection