<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <title>จัดการหน้าศูนย์ดูแลลูกค้า</title>
    @include('admin.inc_header')
</head>

<body class="main">
    @include('admin.inc_mobilemenu')

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
                        <a href="{{ url('admin/') }}">หน้าหลัก</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        จัดการหน้าศูนย์ดูแลลูกค้า
                    </li>
                </ol>
            </nav>

            @include('admin.inc_account')
        </div>
    </div>

    <div class="wrapper">
        <div class="wrapper-box">
            @include('admin.inc_sidemenu')

            <div class="content">
                <div class="intro-y flex items-center mt-8 mb-5">
                    <h2 class="text-lg font-medium mr-auto">
                        จัดการหน้าศูนย์ดูแลลูกค้า
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

                    <div class="grid grid-cols-12 gap-4 mb-5">
                        <div class="col-span-12 md:col-span-8">
                            <div class="text-slate-500">
                                จัดการข้อมูลหน้า “วิธีการยกเลิกการขาย”, “วิธีการขายสินค้า” และ “วิธีการรับเงิน”
                            </div>
                        </div>

                        <div class="col-span-12 md:col-span-4 text-left md:text-right">
                            <a href="{{ url('/') }}" target="_blank" class="btn btn-outline-secondary">
                                ดูหน้าบ้าน
                            </a>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table table-report">
                            <thead>
                                <tr>
                                    <th class="text-center whitespace-nowrap">ลำดับ</th>
                                    <th class="whitespace-nowrap">ชื่อเมนู</th>
                                    <th class="whitespace-nowrap">Slug</th>
                                    <th class="text-center whitespace-nowrap">จำนวน Section</th>
                                    <th class="text-center whitespace-nowrap">สถานะ</th>
                                    <th class="text-center whitespace-nowrap">จัดการ</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($pages as $index => $page)
                                    <tr class="intro-x">
                                        <td class="text-center">
                                            {{ $index + 1 }}
                                        </td>

                                        <td>
                                            <div class="font-medium whitespace-nowrap">
                                                {{ $page->menu_label ?: $page->page_title ?: '-' }}
                                            </div>

                                            <div class="text-slate-500 text-xs mt-1">
                                                {{ $page->hero_title ?: '-' }}
                                            </div>
                                        </td>

                                        <td>
                                            <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs">
                                                {{ $page->slug }}
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            {{ $page->sections_count ?? 0 }}
                                        </td>

                                        <td class="text-center">
                                            @if ((int) $page->status === 1)
                                                <span
                                                    class="px-3 py-1 rounded-full bg-success/10 text-success text-xs font-semibold">
                                                    เปิดใช้งาน
                                                </span>
                                            @else
                                                <span
                                                    class="px-3 py-1 rounded-full bg-danger/10 text-danger text-xs font-semibold">
                                                    ปิดใช้งาน
                                                </span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                @if ($page->slug === 'cancel-selling')
                                                    <a href="{{ route('cancel.selling') }}" target="_blank"
                                                        class="btn btn-outline-secondary btn-sm">
                                                        ดูหน้า
                                                    </a>
                                                @elseif ($page->slug === 'how-to-sell')
                                                    <a href="{{ route('how.to.sell') }}" target="_blank"
                                                        class="btn btn-outline-secondary btn-sm">
                                                        ดูหน้า
                                                    </a>
                                                @elseif ($page->slug === 'how-to-get-paid')
                                                    <a href="{{ route('how.to.get.paid') }}" target="_blank"
                                                        class="btn btn-outline-secondary btn-sm">
                                                        ดูหน้า
                                                    </a>
                                                @endif

                                                <a href="{{ route('admin.support-pages.edit', $page->slug) }}"
                                                    class="btn btn-primary btn-sm">
                                                    แก้ไข
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-slate-500 py-8">
                                            ยังไม่มีข้อมูลหน้าศูนย์ดูแลลูกค้า
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

    @include('admin.inc_footer')
</body>

</html>
