<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <title>{{ $mode === 'edit' ? 'แก้ไขโมเดลสินค้า' : 'เพิ่มโมเดลสินค้า' }}</title>

    @include('admin/inc_header')

    <style>
        .price-set {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 16px;
            background: #fff;
        }

        .price-set-table {
            width: 100%;
            border-collapse: collapse;
        }

        .price-set-table th,
        .price-set-table td {
            border: 1px solid #e5e7eb;
            padding: 12px;
            vertical-align: middle;
        }

        .price-set-head {
            background: #f8fafc;
            font-weight: 600;
            color: #334155;
        }

        .grade-section-wrap {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: #fff;
            padding: 16px;
            margin-top: 20px;
        }

        .grade-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
        }

        .grade-card {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px;
            background: #ffffff;
        }

        .grade-card label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #334155;
        }

        .set-title {
            font-size: 14px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 10px;
        }

        .price-set-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .price-set-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            height: 32px;
            border-radius: 9999px;
            background: #e0e7ff;
            color: #3730a3;
            font-weight: 700;
            font-size: 13px;
        }

        .brand-box {
            border: 1px solid #e5e7eb;
            background: #f8fafc;
            border-radius: 10px;
            padding: 12px 14px;
            min-height: 42px;
            display: flex;
            align-items: center;
            color: #334155;
            font-weight: 500;
        }

        .field-help {
            margin-top: 6px;
            font-size: 12px;
            color: #64748b;
        }

        @media (max-width: 991px) {
            .grade-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 640px) {
            .grade-grid {
                grid-template-columns: repeat(1, minmax(0, 1fr));
            }

            .price-set-table {
                min-width: 900px;
            }
        }
    </style>
</head>

<body class="main">
    @include('admin/inc_mobilemenu')

    @php
        /*
        |--------------------------------------------------------------------------
        | หน้าที่เข้ามา
        |--------------------------------------------------------------------------
        */

        $pageScope = $pageScope ?? request()->query('scope', 'category');

        if (!in_array($pageScope, ['brand', 'category'], true)) {
            $pageScope = 'category';
        }

        /*
        |--------------------------------------------------------------------------
        | แบรนด์ที่เลือก
        |--------------------------------------------------------------------------
        */

        $selectedBrand = $selectedBrand ?? ($brand ?? null);

        if (!$selectedBrand && !empty($model?->mobile_brand_id) && isset($brands)) {
            $selectedBrand = collect($brands)->firstWhere('id', $model->mobile_brand_id);
        }

        /*
        |--------------------------------------------------------------------------
        | ประเภทสินค้าที่เลือก
        |--------------------------------------------------------------------------
        */

        $selectedCategory = $selectedCategory ?? ($category ?? null);

        if (!$selectedCategory && !empty($model?->mobile_product_category_id) && isset($categories)) {
            $selectedCategory = collect($categories)->firstWhere('id', $model->mobile_product_category_id);
        }

        /*
        |--------------------------------------------------------------------------
        | ID สำหรับแสดงในฟอร์ม
        |--------------------------------------------------------------------------
        */

        $brandId = old('mobile_brand_id', $selectedBrand->id ?? ($model->mobile_brand_id ?? ''));

        $categoryId = old(
            'mobile_product_category_id',
            $selectedCategory->id ?? ($model->mobile_product_category_id ?? ''),
        );

        /*
        |--------------------------------------------------------------------------
        | ชื่อแบรนด์
        |--------------------------------------------------------------------------
        */

        $brandItem = isset($brands) ? collect($brands)->firstWhere('id', $brandId) : null;

        $brandName = $selectedBrand->name ?? ($brandItem->name ?? '-');

        /*
        |--------------------------------------------------------------------------
        | ชื่อประเภทสินค้า
        |--------------------------------------------------------------------------
        */

        $categoryItem = isset($categories) ? collect($categories)->firstWhere('id', $categoryId) : null;

        $categoryName = $selectedCategory->category_name ?? ($categoryItem->category_name ?? '-');

        /*
        |--------------------------------------------------------------------------
        | URL กลับหน้ารายการ
        |--------------------------------------------------------------------------
        */

        if ($pageScope === 'brand' && !empty($brandId)) {
            $indexUrl = url('admin/mobile-models/brand/' . $brandId);
        } elseif ($pageScope === 'category' && !empty($categoryId)) {
            $indexUrl = url('admin/mobile-models/category/' . $categoryId);
        } elseif (!empty($categoryId)) {
            $indexUrl = url('admin/mobile-models/category/' . $categoryId);
        } elseif (!empty($brandId)) {
            $indexUrl = url('admin/mobile-models/brand/' . $brandId);
        } else {
            $indexUrl = url('admin/mobile-brands');
        }

        /*
        |--------------------------------------------------------------------------
        | รายการราคา
        |--------------------------------------------------------------------------
        */

        $oldPrices = old('prices');

        $rows = is_array($oldPrices) ? $oldPrices : $priceRows ?? [];

        if (empty($rows)) {
            $rows = [
                [
                    'capacity' => '',
                    'base_price' => 0,
                    'min_price' => 0,
                    'status' => 1,
                ],
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | ราคาหักเกรด
        |--------------------------------------------------------------------------
        */

        $oldGradePrices = old('grade_prices');

        $gradePriceValues = is_array($oldGradePrices) ? $oldGradePrices : $modelGradePrices ?? [];

        /*
        |--------------------------------------------------------------------------
        | ลำดับการแสดงผล
        |--------------------------------------------------------------------------
        */

        $sortOrder = old('sort_order', !empty($model->sort_order) ? $model->sort_order : $nextSortOrder ?? 1);
    @endphp

    <div
        class="top-bar-boxed h-[70px] z-[51] relative border-b border-white/[0.08] -mt-7 md:-mt-5 -mx-3 sm:-mx-8 px-3 sm:px-8 md:pt-0 mb-12">
        <div class="h-full flex items-center">
            <a href="{{ url('admin/') }}" class="-intro-x hidden md:flex">
                <img alt="Logo" class="w-6" src="{{ asset('dist/images/logo.svg') }}">

                <span class="text-white text-lg ml-3"></span>
            </a>

            <nav aria-label="breadcrumb" class="-intro-x h-full mr-auto">
                <ol class="breadcrumb breadcrumb-light">
                    <li class="breadcrumb-item">
                        <a href="{{ url('admin/') }}">
                            สินค้า
                        </a>
                    </li>

                    <li class="breadcrumb-item">
                        <a href="{{ url('admin/mobile-brands') }}">
                            แบรนด์สินค้า
                        </a>
                    </li>

                    <li class="breadcrumb-item">
                        <a href="{{ $indexUrl }}">
                            โมเดลสินค้า
                        </a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">
                        {{ $mode === 'edit' ? 'แก้ไขข้อมูล' : 'เพิ่มข้อมูล' }}
                    </li>
                </ol>
            </nav>

            @include('admin/inc_account')
        </div>
    </div>

    <div class="wrapper">
        <div class="wrapper-box">
            @include('admin/inc_sidemenu')

            <div class="content">
                <div class="intro-y flex items-center mt-8 mb-5">
                    <h2 class="text-lg font-medium mr-auto">
                        {{ $mode === 'edit' ? 'แก้ไขโมเดลสินค้า' : 'เพิ่มโมเดลสินค้า' }}
                    </h2>
                </div>

                <div class="intro-y box p-5">
                    @if (session('success'))
                        <div class="alert alert-success mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST"
                        action="{{ $mode === 'edit' ? url('admin/mobile-models/' . $model->id . '/update') : url('admin/mobile-models/store') }}">
                        @csrf

                        <input type="hidden" name="page_scope" value="{{ $pageScope }}">

                        <div class="grid grid-cols-12 gap-4">
                            {{-- แบรนด์สินค้า --}}
                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label">
                                    แบรนด์สินค้า
                                    <span class="text-danger">*</span>
                                </label>

                                @if ($pageScope === 'category')
                                    <select name="mobile_brand_id" class="form-select" required>
                                        <option value="">
                                            เลือกแบรนด์สินค้า
                                        </option>

                                        @foreach ($brands as $brandItem)
                                            <option value="{{ $brandItem->id }}"
                                                {{ (string) $brandId === (string) $brandItem->id ? 'selected' : '' }}>
                                                {{ $brandItem->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="hidden" name="mobile_brand_id" value="{{ $brandId }}">

                                    <div class="brand-box">
                                        {{ $brandName }}
                                    </div>
                                @endif
                            </div>

                            {{-- ประเภทสินค้า --}}
                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label">
                                    ประเภทสินค้า
                                    <span class="text-danger">*</span>
                                </label>

                                @if ($pageScope === 'brand')
                                    <select name="mobile_product_category_id" class="form-select" required>
                                        <option value="">
                                            เลือกประเภทสินค้า
                                        </option>

                                        @foreach ($categories as $categoryItem)
                                            <option value="{{ $categoryItem->id }}"
                                                {{ (string) $categoryId === (string) $categoryItem->id ? 'selected' : '' }}>
                                                {{ $categoryItem->category_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="hidden" name="mobile_product_category_id"
                                        value="{{ $categoryId }}">

                                    <div class="brand-box">
                                        {{ $categoryName }}
                                    </div>
                                @endif
                            </div>

                            {{-- ชื่อโมเดล --}}
                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label">
                                    ชื่อโมเดลสินค้า
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $model->name ?? '') }}" placeholder="กรอกชื่อโมเดลสินค้า"
                                    required>
                            </div>

                            {{-- ลำดับการแสดงผล --}}
                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label">
                                    ลำดับการแสดงผล
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="number" name="sort_order" class="form-control" min="1"
                                    step="1" value="{{ $sortOrder }}" placeholder="เช่น 1" required>

                                <div class="field-help">
                                    หากใส่ลำดับซ้ำ รายการเดิมจะถูกขยับลงอัตโนมัติ
                                </div>
                            </div>

                            {{-- สถานะ --}}
                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label">
                                    สถานะโมเดล
                                </label>

                                <select name="status" class="form-select">
                                    <option value="1"
                                        {{ (string) old('status', isset($model->status) ? (int) $model->status : 1) === '1' ? 'selected' : '' }}>
                                        เปิดใช้งาน
                                    </option>

                                    <option value="0"
                                        {{ (string) old('status', isset($model->status) ? (int) $model->status : 1) === '0' ? 'selected' : '' }}>
                                        ปิดใช้งาน
                                    </option>
                                </select>
                            </div>
                        </div>

                        {{-- ราคาหักตามเกรด --}}
                        <div class="grade-section-wrap">
                            <div class="set-title">
                                ราคาหักตามเกรดของรุ่นนี้
                            </div>

                            <div class="grade-grid">
                                @forelse ($grades as $grade)
                                    <div class="grade-card">
                                        <label>
                                            {{ $grade->grade_name }}

                                            @if (!empty($grade->grade_code))
                                                ({{ $grade->grade_code }})
                                            @endif
                                        </label>

                                        <input type="number" step="0.01" min="0" class="form-control"
                                            name="grade_prices[{{ $grade->id }}]"
                                            value="{{ old('grade_prices.' . $grade->id, $gradePriceValues[$grade->id] ?? 0) }}">
                                    </div>
                                @empty
                                    <div class="text-slate-500">
                                        ยังไม่มีข้อมูลเกรด
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        {{-- รายการราคาแยกตามความจุ --}}
                        <div class="mt-8">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-lg font-medium">
                                    รายการราคาแยกตามความจุ
                                </h3>

                                <button type="button" class="btn btn-primary" onclick="addPriceRow()">
                                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>

                                    เพิ่มรายการราคา
                                </button>
                            </div>

                            <div id="price-rows-body">
                                @foreach ($rows as $index => $row)
                                    <div class="price-set" data-row-index="{{ $index }}">
                                        <div class="overflow-x-auto">
                                            <table class="price-set-table">
                                                <tr class="price-set-head">
                                                    <td style="width: 70px;" class="text-center">
                                                        ชุด
                                                    </td>

                                                    <td style="width: 260px;">
                                                        ความจุ
                                                    </td>

                                                    <td style="width: 220px;">
                                                        ราคาพื้นฐาน
                                                    </td>

                                                    <td style="width: 220px;">
                                                        ราคาต่ำสุด
                                                    </td>

                                                    <td style="width: 180px;">
                                                        สถานะ
                                                    </td>

                                                    <td style="width: 120px;" class="text-center">
                                                        จัดการ
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="text-center">
                                                        <span class="price-set-number">
                                                            {{ $loop->iteration }}
                                                        </span>
                                                    </td>

                                                    <td>
                                                        <input type="hidden" name="prices[{{ $index }}][id]"
                                                            value="{{ $row['id'] ?? '' }}">

                                                        <input type="text" class="form-control"
                                                            name="prices[{{ $index }}][capacity]"
                                                            value="{{ old("prices.$index.capacity", $row['capacity'] ?? '') }}"
                                                            placeholder="เช่น 128GB" required>
                                                    </td>

                                                    <td>
                                                        <input type="number" step="0.01" min="0"
                                                            class="form-control"
                                                            name="prices[{{ $index }}][base_price]"
                                                            value="{{ old("prices.$index.base_price", $row['base_price'] ?? 0) }}"
                                                            required>
                                                    </td>

                                                    <td>
                                                        <input type="number" step="0.01" min="0"
                                                            class="form-control"
                                                            name="prices[{{ $index }}][min_price]"
                                                            value="{{ old("prices.$index.min_price", $row['min_price'] ?? 0) }}"
                                                            required>
                                                    </td>

                                                    <td>
                                                        <select class="form-select"
                                                            name="prices[{{ $index }}][status]">
                                                            <option value="1"
                                                                {{ (string) old("prices.$index.status", (int) ($row['status'] ?? 1)) === '1' ? 'selected' : '' }}>
                                                                เปิดใช้งาน
                                                            </option>

                                                            <option value="0"
                                                                {{ (string) old("prices.$index.status", (int) ($row['status'] ?? 1)) === '0' ? 'selected' : '' }}>
                                                                ปิดใช้งาน
                                                            </option>
                                                        </select>
                                                    </td>

                                                    <td class="text-center">
                                                        <div class="price-set-actions">
                                                            <button type="button" class="btn btn-danger"
                                                                onclick="removePriceRow(this)">
                                                                ลบ
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="text-muted mt-2">
                                1 ชุด = 1 ความจุ + ราคาพื้นฐาน + ราคาต่ำสุด
                            </div>
                        </div>

                        <div class="mt-5 flex items-center">
                            <a href="{{ $indexUrl }}" class="btn btn-outline-secondary w-24 mr-2">
                                ยกเลิก
                            </a>

                            <button type="submit" class="btn btn-primary">
                                {{ $mode === 'edit' ? 'บันทึกข้อมูล' : 'เพิ่มข้อมูล' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('admin/inc_footer')

    <script>
        let priceRowIndex = {{ count($rows) }};

        function refreshSetNumbers() {
            document
                .querySelectorAll('#price-rows-body .price-set')
                .forEach((setEl, index) => {
                    const numberEl = setEl.querySelector('.price-set-number');

                    if (numberEl) {
                        numberEl.textContent = index + 1;
                    }
                });
        }

        function addPriceRow() {
            const wrapper = document.getElementById('price-rows-body');

            if (!wrapper) {
                return;
            }

            const div = document.createElement('div');

            div.className = 'price-set';
            div.setAttribute('data-row-index', priceRowIndex);

            div.innerHTML = `
                <div class="overflow-x-auto">
                    <table class="price-set-table">
                        <tr class="price-set-head">
                            <td style="width: 70px;" class="text-center">
                                ชุด
                            </td>

                            <td style="width: 260px;">
                                ความจุ
                            </td>

                            <td style="width: 220px;">
                                ราคาพื้นฐาน
                            </td>

                            <td style="width: 220px;">
                                ราคาต่ำสุด
                            </td>

                            <td style="width: 180px;">
                                สถานะ
                            </td>

                            <td style="width: 120px;" class="text-center">
                                จัดการ
                            </td>
                        </tr>

                        <tr>
                            <td class="text-center">
                                <span class="price-set-number">
                                    ${priceRowIndex + 1}
                                </span>
                            </td>

                            <td>
                                <input
                                    type="hidden"
                                    name="prices[${priceRowIndex}][id]"
                                    value="">

                                <input
                                    type="text"
                                    class="form-control"
                                    name="prices[${priceRowIndex}][capacity]"
                                    placeholder="เช่น 128GB"
                                    required>
                            </td>

                            <td>
                                <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    class="form-control"
                                    name="prices[${priceRowIndex}][base_price]"
                                    value="0"
                                    required>
                            </td>

                            <td>
                                <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    class="form-control"
                                    name="prices[${priceRowIndex}][min_price]"
                                    value="0"
                                    required>
                            </td>

                            <td>
                                <select
                                    class="form-select"
                                    name="prices[${priceRowIndex}][status]">
                                    <option value="1" selected>
                                        เปิดใช้งาน
                                    </option>

                                    <option value="0">
                                        ปิดใช้งาน
                                    </option>
                                </select>
                            </td>

                            <td class="text-center">
                                <div class="price-set-actions">
                                    <button
                                        type="button"
                                        class="btn btn-danger"
                                        onclick="removePriceRow(this)">
                                        ลบ
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            `;

            wrapper.appendChild(div);

            priceRowIndex++;

            refreshSetNumbers();

            if (window.lucide) {
                window.lucide.createIcons();
            }
        }

        function removePriceRow(button) {
            const wrapper = document.getElementById('price-rows-body');

            if (!wrapper) {
                return;
            }

            const allSets = wrapper.querySelectorAll('.price-set');

            if (allSets.length <= 1) {
                alert('ต้องมีรายการราคาอย่างน้อย 1 รายการ');
                return;
            }

            const priceSet = button.closest('.price-set');

            if (priceSet) {
                priceSet.remove();
            }

            refreshSetNumbers();
        }

        document.addEventListener('DOMContentLoaded', function() {
            refreshSetNumbers();
        });
    </script>
</body>

</html>
