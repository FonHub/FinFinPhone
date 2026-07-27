<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <title>{{ $mode === 'edit' ? 'แก้ไขชุดคำถาม' : 'เพิ่มชุดคำถาม' }}</title>
    @include('admin/inc_header')
</head>

<body class="main">
    @include('admin/inc_mobilemenu')

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
                    <li class="breadcrumb-item">
                        <a href="{{ url('admin/product-grade-questions') }}">ชุดคำถามคัดเกรดสินค้า</a>
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
                        {{ $mode === 'edit' ? 'แก้ไขชุดคำถาม' : 'เพิ่มชุดคำถาม' }}
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
                        action="{{ $mode === 'edit' ? url('admin/product-grade-questions/' . $question->id . '/update') : url('admin/product-grade-questions/store') }}">
                        @csrf

                        @php
                            $issueIcons = [
                                'none' => '
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-5 h-5">
    <circle cx="12" cy="12" r="8.5" stroke-width="1.8" />
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8.5 12.2l2.2 2.2 4.9-5.2" />
</svg>
',

                                'touch' => '
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-5 h-5">
    <rect x="6.5" y="3.5" width="11" height="17" rx="2.5" stroke-width="1.8" />
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 17.5h.01" />
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.5 9.5c.7-.7 1.5-1 2.5-1s1.8.3 2.5 1M8.2 7.2A5.8 5.8 0 0 1 12 5.8a5.8 5.8 0 0 1 3.8 1.4" />
</svg>
',

                                'connect' => '
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-5 h-5">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.8 8.5A15 15 0 0 1 12 5.5a15 15 0 0 1 9.2 3" />
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 12a9.5 9.5 0 0 1 12 0" />
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.2 15.2a5 5 0 0 1 5.6 0" />
    <circle cx="12" cy="18.2" r="1.1" fill="currentColor" stroke="none" />
</svg>
',

                                'vibration' => '
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-5 h-5">
    <rect x="8" y="4" width="8" height="16" rx="2.2" stroke-width="1.8" />
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.5 8.5v7M19.5 8.5v7M2.5 10.5v3M21.5 10.5v3" />
</svg>
',

                                'call' => '
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-5 h-5">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7.2 5.2l2 3.8c.3.6.2 1.2-.3 1.6l-1.1 1c1.1 2.2 2.8 3.9 5 5l1-1.1c.4-.5 1.1-.6 1.6-.3l3.8 2c.6.3.9 1 .7 1.7-.4 1.3-1.6 2.1-3 2.1C9.2 21 3 14.8 3 7.1c0-1.4.8-2.6 2.1-3 .7-.2 1.4.1 1.7 1.1z" />
</svg>
',

                                'face_scan' => '
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-5 h-5">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 4.5H6.8A2.3 2.3 0 0 0 4.5 6.8V8M16 4.5h1.2a2.3 2.3 0 0 1 2.3 2.3V8M19.5 16v1.2a2.3 2.3 0 0 1-2.3 2.3H16M8 19.5H6.8a2.3 2.3 0 0 1-2.3-2.3V16" />
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.5 10.3c.5-.6 1.3-1 2.5-1s2 .4 2.5 1M9.3 14.2c.7.8 1.6 1.3 2.7 1.3s2-.5 2.7-1.3" />
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h.01M15 12h.01" />
</svg>
',

                                'home' => '
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-5 h-5">
    <rect x="7" y="3.5" width="10" height="17" rx="2.5" stroke-width="1.8" />
    <circle cx="12" cy="16.8" r="1.4" stroke-width="1.8" />
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 6.8h4" />
</svg>
',

                                'display' => '
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-5 h-5">
    <rect x="4" y="5" width="16" height="11" rx="2" stroke-width="1.8" />
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 20h6M12 16v4" />
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7.5 9.5h.01M10.5 9.5h.01M13.5 9.5h.01M16.5 9.5h.01" />
</svg>
',

                                'camera' => '
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-5 h-5">
    <rect x="3.5" y="6.5" width="17" height="12" rx="2.5" stroke-width="1.8" />
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 6.5l1.2-2h5.6l1.2 2" />
    <circle cx="12" cy="12.5" r="3.2" stroke-width="1.8" />
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M18 9h.01" />
</svg>
',

                                'sensor' => '
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-5 h-5">
    <circle cx="12" cy="12" r="2.5" stroke-width="1.8" />
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v2.2M12 17.8V20M4 12h2.2M17.8 12H20M6.4 6.4l1.5 1.5M16.1 16.1l1.5 1.5M17.6 6.4l-1.5 1.5M7.9 16.1l-1.5 1.5" />
</svg>
',

                                'button' => '
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-5 h-5">
    <rect x="7" y="3.5" width="10" height="17" rx="2.5" stroke-width="1.8" />
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.8 8.5h1.2M17.8 11.5h1.2M5 10h1.2" />
</svg>
',

                                'speaker' => '
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-5 h-5">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 14.5h3.2l4.3 3V6.5l-4.3 3H5z" />
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.5 10a3 3 0 0 1 0 4" />
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.8 8a6 6 0 0 1 0 8" />
</svg>
',

                                'mic' => '
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-5 h-5">
    <rect x="9" y="4" width="6" height="10" rx="3" stroke-width="1.8" />
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6.5 11.5a5.5 5.5 0 0 0 11 0M12 17v3M9 20h6" />
</svg>
',

                                'charge' => '
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-5 h-5">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 7V4M15 7V4" />
    <rect x="7" y="7" width="10" height="7" rx="1.8" stroke-width="1.8" />
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 18h4M12 14v4" />
</svg>
',

                                'sim' => '
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-5 h-5">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 4.5h6l3 3v12a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2v-13a2 2 0 0 1 2-2z" />
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.5 10.5h5M9.5 14h5" />
</svg>
',

                                'other' => '
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-5 h-5">
    <circle cx="12" cy="12" r="8" stroke-width="1.8" />
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8.2a2.4 2.4 0 0 1 2.4 2.2c0 1.5-1.8 2-2.4 3.1M12 16.8h.01" />
</svg>
',
                            ];

                            $issueIconLabels = [
                                '' => 'ไม่มี',
                                'none' => 'ไม่มีปัญหา',
                                'touch' => 'ระบบสัมผัส',
                                'connect' => 'WiFi / Bluetooth / GPS',
                                'vibration' => 'ระบบสั่น',
                                'call' => 'โทรออก / รับสาย',
                                'face_scan' => 'สแกนนิ้ว / Face Scan',
                                'home' => 'ปุ่ม Home',
                                'display' => 'จอภาพ / ลำโพง',
                                'camera' => 'กล้อง / แฟลช',
                                'sensor' => 'Sensor',
                                'button' => 'ปุ่ม Power / Volume',
                                'speaker' => 'ลำโพง',
                                'mic' => 'ไมโครโฟน',
                                'charge' => 'ชาร์จ',
                                'sim' => 'ซิม',
                                'other' => 'อื่น ๆ',
                            ];
                        @endphp

                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label">ประเภทสินค้า</label>
                                <select name="mobile_product_category_id" id="mobile_product_category_id"
                                    class="form-select">
                                    <option value="">-- เลือกประเภทสินค้า --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ (string) old('mobile_product_category_id', $question->mobile_product_category_id ?? '') === (string) $category->id ? 'selected' : '' }}>
                                            {{ $category->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label">แบรนด์สินค้า</label>
                                <select name="mobile_brand_id" id="mobile_brand_id" class="form-select">
                                    <option value="">-- เลือกแบรนด์สินค้า --</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}"
                                            {{ (string) old('mobile_brand_id', $question->mobile_brand_id ?? '') === (string) $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label">หัวข้อคำถาม <span class="text-danger">*</span></label>
                                <input type="text" name="question_title" class="form-control"
                                    value="{{ old('question_title', $question->question_title ?? '') }}"
                                    placeholder="เช่น สภาพหน้าจอ">
                            </div>

                            <div class="col-span-12 md:col-span-3">
                                <label class="form-label">ประเภทคำตอบ <span class="text-danger">*</span></label>
                                <select name="answer_type" class="form-select">
                                    <option value="single"
                                        {{ old('answer_type', $question->answer_type ?? 'single') === 'single' ? 'selected' : '' }}>
                                        เลือกได้ข้อเดียว
                                    </option>
                                    <option value="multiple"
                                        {{ old('answer_type', $question->answer_type ?? 'single') === 'multiple' ? 'selected' : '' }}>
                                        เลือกได้หลายข้อ
                                    </option>
                                </select>
                            </div>

                            <div class="col-span-12 md:col-span-3">
                                <label class="form-label">ลำดับการแสดง</label>
                                <input type="number" name="sort_order" min="0" class="form-control"
                                    value="{{ old('sort_order', $question->sort_order ?? 0) }}">
                            </div>

                            <div class="col-span-12 md:col-span-3">
                                <label class="form-label">สถานะ</label>
                                <select name="status" class="form-select">
                                    <option value="1"
                                        {{ old('status', isset($question->status) ? (int) $question->status : 1) == 1 ? 'selected' : '' }}>
                                        เปิดใช้งาน
                                    </option>
                                    <option value="0"
                                        {{ old('status', isset($question->status) ? (int) $question->status : 1) == 0 ? 'selected' : '' }}>
                                        ปิดใช้งาน
                                    </option>
                                </select>
                            </div>

                            <div class="col-span-12">
                                <label class="form-label">รายละเอียดเพิ่มเติม</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="รายละเอียดเพิ่มเติม (ถ้ามี)">{{ old('description', $question->description ?? '') }}</textarea>
                            </div>
                        </div>

                        <div class="mt-8">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-lg font-medium">ตัวเลือกคำตอบ</h3>
                                <button type="button" class="btn btn-primary" onclick="addOptionRow()">
                                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                                    เพิ่มตัวเลือก
                                </button>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="table table-bordered" id="options-table">
                                    <thead>
                                        <tr>
                                            <th style="min-width: 220px;">ชื่อตัวเลือก</th>
                                            <th style="min-width: 180px;">ไอคอน</th>
                                            <th style="min-width: 180px;">เกรด</th>
                                            <th style="width: 110px;">ลำดับ</th>
                                            <th style="width: 140px;">สถานะ</th>
                                            <th class="text-center" style="width: 100px;">จัดการ</th>
                                        </tr>
                                    </thead>

                                    <tbody id="option-rows-body">
                                        @php
                                            $oldOptions = old('options');
                                            $rows = is_array($oldOptions) ? $oldOptions : $optionRows;
                                        @endphp

                                        @foreach ($rows as $index => $row)
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="options[{{ $index }}][id]"
                                                        value="{{ $row['id'] ?? '' }}">

                                                    <input type="text" class="form-control"
                                                        name="options[{{ $index }}][option_title]"
                                                        value="{{ old("options.$index.option_title", $row['option_title'] ?? '') }}"
                                                        placeholder="เช่น หน้าจอไม่มีรอย">
                                                </td>

                                                <td>
                                                    @php
                                                        $selectedIconKey = old(
                                                            "options.$index.icon_key",
                                                            $row['icon_key'] ?? '',
                                                        );

                                                        if ($selectedIconKey === 'null') {
                                                            $selectedIconKey = '';
                                                        }

                                                        if (
                                                            !empty($selectedIconKey) &&
                                                            !array_key_exists($selectedIconKey, $issueIcons)
                                                        ) {
                                                            $selectedIconKey = '';
                                                        }
                                                    @endphp

                                                    <div class="flex items-center gap-2">
                                                        <div
                                                            class="w-10 h-10 rounded-lg border flex items-center justify-center text-slate-600 bg-slate-50 option-icon-preview">
                                                            {!! !empty($selectedIconKey) ? $issueIcons[$selectedIconKey] : '' !!}
                                                        </div>

                                                        <select class="form-select option-icon-select"
                                                            name="options[{{ $index }}][icon_key]"
                                                            onchange="changeOptionIconPreview(this)">
                                                            @foreach ($issueIconLabels as $iconKey => $iconLabel)
                                                                <option value="{{ $iconKey }}"
                                                                    {{ $selectedIconKey === $iconKey ? 'selected' : '' }}>
                                                                    {{ $iconLabel }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </td>

                                                <td>
                                                    <select class="form-select"
                                                        name="options[{{ $index }}][grade_master_id]">
                                                        <option value="">-- เลือกเกรด --</option>
                                                        @foreach ($grades as $grade)
                                                            <option value="{{ $grade->id }}"
                                                                {{ (string) old("options.$index.grade_master_id", $row['grade_master_id'] ?? '') === (string) $grade->id ? 'selected' : '' }}>
                                                                {{ $grade->grade_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                <td>
                                                    <input type="number" min="0" class="form-control"
                                                        name="options[{{ $index }}][sort_order]"
                                                        value="{{ old("options.$index.sort_order", $row['sort_order'] ?? 0) }}">
                                                </td>

                                                <td>
                                                    <select class="form-select"
                                                        name="options[{{ $index }}][status]">
                                                        <option value="1"
                                                            {{ (string) old("options.$index.status", (int) ($row['status'] ?? 1)) === '1' ? 'selected' : '' }}>
                                                            เปิดใช้งาน
                                                        </option>
                                                        <option value="0"
                                                            {{ (string) old("options.$index.status", (int) ($row['status'] ?? 1)) === '0' ? 'selected' : '' }}>
                                                            ปิดใช้งาน
                                                        </option>
                                                    </select>
                                                </td>

                                                <td class="text-center">
                                                    <button type="button" class="btn btn-danger"
                                                        onclick="removeOptionRow(this)">
                                                        ลบ
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="mt-5 flex items-center">
                            <a href="{{ url('admin/product-grade-questions') }}"
                                class="btn btn-outline-secondary w-24 mr-2">
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
        let optionRowIndex = {{ count($rows) }};

        const issueIcons = @json($issueIcons);
        const issueIconLabels = @json($issueIconLabels);
        const allowedIssueIconKeys = Object.keys(issueIcons);
        const selectableIssueIconKeys = Object.keys(issueIconLabels);

        function escapeHtml(value) {
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function normalizeIconKey(iconKey) {
            if (iconKey === null || iconKey === undefined) {
                return '';
            }

            iconKey = String(iconKey);

            if (iconKey === 'null') {
                return '';
            }

            if (iconKey !== '' && !allowedIssueIconKeys.includes(iconKey)) {
                return '';
            }

            return iconKey;
        }

        function buildIconOptions(selectedKey = '') {
            selectedKey = normalizeIconKey(selectedKey);

            return selectableIssueIconKeys.map(function(iconKey) {
                const label = issueIconLabels[iconKey] || iconKey || 'ไม่มี';
                const selected = iconKey === selectedKey ? 'selected' : '';

                return `<option value="${escapeHtml(iconKey)}" ${selected}>${escapeHtml(label)}</option>`;
            }).join('');
        }

        function changeOptionIconPreview(select) {
            let selectedKey = normalizeIconKey(select.value);

            select.value = selectedKey;

            const row = select.closest('tr');
            const preview = row.querySelector('.option-icon-preview');

            if (preview) {
                preview.innerHTML = selectedKey !== '' ? (issueIcons[selectedKey] || '') : '';
            }
        }

        function addOptionRow() {
            const tbody = document.getElementById('option-rows-body');

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <input type="hidden" name="options[${optionRowIndex}][id]" value="">
                    <input type="text" class="form-control"
                        name="options[${optionRowIndex}][option_title]"
                        placeholder="เช่น หน้าจอไม่มีรอย">
                </td>

                <td>
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 rounded-lg border flex items-center justify-center text-slate-600 bg-slate-50 option-icon-preview"></div>

                        <select class="form-select option-icon-select"
                            name="options[${optionRowIndex}][icon_key]"
                            onchange="changeOptionIconPreview(this)">
                            ${buildIconOptions('')}
                        </select>
                    </div>
                </td>

                <td>
                    <select class="form-select" name="options[${optionRowIndex}][grade_master_id]">
                        <option value="">-- เลือกเกรด --</option>
                        @foreach ($grades as $grade)
                            <option value="{{ $grade->id }}">{{ $grade->grade_name }}</option>
                        @endforeach
                    </select>
                </td>

                <td>
                    <input type="number" min="0" class="form-control"
                        name="options[${optionRowIndex}][sort_order]" value="0">
                </td>

                <td>
                    <select class="form-select" name="options[${optionRowIndex}][status]">
                        <option value="1" selected>เปิดใช้งาน</option>
                        <option value="0">ปิดใช้งาน</option>
                    </select>
                </td>

                <td class="text-center">
                    <button type="button" class="btn btn-danger" onclick="removeOptionRow(this)">ลบ</button>
                </td>
            `;

            tbody.appendChild(tr);
            optionRowIndex++;

            if (window.lucide) {
                lucide.createIcons();
            }
        }

        function removeOptionRow(button) {
            const tbody = document.getElementById('option-rows-body');

            if (tbody.querySelectorAll('tr').length <= 1) {
                alert('ต้องมีตัวเลือกอย่างน้อย 1 รายการ');
                return;
            }

            button.closest('tr').remove();
        }
    </script>
</body>

</html>
