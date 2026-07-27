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
        $selectedBrand = $selectedBrand ?? ($brand ?? null);

        if (!$selectedBrand && !empty($model?->mobile_brand_id ?? null) && isset($brands)) {
            $selectedBrand = collect($brands)->firstWhere('id', $model->mobile_brand_id);
        }

        $selectedCategory = $selectedCategory ?? ($category ?? null);

        if (!$selectedCategory && !empty($model?->mobile_product_category_id ?? null) && isset($categories)) {
            $selectedCategory = collect($categories)->firstWhere('id', $model->mobile_product_category_id);
        }

        $brandId = old('mobile_brand_id', $selectedBrand->id ?? ($model->mobile_brand_id ?? ''));
        $brandName =
            $selectedBrand->name ?? (isset($brands) ? collect($brands)->firstWhere('id', $brandId)->name ?? '-' : '-');

        $categoryId = old(
            'mobile_product_category_id',
            $selectedCategory->id ?? ($model->mobile_product_category_id ?? ''),
        );
        $indexUrl = $categoryId ? url('admin/mobile-models/category/' . $categoryId) : url('admin/mobile-brands');

        $oldPrices = old('prices');
        $rows = is_array($oldPrices) ? $oldPrices : $priceRows ?? [];

        $oldGradePrices = old('grade_prices');
        $gradePriceValues = is_array($oldGradePrices) ? $oldGradePrices : $modelGradePrices ?? [];
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
                    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">สินค้า</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('admin/mobile-brands') }}">แบรนด์สินค้า</a></li>
                    @if (!empty($selectedBrand))
                        <li class="breadcrumb-item">
                            <a
                                href="{{ url('admin/mobile-product-categories/' . $selectedBrand->id) }}">ประเภทสินค้า</a>
                        </li>
                    @endif
                    <li class="breadcrumb-item"><a href="{{ $indexUrl }}">โมเดลสินค้า</a></li>
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

                        <input type="hidden" name="mobile_brand_id" value="{{ $brandId }}">
                        <input type="hidden" name="mobile_product_category_id" value="{{ $categoryId }}">

                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label">แบรนด์สินค้า <span class="text-danger">*</span></label>
                                <div class="brand-box">
                                    {{ $brandName }}
                                </div>
                            </div>

                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label">ประเภทสินค้า <span class="text-danger">*</span></label>
                                <div class="brand-box">
                                    {{ $selectedCategory->category_name ?? '-' }}
                                </div>
                            </div>

                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label">ชื่อโมเดลสินค้า <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $model->name ?? '') }}" placeholder="กรอกชื่อโมเดลสินค้า">
                            </div>

                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label">สถานะโมเดล</label>
                                <select name="status" class="form-select">
                                    <option value="1"
                                        {{ old('status', isset($model->status) ? (int) $model->status : 1) == 1 ? 'selected' : '' }}>
                                        เปิดใช้งาน
                                    </option>
                                    <option value="0"
                                        {{ old('status', isset($model->status) ? (int) $model->status : 1) == 0 ? 'selected' : '' }}>
                                        ปิดใช้งาน
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="grade-section-wrap">
                            <div class="set-title">ราคาหักตามเกรดของรุ่นนี้</div>

                            <div class="grade-grid">
                                @foreach ($grades as $grade)
                                    <div class="grade-card">
                                        <label>{{ $grade->grade_name }}</label>
                                        <input type="number" step="0.01" min="0" class="form-control"
                                            name="grade_prices[{{ $grade->id }}]"
                                            value="{{ old('grade_prices.' . $grade->id, $gradePriceValues[$grade->id] ?? 0) }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mt-8">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-lg font-medium">รายการราคาแยกตามความจุ</h3>
                                <button type="button" class="btn btn-primary" onclick="addPriceRow()">
                                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                                    เพิ่มรายการราคา
                                </button>
                            </div>

                            <div id="price-rows-body">
                                @foreach ($rows as $index => $row)
                                    <div class="price-set" data-row-index="{{ $index }}">
                                        <table class="price-set-table">
                                            <tr class="price-set-head">
                                                <td style="width: 70px;" class="text-center">ชุด</td>
                                                <td style="width: 260px;">ความจุ</td>
                                                <td style="width: 220px;">ราคาพื้นฐาน</td>
                                                <td style="width: 220px;">ราคาต่ำสุด</td>
                                                <td style="width: 180px;">สถานะ</td>
                                                <td style="width: 120px;" class="text-center">จัดการ</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">
                                                    <span class="price-set-number">{{ $loop->iteration }}</span>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="prices[{{ $index }}][id]"
                                                        value="{{ $row['id'] ?? '' }}">
                                                    <input type="text" class="form-control"
                                                        name="prices[{{ $index }}][capacity]"
                                                        value="{{ old("prices.$index.capacity", $row['capacity'] ?? '') }}"
                                                        placeholder="เช่น 128GB">
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" min="0"
                                                        class="form-control"
                                                        name="prices[{{ $index }}][base_price]"
                                                        value="{{ old("prices.$index.base_price", $row['base_price'] ?? 0) }}">
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" min="0"
                                                        class="form-control"
                                                        name="prices[{{ $index }}][min_price]"
                                                        value="{{ old("prices.$index.min_price", $row['min_price'] ?? 0) }}">
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
            document.querySelectorAll('#price-rows-body .price-set').forEach((setEl, index) => {
                const numberEl = setEl.querySelector('.price-set-number');
                if (numberEl) {
                    numberEl.textContent = index + 1;
                }
            });
        }

        function addPriceRow() {
            const tbody = document.getElementById('price-rows-body');

            const div = document.createElement('div');
            div.className = 'price-set';
            div.setAttribute('data-row-index', priceRowIndex);

            div.innerHTML = `
                <table class="price-set-table">
                    <tr class="price-set-head">
                        <td style="width: 70px;" class="text-center">ชุด</td>
                        <td style="width: 260px;">ความจุ</td>
                        <td style="width: 220px;">ราคาพื้นฐาน</td>
                        <td style="width: 220px;">ราคาต่ำสุด</td>
                        <td style="width: 180px;">สถานะ</td>
                        <td style="width: 120px;" class="text-center">จัดการ</td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            <span class="price-set-number">${priceRowIndex + 1}</span>
                        </td>
                        <td>
                            <input type="hidden" name="prices[${priceRowIndex}][id]" value="">
                            <input type="text" class="form-control"
                                name="prices[${priceRowIndex}][capacity]"
                                placeholder="เช่น 128GB">
                        </td>
                        <td>
                            <input type="number" step="0.01" min="0" class="form-control"
                                name="prices[${priceRowIndex}][base_price]" value="0">
                        </td>
                        <td>
                            <input type="number" step="0.01" min="0" class="form-control"
                                name="prices[${priceRowIndex}][min_price]" value="0">
                        </td>
                        <td>
                            <select class="form-select" name="prices[${priceRowIndex}][status]">
                                <option value="1" selected>เปิดใช้งาน</option>
                                <option value="0">ปิดใช้งาน</option>
                            </select>
                        </td>
                        <td class="text-center">
                            <div class="price-set-actions">
                                <button type="button" class="btn btn-danger" onclick="removePriceRow(this)">ลบ</button>
                            </div>
                        </td>
                    </tr>
                </table>
            `;

            tbody.appendChild(div);
            priceRowIndex++;

            refreshSetNumbers();

            if (window.lucide) {
                lucide.createIcons();
            }
        }

        function removePriceRow(button) {
            const wrapper = document.getElementById('price-rows-body');
            const allSets = wrapper.querySelectorAll('.price-set');

            if (allSets.length <= 1) {
                alert('ต้องมีรายการราคาอย่างน้อย 1 รายการ');
                return;
            }

            button.closest('.price-set').remove();
            refreshSetNumbers();
        }
    </script>
</body>

</html>
