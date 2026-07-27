<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <title>รายละเอียดคำสั่งขาย {{ $order->order_no }}</title>
    @include('admin/inc_header')
</head>

<body class="main">
    @include('admin/inc_mobilemenu')

    @php
        $statusLabel = $statuses[$order->status] ?? $order->status;
        $pickupLabel = $pickupPanels[$order->pickup_panel] ?? ($order->sell_method_name ?? '-');

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

        $finalPrice = (float) ($order->final_estimate_price ?? 0);
        $bonusAmount = (float) ($order->bonus_amount ?? 0);
        $estimateBeforeBonus = max(0, $finalPrice - $bonusAmount);
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
                    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">หลังบ้าน</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('admin/sell-orders') }}">คำสั่งขายสินค้า</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $order->order_no }}</li>
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
                            รายละเอียดคำสั่งขาย {{ $order->order_no }}
                        </h2>
                        <div class="text-slate-500 text-sm mt-1">
                            ตรวจสอบข้อมูลคำสั่งขายสินค้า ปรับราคา และอัปเดตสถานะ
                        </div>
                    </div>

                    <a href="{{ url('admin/sell-orders') }}" class="btn btn-outline-secondary">
                        <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                        กลับ
                    </a>
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

                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="grid grid-cols-12 gap-5">
                    <div class="col-span-12 xl:col-span-8">
                        <div class="intro-y box p-5 mb-5 border border-slate-100">
                            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                                <div>
                                    <div class="text-slate-500 text-sm mb-1">เลขที่คำสั่งขาย</div>
                                    <div class="text-2xl font-bold text-primary">{{ $order->order_no }}</div>

                                    <div
                                        class="mt-3 inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold {{ $pickupStyle['class'] }}">
                                        <i data-lucide="{{ $pickupStyle['icon'] }}" class="w-3.5 h-3.5 mr-1.5"></i>
                                        {{ $pickupLabel }}
                                    </div>
                                </div>

                                <div class="sm:text-right">
                                    <span
                                        class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold {{ $statusStyle['class'] }}">
                                        <i data-lucide="{{ $statusStyle['icon'] }}" class="w-3.5 h-3.5 mr-1.5"></i>
                                        {{ $statusLabel }}
                                    </span>

                                    <div class="text-slate-500 text-xs mt-2">
                                        สร้างเมื่อ:
                                        {{ !empty($order->created_at) ? \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="intro-y box p-5 mb-5 border border-slate-100">
                            <h3 class="text-lg font-medium mb-4">ข้อมูลสินค้า</h3>

                            <div class="grid grid-cols-12 gap-4">
                                <div class="col-span-12 md:col-span-6">
                                    <div class="text-slate-500 text-sm">สินค้า</div>
                                    <div class="font-medium mt-1">{{ $order->summary_title ?? '-' }}</div>
                                </div>

                                <div class="col-span-12 md:col-span-6">
                                    <div class="text-slate-500 text-sm">ประเภท / แบรนด์ / รุ่น / ความจุ</div>
                                    <div class="font-medium mt-1 leading-7">
                                        {{ $order->category_name ?? '-' }}
                                        /
                                        {{ $order->brand_name ?? '-' }}
                                        /
                                        {{ $order->model_name ?? '-' }}
                                        /
                                        {{ $order->capacity ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="intro-y box p-5 mb-5 border border-slate-100">
                            <div class="flex items-center mb-4">
                                <h3 class="text-lg font-medium mr-auto">ราคาประเมิน</h3>

                                @if (!empty($order->admin_adjusted_price))
                                    <span
                                        class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200">
                                        <i data-lucide="badge-dollar-sign" class="w-3.5 h-3.5 mr-1.5"></i>
                                        แอดมินปรับราคาแล้ว
                                    </span>
                                @endif
                            </div>

                            <div class="overflow-x-auto">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td class="font-medium w-60">ราคาประเมิน</td>
                                            <td>฿{{ number_format($estimateBeforeBonus, 0) }}</td>
                                        </tr>

                                        @if ($bonusAmount > 0)
                                            <tr>
                                                <td class="font-medium">โบนัสโค้ด</td>
                                                <td>
                                                    <span class="text-success font-medium">
                                                        +฿{{ number_format($bonusAmount, 0) }}
                                                    </span>

                                                    @if (!empty($order->bonus_code))
                                                        <span
                                                            class="text-success ml-2">({{ $order->bonus_code }})</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif

                                        <tr>
                                            <td class="font-medium">ราคาประเมินสุทธิ</td>
                                            <td class="text-success text-xl font-bold">
                                                ฿{{ number_format($finalPrice, 0) }}
                                            </td>
                                        </tr>

                                        @if (!empty($order->original_final_estimate_price))
                                            <tr>
                                                <td class="font-medium">ราคาก่อนแอดมินปรับ</td>
                                                <td>
                                                    ฿{{ number_format((float) $order->original_final_estimate_price, 0) }}
                                                </td>
                                            </tr>
                                        @endif

                                        @if (!empty($order->price_adjustment_note))
                                            <tr>
                                                <td class="font-medium">หมายเหตุการปรับราคา</td>
                                                <td>{{ $order->price_adjustment_note }}</td>
                                            </tr>
                                        @endif

                                        @if (!empty($order->price_adjusted_at))
                                            <tr>
                                                <td class="font-medium">วันที่ปรับราคา</td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($order->price_adjusted_at)->format('d/m/Y H:i') }}
                                                </td>
                                            </tr>
                                        @endif

                                        <tr>
                                            <td class="font-medium">ราคาตั้งต้นภายในระบบ</td>
                                            <td>฿{{ number_format((float) ($order->base_price ?? 0), 0) }}</td>
                                        </tr>

                                        <tr>
                                            <td class="font-medium">ราคาขั้นต่ำภายในระบบ</td>
                                            <td>฿{{ number_format((float) ($order->min_price ?? 0), 0) }}</td>
                                        </tr>

                                        <tr>
                                            <td class="font-medium">ยอดหักรวมภายในระบบ</td>
                                            <td class="text-danger">
                                                -฿{{ number_format((float) ($order->deduct_total ?? 0), 0) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="intro-y box p-5 mb-5 border border-slate-100">
                            <h3 class="text-lg font-medium mb-4">คำตอบประเมินสภาพเครื่อง</h3>

                            <div class="overflow-x-auto">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>คำถาม</th>
                                            <th>คำตอบ</th>
                                            <th>เกรดภายใน</th>
                                            <th class="text-right">ยอดหัก</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse ($answers as $answer)
                                            <tr>
                                                <td>{{ $answer->question_title ?? '-' }}</td>
                                                <td>{{ $answer->option_title ?? '-' }}</td>
                                                <td>{{ $answer->grade_name ?? '-' }}</td>
                                                <td class="text-right text-danger font-medium">
                                                    ฿{{ number_format((float) ($answer->deduct_price ?? 0), 0) }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-slate-500">
                                                    ไม่พบคำตอบประเมิน
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="intro-y box p-5 mb-5 border border-slate-100">
                            <h3 class="text-lg font-medium mb-4">ข้อมูลผู้ขาย</h3>

                            <div class="grid grid-cols-12 gap-4">
                                <div class="col-span-12 md:col-span-4">
                                    <div class="text-slate-500 text-sm">ชื่อ-นามสกุล</div>
                                    <div class="font-medium mt-1">
                                        {{ $pickupDetail->fullname ?? ($order->customer_name ?? '-') }}
                                    </div>
                                </div>

                                <div class="col-span-12 md:col-span-4">
                                    <div class="text-slate-500 text-sm">เบอร์โทร</div>
                                    <div class="font-medium mt-1">
                                        {{ $pickupDetail->phone ?? ($order->customer_phone ?? '-') }}
                                    </div>
                                </div>

                                <div class="col-span-12 md:col-span-4">
                                    <div class="text-slate-500 text-sm">อีเมล</div>
                                    <div class="font-medium mt-1">
                                        {{ $pickupDetail->email ?? ($order->customer_email ?? '-') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="intro-y box p-5 mb-5 border border-slate-100">
                            <h3 class="text-lg font-medium mb-4">ข้อมูลการรับซื้อ / จัดส่ง</h3>

                            <div class="grid grid-cols-12 gap-4">
                                <div class="col-span-12 md:col-span-4">
                                    <div class="text-slate-500 text-sm">วิธีรับซื้อ</div>
                                    <div class="font-medium mt-1">{{ $pickupLabel }}</div>
                                </div>

                                <div class="col-span-12 md:col-span-4">
                                    <div class="text-slate-500 text-sm">วันที่นัดหมาย</div>
                                    <div class="font-medium mt-1">
                                        {{ !empty($order->pickup_date) ? \Carbon\Carbon::parse($order->pickup_date)->format('d/m/Y') : '-' }}
                                    </div>
                                </div>

                                <div class="col-span-12 md:col-span-4">
                                    <div class="text-slate-500 text-sm">ช่วงเวลา</div>
                                    <div class="font-medium mt-1">{{ $order->pickup_time ?? '-' }}</div>
                                </div>
                            </div>

                            <div class="mt-5 border-t pt-5">
                                @if (($order->pickup_panel ?? '') === 'store')
                                    <div class="grid grid-cols-12 gap-4">
                                        {{-- <div class="col-span-12 md:col-span-6">
                                            <div class="text-slate-500 text-sm">สาขา</div>
                                            <div class="font-medium mt-1">{{ $pickupDetail->branch_name ?? '-' }}
                                            </div>
                                        </div>

                                        <div class="col-span-12 md:col-span-6">
                                            <div class="text-slate-500 text-sm">ที่อยู่สาขา</div>
                                            <div class="font-medium mt-1">{{ $pickupDetail->branch_address ?? '-' }}
                                            </div>
                                        </div> --}}

                                        <div class="col-span-12">
                                            <div class="text-slate-500 text-sm">ที่อยู่ลูกค้า</div>
                                            <div class="font-medium mt-1">{{ $pickupDetail->customer_address ?? '-' }}
                                            </div>
                                        </div>

                                        <div class="col-span-12 md:col-span-6">
                                            <div class="text-slate-500 text-sm">จังหวัด</div>
                                            <div class="font-medium mt-1">{{ $pickupDetail->province ?? '-' }}</div>
                                        </div>

                                        <div class="col-span-12 md:col-span-6">
                                            <div class="text-slate-500 text-sm">เขต / อำเภอ</div>
                                            <div class="font-medium mt-1">{{ $pickupDetail->district ?? '-' }}</div>
                                        </div>
                                    </div>
                                @elseif (($order->pickup_panel ?? '') === 'bts_mrt')
                                    <div class="grid grid-cols-12 gap-4">
                                        <div class="col-span-12 md:col-span-4">
                                            <div class="text-slate-500 text-sm">สายรถไฟฟ้า</div>
                                            <div class="font-medium mt-1">
                                                {{ $pickupDetail->transit_line_name ?? '-' }}</div>
                                        </div>

                                        <div class="col-span-12 md:col-span-4">
                                            <div class="text-slate-500 text-sm">สถานี</div>
                                            <div class="font-medium mt-1">
                                                {{ $pickupDetail->transit_station_name ?? '-' }}</div>
                                        </div>

                                        <div class="col-span-12 md:col-span-4">
                                            <div class="text-slate-500 text-sm">รหัสสถานี</div>
                                            <div class="font-medium mt-1">
                                                {{ $pickupDetail->transit_station_code ?? '-' }}</div>
                                        </div>
                                    </div>
                                @elseif (($order->pickup_panel ?? '') === 'ems')
                                    <div class="grid grid-cols-12 gap-4">
                                        <div class="col-span-12">
                                            <div class="text-slate-500 text-sm">ที่อยู่ผู้ส่ง</div>
                                            <div class="font-medium mt-1">{{ $pickupDetail->sender_address ?? '-' }}
                                            </div>
                                        </div>

                                        <div class="col-span-12 md:col-span-4">
                                            <div class="text-slate-500 text-sm">ศูนย์รับสินค้า</div>
                                            <div class="font-medium mt-1">
                                                {{ $pickupDetail->parcel_receiver_name ?? '-' }}</div>
                                        </div>

                                        <div class="col-span-12 md:col-span-5">
                                            <div class="text-slate-500 text-sm">ที่อยู่ศูนย์รับสินค้า</div>
                                            <div class="font-medium mt-1">
                                                {{ $pickupDetail->parcel_receiver_address ?? '-' }}</div>
                                        </div>

                                        <div class="col-span-12 md:col-span-3">
                                            <div class="text-slate-500 text-sm">เบอร์ศูนย์รับสินค้า</div>
                                            <div class="font-medium mt-1">
                                                {{ $pickupDetail->parcel_receiver_phone ?? '-' }}</div>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-slate-500">-</div>
                                @endif
                            </div>
                        </div>

                        @if ($requiredDocuments && $requiredDocuments->count() > 0)
                            <div class="intro-y box p-5 mb-5 border border-slate-100">
                                <h3 class="text-lg font-medium mb-4">เอกสารที่ต้องได้รับ</h3>

                                <div class="overflow-x-auto">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>เอกสาร</th>
                                                <th>รายละเอียด</th>
                                                <th>สถานะ</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($requiredDocuments as $document)
                                                <tr>
                                                    <td>{{ $document->document_name ?? '-' }}</td>
                                                    <td>{{ $document->description ?? '-' }}</td>
                                                    <td>
                                                        @if ((int) $document->is_received === 1)
                                                            <span class="badge badge-success">ได้รับแล้ว</span>
                                                        @else
                                                            <span class="badge badge-warning">ยังไม่ได้รับ</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        @if ($files && $files->count() > 0)
                            <div class="intro-y box p-5 mb-5 border border-slate-100">
                                <h3 class="text-lg font-medium mb-4">ไฟล์แนบ</h3>

                                <div class="overflow-x-auto">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>ประเภท</th>
                                                <th>ชื่อไฟล์</th>
                                                <th>ไฟล์</th>
                                                <th>อัปโหลดโดย</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($files as $file)
                                                <tr>
                                                    <td>{{ $file->file_type ?? '-' }}</td>
                                                    <td>{{ $file->file_name ?? '-' }}</td>
                                                    <td>
                                                        <a href="{{ asset($file->file_path) }}" target="_blank"
                                                            class="text-primary underline">
                                                            เปิดไฟล์
                                                        </a>
                                                    </td>
                                                    <td>{{ $file->uploaded_by ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        <div class="intro-y box p-5 mb-5 border border-slate-100">
                            <h3 class="text-lg font-medium mb-4">ประวัติสถานะ</h3>

                            <div class="overflow-x-auto">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>วันที่</th>
                                            <th>สถานะเดิม</th>
                                            <th>สถานะใหม่</th>
                                            <th>หมายเหตุ</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse ($histories as $history)
                                            <tr>
                                                <td class="whitespace-nowrap">
                                                    {{ !empty($history->created_at) ? \Carbon\Carbon::parse($history->created_at)->format('d/m/Y H:i') : '-' }}
                                                </td>
                                                <td>{{ $statuses[$history->old_status] ?? ($history->old_status ?? '-') }}
                                                </td>
                                                <td>{{ $statuses[$history->new_status] ?? ($history->new_status ?? '-') }}
                                                </td>
                                                <td>{{ $history->note ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-slate-500">
                                                    ยังไม่มีประวัติสถานะ
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12 xl:col-span-4">
                        <div class="intro-y box p-5 mb-5 border border-slate-100">
                            <h3 class="text-lg font-medium mb-4">ปรับราคาประเมิน</h3>

                            <div class="mb-5 pb-4 border-b border-slate-200">
                                <div class="text-slate-500 text-sm mb-1">
                                    ราคาประเมินปัจจุบัน
                                </div>

                                <div class="text-2xl font-bold text-success">
                                    ฿{{ number_format((float) ($order->final_estimate_price ?? 0), 0) }}
                                </div>

                                @if (!empty($order->original_final_estimate_price) || !empty($order->admin_adjusted_price))
                                    <div class="mt-3 space-y-1 text-sm">
                                        @if (!empty($order->original_final_estimate_price))
                                            <div class="flex items-center justify-between gap-4">
                                                <span class="text-slate-500">ราคาก่อนแอดมินปรับ</span>
                                                <span class="font-medium">
                                                    ฿{{ number_format((float) $order->original_final_estimate_price, 0) }}
                                                </span>
                                            </div>
                                        @endif

                                        @if (!empty($order->admin_adjusted_price))
                                            <div class="flex items-center justify-between gap-4">
                                                <span class="text-slate-500">ราคาที่แอดมินปรับล่าสุด</span>
                                                <span class="font-semibold text-primary">
                                                    ฿{{ number_format((float) $order->admin_adjusted_price, 0) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <form method="POST"
                                action="{{ url('admin/sell-orders/' . $order->id . '/update-price') }}">
                                @csrf

                                <div class="mb-4">
                                    <label class="form-label">ราคาประเมินใหม่ <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-text">฿</div>
                                        <input type="number" step="0.01" min="0"
                                            name="final_estimate_price" class="form-control"
                                            value="{{ old('final_estimate_price', $order->final_estimate_price ?? 0) }}"
                                            placeholder="กรอกราคาประเมินใหม่">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">หมายเหตุการปรับราคา</label>
                                    <textarea name="price_adjustment_note" rows="4" class="form-control"
                                        placeholder="เช่น ตรวจเครื่องจริงพบตำหนิเพิ่ม / สภาพดีกว่าที่ลูกค้าระบุ / เพิ่มราคาให้ลูกค้า">{{ old('price_adjustment_note', $order->price_adjustment_note ?? '') }}</textarea>
                                </div>

                                @if (in_array($order->status, ['completed', 'cancelled']))
                                    <div class="alert alert-warning mb-4">
                                        คำสั่งนี้อยู่ในสถานะ {{ $statuses[$order->status] ?? $order->status }}
                                        หากปรับราคา ระบบจะไม่เปลี่ยนสถานะอัตโนมัติ
                                    </div>
                                @else
                                    <div class="alert alert-primary mb-4">
                                        เมื่อบันทึกราคา ระบบจะเปลี่ยนสถานะเป็น “มีการปรับราคา” อัตโนมัติ
                                    </div>
                                @endif

                                <button type="submit" class="btn btn-warning w-full">
                                    <i data-lucide="badge-dollar-sign" class="w-4 h-4 mr-2"></i>
                                    บันทึกราคาประเมินใหม่
                                </button>
                            </form>
                        </div>

                        <div class="intro-y box p-5 mb-5 border border-slate-100">
                            <h3 class="text-lg font-medium mb-4">แก้ไขสถานะ</h3>

                            <form method="POST"
                                action="{{ url('admin/sell-orders/' . $order->id . '/update-status') }}">
                                @csrf

                                <div class="mb-4">
                                    <label class="form-label">สถานะ</label>
                                    <select name="status" class="form-select">
                                        @foreach ($statuses as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ old('status', $order->status) === $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">หมายเหตุการเปลี่ยนสถานะ</label>
                                    <textarea name="note" rows="3" class="form-control"
                                        placeholder="เช่น โทรหาลูกค้าแล้ว / นัดรับสินค้าแล้ว / ตรวจเครื่องแล้ว">{{ old('note') }}</textarea>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">หมายเหตุภายใน</label>
                                    <textarea name="admin_note" rows="5" class="form-control" placeholder="หมายเหตุภายในสำหรับแอดมิน">{{ old('admin_note', $order->admin_note ?? '') }}</textarea>
                                </div>

                                <button type="submit" class="btn btn-primary w-full">
                                    <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                                    บันทึกสถานะ
                                </button>
                            </form>
                        </div>
                        <div class="intro-y box overflow-hidden mb-5 border border-slate-100">
                            <div
                                class="p-5 bg-gradient-to-r from-amber-50 via-white to-white border-b border-slate-100">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="w-10 h-10 rounded-xl bg-warning/10 text-warning flex items-center justify-center">
                                                <i data-lucide="star" class="w-5 h-5"></i>
                                            </div>

                                            <div>
                                                <h3 class="text-lg font-semibold text-slate-800">
                                                    รีวิวคำสั่งขาย
                                                </h3>
                                                <div class="text-slate-500 text-sm mt-0.5">
                                                    เพิ่มรีวิวแทนลูกค้า หรือจัดการการแสดงผลรีวิวของคำสั่งขายนี้
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if (!empty($review))
                                        <span
                                            class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-success/10 text-success border border-success/20 shrink-0">
                                            <i data-lucide="check-circle" class="w-3.5 h-3.5 mr-1.5"></i>
                                            มีรีวิวแล้ว
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200 shrink-0">
                                            <i data-lucide="message-square-plus" class="w-3.5 h-3.5 mr-1.5"></i>
                                            ยังไม่มีรีวิว
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="p-5">
                                @if (!empty($review))
                                    <div class="space-y-5">
                                        <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-4">
                                            <div class="flex items-start gap-4">
                                                <div
                                                    class="w-12 h-12 rounded-full bg-warning/10 text-warning flex items-center justify-center shrink-0">
                                                    <i data-lucide="user-round" class="w-6 h-6"></i>
                                                </div>

                                                <div class="min-w-0 flex-1">
                                                    <div
                                                        class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                                                        <div>
                                                            <div class="text-slate-500 text-xs mb-1">
                                                                ผู้รีวิว
                                                            </div>
                                                            <div class="font-semibold text-slate-800 text-base">
                                                                {{ $review->customer_name ?? ($order->customer_name ?? '-') }}
                                                            </div>

                                                            @if (!empty($review->customer_phone))
                                                                <div class="text-slate-500 text-xs mt-1">
                                                                    {{ $review->customer_phone }}
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <div class="flex items-center gap-1 text-warning">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                @if ($i <= (int) $review->rating)
                                                                    <i data-lucide="star"
                                                                        class="w-4 h-4 fill-current"></i>
                                                                @else
                                                                    <i data-lucide="star"
                                                                        class="w-4 h-4 text-slate-300"></i>
                                                                @endif
                                                            @endfor

                                                            <span class="ml-1 text-slate-700 text-sm font-semibold">
                                                                {{ (int) $review->rating }}/5
                                                            </span>
                                                        </div>
                                                    </div>

                                                    @if (!empty($review->title))
                                                        <div class="mt-4 text-slate-800 font-semibold leading-6">
                                                            {{ $review->title }}
                                                        </div>
                                                    @endif

                                                    <div class="mt-2 text-slate-600 leading-7 whitespace-pre-line">
                                                        {{ $review->comment ?? '-' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if (!empty($review->image))
                                            <div>
                                                <div class="text-slate-500 text-sm mb-2">
                                                    รูปรีวิว
                                                </div>

                                                <a href="{{ asset('storage/' . $review->image) }}" target="_blank"
                                                    class="block group rounded-xl overflow-hidden border border-slate-200 bg-slate-50">
                                                    <img src="{{ asset('storage/' . $review->image) }}"
                                                        alt="review image"
                                                        class="w-full max-h-[240px] object-cover group-hover:scale-[1.02] transition duration-200">
                                                </a>
                                            </div>
                                        @endif

                                        <div class="grid grid-cols-12 gap-3">
                                            <div class="col-span-12 sm:col-span-6">
                                                <div class="rounded-xl border border-slate-100 bg-white p-4">
                                                    <div class="flex items-center justify-between gap-3">
                                                        <div>
                                                            <div class="text-slate-500 text-xs">
                                                                การแสดงหน้าเว็บ
                                                            </div>

                                                            @if ((int) $review->is_displayed === 1)
                                                                <div class="font-semibold text-success mt-1">
                                                                    แสดงอยู่
                                                                </div>
                                                            @else
                                                                <div class="font-semibold text-danger mt-1">
                                                                    ไม่แสดง
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <div
                                                            class="w-10 h-10 rounded-full {{ (int) $review->is_displayed === 1 ? 'bg-success/10 text-success' : 'bg-danger/10 text-danger' }} flex items-center justify-center">
                                                            <i data-lucide="{{ (int) $review->is_displayed === 1 ? 'eye' : 'eye-off' }}"
                                                                class="w-5 h-5"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-span-12 sm:col-span-6">
                                                <div class="rounded-xl border border-slate-100 bg-white p-4">
                                                    <div class="flex items-center justify-between gap-3">
                                                        <div>
                                                            <div class="text-slate-500 text-xs">
                                                                สถานะรีวิว
                                                            </div>

                                                            @if ((int) $review->is_active === 1)
                                                                <div class="font-semibold text-success mt-1">
                                                                    เปิดใช้งาน
                                                                </div>
                                                            @else
                                                                <div class="font-semibold text-danger mt-1">
                                                                    ปิดใช้งาน
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <div
                                                            class="w-10 h-10 rounded-full {{ (int) $review->is_active === 1 ? 'bg-success/10 text-success' : 'bg-danger/10 text-danger' }} flex items-center justify-center">
                                                            <i data-lucide="{{ (int) $review->is_active === 1 ? 'toggle-right' : 'toggle-left' }}"
                                                                class="w-5 h-5"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <form method="POST"
                                            action="{{ route('admin.sell-orders.review-display.update', $order->id) }}"
                                            class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                                            @csrf

                                            <div class="flex items-center gap-2 mb-4">
                                                <i data-lucide="settings-2" class="w-4 h-4 text-primary"></i>
                                                <div class="font-medium text-slate-800">
                                                    ตั้งค่าการแสดงผลรีวิว
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-12 gap-4">
                                                <div class="col-span-12 sm:col-span-6">
                                                    <label class="form-label">
                                                        แสดงหน้าเว็บ
                                                    </label>
                                                    <select name="is_displayed" class="form-select">
                                                        <option value="1"
                                                            {{ (string) old('is_displayed', (int) $review->is_displayed) === '1' ? 'selected' : '' }}>
                                                            แสดง
                                                        </option>
                                                        <option value="0"
                                                            {{ (string) old('is_displayed', (int) $review->is_displayed) === '0' ? 'selected' : '' }}>
                                                            ไม่แสดง
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="col-span-12 sm:col-span-6">
                                                    <label class="form-label">
                                                        สถานะรีวิว
                                                    </label>
                                                    <select name="is_active" class="form-select">
                                                        <option value="1"
                                                            {{ (string) old('is_active', (int) $review->is_active) === '1' ? 'selected' : '' }}>
                                                            เปิดใช้งาน
                                                        </option>
                                                        <option value="0"
                                                            {{ (string) old('is_active', (int) $review->is_active) === '0' ? 'selected' : '' }}>
                                                            ปิดใช้งาน
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="col-span-12">
                                                    <button type="submit" class="btn btn-primary w-full">
                                                        <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                                                        บันทึกสถานะรีวิว
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                @else
                                    <form method="POST"
                                        action="{{ route('admin.sell-orders.review.store', $order->id) }}"
                                        enctype="multipart/form-data" class="space-y-5">
                                        @csrf

                                        <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-4">
                                            <div class="flex items-center gap-2 mb-4">
                                                <i data-lucide="user-round" class="w-4 h-4 text-primary"></i>
                                                <div class="font-medium text-slate-800">
                                                    ข้อมูลลูกค้าที่จะแสดงในรีวิว
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-12 gap-4">
                                                <div class="col-span-12">
                                                    <label class="form-label">
                                                        ชื่อลูกค้าที่แสดงในรีวิว
                                                    </label>
                                                    <input type="text" name="customer_name" class="form-control"
                                                        value="{{ old('customer_name', $order->customer_name ?? '') }}"
                                                        placeholder="ชื่อลูกค้าที่ต้องการแสดง">
                                                </div>

                                                <div class="col-span-12">
                                                    <label class="form-label">
                                                        เบอร์โทรลูกค้า
                                                    </label>
                                                    <input type="text" name="customer_phone" class="form-control"
                                                        value="{{ old('customer_phone', $order->customer_phone ?? '') }}"
                                                        placeholder="เบอร์โทรลูกค้า">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="rounded-xl border border-slate-100 bg-white p-4">
                                            <div class="flex items-center gap-2 mb-4">
                                                <i data-lucide="star" class="w-4 h-4 text-warning"></i>
                                                <div class="font-medium text-slate-800">
                                                    รายละเอียดรีวิว
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-12 gap-4">
                                                <div class="col-span-12">
                                                    <label class="form-label">
                                                        คะแนนรีวิว <span class="text-danger">*</span>
                                                    </label>
                                                    <select name="rating" class="form-select">
                                                        <option value="">เลือกคะแนน</option>
                                                        @for ($i = 5; $i >= 1; $i--)
                                                            <option value="{{ $i }}"
                                                                {{ (string) old('rating') === (string) $i ? 'selected' : '' }}>
                                                                {{ $i }} ดาว
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>

                                                <div class="col-span-12">
                                                    <label class="form-label">
                                                        หัวข้อรีวิว
                                                    </label>
                                                    <input type="text" name="title" class="form-control"
                                                        value="{{ old('title') }}"
                                                        placeholder="เช่น บริการดีมาก ได้ราคาดี">
                                                </div>

                                                <div class="col-span-12">
                                                    <label class="form-label">
                                                        ข้อความรีวิว <span class="text-danger">*</span>
                                                    </label>
                                                    <textarea name="comment" rows="5" class="form-control" placeholder="กรอกข้อความรีวิว">{{ old('comment') }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-4">
                                            <div class="flex items-center gap-2 mb-4">
                                                <i data-lucide="image" class="w-4 h-4 text-primary"></i>
                                                <div class="font-medium text-slate-800">
                                                    รูปภาพและการแสดงผล
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-12 gap-4">
                                                <div class="col-span-12">
                                                    <label class="form-label">
                                                        รูปรีวิว
                                                    </label>
                                                    <input type="file" name="image" class="form-control"
                                                        accept="image/jpeg,image/png,image/webp">
                                                    <div class="text-slate-500 text-xs mt-1">
                                                        รองรับ jpg, png, webp ขนาดไม่เกิน 4MB
                                                    </div>
                                                </div>

                                                <div class="col-span-12 sm:col-span-6">
                                                    <label class="form-label">
                                                        แสดงหน้าเว็บ
                                                    </label>
                                                    <select name="is_displayed" class="form-select">
                                                        <option value="1"
                                                            {{ (string) old('is_displayed', 1) === '1' ? 'selected' : '' }}>
                                                            แสดง
                                                        </option>
                                                        <option value="0"
                                                            {{ (string) old('is_displayed', 1) === '0' ? 'selected' : '' }}>
                                                            ไม่แสดง
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="col-span-12 sm:col-span-6">
                                                    <label class="form-label">
                                                        สถานะ
                                                    </label>
                                                    <select name="is_active" class="form-select">
                                                        <option value="1"
                                                            {{ (string) old('is_active', 1) === '1' ? 'selected' : '' }}>
                                                            เปิดใช้งาน
                                                        </option>
                                                        <option value="0"
                                                            {{ (string) old('is_active', 1) === '0' ? 'selected' : '' }}>
                                                            ปิดใช้งาน
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-success w-full">
                                            <i data-lucide="star" class="w-4 h-4 mr-2"></i>
                                            เพิ่มรีวิวให้คำสั่งขายนี้
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        <div class="intro-y box p-5 border border-slate-100">
                            <h3 class="text-lg font-medium mb-4">ข้อมูลระบบ</h3>

                            <div class="text-sm leading-7">
                                <div class="flex justify-between border-b py-2">
                                    <span class="text-slate-500">Order ID</span>
                                    <span class="font-medium">{{ $order->id }}</span>
                                </div>

                                <div class="flex justify-between border-b py-2">
                                    <span class="text-slate-500">User ID</span>
                                    <span class="font-medium">{{ $order->user_id ?? '-' }}</span>
                                </div>

                                <div class="flex justify-between border-b py-2">
                                    <span class="text-slate-500">Sell Method ID</span>
                                    <span class="font-medium">{{ $order->sell_method_id ?? '-' }}</span>
                                </div>

                                <div class="flex justify-between border-b py-2">
                                    <span class="text-slate-500">Pickup Panel</span>
                                    <span class="font-medium">{{ $order->pickup_panel }}</span>
                                </div>

                                <div class="flex justify-between py-2">
                                    <span class="text-slate-500">อัปเดตล่าสุด</span>
                                    <span class="font-medium">
                                        {{ !empty($order->updated_at) ? \Carbon\Carbon::parse($order->updated_at)->format('d/m/Y H:i') : '-' }}
                                    </span>
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
        document.addEventListener('DOMContentLoaded', function() {
            if (window.lucide) {
                lucide.createIcons();
            }
        });
    </script>
</body>

</html>
