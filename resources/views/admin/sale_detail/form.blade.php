<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <title>แก้ไขรายละเอียดการขายสินค้า</title>
    @include('admin/inc_header')

    <style>
        .form-section-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 18px;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
        }

        .form-section-header {
            padding: 18px 22px;
            border-bottom: 1px solid #eef2f7;
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 18px 18px 0 0;
        }

        .form-section-body {
            padding: 22px;
        }

        .tab-box {
            border: 1px solid #dbe4ee;
            background: #ffffff;
            border-radius: 18px;
            padding: 18px;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.04);
        }

        .tab-box + .tab-box {
            margin-top: 20px;
        }

        .step-box {
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            border-radius: 16px;
            padding: 16px;
        }

        .step-box + .step-box {
            margin-top: 14px;
        }

        .mini-title {
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: .3px;
        }

        .block-title {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
        }

        .action-btn-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .thumb-preview {
            width: 130px;
            height: 90px;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid #dbe4ee;
            background: #fff;
        }

        .soft-divider {
            height: 1px;
            background: #e2e8f0;
            margin: 18px 0;
        }

        .sticky-save-bar {
            position: sticky;
            bottom: 0;
            z-index: 30;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(10px);
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 14px 18px;
            margin-top: 20px;
            box-shadow: 0 -4px 16px rgba(15, 23, 42, 0.06);
        }
    </style>
</head>

<body class="main">
    @include('admin/inc_mobilemenu')

    <div
        class="top-bar-boxed h-[70px] z-[51] relative border-b border-white/[0.08] -mt-7 md:-mt-5 -mx-3 sm:-mx-8 px-3 sm:px-8 md:pt-0 mb-12">
        <div class="h-full flex items-center">
            <a href="{{ url('admin/') }}" class="-intro-x hidden md:flex">
                <img alt="Midone - HTML Admin Template" class="w-6" src="{{ asset('dist/images/logo.svg') }}">
                <span class="text-white text-lg ml-3"></span>
            </a>

            <nav aria-label="breadcrumb" class="-intro-x h-full mr-auto">
                <ol class="breadcrumb breadcrumb-light">
                    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">หน้าหลัก</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        รายละเอียดการขายสินค้า
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
                <div class="intro-y flex items-center mt-8 mb-4">
                    <h2 class="text-lg font-medium mr-auto">
                        แก้ไขรายละเอียดการขายสินค้า
                    </h2>
                </div>

                @if (session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger mt-3">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger mt-3">
                        <ul class="mb-0 pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @php
                    $saleDetailData = old()
                        ? [
                            'id' => old('id'),
                            'title' => old('title'),
                            'sub_title' => old('sub_title'),
                            'status' => old('status', 'active'),
                            'tabs' => old('tabs', []),
                        ]
                        : [
                            'id' => $saleDetail->id ?? '',
                            'title' => $saleDetail->title ?? '',
                            'sub_title' => $saleDetail->sub_title ?? '',
                            'status' => $saleDetail->status ?? 'active',
                            'tabs' => ($saleDetail->tabs ?? collect())->map(function ($tab) {
                                return [
                                    'id' => $tab->id,
                                    'tab_key' => $tab->tab_key,
                                    'name' => $tab->name,
                                    'sort_order' => $tab->sort_order,
                                    'status' => $tab->status,
                                    'steps' => ($tab->steps ?? collect())->map(function ($step) {
                                        return [
                                            'id' => $step->id,
                                            'step_label' => $step->step_label,
                                            'title' => $step->title,
                                            'description' => $step->description,
                                            'image' => $step->image,
                                            'sort_order' => $step->sort_order,
                                            'status' => $step->status,
                                        ];
                                    })->toArray(),
                                ];
                            })->toArray(),
                        ];
                @endphp

                <form action="{{ url('admin/save-sale-detail') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $saleDetailData['id'] }}">

                    <div class="form-section-card intro-y">
                        <div class="form-section-header">
                            <div class="block-title">ข้อมูลหลัก</div>
                            <div class="text-slate-500 text-sm mt-1">ตั้งค่าหัวข้อและสถานะการแสดงผล</div>
                        </div>
                        <div class="form-section-body">
                            <div class="grid grid-cols-12 gap-5">
                                <div class="col-span-12 md:col-span-5">
                                    <label class="form-label">หัวข้อ <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control"
                                        value="{{ $saleDetailData['title'] }}"
                                        placeholder="เช่น รายละเอียดการขายสินค้า">
                                </div>

                                <div class="col-span-12 md:col-span-5">
                                    <label class="form-label">หัวข้อย่อย</label>
                                    <input type="text" name="sub_title" class="form-control"
                                        value="{{ $saleDetailData['sub_title'] }}"
                                        placeholder="ข้อความรองด้านบนหรือคำอธิบายสั้น ๆ">
                                </div>

                                <div class="col-span-12 md:col-span-2">
                                    <label class="form-label">สถานะ</label>
                                    <select name="status" class="form-select w-full">
                                        <option value="active" {{ $saleDetailData['status'] == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ $saleDetailData['status'] == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section-card intro-y mt-5">
                        <div class="form-section-header flex items-center justify-between gap-3">
                            <div>
                                <div class="block-title">แท็บและขั้นตอน</div>
                                <div class="text-slate-500 text-sm mt-1">เพิ่ม/แก้ไขแท็บ เช่น รับถึงที่ หรือ ส่งพัสดุ</div>
                            </div>
                            <button type="button" id="add-tab" class="btn btn-primary">
                                + เพิ่มแท็บ
                            </button>
                        </div>

                        <div class="form-section-body">
                            <div id="tabs-wrapper">
                                @foreach ($saleDetailData['tabs'] as $tabIndex => $tab)
                                    <div class="tab-box tab-card" data-tab-index="{{ $tabIndex }}">
                                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
                                            <div>
                                                <div class="mini-title">แท็บ</div>
                                                <div class="block-title">แท็บที่ <span class="tab-no">{{ $tabIndex + 1 }}</span></div>
                                            </div>

                                            <div class="action-btn-row">
                                                <button type="button" class="btn btn-success add-step">+ เพิ่มขั้นตอน</button>
                                                <button type="button" class="btn btn-outline-danger remove-tab">ลบแท็บ</button>
                                            </div>
                                        </div>

                                        <input type="hidden" name="tabs[{{ $tabIndex }}][id]" value="{{ $tab['id'] ?? '' }}">

                                        <div class="grid grid-cols-12 gap-4">
                                            <div class="col-span-12 md:col-span-3">
                                                <label class="form-label">Tab Key</label>
                                                <input type="text" name="tabs[{{ $tabIndex }}][tab_key]" class="form-control"
                                                    value="{{ $tab['tab_key'] ?? '' }}"
                                                    placeholder="pickup / delivery">
                                            </div>

                                            <div class="col-span-12 md:col-span-4">
                                                <label class="form-label">ชื่อแท็บ <span class="text-danger">*</span></label>
                                                <input type="text" name="tabs[{{ $tabIndex }}][name]" class="form-control"
                                                    value="{{ $tab['name'] ?? '' }}"
                                                    placeholder="เช่น รับถึงที่">
                                            </div>

                                            <div class="col-span-12 md:col-span-2">
                                                <label class="form-label">ลำดับ</label>
                                                <input type="number" name="tabs[{{ $tabIndex }}][sort_order]" class="form-control"
                                                    value="{{ $tab['sort_order'] ?? ($tabIndex + 1) }}">
                                            </div>

                                            <div class="col-span-12 md:col-span-3">
                                                <label class="form-label">สถานะ</label>
                                                <select name="tabs[{{ $tabIndex }}][status]" class="form-select w-full">
                                                    <option value="active" {{ ($tab['status'] ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                                    <option value="inactive" {{ ($tab['status'] ?? 'active') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="soft-divider"></div>

                                        <div class="mini-title">รายการขั้นตอน</div>

                                        <div class="steps-wrapper">
                                            @foreach (($tab['steps'] ?? []) as $stepIndex => $step)
                                                <div class="step-box step-card" data-step-index="{{ $stepIndex }}">
                                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
                                                        <div class="block-title">
                                                            ขั้นตอนที่ <span class="step-no">{{ $stepIndex + 1 }}</span>
                                                        </div>

                                                        <button type="button" class="btn btn-outline-danger remove-step">
                                                            ลบขั้นตอน
                                                        </button>
                                                    </div>

                                                    <input type="hidden" name="tabs[{{ $tabIndex }}][steps][{{ $stepIndex }}][id]" value="{{ $step['id'] ?? '' }}">
                                                    <input type="hidden" name="tabs[{{ $tabIndex }}][steps][{{ $stepIndex }}][old_image]" value="{{ $step['image'] ?? '' }}">

                                                    <div class="grid grid-cols-12 gap-4">
                                                        <div class="col-span-12 md:col-span-3">
                                                            <label class="form-label">ข้อความลำดับ</label>
                                                            <input type="text"
                                                                name="tabs[{{ $tabIndex }}][steps][{{ $stepIndex }}][step_label]"
                                                                class="form-control"
                                                                value="{{ $step['step_label'] ?? '' }}"
                                                                placeholder="เช่น ขั้นตอนที่ 1">
                                                        </div>

                                                        <div class="col-span-12 md:col-span-4">
                                                            <label class="form-label">หัวข้อ</label>
                                                            <input type="text"
                                                                name="tabs[{{ $tabIndex }}][steps][{{ $stepIndex }}][title]"
                                                                class="form-control"
                                                                value="{{ $step['title'] ?? '' }}"
                                                                placeholder="หัวข้อขั้นตอน">
                                                        </div>

                                                        <div class="col-span-12 md:col-span-2">
                                                            <label class="form-label">ลำดับ</label>
                                                            <input type="number"
                                                                name="tabs[{{ $tabIndex }}][steps][{{ $stepIndex }}][sort_order]"
                                                                class="form-control"
                                                                value="{{ $step['sort_order'] ?? ($stepIndex + 1) }}">
                                                        </div>

                                                        <div class="col-span-12 md:col-span-3">
                                                            <label class="form-label">สถานะ</label>
                                                            <select name="tabs[{{ $tabIndex }}][steps][{{ $stepIndex }}][status]" class="form-select w-full">
                                                                <option value="active" {{ ($step['status'] ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                                                <option value="inactive" {{ ($step['status'] ?? 'active') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-span-12 md:col-span-6">
                                                            <label class="form-label">รายละเอียด</label>
                                                            <textarea
                                                                name="tabs[{{ $tabIndex }}][steps][{{ $stepIndex }}][description]"
                                                                class="form-control"
                                                                rows="5"
                                                                placeholder="กรอกรายละเอียดของขั้นตอนนี้">{{ $step['description'] ?? '' }}</textarea>
                                                        </div>

                                                        <div class="col-span-12 md:col-span-3">
                                                            <label class="form-label">รูปภาพ (280 * 188 px)</label>
                                                            <input type="file"
                                                                name="tabs[{{ $tabIndex }}][steps][{{ $stepIndex }}][image]"
                                                                class="form-control"
                                                                accept="image/*">
                                                        </div>

                                                        <div class="col-span-12 md:col-span-3">
                                                            @if (!empty($step['image']))
                                                                <label class="form-label">รูปปัจจุบัน</label>
                                                                <div>
                                                                    <img src="{{ asset('storage/' . $step['image']) }}"
                                                                        class="thumb-preview"
                                                                        alt="step-image">
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if (empty($saleDetailData['tabs']))
                                <div class="text-center text-slate-500 py-8">
                                    ยังไม่มีแท็บ กดปุ่ม <strong>เพิ่มแท็บ</strong> เพื่อเริ่มสร้างข้อมูล
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="sticky-save-bar">
                        <div class="flex justify-end gap-2">
                            <a href="{{ url('admin/') }}" class="btn btn-outline-secondary">
                                ยกเลิก
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                                บันทึกข้อมูล
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('admin/inc_footer')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabsWrapper = document.getElementById('tabs-wrapper');
            const addTabBtn = document.getElementById('add-tab');

            function refreshIndexes() {
                const tabCards = tabsWrapper.querySelectorAll('.tab-card');

                tabCards.forEach((tabCard, tabIndex) => {
                    tabCard.setAttribute('data-tab-index', tabIndex);

                    const tabNo = tabCard.querySelector('.tab-no');
                    if (tabNo) {
                        tabNo.textContent = tabIndex + 1;
                    }

                    const tabInputs = tabCard.querySelectorAll('input, textarea, select');
                    tabInputs.forEach((input) => {
                        if (input.name) {
                            input.name = input.name.replace(/tabs\[\d+\]/g, `tabs[${tabIndex}]`);
                        }
                    });

                    const stepCards = tabCard.querySelectorAll('.step-card');
                    stepCards.forEach((stepCard, stepIndex) => {
                        stepCard.setAttribute('data-step-index', stepIndex);

                        const stepNo = stepCard.querySelector('.step-no');
                        if (stepNo) {
                            stepNo.textContent = stepIndex + 1;
                        }

                        const stepInputs = stepCard.querySelectorAll('input, textarea, select');
                        stepInputs.forEach((input) => {
                            if (input.name) {
                                input.name = input.name
                                    .replace(/tabs\[\d+\]/g, `tabs[${tabIndex}]`)
                                    .replace(/steps\[\d+\]/g, `steps[${stepIndex}]`);
                            }
                        });
                    });
                });
            }

            function getTabTemplate(tabIndex) {
                return `
                    <div class="tab-box tab-card" data-tab-index="${tabIndex}">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
                            <div>
                                <div class="mini-title">แท็บ</div>
                                <div class="block-title">แท็บที่ <span class="tab-no">${tabIndex + 1}</span></div>
                            </div>

                            <div class="action-btn-row">
                                <button type="button" class="btn btn-success add-step">+ เพิ่มขั้นตอน</button>
                                <button type="button" class="btn btn-outline-danger remove-tab">ลบแท็บ</button>
                            </div>
                        </div>

                        <input type="hidden" name="tabs[${tabIndex}][id]" value="">

                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-12 md:col-span-3">
                                <label class="form-label">Tab Key</label>
                                <input type="text" name="tabs[${tabIndex}][tab_key]" class="form-control" placeholder="pickup / delivery">
                            </div>

                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label">ชื่อแท็บ <span class="text-danger">*</span></label>
                                <input type="text" name="tabs[${tabIndex}][name]" class="form-control" placeholder="เช่น รับถึงที่">
                            </div>

                            <div class="col-span-12 md:col-span-2">
                                <label class="form-label">ลำดับ</label>
                                <input type="number" name="tabs[${tabIndex}][sort_order]" class="form-control" value="${tabIndex + 1}">
                            </div>

                            <div class="col-span-12 md:col-span-3">
                                <label class="form-label">สถานะ</label>
                                <select name="tabs[${tabIndex}][status]" class="form-select w-full">
                                    <option value="active" selected>Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="soft-divider"></div>

                        <div class="mini-title">รายการขั้นตอน</div>
                        <div class="steps-wrapper"></div>
                    </div>
                `;
            }

            function getStepTemplate(tabIndex, stepIndex) {
                return `
                    <div class="step-box step-card" data-step-index="${stepIndex}">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
                            <div class="block-title">
                                ขั้นตอนที่ <span class="step-no">${stepIndex + 1}</span>
                            </div>

                            <button type="button" class="btn btn-outline-danger remove-step">
                                ลบขั้นตอน
                            </button>
                        </div>

                        <input type="hidden" name="tabs[${tabIndex}][steps][${stepIndex}][id]" value="">
                        <input type="hidden" name="tabs[${tabIndex}][steps][${stepIndex}][old_image]" value="">

                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-12 md:col-span-3">
                                <label class="form-label">ข้อความลำดับ</label>
                                <input type="text" name="tabs[${tabIndex}][steps][${stepIndex}][step_label]" class="form-control" placeholder="เช่น ขั้นตอนที่ 1">
                            </div>

                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label">หัวข้อ</label>
                                <input type="text" name="tabs[${tabIndex}][steps][${stepIndex}][title]" class="form-control" placeholder="หัวข้อขั้นตอน">
                            </div>

                            <div class="col-span-12 md:col-span-2">
                                <label class="form-label">ลำดับ</label>
                                <input type="number" name="tabs[${tabIndex}][steps][${stepIndex}][sort_order]" class="form-control" value="${stepIndex + 1}">
                            </div>

                            <div class="col-span-12 md:col-span-3">
                                <label class="form-label">สถานะ</label>
                                <select name="tabs[${tabIndex}][steps][${stepIndex}][status]" class="form-select w-full">
                                    <option value="active" selected>Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>

                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label">รายละเอียด</label>
                                <textarea name="tabs[${tabIndex}][steps][${stepIndex}][description]" class="form-control" rows="5" placeholder="กรอกรายละเอียดของขั้นตอนนี้"></textarea>
                            </div>

                            <div class="col-span-12 md:col-span-3">
                                <label class="form-label">รูปภาพ (280 * 188 px)</label>
                                <input type="file" name="tabs[${tabIndex}][steps][${stepIndex}][image]" class="form-control" accept="image/*">
                            </div>

                            <div class="col-span-12 md:col-span-3"></div>
                        </div>
                    </div>
                `;
            }

            if (addTabBtn) {
                addTabBtn.addEventListener('click', function() {
                    const tabIndex = tabsWrapper.querySelectorAll('.tab-card').length;
                    tabsWrapper.insertAdjacentHTML('beforeend', getTabTemplate(tabIndex));
                    refreshIndexes();
                });
            }

            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-tab')) {
                    e.target.closest('.tab-card').remove();
                    refreshIndexes();
                }

                if (e.target.classList.contains('add-step')) {
                    const tabCard = e.target.closest('.tab-card');
                    const tabIndex = Array.from(tabsWrapper.querySelectorAll('.tab-card')).indexOf(tabCard);
                    const stepsWrapper = tabCard.querySelector('.steps-wrapper');
                    const stepIndex = stepsWrapper.querySelectorAll('.step-card').length;

                    stepsWrapper.insertAdjacentHTML('beforeend', getStepTemplate(tabIndex, stepIndex));
                    refreshIndexes();
                }

                if (e.target.classList.contains('remove-step')) {
                    e.target.closest('.step-card').remove();
                    refreshIndexes();
                }
            });

            refreshIndexes();
        });
    </script>
</body>

</html>