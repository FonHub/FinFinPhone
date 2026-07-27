<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <title>ชุดคำถามคัดเกรดสินค้า</title>
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
                    <li class="breadcrumb-item">
                        <a href="{{ url('admin/') }}">สินค้า</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        ชุดคำถามคัดเกรดสินค้า
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
                <div class="intro-y flex items-center mt-8 mb-2">
                    <h2 class="text-lg font-medium mr-auto">
                        ชุดคำถามคัดเกรดสินค้า
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

                                <div class="grid grid-cols-12 mt-5">
                                    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">

                                        <div class="flex justify-end mb-4">
                                            <a href="{{ url('admin/product-grade-questions/create') }}"
                                                class="btn btn-primary">
                                                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                                                เพิ่มชุดคำถาม
                                            </a>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-report mt-2" id="questions-table">
                                                <thead>
                                                    <tr>
                                                        <th class="whitespace-nowrap text-center">ลำดับ</th>
                                                        <th class="whitespace-nowrap">ประเภทสินค้า</th>
                                                        <th class="whitespace-nowrap">แบรนด์สินค้า</th>
                                                        <th class="whitespace-nowrap">หัวข้อคำถาม</th>
                                                        <th class="whitespace-nowrap text-center">ประเภทคำตอบ</th>
                                                        <th class="whitespace-nowrap text-center">จำนวนตัวเลือก</th>
                                                        <th class="whitespace-nowrap text-center">ลำดับการแสดง</th>
                                                        <th class="whitespace-nowrap text-center">สถานะ</th>
                                                        <th class="text-center whitespace-nowrap">ACTIONS</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @forelse ($questions as $key => $value)
                                                        <tr class="intro-x">
                                                            <td class="text-center">{{ $key + 1 }}</td>

                                                            <td>
                                                                {{ $value->productCategory->category_name ?? '-' }}
                                                            </td>
                                                            <td>
                                                                {{ $value->brand->name ?? '-' }}
                                                            </td>

                                                            <td>
                                                                {{ $value->question_title }}
                                                            </td>

                                                            <td class="text-center">
                                                                @if ($value->answer_type === 'multiple')
                                                                    <span
                                                                        class="px-3 py-1 rounded-full text-white bg-warning">
                                                                        เลือกได้หลายข้อ
                                                                    </span>
                                                                @else
                                                                    <span
                                                                        class="px-3 py-1 rounded-full text-white bg-primary">
                                                                        เลือกได้ข้อเดียว
                                                                    </span>
                                                                @endif
                                                            </td>

                                                            <td class="text-center">
                                                                {{ $value->options_count }}
                                                            </td>

                                                            <td class="text-center">
                                                                {{ $value->sort_order }}
                                                            </td>

                                                            <td class="text-center">
                                                                @if ((int) $value->status === 1)
                                                                    <span
                                                                        class="px-3 py-1 rounded-full text-white bg-success">
                                                                        เปิดใช้งาน
                                                                    </span>
                                                                @else
                                                                    <span
                                                                        class="px-3 py-1 rounded-full text-white bg-danger">
                                                                        ปิดใช้งาน
                                                                    </span>
                                                                @endif
                                                            </td>

                                                            <td class="text-center">
                                                                <div
                                                                    class="flex justify-center items-center flex-wrap gap-3">
                                                                    <a class="flex items-center"
                                                                        href="{{ url('admin/product-grade-questions/' . $value->id . '/edit') }}">
                                                                        <i data-lucide="check-square"
                                                                            class="w-4 h-4 mr-1"></i>
                                                                        แก้ไข
                                                                    </a>

                                                                    <a href="javascript:void(0);"
                                                                        class="flex items-center text-danger"
                                                                        onclick="openDeleteModal({{ $value->id }}, '{{ addslashes($value->question_title) }}')">
                                                                        <i data-lucide="trash-2"
                                                                            class="w-4 h-4 mr-1"></i>
                                                                        ลบข้อมูล
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="9" class="text-center text-slate-500 py-5">
                                                                ยังไม่มีข้อมูลชุดคำถาม
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
        </div>
    </div>

    <div id="delete-confirmation-modal" class="modal" tabindex="-1" aria-hidden="true" data-tw-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <form method="POST" action="{{ url('admin/product-grade-questions/delete') }}">
                        @csrf

                        <div class="p-5 text-center">
                            <input type="hidden" id="delete-id" name="id">

                            <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>

                            <div class="text-3xl mt-5">
                                คุณต้องการลบข้อมูล?
                            </div>

                            <div class="text-slate-500 mt-2" id="delete-text">
                                หากลบแล้วจะไม่สามารถกู้คืนข้อมูลเดิมได้
                            </div>
                        </div>

                        <div class="px-5 pb-8 text-center">
                            <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">
                                ยกเลิก
                            </button>

                            <button type="submit" class="btn btn-danger w-24">
                                ลบข้อมูล
                            </button>
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
            deleteText.innerHTML = 'คุณต้องการลบชุดคำถาม <b>' + name +
                '</b> ใช่หรือไม่?<br>หากลบแล้วจะไม่สามารถกู้คืนข้อมูลเดิมได้';

            const modalEl = document.querySelector('#delete-confirmation-modal');
            const modal = tailwind.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }

        jQuery(document).ready(function($) {
            $('#questions-table').DataTable({
                responsive: true,
                order: [
                    [5, "asc"],
                    [0, "desc"]
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Thai.json"
                }
            });
        });
    </script>
</body>

</html>
