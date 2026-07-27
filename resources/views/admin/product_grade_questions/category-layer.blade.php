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

                                <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                                    <div>
                                        <div class="text-lg font-medium text-slate-700">
                                            เลือกประเภทสินค้า
                                        </div>
                                        <div class="text-slate-500 text-sm mt-1">
                                            เลือกประเภทสินค้าก่อน เพื่อไปจัดการชุดคำถามตามแบรนด์
                                        </div>
                                    </div>

                                    <a href="{{ url('admin/product-grade-questions/create') }}" class="btn btn-primary">
                                        <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                                        เพิ่มชุดคำถาม
                                    </a>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-report mt-2" id="categories-table">
                                        <thead>
                                            <tr>
                                                <th class="whitespace-nowrap text-center">ลำดับ</th>
                                                <th class="whitespace-nowrap">ประเภทสินค้า</th>
                                                <th class="whitespace-nowrap text-center">จำนวนชุดคำถาม</th>
                                                <th class="whitespace-nowrap text-center">สถานะ</th>
                                                <th class="text-center whitespace-nowrap">จัดการ</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse ($categories as $key => $value)
                                                <tr class="intro-x">
                                                    <td class="text-center">{{ $key + 1 }}</td>

                                                    <td>
                                                        <div class="font-medium whitespace-nowrap">
                                                            {{ $value->category_name ?? $value->name ?? '-' }}
                                                        </div>
                                                    </td>

                                                    <td class="text-center">
                                                        <span class="px-3 py-1 rounded-full bg-slate-200 text-slate-700">
                                                            {{ $value->questions_count ?? 0 }}
                                                        </span>
                                                    </td>

                                                    <td class="text-center">
                                                        @if ((int) ($value->status ?? 1) === 1)
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
                                                        <a class="btn btn-outline-primary"
                                                            href="{{ url('admin/product-grade-questions/by-category/' . $value->id . '/brands') }}">
                                                            <i data-lucide="layers" class="w-4 h-4 mr-2"></i>
                                                            ดูแบรนด์สินค้า
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-slate-500 py-5">
                                                        ยังไม่มีข้อมูลประเภทสินค้า
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

    @include('admin/inc_footer')

    <script>
        jQuery(document).ready(function($) {
            $('#categories-table').DataTable({
                responsive: true,
                order: [
                    [0, "asc"]
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Thai.json"
                }
            });
        });
    </script>
</body>

</html>