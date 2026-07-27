<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <title>บทความ / ข่าวสาร - News</title>
    @include('admin/inc_header')
</head>

<body class="main">
    @include('admin/inc_mobilemenu')

    <div
        class="top-bar-boxed h-[70px] z-[51] relative border-b border-white/[0.08] -mt-7 md:-mt-5 -mx-3 sm:-mx-8 px-3 sm:px-8 md:pt-0 mb-12">
        <div class="h-full flex items-center">
            <a href="{{ url('admin/') }}" class="-intro-x hidden md:flex">
                <img alt="Admin Logo" class="w-6" src="{{ asset('dist/images/logo.svg') }}">
                <span class="text-white text-lg ml-3"></span>
            </a>

            <nav aria-label="breadcrumb" class="-intro-x h-full mr-auto">
                <ol class="breadcrumb breadcrumb-light">
                    <li class="breadcrumb-item">
                        <a href="{{ url('admin/') }}">แดชบอร์ด</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        บทความ / ข่าวสาร
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
                        บทความ / ข่าวสาร
                    </h2>
                </div>

                <div class="intro-y col-span-12 lg:col-span-12">
                    <div class="intro-y box rounded-2xl shadow-lg bg-white dark:bg-slate-800">
                        <div id="input" class="p-6">
                            <div id="inline-form" class="p-5 bg-slate-50 dark:bg-slate-700 rounded-lg">
                                <div class="preview">

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

                                            <div
                                                class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
                                                <div>
                                                    <div class="text-lg font-medium text-slate-800 dark:text-slate-100">
                                                        รายการบทความ / ข่าวสาร
                                                    </div>
                                                    <div class="text-slate-500 text-sm mt-1">
                                                        จัดการบทความที่แสดงในหน้าบ้าน
                                                    </div>
                                                </div>

                                                <a href="{{ url('admin/form-news') }}" class="btn btn-primary">
                                                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                                                    เพิ่มข้อมูล
                                                </a>
                                            </div>

                                            <div class="table-responsive">
                                                <table class="table table-report mt-2" id="news-table">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center whitespace-nowrap">ลำดับ</th>
                                                            <th class="text-center whitespace-nowrap">รูปภาพ</th>
                                                            <th class="whitespace-nowrap">หัวข้อ</th>
                                                            <th class="text-center whitespace-nowrap">สถานะ</th>
                                                            <th class="text-center whitespace-nowrap">วันที่เผยแพร่</th>
                                                            <th class="text-center whitespace-nowrap">วันที่สร้าง</th>
                                                            <th class="text-center whitespace-nowrap">จัดการ</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        @if (isset($news) && $news->count() > 0)
                                                            @foreach ($news as $key => $value)
                                                                @php
                                                                    $statusValue = $value->status;

                                                                    $isActive = false;

                                                                    if (
                                                                        (string) $statusValue === '1' ||
                                                                        $statusValue === 'active'
                                                                    ) {
                                                                        $isActive = true;
                                                                    }

                                                                    $publishedAt = !empty($value->published_at)
                                                                        ? \Carbon\Carbon::parse(
                                                                            $value->published_at,
                                                                        )->format('d/m/Y H:i')
                                                                        : '-';

                                                                    $createdAt = !empty($value->created_at)
                                                                        ? \Carbon\Carbon::parse(
                                                                            $value->created_at,
                                                                        )->format('d/m/Y H:i')
                                                                        : '-';

                                                                    $imageUrl = !empty($value->image)
                                                                        ? asset('storage/' . $value->image)
                                                                        : null;
                                                                @endphp

                                                                <tr class="intro-x">
                                                                    <td class="w-24 text-center">
                                                                        {{ $key + 1 }}
                                                                    </td>

                                                                    <td class="w-40 text-center">
                                                                        @if ($imageUrl)
                                                                            <div
                                                                                class="w-20 h-16 mx-auto rounded-lg overflow-hidden bg-slate-100 border border-slate-200">
                                                                                <img src="{{ $imageUrl }}"
                                                                                    class="w-full h-full object-cover"
                                                                                    alt="{{ $value->title }}">
                                                                            </div>
                                                                        @else
                                                                            <div
                                                                                class="w-20 h-16 mx-auto rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 text-xs">
                                                                                ไม่มีรูป
                                                                            </div>
                                                                        @endif
                                                                    </td>

                                                                    <td>
                                                                        <div
                                                                            class="font-medium text-slate-800 dark:text-slate-100">
                                                                            {{ \Illuminate\Support\Str::limit($value->title, 70, '...') }}
                                                                        </div>

                                                                        @if (!empty($value->slug))
                                                                            <div class="text-slate-500 text-xs mt-1">
                                                                                Slug: {{ $value->slug }}
                                                                            </div>
                                                                        @endif

                                                                        @if (!empty($value->short_description))
                                                                            <div
                                                                                class="text-slate-500 text-xs mt-1 leading-5">
                                                                                {{ \Illuminate\Support\Str::limit($value->short_description, 90, '...') }}
                                                                            </div>
                                                                        @endif
                                                                    </td>

                                                                    <td class="text-center">
                                                                        @if ($isActive)
                                                                            <span
                                                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-success/10 text-success">
                                                                                เปิดใช้งาน
                                                                            </span>
                                                                        @else
                                                                            <span
                                                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-200 text-slate-600">
                                                                                ปิดใช้งาน
                                                                            </span>
                                                                        @endif
                                                                    </td>

                                                                    <td class="text-center whitespace-nowrap">
                                                                        {{ $publishedAt }}
                                                                    </td>

                                                                    <td class="text-center whitespace-nowrap">
                                                                        {{ $createdAt }}
                                                                    </td>

                                                                    <td class="text-center">
                                                                        <div class="flex justify-center items-center">
                                                                            <a class="flex items-center mr-3 text-primary"
                                                                                href="{{ url('admin/news/' . $value->id) }}">
                                                                                <i data-lucide="check-square"
                                                                                    class="w-4 h-4 mr-1"></i>
                                                                                แก้ไข
                                                                            </a>

                                                                            <a href="javascript:void(0);"
                                                                                class="flex items-center text-danger"
                                                                                onclick="openDeleteModal({{ $value->id }})">
                                                                                <i data-lucide="trash-2"
                                                                                    class="w-4 h-4 mr-1"></i>
                                                                                ลบข้อมูล
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
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
    </div>

    <div id="delete-confirmation-modal" class="modal" tabindex="-1" aria-hidden="true" data-tw-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <form method="POST" action="{{ url('admin/delete-news') }}">
                        @csrf

                        <div class="p-5 text-center">
                            <input type="hidden" id="delete-id" name="id">

                            <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>

                            <div class="text-3xl mt-5">
                                คุณต้องการลบข้อมูล?
                            </div>

                            <div class="text-slate-500 mt-2">
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
        function openDeleteModal(id) {
            document.getElementById('delete-id').value = id;

            const modalEl = document.querySelector('#delete-confirmation-modal');
            const modal = tailwind.Modal.getOrCreateInstance(modalEl);

            modal.show();
        }

        jQuery(document).ready(function($) {
            $('#news-table').DataTable({
                responsive: true,
                order: [
                    [0, 'asc']
                ],
                pageLength: 10,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.21/i18n/Thai.json'
                },
                columnDefs: [{
                        targets: [1, 6],
                        orderable: false,
                        searchable: false
                    },
                    {
                        targets: [0, 1, 3, 4, 5, 6],
                        className: 'text-center'
                    }
                ]
            });
        });
    </script>
</body>

</html>
