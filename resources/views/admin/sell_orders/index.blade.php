<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <title>คำสั่งขายสินค้า</title>
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
                    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">หลังบ้าน</a></li>
                    <li class="breadcrumb-item active" aria-current="page">คำสั่งขายสินค้า</li>
                </ol>
            </nav>

            @include('admin/inc_account')
        </div>
    </div>

    <div class="wrapper">
        <div class="wrapper-box">
            @include('admin/inc_sidemenu')

            <div class="content">
                <div class="intro-y flex flex-col sm:flex-row sm:items-center mt-8 mb-5 gap-3">
                    <div class="mr-auto">
                        <h2 class="text-xl font-semibold">
                            คำสั่งขายสินค้า
                        </h2>
                        <div class="text-slate-500 text-sm mt-1">
                            รายการคำสั่งขายสินค้าจากหน้าบ้านทั้งหมด
                        </div>
                    </div>
                </div>

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
                    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                        <div class="intro-y box p-5 border border-slate-100">
                            <div class="flex items-center">
                                <div
                                    class="w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                                    <i data-lucide="clipboard-list" class="w-6 h-6"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-slate-500 text-sm">คำสั่งทั้งหมด</div>
                                    <div class="text-2xl font-bold mt-1">
                                        {{ number_format($summary['total'] ?? 0) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                        <div class="intro-y box p-5 border border-slate-100">
                            <div class="flex items-center">
                                <div
                                    class="w-12 h-12 rounded-full bg-warning/10 text-warning flex items-center justify-center">
                                    <i data-lucide="clock" class="w-6 h-6"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-slate-500 text-sm">รอติดต่อกลับ</div>
                                    <div class="text-2xl font-bold mt-1 text-warning">
                                        {{ number_format($summary['pending'] ?? 0) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                        <div class="intro-y box p-5 border border-slate-100">
                            <div class="flex items-center">
                                <div
                                    class="w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                                    <i data-lucide="search-check" class="w-6 h-6"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-slate-500 text-sm">กำลังตรวจสอบ</div>
                                    <div class="text-2xl font-bold mt-1 text-primary">
                                        {{ number_format($summary['inspecting'] ?? 0) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                        <div class="intro-y box p-5 border border-slate-100">
                            <div class="flex items-center">
                                <div
                                    class="w-12 h-12 rounded-full bg-success/10 text-success flex items-center justify-center">
                                    <i data-lucide="check-circle" class="w-6 h-6"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-slate-500 text-sm">สำเร็จแล้ว</div>
                                    <div class="text-2xl font-bold mt-1 text-success">
                                        {{ number_format($summary['completed'] ?? 0) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="intro-y box mb-5 overflow-hidden border border-slate-100">
                    <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/80">
                        <div class="flex flex-col lg:flex-row lg:items-center gap-3">
                            <div class="mr-auto">
                                <div class="flex items-center">
                                    <div
                                        class="w-9 h-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center mr-3">
                                        <i data-lucide="filter" class="w-5 h-5"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium text-base">ค้นหาและกรองข้อมูล</div>
                                        <div class="text-slate-500 text-xs mt-0.5">
                                            ค้นหาจากเลขคำสั่งขาย ชื่อลูกค้า เบอร์โทร อีเมล หรือชื่อสินค้า
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if (request()->hasAny(['keyword', 'status', 'pickup_panel', 'date_from', 'date_to']))
                                <div
                                    class="inline-flex items-center text-xs text-primary bg-primary/10 rounded-full px-3 py-1.5">
                                    <i data-lucide="check-circle" class="w-3.5 h-3.5 mr-1"></i>
                                    กำลังใช้ตัวกรอง
                                </div>
                            @endif
                        </div>
                    </div>

                    <form method="GET" action="{{ url('admin/sell-orders') }}" class="p-5">
                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-12 xl:col-span-4">
                                <label class="form-label font-medium">ค้นหาคำสั่งขาย</label>
                                <div class="relative">
                                    <i data-lucide="search"
                                        class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="text" name="keyword" class="form-control pl-10"
                                        value="{{ request('keyword') }}"
                                        placeholder="เลขคำสั่ง / ชื่อผู้ขาย / เบอร์ / รุ่นสินค้า">
                                </div>
                            </div>

                            <div class="col-span-12 sm:col-span-6 xl:col-span-2">
                                <label class="form-label font-medium">สถานะ</label>
                                <select name="status" class="form-select">
                                    <option value="">ทุกสถานะ</option>
                                    @foreach ($statuses as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ request('status') === $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-span-12 sm:col-span-6 xl:col-span-2">
                                <label class="form-label font-medium">วิธีรับซื้อ</label>
                                <select name="pickup_panel" class="form-select">
                                    <option value="">ทุกวิธีรับซื้อ</option>
                                    @foreach ($pickupPanels as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ request('pickup_panel') === $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-span-12 sm:col-span-6 xl:col-span-2">
                                <label class="form-label font-medium">วันที่เริ่ม</label>
                                <div class="relative">
                                    <i data-lucide="calendar"
                                        class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="date" name="date_from" class="form-control pl-10"
                                        value="{{ request('date_from') }}">
                                </div>
                            </div>

                            <div class="col-span-12 sm:col-span-6 xl:col-span-2">
                                <label class="form-label font-medium">วันที่สิ้นสุด</label>
                                <div class="relative">
                                    <i data-lucide="calendar-days"
                                        class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="date" name="date_to" class="form-control pl-10"
                                        value="{{ request('date_to') }}">
                                </div>
                            </div>
                        </div>

                        <div
                            class="mt-5 pt-5 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center gap-3">
                            <div class="text-slate-500 text-xs mr-auto">
                                @if (request()->hasAny(['keyword', 'status', 'pickup_panel', 'date_from', 'date_to']))
                                    ผลลัพธ์ตามเงื่อนไขที่เลือก
                                @else
                                    แสดงรายการล่าสุดทั้งหมด
                                @endif
                            </div>

                            <div class="flex flex-col sm:flex-row gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i data-lucide="search" class="w-4 h-4 mr-2"></i>
                                    ค้นหา
                                </button>

                                <a href="{{ url('admin/sell-orders') }}" class="btn btn-outline-secondary">
                                    <i data-lucide="rotate-ccw" class="w-4 h-4 mr-2"></i>
                                    ล้างค่า
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="intro-y box p-5">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-5">
                        <div class="mr-auto">
                            <h3 class="font-medium text-base">รายการคำสั่งขาย</h3>
                            <div class="text-slate-500 text-xs mt-1">
                                พบข้อมูล {{ number_format($orders->total()) }} รายการ
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="whitespace-nowrap">เลขที่คำสั่ง</th>
                                    <th class="whitespace-nowrap">ผู้ขาย</th>
                                    <th class="whitespace-nowrap">สินค้า</th>
                                    <th class="whitespace-nowrap text-right">ราคาประเมิน</th>
                                    <th class="whitespace-nowrap">วิธีรับซื้อ</th>
                                    <th class="whitespace-nowrap">วันนัดหมาย</th>
                                    <th class="whitespace-nowrap">สถานะ</th>
                                    <th class="whitespace-nowrap">วันที่สร้าง</th>
                                    <th class="whitespace-nowrap text-center">จัดการ</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($orders as $order)
                                    @php
                                        $statusLabel = $statuses[$order->status] ?? $order->status;
                                        $pickupLabel = $pickupPanels[$order->pickup_panel] ?? $order->pickup_panel;

                                        $statusStyles = [
                                            'pending' => [
                                                'class' => 'bg-warning/10 text-warning border border-warning/20',
                                                'icon' => 'clock',
                                            ],
                                            'confirmed' => [
                                                'class' => 'bg-primary/10 text-primary border border-primary/20',
                                                'icon' => 'check-circle',
                                            ],
                                            'contacted' => [
                                                'class' => 'bg-cyan-50 text-cyan-700 border border-cyan-200',
                                                'icon' => 'phone-call',
                                            ],
                                            'waiting_receive' => [
                                                'class' => 'bg-amber-50 text-amber-700 border border-amber-200',
                                                'icon' => 'package-clock',
                                            ],
                                            'received' => [
                                                'class' => 'bg-blue-50 text-blue-700 border border-blue-200',
                                                'icon' => 'package-check',
                                            ],
                                            'inspecting' => [
                                                'class' => 'bg-indigo-50 text-indigo-700 border border-indigo-200',
                                                'icon' => 'search-check',
                                            ],
                                            'price_adjusted' => [
                                                'class' => 'bg-purple-50 text-purple-700 border border-purple-200',
                                                'icon' => 'badge-dollar-sign',
                                            ],
                                            'completed' => [
                                                'class' => 'bg-success/10 text-success border border-success/20',
                                                'icon' => 'check-circle-2',
                                            ],
                                            'cancelled' => [
                                                'class' => 'bg-danger/10 text-danger border border-danger/20',
                                                'icon' => 'x-circle',
                                            ],
                                        ];

                                        $statusStyle = $statusStyles[$order->status] ?? [
                                            'class' => 'bg-slate-100 text-slate-600 border border-slate-200',
                                            'icon' => 'circle',
                                        ];

                                        $pickupStyles = [
                                            'store' => [
                                                'class' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                                                'icon' => 'map-pin',
                                            ],
                                            'bts_mrt' => [
                                                'class' => 'bg-sky-50 text-sky-700 border border-sky-200',
                                                'icon' => 'train',
                                            ],
                                            'ems' => [
                                                'class' => 'bg-orange-50 text-orange-700 border border-orange-200',
                                                'icon' => 'package',
                                            ],
                                        ];

                                        $pickupStyle = $pickupStyles[$order->pickup_panel] ?? [
                                            'class' => 'bg-slate-100 text-slate-600 border border-slate-200',
                                            'icon' => 'truck',
                                        ];
                                    @endphp

                                    <tr>
                                        <td class="whitespace-nowrap">
                                            <div class="font-semibold text-primary">
                                                {{ $order->order_no }}
                                            </div>
                                            <div class="text-xs text-slate-500 mt-1">
                                                ID: {{ $order->id }}
                                            </div>
                                        </td>

                                        <td class="whitespace-nowrap">
                                            <div class="font-medium">{{ $order->customer_name }}</div>
                                            <div class="text-slate-500 text-xs mt-1">
                                                {{ $order->customer_phone }}
                                            </div>
                                            @if (!empty($order->customer_email))
                                                <div class="text-slate-500 text-xs">
                                                    {{ $order->customer_email }}
                                                </div>
                                            @endif
                                        </td>

                                        <td>
                                            <div class="min-w-[240px]">
                                                <div class="font-medium">
                                                    {{ $order->summary_title ?? '-' }}
                                                </div>
                                            </div>
                                        </td>

                                        <td class="text-right whitespace-nowrap">
                                            <div class="font-bold text-success">
                                                ฿{{ number_format((float) $order->final_estimate_price, 0) }}
                                            </div>
                                        </td>

                                        <td class="whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold {{ $pickupStyle['class'] }}">
                                                <i data-lucide="{{ $pickupStyle['icon'] }}"
                                                    class="w-3.5 h-3.5 mr-1.5"></i>
                                                {{ $pickupLabel }}
                                            </span>
                                        </td>

                                        <td class="whitespace-nowrap">
                                            @if (!empty($order->pickup_date))
                                                <div class="font-medium">
                                                    {{ \Carbon\Carbon::parse($order->pickup_date)->format('d/m/Y') }}
                                                </div>
                                                <div class="text-xs text-slate-500 mt-1">
                                                    {{ $order->pickup_time ?? '-' }}
                                                </div>
                                            @else
                                                <span class="text-slate-400">-</span>
                                            @endif
                                        </td>

                                        <td class="whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold {{ $statusStyle['class'] }}">
                                                <i data-lucide="{{ $statusStyle['icon'] }}"
                                                    class="w-3.5 h-3.5 mr-1.5"></i>
                                                {{ $statusLabel }}
                                            </span>
                                        </td>

                                        <td class="whitespace-nowrap">
                                            <div class="font-medium">
                                                {{ !empty($order->created_at) ? \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') : '-' }}
                                            </div>
                                            <div class="text-xs text-slate-500 mt-1">
                                                {{ !empty($order->created_at) ? \Carbon\Carbon::parse($order->created_at)->format('H:i') : '' }}
                                            </div>
                                        </td>

                                        <td class="text-center whitespace-nowrap">
                                            <a href="{{ url('admin/sell-orders/' . $order->id) }}"
                                                class="btn btn-primary btn-sm">
                                                <i data-lucide="eye" class="w-4 h-4 mr-1"></i>
                                                ดูรายละเอียด
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-slate-500 py-8">
                                            ไม่พบข้อมูลคำสั่งขายสินค้า
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-5">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin/inc_footer')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.lucide) {
                lucide.createIcons();
            }
        });
    </script>
</body>

</html>
