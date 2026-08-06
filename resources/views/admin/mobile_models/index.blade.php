<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <title>โมเดลสินค้า - mobile models</title>
    @include('admin/inc_header')
</head>

<body class="main">
    @include('admin/inc_mobilemenu')

    @php
        $selectedCategory = $selectedCategory ?? ($category ?? null);
        $selectedBrand = $selectedBrand ?? null;

        $categoryId = $selectedCategory?->id ?? request()->route('category');

        $brandId = $selectedBrand?->id ?? ($selectedCategory?->mobile_brand_id ?? request()->route('brand'));

        $categoryName = $selectedCategory?->category_name ?? 'ทั้งหมด';
        $brandName = $selectedBrand?->name ?? 'ทั้งหมด';

        /*
    |--------------------------------------------------------------------------
    | ตรวจสอบว่าหน้านี้เข้าผ่านทางใด
    |--------------------------------------------------------------------------
    */

        $pageScope = !empty($categoryId) ? 'category' : 'brand';
        $scopeId = $pageScope === 'category' ? $categoryId : $brandId;

        $exportTemplateUrl =
            $pageScope === 'category'
                ? url('admin/mobile-models/category/' . $categoryId . '/export-template')
                : url('admin/mobile-models/brand/' . $brandId . '/export-template');

        $exportUrl =
            $pageScope === 'category'
                ? url('admin/mobile-models/category/' . $categoryId . '/export')
                : url('admin/mobile-models/brand/' . $brandId . '/export');

        $createUrl =
            $pageScope === 'category'
                ? url('admin/mobile-models/category/' . $categoryId . '/create')
                : url('admin/mobile-models/brand/' . $brandId . '/create');
    @endphp

    <div
        class="top-bar-boxed h-[70px] z-[51] relative border-b border-white/[0.08] -mt-7 md:-mt-5 -mx-3 sm:-mx-8 px-3 sm:px-8 md:pt-0 mb-12">
        <div class="h-full flex items-center">
            <a href="{{ url('admin/') }}" class="-intro-x hidden md:flex">
                <img alt="Midone - HTML Admin Template" class="w-6" src="{{ asset('dist/images/logo.svg') }}">
                <span class="text-white text-lg ml-3"></span>
            </a>

            <nav aria-label="breadcrumb" class="-intro-x h-full mr-auto">
                <ol class="breadcrumb breadcrumb-light">
                    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">สินค้า</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('admin/mobile-brands') }}">แบรนด์สินค้า</a></li>

                    @if ($brandId)
                        <li class="breadcrumb-item">
                            <a href="{{ url('admin/mobile-product-categories/' . $brandId) }}">ประเภทสินค้า</a>
                        </li>
                    @endif

                    <li class="breadcrumb-item active" aria-current="page">โมเดลสินค้า : {{ $categoryName }}</li>
                </ol>
            </nav>

            @include('admin/inc_account')
        </div>
    </div>

    <div class="wrapper">
        <div class="wrapper-box">
            @include('admin/inc_sidemenu')

            <div class="content">
                <div class="intro-y flex items-center mt-8 mb-2">
                    <h2 class="text-lg font-medium mr-auto">
                        โมเดลสินค้า : {{ $categoryName }}
                    </h2>
                </div>

                <div class="intro-y col-span-12 lg:col-span-12">
                    <div class="intro-y box rounded-2xl shadow-lg bg-white dark:bg-slate-800">
                        <div class="p-6">
                            <div class="p-5 bg-slate-50 dark:bg-slate-700 rounded-lg">

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
                                        {{ $errors->first() }}
                                    </div>
                                @endif

                                <div class="flex flex-col gap-4 mb-5">
                                    <div class="flex flex-wrap items-center justify-between gap-3">
                                        <div>
                                            <div class="text-base font-medium text-slate-700">
                                                จัดการข้อมูลโมเดลสินค้า
                                            </div>

                                            <div class="text-slate-500 text-sm mt-1">
                                                แบรนด์ปัจจุบัน: <b>{{ $brandName }}</b><br>
                                                ประเภทสินค้าปัจจุบัน: <b>{{ $categoryName }}</b><br>
                                                สามารถดาวน์โหลดไฟล์ตัวอย่าง, export ข้อมูลเดิม และ import
                                                กลับเข้าระบบได้
                                            </div>
                                        </div>

                                        <div class="flex flex-wrap gap-2">
                                            @if ($scopeId)
                                                <a href="{{ $exportTemplateUrl }}" class="btn btn-outline-secondary">
                                                    <i data-lucide="file-down" class="w-4 h-4 mr-2"></i>
                                                    ดาวน์โหลด Template
                                                </a>

                                                <a href="{{ $exportUrl }}" class="btn text-white"
                                                    style="background-color:#0f9d8a; border-color:#0f9d8a;">
                                                    <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                                                    Export Excel
                                                </a>

                                                <a href="{{ $createUrl }}" class="btn btn-primary">
                                                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                                                    เพิ่มโมเดลสินค้า
                                                </a>
                                            @else
                                                <div class="alert alert-warning">
                                                    ไม่พบรหัสแบรนด์หรือรหัสประเภทสินค้า
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                                        <form action="{{ url('admin/mobile-models/import') }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="scope" value="{{ $pageScope }}">

                                            <input type="hidden" name="mobile_brand_id" value="{{ $brandId }}">

                                            <input type="hidden" name="mobile_product_category_id"
                                                value="{{ $categoryId }}">

                                            <div class="grid grid-cols-12 gap-4 items-end">
                                                <div class="col-span-12 xl:col-span-3">
                                                    <div class="text-base font-medium text-slate-700">Import Excel</div>
                                                    <div class="text-slate-500 text-sm mt-1">
                                                        รองรับไฟล์ .xlsx, .xls และ .csv
                                                    </div>
                                                </div>

                                                <div class="col-span-12 xl:col-span-6">
                                                    <label class="form-label mb-2">เลือกไฟล์</label>

                                                    <div class="flex flex-col md:flex-row gap-2">
                                                        <label for="import_file"
                                                            class="btn btn-outline-secondary whitespace-nowrap">
                                                            <i data-lucide="folder-open" class="w-4 h-4 mr-2"></i>
                                                            เลือกไฟล์
                                                        </label>

                                                        <input id="import_file" type="file" name="file"
                                                            class="hidden" accept=".xlsx,.xls,.csv" required
                                                            onchange="document.getElementById('selected-file-name').value = this.files.length ? this.files[0].name : ''">

                                                        <input id="selected-file-name" type="text"
                                                            class="form-control h-11" placeholder="ยังไม่ได้เลือกไฟล์"
                                                            readonly>
                                                    </div>
                                                </div>

                                                <div class="col-span-12 xl:col-span-3">
                                                    <button type="submit" class="btn btn-primary w-full h-11">
                                                        <i data-lucide="upload" class="w-4 h-4 mr-2"></i>
                                                        นำเข้าข้อมูล
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div
                                    class="table-responsive rounded-2xl border border-slate-200 bg-white overflow-hidden">
                                    <table class="table table-report mb-0" id="models-table">
                                        <thead class="bg-slate-50">
                                            <tr>
                                                <th class="whitespace-nowrap text-center">
                                                    ลำดับการแสดงผล
                                                </th>

                                                <th class="whitespace-nowrap">
                                                    แบรนด์สินค้า
                                                </th>

                                                <th class="whitespace-nowrap">
                                                    ประเภทสินค้า
                                                </th>

                                                <th class="whitespace-nowrap">
                                                    ชื่อโมเดล
                                                </th>

                                                <th class="whitespace-nowrap text-center">
                                                    สถานะ
                                                </th>

                                                <th class="text-center whitespace-nowrap">
                                                    ACTIONS
                                                </th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse ($models as $value)
                                                <tr class="intro-x">
                                                    <td class="text-center font-medium"
                                                        data-order="{{ (int) $value->sort_order }}">
                                                        {{ (int) $value->sort_order }}
                                                    </td>

                                                    <td>
                                                        {{ $value->brand->name ?? '-' }}
                                                    </td>

                                                    <td>
                                                        {{ $value->productCategory->category_name ?? '-' }}
                                                    </td>

                                                    <td>
                                                        {{ $value->name }}
                                                    </td>

                                                    <td class="text-center">
                                                        @if ((int) $value->status === 1)
                                                            <span class="px-3 py-1 rounded-full text-white bg-success">
                                                                เปิดใช้งาน
                                                            </span>
                                                        @else
                                                            <span class="px-3 py-1 rounded-full text-white bg-danger">
                                                                ปิดใช้งาน
                                                            </span>
                                                        @endif
                                                    </td>

                                                    <td class="text-center">
                                                        <div class="flex justify-center items-center flex-wrap gap-3">
                                                            <a class="flex items-center"
                                                                href="{{ url('admin/mobile-models/' . $value->id . '/edit') }}?scope={{ $pageScope }}">
                                                                <i data-lucide="check-square"
                                                                    class="w-4 h-4 mr-1"></i>

                                                                แก้ไข
                                                            </a>

                                                            <a href="javascript:void(0);"
                                                                class="flex items-center text-danger"
                                                                onclick='openDeleteModal(
                                    {{ $value->id }},
                                    @json($value->name)
                                )'>
                                                                <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i>

                                                                ลบข้อมูล
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-slate-500 py-5">
                                                        ยังไม่มีข้อมูลโมเดลสินค้า
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="delete-confirmation-modal" class="modal" tabindex="-1" aria-hidden="true" data-tw-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <form method="POST" action="{{ url('admin/mobile-models/delete') }}">
                        @csrf

                        <div class="p-5 text-center">
                            <input type="hidden" id="delete-id" name="id">

                            <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>

                            <div class="text-3xl mt-5">คุณต้องการลบข้อมูล?</div>

                            <div class="text-slate-500 mt-2" id="delete-text">
                                หากลบแล้วจะไม่สามารถกู้คืนข้อมูลเดิมได้
                            </div>
                        </div>

                        <div class="px-5 pb-8 text-center">
                            <button type="button" data-tw-dismiss="modal"
                                class="btn btn-outline-secondary w-24 mr-1">ยกเลิก</button>

                            <button type="submit" class="btn btn-danger w-24">ลบข้อมูล</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('admin/inc_footer')

    <script>
        function openDeleteModal(id, name) {
            document.getElementById('delete-id').value = id;

            const deleteText = document.getElementById('delete-text');
            deleteText.innerHTML = 'คุณต้องการลบโมเดลสินค้า <b>' + name +
                '</b> ใช่หรือไม่?<br>หากลบแล้วจะไม่สามารถกู้คืนข้อมูลเดิมได้';

            const modalEl = document.querySelector('#delete-confirmation-modal');
            const modal = tailwind.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }

        jQuery(document).ready(function($) {
            $('#models-table').DataTable({
                responsive: true,

                /*
                |--------------------------------------------------------------------------
                | เรียงตาม sort_order จากน้อยไปมาก
                |--------------------------------------------------------------------------
                */

                order: [
                    [0, 'asc']
                ],

                columnDefs: [{
                        targets: 0,
                        type: 'num'
                    },
                    {
                        targets: 5,
                        orderable: false,
                        searchable: false
                    }
                ],

                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.21/i18n/Thai.json'
                }
            });
        });
    </script>
</body>

</html>
