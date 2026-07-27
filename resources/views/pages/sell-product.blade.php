@extends('layouts.app')

@section('title', 'Cashkub')
<style>
    .w-200px {
        width: 200px;
    }
</style>

@section('content')

    {{-- HERO SECTION --}}
    <section id="hero-slider" class="relative overflow-hidden bg-[#DFF3EA]">
        <div class="relative w-full">
            {{-- viewport --}}
            <div class="overflow-hidden">
                {{-- track --}}
                <div id="hero-slider-track" class="flex transition-transform duration-500 ease-in-out will-change-transform">

                    @foreach ($banner as $index => $slide)
                        <div class="hero-slide min-w-full shrink-0">
                            <div class="relative w-full h-[220px] sm:h-[320px] md:h-[420px] lg:h-[560px] xl:h-[680px]">
                                {{-- Desktop / Tablet --}}
                                <img src="{{ asset('storage/' . $slide->desktop_image) }}"
                                    class="hidden sm:block absolute inset-0 w-full h-full object-cover object-center select-none pointer-events-none">

                                {{-- Mobile --}}
                                <img src="{{ asset('storage/' . $slide->mobile_image) }}"
                                    class="block sm:hidden absolute inset-0 w-full h-full object-cover object-center select-none pointer-events-none">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @if (count($banner) > 1)
                {{-- ปุ่มซ้าย --}}
                <button type="button" id="hero-prev" aria-label="Previous slide"
                    class="hidden md:flex absolute left-4 lg:left-6 top-1/2 -translate-y-1/2 z-20 items-center justify-center w-12 h-12 rounded-full bg-white/85 hover:bg-white shadow-md transition">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M15 18l-6-6 6-6" stroke="#285F43" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>

                {{-- ปุ่มขวา --}}
                <button type="button" id="hero-next" aria-label="Next slide"
                    class="hidden md:flex absolute right-4 lg:right-6 top-1/2 -translate-y-1/2 z-20 items-center justify-center w-12 h-12 rounded-full bg-white/85 hover:bg-white shadow-md transition">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M9 6l6 6-6 6" stroke="#285F43" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>

                {{-- dots --}}
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
                if (document.hidden) stopAutoplay();
                else startAutoplay();
            });

            updateSlider(0);
            startAutoplay();
        })();
    </script>

    {{-- CATEGORY + ESTIMATE --}}
    <section id="sell-estimate-section" class="w-full max-w-[1140px] mx-auto px-4 sm:px-6" style="max-width: 1140px;">
        <div class="bg-white rounded-3xl shadow-lg p-6 sm:p-8 md:p-10 -mt-16 sm:-mt-20 md:-mt-[10rem] relative z-10">

            <h2
                class="leading-snug font-medium text-[26px] md:text-[26px] lg:text-[38px] text-[#285F43] text-center mb-6 sm:mb-8">
                เลือกที่ต้องการขาย
            </h2>

            @if (!empty($categories) && count($categories) > 0)
                <div class="relative mb-8 sm:mb-10">
                    <div class="overflow-hidden" id="cat-viewport">
                        <div class="flex flex-nowrap transition-transform duration-300 ease-out my-8 mb-10" id="cat-track">
                            @foreach ($categories as $cat)
                                @php
                                    $isActive = (string) $cat['key'] === (string) $activeKey;
                                @endphp

                                <div class="cat-slide px-2">
                                    <button type="button"
                                        class="sell-category-card {{ $isActive ? 'is-active' : '' }}
                                        relative rounded-2xl text-center cursor-pointer
                                        h-[140px] sm:h-[160px] md:h-[240px]
                                        px-3 py-4 sm:px-4 sm:py-5 md:px-4 md:py-6
                                        flex flex-col items-center justify-center
                                        focus:outline-none focus:ring-2 focus:ring-[#285F43]/30"
                                        data-key="{{ $cat['key'] }}" data-option="{{ $cat['label_th'] }}"
                                        aria-pressed="{{ $isActive ? 'true' : 'false' }}">

                                        <div
                                            class="icon-wrap relative mb-2 w-[70%] aspect-square max-w-[110px] sm:max-w-[120px] md:max-w-[150px]">
                                            <img src="{{ asset($cat['icon_default']) }}" alt="{{ $cat['name'] }}"
                                                class="icon-default absolute inset-0 m-auto w-full h-full object-contain">

                                            <img src="{{ asset($cat['icon_active']) }}" alt="{{ $cat['name'] }} active"
                                                class="icon-active absolute inset-0 m-auto w-full h-full object-contain">
                                        </div>

                                        <p
                                            class="card-text leading-tight font-semibold text-[14px] sm:text-[15px] md:text-[16px]">
                                            {{ $cat['name'] }}
                                        </p>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-center gap-2 mt-3" id="cat-dots"></div>
                </div>

                <div id="estimate-form-wrap" class="relative z-50 transform-none">
                    <style>
                        .estimate-select-row {
                            width: 260px;
                            margin-left: auto;
                            margin-right: auto;
                            display: flex;
                            flex-direction: column;
                            gap: 12px;
                        }

                        .estimate-select-item {
                            width: 100%;
                        }

                        .estimate-select-input {
                            width: 100%;
                            height: 42px;
                            border: 1px solid #D1D5DB;
                            border-radius: 9999px;
                            padding: 8px 16px;
                            background: #ffffff;
                            font-size: 15px;
                            color: #111827;
                            outline: none;
                        }

                        .estimate-select-input:focus {
                            border-color: #285F43;
                            box-shadow: 0 0 0 2px rgba(40, 95, 67, 0.2);
                        }

                        @media (min-width: 768px) {
                            .estimate-select-row {
                                width: 100%;
                                max-width: 900px;
                                display: grid;
                                grid-template-columns: repeat(4, minmax(200px, 1fr));
                                gap: 16px;
                                align-items: center;
                            }

                            .estimate-select-item {
                                width: 100%;
                                min-width: 200px;
                            }
                        }
                    </style>

                    <form method="GET" action="{{ route('sell.product.estimate') }}">
                        <div class="estimate-select-row">

                            <input type="hidden" id="selectedCategoryKey" name="selected_category_key"
                                value="{{ $activeKey }}">

                            <div class="estimate-select-item">
                                <select id="categorySelect" name="mobile_product_category_id" class="estimate-select-input">
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat['key'] }}"
                                            {{ (string) $cat['key'] === (string) $activeKey ? 'selected' : '' }}>
                                            {{ $cat['label_th'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="estimate-select-item">
                                <select id="brandSelect" name="mobile_brand_id" class="estimate-select-input">
                                    <option value="">เลือกแบรนด์</option>
                                </select>
                            </div>

                            <div class="estimate-select-item">
                                <select id="modelSelect" name="mobile_model_id" class="estimate-select-input">
                                    <option value="">เลือกรุ่น</option>
                                </select>
                            </div>

                            <div class="estimate-select-item">
                                <select id="capacitySelect" name="mobile_model_price_id" class="estimate-select-input">
                                    <option value="">เลือกความจุ</option>
                                </select>
                            </div>
                        </div>

                        <div class="m-auto text-center my-5">
                            <button type="submit"
                                class="bg-[#3AAA5B] hover:bg-[#2E944C] text-white px-8 py-2 rounded-full shadow-md transition w-[260px]">
                                ประเมินราคา
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="text-center text-[#5F7A6E] py-5">
                    ยังไม่มีข้อมูลประเภทสินค้า
                </div>
            @endif
        </div>
    </section>

    <script>
        (function() {
            const viewport = document.getElementById('cat-viewport');
            const track = document.getElementById('cat-track');
            const dotsWrap = document.getElementById('cat-dots');
            const categoryCards = Array.from(document.querySelectorAll('.sell-category-card'));
            const categorySelect = document.getElementById('categorySelect');
            const selectedCategoryKey = document.getElementById('selectedCategoryKey');
            const brandSelect = document.getElementById('brandSelect');
            const modelSelect = document.getElementById('modelSelect');
            const capacitySelect = document.getElementById('capacitySelect');

            const categoryModels = @json(collect($categories)->mapWithKeys(function ($cat) {
                    return [
                        (string) $cat['key'] => $cat['models'] ?? [],
                    ];
                }));

            if (!viewport || !track || !dotsWrap) {
                return;
            }

            const slides = Array.from(track.querySelectorAll('.cat-slide'));

            let index = 0;
            let perPage = 5;

            let startX = 0;
            let startY = 0;
            let isDragging = false;
            let isSwiping = false;

            function calcPerPage() {
                return window.innerWidth >= 768 ? 5 : 3;
            }

            function pagesCount() {
                return Math.max(1, Math.ceil(slides.length / perPage));
            }

            function clampIndex(i) {
                const max = pagesCount() - 1;

                return Math.min(Math.max(i, 0), max);
            }

            function getStepWidth() {
                if (!slides.length) {
                    return 0;
                }

                return slides[0].getBoundingClientRect().width;
            }

            function renderDots() {
                dotsWrap.innerHTML = '';

                const total = pagesCount();

                if (total <= 1) {
                    return;
                }

                for (let i = 0; i < total; i++) {
                    const dot = document.createElement('button');

                    dot.type = 'button';
                    dot.className = 'w-2 h-2 rounded-full transition ' + (i === index ? 'bg-gray-900' : 'bg-gray-300');
                    dot.setAttribute('aria-label', 'Go to page ' + (i + 1));

                    dot.addEventListener('click', function() {
                        go(i);
                    });

                    dotsWrap.appendChild(dot);
                }
            }

            function updateDotsActive() {
                Array.from(dotsWrap.children).forEach(function(dot, di) {
                    dot.className = 'w-2 h-2 rounded-full transition ' + (di === index ? 'bg-gray-900' :
                        'bg-gray-300');
                });
            }

            function go(i) {
                index = clampIndex(i);

                const stepWidth = getStepWidth();
                const x = index * stepWidth * perPage;

                track.style.transform = 'translateX(' + (-x) + 'px)';

                updateDotsActive();
            }

            function resetBrandOptions() {
                if (!brandSelect) {
                    return;
                }

                brandSelect.innerHTML = '<option value="">เลือกแบรนด์</option>';
            }

            function resetModelOptions() {
                if (!modelSelect) {
                    return;
                }

                modelSelect.innerHTML = '<option value="">เลือกรุ่น</option>';
            }

            function resetCapacityOptions() {
                if (!capacitySelect) {
                    return;
                }

                capacitySelect.innerHTML = '<option value="">เลือกความจุ</option>';
            }

            function getModelsByCategory(categoryKey) {
                return categoryModels[String(categoryKey)] || [];
            }

            function renderBrandOptions(categoryKey) {
                if (!brandSelect) {
                    return;
                }

                const models = getModelsByCategory(categoryKey);
                const brandMap = {};

                resetBrandOptions();
                resetModelOptions();
                resetCapacityOptions();

                models.forEach(function(model) {
                    if (!model.mobile_brand_id || !model.brand_name) {
                        return;
                    }

                    brandMap[String(model.mobile_brand_id)] = model.brand_name;
                });

                Object.keys(brandMap).forEach(function(brandId) {
                    const option = document.createElement('option');

                    option.value = brandId;
                    option.textContent = brandMap[brandId];

                    brandSelect.appendChild(option);
                });

                const brandIds = Object.keys(brandMap);

                if (brandIds.length === 1) {
                    brandSelect.value = brandIds[0];

                    renderModelOptions(categoryKey, brandIds[0]);
                }
            }

            function renderModelOptions(categoryKey, brandId) {
                if (!modelSelect) {
                    return;
                }

                const models = getModelsByCategory(categoryKey);

                resetModelOptions();
                resetCapacityOptions();

                models
                    .filter(function(model) {
                        if (!brandId) {
                            return false;
                        }

                        return String(model.mobile_brand_id) === String(brandId);
                    })
                    .forEach(function(model) {
                        const option = document.createElement('option');

                        option.value = model.id;
                        option.textContent = model.name;

                        modelSelect.appendChild(option);
                    });
            }

            function renderCapacityOptions(modelId) {
                if (!capacitySelect) {
                    return;
                }

                resetCapacityOptions();

                if (!modelId) {
                    return;
                }

                const categoryKey = categorySelect ? String(categorySelect.value) : '';
                const models = getModelsByCategory(categoryKey);

                const selectedModel = models.find(function(model) {
                    return String(model.id) === String(modelId);
                });

                if (!selectedModel || !Array.isArray(selectedModel.capacities)) {
                    return;
                }

                selectedModel.capacities.forEach(function(price) {
                    const option = document.createElement('option');

                    option.value = price.id;
                    option.textContent = price.capacity;

                    option.dataset.capacity = price.capacity || '';
                    option.dataset.basePrice = price.base_price || '';
                    option.dataset.minPrice = price.min_price || '';

                    capacitySelect.appendChild(option);
                });
            }

            function setActiveCategory(key) {
                key = String(key || '');

                categoryCards.forEach(function(card) {
                    const isActive = String(card.dataset.key) === key;

                    card.classList.toggle('is-active', isActive);
                    card.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                });

                if (selectedCategoryKey) {
                    selectedCategoryKey.value = key;
                }

                if (categorySelect) {
                    categorySelect.value = key;
                }

                renderBrandOptions(key);
            }

            function refresh() {
                perPage = calcPerPage();
                index = clampIndex(index);

                renderDots();
                go(index);
            }

            categoryCards.forEach(function(card) {
                card.addEventListener('click', function(e) {
                    if (isSwiping) {
                        e.preventDefault();
                        return;
                    }

                    const key = this.dataset.key || '';

                    setActiveCategory(key);
                });
            });

            if (categorySelect) {
                categorySelect.addEventListener('change', function() {
                    setActiveCategory(this.value);
                });
            }

            if (brandSelect) {
                brandSelect.addEventListener('change', function() {
                    const categoryKey = categorySelect ? categorySelect.value : '';

                    renderModelOptions(categoryKey, this.value);
                });
            }

            if (modelSelect) {
                modelSelect.addEventListener('change', function() {
                    renderCapacityOptions(this.value);
                });
            }

            viewport.addEventListener('touchstart', function(e) {
                if (!e.touches || !e.touches.length) {
                    return;
                }

                isDragging = true;
                isSwiping = false;
                startX = e.touches[0].clientX;
                startY = e.touches[0].clientY;
            }, {
                passive: true
            });

            viewport.addEventListener('touchmove', function(e) {
                if (!isDragging || !e.touches || !e.touches.length) {
                    return;
                }

                const moveX = e.touches[0].clientX;
                const moveY = e.touches[0].clientY;

                const diffX = Math.abs(moveX - startX);
                const diffY = Math.abs(moveY - startY);

                if (diffX > 10 && diffX > diffY) {
                    isSwiping = true;
                }
            }, {
                passive: true
            });

            viewport.addEventListener('touchend', function(e) {
                if (!isDragging) {
                    return;
                }

                isDragging = false;

                const endX = (e.changedTouches && e.changedTouches[0]) ? e.changedTouches[0].clientX : startX;
                const diff = endX - startX;

                if (isSwiping && Math.abs(diff) >= 30) {
                    if (diff < 0) {
                        go(index + 1);
                    } else {
                        go(index - 1);
                    }
                }

                setTimeout(function() {
                    isSwiping = false;
                }, 50);
            }, {
                passive: true
            });

            window.addEventListener('resize', refresh);

            setActiveCategory('{{ $activeKey }}');
            refresh();
        })();
    </script>

    {{-- SELL METHOD FROM ADMIN FORM --}}
    @if (!empty($saleDetailSection) && $saleDetailSection->tabs->count() > 0)
        @php
            $activeTabs = $saleDetailSection->tabs
                ->where('status', 'active')
                ->filter(function ($tab) {
                    return $tab->steps && $tab->steps->where('status', 'active')->count() > 0;
                })
                ->values();

            $firstTab = $activeTabs->first();
            $firstTabKey = $firstTab->tab_key ?? 'tab_0';
        @endphp

        @if ($activeTabs->count() > 0)
            <section class="py-6 text-center">
                <h2 class="font-medium mb-3 text-[26px] md:text-[26px] lg:text-[38px] text-[#285F43] text-center">
                    {{ $saleDetailSection->title ?: 'สามารถขายได้ 2 วิธีง่ายๆ' }}
                </h2>

                @if (!empty($saleDetailSection->sub_title))
                    <p class="mb-6 text-[15px] md:text-[17px] text-[#6B7280] leading-7 max-w-[760px] mx-auto px-4">
                        {{ $saleDetailSection->sub_title }}
                    </p>
                @else
                    <div class="mb-6"></div>
                @endif

                {{-- Segmented Tabs --}}
                <div class="flex justify-center">
                    <div class="relative inline-flex items-center rounded-full bg-white p-1 pr-[10px] shadow-sm border border-gray-200 overflow-x-auto max-w-full"
                        id="sell-method-seg" role="tablist" aria-label="Sell method">

                        {{-- Sliding indicator --}}
                        <div id="sell-method-indicator"
                            class="absolute top-1 bottom-1 left-1 rounded-full shadow transition-transform duration-300 ease-out"
                            style="background:#285F43; width:0px; transform:translateX(0px);" aria-hidden="true">
                        </div>

                        @foreach ($activeTabs as $tabIndex => $tab)
                            @php
                                $tabKey = $tab->tab_key ?: 'tab_' . $tabIndex;
                                $isFirst = $tabIndex === 0;
                            @endphp

                            <button type="button"
                                class="sell-seg-btn relative z-10 rounded-full transition-colors duration-200 shrink-0"
                                data-tab="{{ $tabKey }}" role="tab"
                                aria-selected="{{ $isFirst ? 'true' : 'false' }}"
                                style="width:160px; height:50px; font-size:20px;">
                                {{ $tab->name ?: 'แท็บที่ ' . ($tabIndex + 1) }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Panels --}}
                <div class="mt-12 container mx-auto px-6" style="max-width: 1140px;">
                    @foreach ($activeTabs as $tabIndex => $tab)
                        @php
                            $tabKey = $tab->tab_key ?: 'tab_' . $tabIndex;
                            $isFirst = $tabIndex === 0;
                            $activeSteps = $tab->steps
                                ->where('status', 'active')
                                ->sortBy([['sort_order', 'asc'], ['id', 'asc']])
                                ->values();
                        @endphp

                        <div class="sell-panel {{ $isFirst ? '' : 'hidden' }}" data-panel="{{ $tabKey }}"
                            role="tabpanel">

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                                @foreach ($activeSteps as $stepIndex => $step)
                                    @php
                                        $stepImage = !empty($step->image)
                                            ? asset('storage/' . $step->image)
                                            : asset('assets/media/image-02.png');

                                        $stepLabel = $step->step_label ?: 'ขั้นตอนที่ ' . ($stepIndex + 1);
                                        $stepTitle = $step->title ?: '-';
                                        $stepDescription = $step->description ?: '';
                                    @endphp

                                    <div class="bg-white rounded-2xl p-6 shadow border border-gray-100">
                                        <img src="{{ $stepImage }}" alt="{{ $stepTitle }}"
                                            class="mx-auto mb-4 rounded-2xl w-full h-[220px] object-cover">

                                        <div class="text-sm text-gray-500 mb-1">
                                            {{ $stepLabel }}
                                        </div>

                                        <h3 class="text-xl font-semibold mb-2">
                                            {{ $stepTitle }}
                                        </h3>

                                        @if (!empty($stepDescription))
                                            <p class="hidden sm:block text-sm text-gray-600 text-left leading-7">
                                                {{ $stepDescription }}
                                            </p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <script>
                    (function() {
                        const seg = document.getElementById('sell-method-seg');

                        if (!seg) {
                            return;
                        }

                        const indicator = document.getElementById('sell-method-indicator');
                        const btns = Array.from(seg.querySelectorAll('.sell-seg-btn'));
                        const panels = Array.from(document.querySelectorAll('.sell-panel'));
                        const GREEN = '#285F43';

                        function animatePanelIn(panel) {
                            panel.classList.remove('hidden');
                            panel.style.opacity = '0';
                            panel.style.transform = 'translateY(6px)';
                            panel.style.transition = 'opacity 220ms ease, transform 220ms ease';

                            requestAnimationFrame(function() {
                                panel.style.opacity = '1';
                                panel.style.transform = 'translateY(0px)';
                            });

                            setTimeout(function() {
                                panel.style.opacity = '';
                                panel.style.transform = '';
                                panel.style.transition = '';
                            }, 260);
                        }

                        function setBtnStyle(btn, isActive) {
                            if (isActive) {
                                btn.style.color = '#FFFFFF';
                                btn.style.background = 'transparent';
                            } else {
                                btn.style.color = GREEN;
                                btn.style.background = 'transparent';
                            }
                        }

                        function setActive(tabName) {
                            btns.forEach(function(btn) {
                                const isActive = btn.dataset.tab === tabName;

                                btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
                                setBtnStyle(btn, isActive);
                            });

                            panels.forEach(function(panel) {
                                const isTarget = panel.dataset.panel === tabName;

                                if (isTarget) {
                                    animatePanelIn(panel);
                                } else {
                                    panel.classList.add('hidden');
                                }
                            });

                            const activeBtn = btns.find(function(btn) {
                                return btn.dataset.tab === tabName;
                            });

                            if (!activeBtn || !indicator) {
                                return;
                            }

                            const segRect = seg.getBoundingClientRect();
                            const btnRect = activeBtn.getBoundingClientRect();
                            const left = btnRect.left - segRect.left + seg.scrollLeft;

                            indicator.style.width = btnRect.width + 'px';
                            indicator.style.transform = 'translateX(' + left + 'px)';
                            indicator.style.background = GREEN;
                        }

                        btns.forEach(function(btn) {
                            btn.addEventListener('click', function() {
                                setActive(btn.dataset.tab);
                            });
                        });

                        const firstTab = btns[0] ? btns[0].dataset.tab : null;

                        if (firstTab) {
                            setActive(firstTab);
                        }

                        window.addEventListener('resize', function() {
                            const currentBtn = btns.find(function(btn) {
                                return btn.getAttribute('aria-selected') === 'true';
                            });

                            setActive(currentBtn ? currentBtn.dataset.tab : firstTab);
                        });
                    })();
                </script>
            </section>
        @endif
    @endif


    {{-- REVIEWS --}}
    <section class="py-20 bg-gray-50">
        <h2 class="font-medium mb-6 text-[26px] md:text-[26px] lg:text-[38px] text-[#285F43] text-center">
            รีวิวความประทับใจ
        </h2>

        @if (!empty($reviews) && $reviews->count() > 0)
            <div class="relative container mx-auto px-6" style="max-width: 1140px;">
                <button type="button"
                    class="review-nav review-prev hidden md:flex items-center justify-center absolute left-2 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white shadow border border-gray-200 z-10"
                    aria-label="Previous">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M15 18l-6-6 6-6" stroke="#285F43" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>

                <button type="button"
                    class="review-nav review-next hidden md:flex items-center justify-center absolute right-2 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white shadow border border-gray-200 z-10"
                    aria-label="Next">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M9 6l6 6-6 6" stroke="#285F43" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>

                <div class="overflow-hidden" id="review-viewport">
                    <div class="flex transition-transform duration-300 ease-out" id="review-track">
                        @foreach ($reviews as $idx => $review)
                            @php
                                $reviewImage = !empty($review->image)
                                    ? asset('storage/' . $review->image)
                                    : asset('assets/media/review/avatar.png');

                                $reviewTitle = !empty($review->title) ? $review->title : 'รีวิวจากลูกค้า';

                                $reviewComment = !empty($review->comment)
                                    ? $review->comment
                                    : 'ขอบคุณที่ใช้บริการ Cashkub';

                                $reviewRating = max(1, min(5, (int) ($review->rating ?? 5)));
                            @endphp

                            <div class="review-slide basis-1/2 md:basis-1/2 lg:basis-[20%] shrink-0 px-2">
                                <div class="bg-white shadow-sm rounded-2xl h-full overflow-hidden border border-gray-100">
                                    <div class="px-4 pt-4">
                                        <div
                                            class="review-image-wrap w-full overflow-hidden rounded-2xl aspect-[4/3] md:aspect-[4/5] lg:aspect-[4/5] bg-[#F5F7F6]">
                                            <img src="{{ $reviewImage }}" alt="Review {{ $idx + 1 }}"
                                                class="w-full h-full object-cover">
                                        </div>
                                    </div>

                                    <div class="px-4 pb-5 pt-3 text-left">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center gap-1">
                                                @for ($s = 1; $s <= 5; $s++)
                                                    <svg width="18" height="18" viewBox="0 0 24 24"
                                                        fill="{{ $s <= $reviewRating ? '#F5B301' : '#E5E7EB' }}"
                                                        aria-hidden="true">
                                                        <path
                                                            d="M12 2l2.9 6.1 6.7.9-4.9 4.7 1.2 6.6L12 17.9 6.1 20.3l1.2-6.6L2.4 9l6.7-.9L12 2z" />
                                                    </svg>
                                                @endfor
                                            </div>

                                            <div class="text-xl font-semibold text-gray-900">
                                                {{ number_format($reviewRating, 1) }}
                                            </div>
                                        </div>

                                        <div class="text-gray-900 font-semibold leading-tight line-clamp-2">
                                            {{ $reviewTitle }}
                                        </div>

                                        <div class="mt-2 text-sm text-gray-600 leading-6 line-clamp-3">
                                            {{ $reviewComment }}
                                        </div>

                                        <div class="mt-3 text-sm text-gray-500">
                                            By {{ $review->display_phone ?? 'สมาชิก Cashkub' }}
                                        </div>

                                        <div class="text-sm text-gray-500 line-clamp-1">
                                            ขายสินค้า {{ $review->order_title ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

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
                    let perPage = 5;

                    const AUTO_PLAY = true;
                    const AUTO_INTERVAL_MS = 3500;
                    let timer = null;
                    let isHovering = false;

                    function calcPerPage() {
                        const w = window.innerWidth;

                        if (w >= 1024) return 5;
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

                        if (total <= 1) {
                            return;
                        }

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
                        const showNav = window.innerWidth >= 768 && total > 1;

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
                        if (!AUTO_PLAY || pagesCount() <= 1) return;
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

                    if (prev) {
                        prev.addEventListener('click', () => {
                            go(index - 1);
                            resetAutoplay();
                        });
                    }

                    if (next) {
                        next.addEventListener('click', () => {
                            go(index + 1);
                            resetAutoplay();
                        });
                    }

                    viewport.addEventListener('mouseenter', () => {
                        isHovering = true;
                    });

                    viewport.addEventListener('mouseleave', () => {
                        isHovering = false;
                    });

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

                    refresh();
                    startAutoplay();
                })();
            </script>
        @else
            <div class="container mx-auto px-6" style="max-width: 1140px;">
                <div class="rounded-3xl bg-white border border-gray-100 shadow-sm px-6 py-12 text-center">
                    <div class="text-[#285F43] text-[22px] font-semibold">
                        ยังไม่มีรีวิวความประทับใจ
                    </div>
                    <p class="mt-3 text-gray-500 text-[15px]">
                        เมื่อมีลูกค้ารีวิวหลังขายสินค้า รายการจะแสดงที่นี่
                    </p>
                </div>
            </div>
        @endif
    </section>

@endsection
