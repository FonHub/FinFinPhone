<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <title>{{ $mode === 'edit' ? 'แก้ไขคำถามสินค้า' : 'เพิ่มคำถามสินค้า' }}</title>
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
                    <li class="breadcrumb-item"><a href="{{ url('admin/product-questions') }}">คำถามสินค้า</a></li>
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
                        {{ $mode === 'edit' ? 'แก้ไขคำถามสินค้า' : 'เพิ่มคำถามสินค้า' }}
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
                        action="{{ $mode === 'edit' ? url('admin/product-questions/' . $question->id . '/update') : url('admin/product-questions/store') }}">
                        @csrf

                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label">ประเภทสินค้า</label>
                                <select name="mobile_product_category_id" id="mobile_product_category_id"
                                    class="form-select" onchange="filterModelsByCategory()">
                                    <option value="">-- เลือกประเภทสินค้า --</option>

                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ (string) old('mobile_product_category_id', $question->mobile_product_category_id ?? '') === (string) $category->id ? 'selected' : '' }}>
                                            {{ $category->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-help">
                                    เมื่อเลือกประเภทสินค้า ช่องโมเดลด้านล่างจะแสดงเฉพาะโมเดลในประเภทนั้น
                                </div>
                            </div>

                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label">คำถาม <span class="text-danger">*</span></label>
                                <input type="text" name="question" class="form-control"
                                    value="{{ old('question', $question->question ?? '') }}"
                                    placeholder="กรอกคำถามสินค้า">
                            </div>

                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label">ประเภทคำถาม <span class="text-danger">*</span></label>
                                <select name="question_type" id="question_type" class="form-select"
                                    onchange="toggleAnswerSection()">
                                    <option value="general"
                                        {{ old('question_type', $question->question_type ?? 'general') === 'general' ? 'selected' : '' }}>
                                        คำถามทั่วไป
                                    </option>

                                    <option value="model_specific"
                                        {{ old('question_type', $question->question_type ?? 'general') === 'model_specific' ? 'selected' : '' }}>
                                        คำถามเฉพาะโมเดล
                                    </option>
                                </select>
                            </div>

                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label">ลำดับการแสดง</label>
                                <input type="number" name="sort_order" class="form-control"
                                    value="{{ old('sort_order', $question->sort_order ?? 0) }}" min="0">
                            </div>

                            <div class="col-span-12 md:col-span-4">
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
                        </div>

                        <div id="general-answer-section" class="mt-6">
                            <label class="form-label">คำตอบทั่วไป</label>

                            <textarea name="general_answer" class="form-control" rows="4" placeholder="กรอกคำตอบทั่วไป">{{ old('general_answer', $question->general_answer ?? '') }}</textarea>
                        </div>

                        @php
                            $oldAnswers = old('answers');
                            $rows = is_array($oldAnswers) ? $oldAnswers : $answerRows;

                            $mobileModelList = collect($mobileModels)
                                ->map(function ($mobileModel) {
                                    $brandName = $mobileModel->brand->name ?? '-';
                                    $categoryName = $mobileModel->productCategory->category_name ?? '-';
                                    $modelName = $mobileModel->name ?? '-';

                                    return [
                                        'id' => (int) $mobileModel->id,
                                        'category_id' => (int) $mobileModel->mobile_product_category_id,
                                        'label' => $brandName . ' / ' . $categoryName . ' / ' . $modelName,
                                    ];
                                })
                                ->values()
                                ->toArray();

                            $mobileModelLabelMap = collect($mobileModelList)
                                ->keyBy('id')
                                ->map(function ($item) {
                                    return $item['label'];
                                })
                                ->toArray();
                        @endphp

                        <div id="model-answer-section" class="mt-8">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <h3 class="text-lg font-medium">คำตอบแยกตามโมเดลสินค้า</h3>
                                    <div class="text-slate-500 text-sm mt-1">
                                        พิมพ์ชื่อแบรนด์ / ประเภท / รุ่น เพื่อค้นหาโมเดลได้
                                    </div>
                                </div>

                                <button type="button" class="btn btn-primary" onclick="addAnswerRow()">
                                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                                    เพิ่มคำตอบ
                                </button>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="table table-bordered" id="answer-rows-table">
                                    <thead>
                                        <tr>
                                            <th style="min-width: 340px;">โมเดลสินค้า</th>
                                            <th style="min-width: 320px;">คำตอบ</th>
                                            <th style="width: 120px;">เรียงลำดับ</th>
                                            <th style="width: 160px;">สถานะ</th>
                                            <th class="text-center" style="width: 110px;">จัดการ</th>
                                        </tr>
                                    </thead>

                                    <tbody id="answer-rows-body">
                                        @foreach ($rows as $index => $row)
                                            @php
                                                $selectedModelId = $row['mobile_model_id'] ?? '';
                                                $selectedModelLabel = '';

                                                if (
                                                    !empty($selectedModelId) &&
                                                    isset($mobileModelLabelMap[(int) $selectedModelId])
                                                ) {
                                                    $selectedModelLabel = $mobileModelLabelMap[(int) $selectedModelId];
                                                }
                                            @endphp

                                            <tr>
                                                <td>
                                                    <input type="hidden" class="model-id-input"
                                                        name="answers[{{ $index }}][mobile_model_id]"
                                                        value="{{ $selectedModelId }}">

                                                    <input type="hidden" name="answers[{{ $index }}][id]"
                                                        value="{{ $row['id'] ?? '' }}">

                                                    <input type="text" class="form-control model-search-input"
                                                        list="model-options-{{ $index }}"
                                                        value="{{ $selectedModelLabel }}"
                                                        placeholder="พิมพ์เพื่อค้นหาโมเดลสินค้า" autocomplete="off"
                                                        oninput="handleModelInput(this)"
                                                        onchange="handleModelInput(this)">

                                                    <datalist id="model-options-{{ $index }}"
                                                        class="model-datalist"></datalist>

                                                    <div class="text-xs text-slate-500 mt-1">
                                                        ต้องเลือกจากรายการที่ระบบแนะนำเท่านั้น
                                                    </div>
                                                </td>

                                                <td>
                                                    <textarea class="form-control" name="answers[{{ $index }}][answer]" rows="2" placeholder="กรอกคำตอบ">{{ $row['answer'] ?? '' }}</textarea>
                                                </td>

                                                <td style="width: 120px;">
                                                    <input type="number" min="0" class="form-control"
                                                        name="answers[{{ $index }}][sort_order]"
                                                        value="{{ $row['sort_order'] ?? 0 }}">
                                                </td>

                                                <td style="width: 160px;">
                                                    <select class="form-select"
                                                        name="answers[{{ $index }}][status]">
                                                        <option value="1"
                                                            {{ (int) ($row['status'] ?? 1) === 1 ? 'selected' : '' }}>
                                                            เปิดใช้งาน
                                                        </option>
                                                        <option value="0"
                                                            {{ (int) ($row['status'] ?? 1) === 0 ? 'selected' : '' }}>
                                                            ปิดใช้งาน
                                                        </option>
                                                    </select>
                                                </td>

                                                <td class="text-center" style="width: 110px;">
                                                    <button type="button" class="btn btn-danger"
                                                        onclick="removeAnswerRow(this)">
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
                            <a href="{{ url('admin/product-questions') }}"
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
        let answerRowIndex = {{ count($rows) }};

        const allMobileModels = @json($mobileModelList);

        function getSelectedCategoryId() {
            const categorySelect = document.getElementById('mobile_product_category_id');

            if (!categorySelect) {
                return '';
            }

            return String(categorySelect.value || '');
        }

        function getFilteredModels() {
            const categoryId = getSelectedCategoryId();

            if (!categoryId) {
                return allMobileModels;
            }

            return allMobileModels.filter(function(model) {
                return String(model.category_id) === String(categoryId);
            });
        }

        function renderModelDatalist(datalist) {
            if (!datalist) {
                return;
            }

            const models = getFilteredModels();

            datalist.innerHTML = '';

            models.forEach(function(model) {
                const option = document.createElement('option');
                option.value = model.label;
                option.setAttribute('data-id', model.id);
                option.setAttribute('data-category-id', model.category_id);

                datalist.appendChild(option);
            });
        }

        function renderAllModelDatalists() {
            document.querySelectorAll('.model-datalist').forEach(function(datalist) {
                renderModelDatalist(datalist);
            });
        }

        function findModelByLabel(label) {
            const models = getFilteredModels();

            return models.find(function(model) {
                return String(model.label) === String(label);
            });
        }

        function findModelById(id) {
            return allMobileModels.find(function(model) {
                return String(model.id) === String(id);
            });
        }

        function handleModelInput(input) {
            const row = input.closest('tr');
            const hiddenInput = row ? row.querySelector('.model-id-input') : null;

            if (!hiddenInput) {
                return;
            }

            const model = findModelByLabel(input.value);

            if (model) {
                hiddenInput.value = model.id;
                input.classList.remove('border-danger');
            } else {
                hiddenInput.value = '';
                input.classList.add('border-danger');
            }
        }

        function validateSelectedModelsAfterCategoryChange() {
            const categoryId = getSelectedCategoryId();

            document.querySelectorAll('#answer-rows-body tr').forEach(function(row) {
                const input = row.querySelector('.model-search-input');
                const hiddenInput = row.querySelector('.model-id-input');

                if (!input || !hiddenInput) {
                    return;
                }

                if (!hiddenInput.value) {
                    input.value = '';
                    input.classList.remove('border-danger');
                    return;
                }

                const model = findModelById(hiddenInput.value);

                if (!model) {
                    input.value = '';
                    hiddenInput.value = '';
                    input.classList.remove('border-danger');
                    return;
                }

                if (categoryId && String(model.category_id) !== String(categoryId)) {
                    input.value = '';
                    hiddenInput.value = '';
                    input.classList.remove('border-danger');
                    return;
                }

                input.value = model.label;
                input.classList.remove('border-danger');
            });
        }

        function toggleAnswerSection() {
            const type = document.getElementById('question_type').value;
            const modelSection = document.getElementById('model-answer-section');
            const generalSection = document.getElementById('general-answer-section');

            if (type === 'model_specific') {
                modelSection.style.display = '';
                generalSection.style.display = 'none';
            } else {
                modelSection.style.display = 'none';
                generalSection.style.display = '';
            }
        }

        function filterModelsByCategory() {
            renderAllModelDatalists();
            validateSelectedModelsAfterCategoryChange();
        }

        function addAnswerRow() {
            const tbody = document.getElementById('answer-rows-body');
            const datalistId = 'model-options-' + answerRowIndex;

            const tr = document.createElement('tr');

            tr.innerHTML = `
                <td>
                    <input type="hidden" class="model-id-input" name="answers[${answerRowIndex}][mobile_model_id]" value="">
                    <input type="hidden" name="answers[${answerRowIndex}][id]" value="">

                    <input type="text"
                        class="form-control model-search-input"
                        list="${datalistId}"
                        value=""
                        placeholder="พิมพ์เพื่อค้นหาโมเดลสินค้า"
                        autocomplete="off"
                        oninput="handleModelInput(this)"
                        onchange="handleModelInput(this)">

                    <datalist id="${datalistId}" class="model-datalist"></datalist>

                    <div class="text-xs text-slate-500 mt-1">
                        ต้องเลือกจากรายการที่ระบบแนะนำเท่านั้น
                    </div>
                </td>

                <td>
                    <textarea class="form-control" name="answers[${answerRowIndex}][answer]" rows="2" placeholder="กรอกคำตอบ"></textarea>
                </td>

                <td style="width: 120px;">
                    <input type="number" min="0" class="form-control" name="answers[${answerRowIndex}][sort_order]" value="0">
                </td>

                <td style="width: 160px;">
                    <select class="form-select" name="answers[${answerRowIndex}][status]">
                        <option value="1" selected>เปิดใช้งาน</option>
                        <option value="0">ปิดใช้งาน</option>
                    </select>
                </td>

                <td class="text-center" style="width: 110px;">
                    <button type="button" class="btn btn-danger" onclick="removeAnswerRow(this)">ลบ</button>
                </td>
            `;

            tbody.appendChild(tr);

            const datalist = tr.querySelector('.model-datalist');
            renderModelDatalist(datalist);

            answerRowIndex++;

            if (window.lucide) {
                lucide.createIcons();
            }
        }

        function removeAnswerRow(button) {
            const tbody = document.getElementById('answer-rows-body');

            if (tbody.querySelectorAll('tr').length <= 1) {
                const type = document.getElementById('question_type').value;

                if (type === 'model_specific') {
                    alert('คำถามแบบเฉพาะโมเดลต้องมีคำตอบอย่างน้อย 1 รายการ');
                    return;
                }
            }

            button.closest('tr').remove();
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleAnswerSection();
            renderAllModelDatalists();
            validateSelectedModelsAfterCategoryChange();
        });
    </script>
</body>

</html>
