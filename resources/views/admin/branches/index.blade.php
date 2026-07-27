<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <title>สาขา/พื้นที่ให้บริการ</title>
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
                        สาขา/พื้นที่ให้บริการ
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
                    <div>
                        <h2 class="text-lg font-medium mr-auto">
                            สาขา/พื้นที่ให้บริการ
                        </h2>
                        <div class="text-slate-500 text-sm mt-1">
                            จัดการจังหวัด/อำเภอที่ให้บริการ และช่วงเวลาที่ลูกค้าเลือกนัดหมายได้
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

                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        {{ $errors->first() }}
                    </div>
                @endif

                @php
                    $oldDistrictIds = collect(old('district_ids', []))
                        ->map(function ($id) {
                            return (string) $id;
                        })
                        ->toArray();

                    $oldProvinceId = old('thai_province_id', '');
                    $oldDistrictMode = old('district_mode', 'all');

                    $provinceDistrictOptions = ($provinces ?? collect())
                        ->map(function ($province) {
                            return [
                                'id' => $province->id,
                                'name_th' => $province->name_th,
                                'districts' => $province->districts
                                    ->map(function ($district) {
                                        return [
                                            'id' => $district->id,
                                            'name_th' => $district->name_th,
                                        ];
                                    })
                                    ->values()
                                    ->toArray(),
                            ];
                        })
                        ->values()
                        ->toArray();
                @endphp

                <div class="grid grid-cols-12 gap-6">
                    <div class="col-span-12 xl:col-span-5">
                        <div class="intro-y box p-5">
                            <div class="flex items-start justify-between gap-4 mb-5">
                                <div>
                                    <h3 class="text-lg font-medium">
                                        เพิ่มพื้นที่ให้บริการ
                                    </h3>
                                    <div class="text-slate-500 text-sm mt-1">
                                        เลือกจังหวัดที่ให้บริการ และกำหนดว่าจะให้บริการทั้งจังหวัดหรือเฉพาะบางอำเภอ
                                    </div>
                                </div>

                                <div
                                    class="w-11 h-11 rounded-full bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                    <i data-lucide="map-pin" class="w-5 h-5"></i>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('admin.branches.store') }}">
                                @csrf

                                <div class="grid grid-cols-12 gap-4">
                                    <div class="col-span-12">
                                        <label class="form-label">
                                            จังหวัด <span class="text-danger">*</span>
                                        </label>

                                        <select name="thai_province_id" id="thai_province_id" class="form-control">
                                            <option value="">-- เลือกจังหวัด --</option>

                                            @foreach ($provinces as $province)
                                                <option value="{{ $province->id }}"
                                                    {{ (string) $oldProvinceId === (string) $province->id ? 'selected' : '' }}>
                                                    {{ $province->name_th }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-span-12">
                                        <label class="form-label">
                                            รูปแบบพื้นที่ให้บริการ <span class="text-danger">*</span>
                                        </label>

                                        <div class="grid grid-cols-12 gap-3">
                                            <label
                                                class="col-span-12 md:col-span-6 flex items-center gap-3 rounded-lg border border-slate-200 bg-white px-4 py-3 cursor-pointer">
                                                <input type="radio" name="district_mode" value="all"
                                                    class="form-check-input"
                                                    {{ $oldDistrictMode === 'all' ? 'checked' : '' }}>
                                                <span>
                                                    <span class="block font-medium">ทั้งจังหวัด</span>
                                                    <span class="block text-xs text-slate-500 mt-1">
                                                        ลูกค้าเลือกอำเภอใดก็ได้ในจังหวัดนี้
                                                    </span>
                                                </span>
                                            </label>

                                            <label
                                                class="col-span-12 md:col-span-6 flex items-center gap-3 rounded-lg border border-slate-200 bg-white px-4 py-3 cursor-pointer">
                                                <input type="radio" name="district_mode" value="selected"
                                                    class="form-check-input"
                                                    {{ $oldDistrictMode === 'selected' ? 'checked' : '' }}>
                                                <span>
                                                    <span class="block font-medium">เลือกบางอำเภอ</span>
                                                    <span class="block text-xs text-slate-500 mt-1">
                                                        เลือกเฉพาะอำเภอที่เปิดให้บริการ
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-span-12" id="districtSelectBox">
                                        <label class="form-label">
                                            เขต / อำเภอที่ให้บริการ
                                        </label>

                                        <div
                                            class="rounded-lg border border-slate-200 bg-slate-50 p-4 max-h-[280px] overflow-y-auto">
                                            <div id="districtCheckboxList" class="grid grid-cols-12 gap-2">
                                                <div class="col-span-12 text-slate-500 text-sm">
                                                    กรุณาเลือกจังหวัดก่อน
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-xs text-slate-500 mt-2">
                                            เลือกได้หลายอำเภอ กรณีเลือก “ทั้งจังหวัด” ไม่ต้องเลือกอำเภอ
                                        </div>
                                    </div>

                                    <div class="col-span-12">
                                        <button type="submit" class="btn btn-primary w-full">
                                            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                                            บันทึกพื้นที่ให้บริการ
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-span-12 xl:col-span-7">
                        <div class="intro-y box p-5">
                            <div class="flex items-start justify-between gap-4 mb-5">
                                <div>
                                    <h3 class="text-lg font-medium">
                                        รายการพื้นที่ให้บริการ
                                    </h3>
                                    <div class="text-slate-500 text-sm mt-1">
                                        ข้อมูลจากตาราง service_areas โดยอ้างอิงรหัสจังหวัด/อำเภอ
                                    </div>
                                </div>

                                <div class="px-3 py-2 rounded-full bg-slate-100 text-slate-600 text-xs font-medium">
                                    ทั้งหมด {{ $serviceAreas->count() ?? 0 }} รายการ
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-report mt-2" id="service-areas-table">
                                    <thead>
                                        <tr>
                                            <th class="text-center whitespace-nowrap">ลำดับ</th>
                                            <th class="whitespace-nowrap">จังหวัด</th>
                                            <th class="whitespace-nowrap">เขต/อำเภอ</th>
                                            <th class="text-center whitespace-nowrap">รูปแบบ</th>
                                            <th class="text-center whitespace-nowrap">วันที่สร้าง</th>
                                            <th class="text-center whitespace-nowrap">จัดการ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($serviceAreas as $key => $serviceArea)
                                            @php
                                                $provinceName =
                                                    $serviceArea->provinceData->name_th ??
                                                    ($serviceArea->province ?? '-');

                                                $districtName =
                                                    (int) $serviceArea->is_all_districts === 1
                                                        ? 'ทุกอำเภอ'
                                                        : $serviceArea->districtData->name_th ??
                                                            ($serviceArea->district ?? '-');
                                            @endphp

                                            <tr>
                                                <td class="text-center">
                                                    {{ $key + 1 }}
                                                </td>

                                                <td>
                                                    <div class="font-medium whitespace-nowrap">
                                                        {{ $provinceName }}
                                                    </div>
                                                </td>

                                                <td>
                                                    @if ((int) $serviceArea->is_all_districts === 1)
                                                        <span
                                                            class="px-2 py-1 rounded-full text-xs bg-primary/10 text-primary">
                                                            ทุกอำเภอ
                                                        </span>
                                                    @else
                                                        <span
                                                            class="px-2 py-1 rounded-full text-xs bg-success/10 text-success">
                                                            {{ $districtName }}
                                                        </span>
                                                    @endif
                                                </td>

                                                <td class="text-center">
                                                    @if ((int) $serviceArea->is_all_districts === 1)
                                                        <span
                                                            class="px-2 py-1 rounded-full text-xs bg-primary/10 text-primary">
                                                            ทั้งจังหวัด
                                                        </span>
                                                    @else
                                                        <span
                                                            class="px-2 py-1 rounded-full text-xs bg-slate-100 text-slate-600">
                                                            รายอำเภอ
                                                        </span>
                                                    @endif
                                                </td>

                                                <td class="text-center">
                                                    {{ optional($serviceArea->created_at)->format('d/m/Y H:i') }}
                                                </td>

                                                <td class="text-center">
                                                    <div class="flex justify-center items-center gap-3">
                                                        <a href="javascript:void(0);"
                                                            class="flex items-center text-danger"
                                                            onclick="openDeleteServiceAreaModal({{ $serviceArea->id }})">
                                                            <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i>
                                                            ลบ
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-slate-500 py-8">
                                                    ยังไม่มีพื้นที่ให้บริการ
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12 xl:col-span-5">
                        <div class="intro-y box p-5">
                            <div class="flex items-start justify-between gap-4 mb-5">
                                <div>
                                    <h3 class="text-lg font-medium">
                                        เพิ่มช่วงเวลาที่รับบริการ
                                    </h3>
                                    <div class="text-slate-500 text-sm mt-1">
                                        กำหนดช่วงเวลาที่ลูกค้าเลือกนัดหมายได้
                                    </div>
                                </div>

                                <div
                                    class="w-11 h-11 rounded-full bg-warning/10 text-warning flex items-center justify-center shrink-0">
                                    <i data-lucide="clock" class="w-5 h-5"></i>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('admin.branches.time-slots.store') }}">
                                @csrf

                                <div class="grid grid-cols-12 gap-4">
                                    <div class="col-span-12">
                                        <label class="form-label">
                                            ชื่อช่วงเวลา
                                        </label>
                                        <input type="text" name="label" value="{{ old('label') }}"
                                            class="form-control"
                                            placeholder="ถ้าไม่กรอก ระบบจะสร้างจากเวลาให้อัตโนมัติ">
                                    </div>

                                    <div class="col-span-6">
                                        <label class="form-label">
                                            เวลาเริ่มต้น <span class="text-danger">*</span>
                                        </label>
                                        <input type="time" name="start_time" value="{{ old('start_time') }}"
                                            class="form-control">
                                    </div>

                                    <div class="col-span-6">
                                        <label class="form-label">
                                            เวลาสิ้นสุด <span class="text-danger">*</span>
                                        </label>
                                        <input type="time" name="end_time" value="{{ old('end_time') }}"
                                            class="form-control">
                                    </div>

                                    <div class="col-span-6">
                                        <label class="form-label">
                                            ลำดับ
                                        </label>
                                        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                                            class="form-control" placeholder="0">
                                    </div>

                                    <div class="col-span-6">
                                        <label class="form-label">
                                            สถานะ
                                        </label>
                                        <select name="is_active" class="form-control">
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

                                    <div class="col-span-12">
                                        <button type="submit" class="btn btn-primary w-full">
                                            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                                            เพิ่มช่วงเวลา
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-span-12 xl:col-span-7">
                        <div class="intro-y box p-5">
                            <div class="flex items-start justify-between gap-4 mb-5">
                                <div>
                                    <h3 class="text-lg font-medium">
                                        ช่วงเวลาที่รับบริการ
                                    </h3>
                                    <div class="text-slate-500 text-sm mt-1">
                                        ข้อมูลจากตาราง service_time_slots
                                    </div>
                                </div>

                                <div class="px-3 py-2 rounded-full bg-slate-100 text-slate-600 text-xs font-medium">
                                    ทั้งหมด {{ $serviceTimeSlots->count() ?? 0 }} รายการ
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-report mt-2" id="service-time-slots-table">
                                    <thead>
                                        <tr>
                                            <th class="text-center whitespace-nowrap">ลำดับ</th>
                                            <th class="whitespace-nowrap">ช่วงเวลา</th>
                                            <th class="text-center whitespace-nowrap">เวลาเริ่ม</th>
                                            <th class="text-center whitespace-nowrap">เวลาสิ้นสุด</th>
                                            <th class="text-center whitespace-nowrap">ลำดับ</th>
                                            <th class="text-center whitespace-nowrap">สถานะ</th>
                                            <th class="text-center whitespace-nowrap">จัดการ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($serviceTimeSlots as $key => $slot)
                                            <tr>
                                                <td class="text-center">
                                                    {{ $key + 1 }}
                                                </td>

                                                <td>
                                                    <form id="update-time-slot-form-{{ $slot->id }}"
                                                        method="POST"
                                                        action="{{ route('admin.branches.time-slots.update', $slot->id) }}">
                                                        @csrf

                                                        <input type="text" name="label"
                                                            value="{{ old('label', $slot->label) }}"
                                                            class="form-control min-w-[160px]"
                                                            placeholder="เช่น 09:00 - 12:00">
                                                    </form>
                                                </td>

                                                <td class="text-center">
                                                    <input type="time" name="start_time"
                                                        form="update-time-slot-form-{{ $slot->id }}"
                                                        value="{{ old('start_time', \Carbon\Carbon::parse($slot->start_time)->format('H:i')) }}"
                                                        class="form-control min-w-[120px]">
                                                </td>

                                                <td class="text-center">
                                                    <input type="time" name="end_time"
                                                        form="update-time-slot-form-{{ $slot->id }}"
                                                        value="{{ old('end_time', \Carbon\Carbon::parse($slot->end_time)->format('H:i')) }}"
                                                        class="form-control min-w-[120px]">
                                                </td>

                                                <td class="text-center">
                                                    <input type="number" name="sort_order"
                                                        form="update-time-slot-form-{{ $slot->id }}"
                                                        value="{{ old('sort_order', $slot->sort_order) }}"
                                                        class="form-control min-w-[80px] text-center">
                                                </td>

                                                <td class="text-center">
                                                    <select name="is_active"
                                                        form="update-time-slot-form-{{ $slot->id }}"
                                                        class="form-control min-w-[120px]">
                                                        <option value="1"
                                                            {{ (string) old('is_active', $slot->is_active) === '1' ? 'selected' : '' }}>
                                                            เปิด
                                                        </option>
                                                        <option value="0"
                                                            {{ (string) old('is_active', $slot->is_active) === '0' ? 'selected' : '' }}>
                                                            ปิด
                                                        </option>
                                                    </select>
                                                </td>

                                                <td class="text-center">
                                                    <div class="flex justify-center items-center gap-3">
                                                        <button type="submit"
                                                            form="update-time-slot-form-{{ $slot->id }}"
                                                            class="flex items-center text-primary">
                                                            <i data-lucide="save" class="w-4 h-4 mr-1"></i>
                                                            บันทึก
                                                        </button>

                                                        <a href="javascript:void(0);"
                                                            class="flex items-center text-danger"
                                                            onclick="openDeleteTimeSlotModal({{ $slot->id }})">
                                                            <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i>
                                                            ลบ
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-slate-500 py-8">
                                                    ยังไม่มีช่วงเวลาที่รับบริการ
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="delete-service-area-modal" class="modal" tabindex="-1" aria-hidden="true"
                    data-tw-backdrop="static">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body p-0">
                                <form method="POST" action="{{ route('admin.branches.delete') }}">
                                    @csrf
                                    <div class="p-5 text-center">
                                        <input type="hidden" id="delete-service-area-id" name="id">
                                        <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                                        <div class="text-3xl mt-5">
                                            คุณต้องการลบพื้นที่ให้บริการ?
                                        </div>
                                        <div class="text-slate-500 mt-2">
                                            หากลบแล้วจะไม่สามารถกู้คืนข้อมูลเดิมได้
                                        </div>
                                    </div>
                                    <div class="px-5 pb-8 text-center">
                                        <button type="button" data-tw-dismiss="modal"
                                            class="btn btn-outline-secondary w-24 mr-1">
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

                <div id="delete-time-slot-modal" class="modal" tabindex="-1" aria-hidden="true"
                    data-tw-backdrop="static">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body p-0">
                                <form method="POST" action="{{ route('admin.branches.time-slots.delete') }}">
                                    @csrf
                                    <div class="p-5 text-center">
                                        <input type="hidden" id="delete-time-slot-id" name="id">
                                        <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                                        <div class="text-3xl mt-5">
                                            คุณต้องการลบช่วงเวลา?
                                        </div>
                                        <div class="text-slate-500 mt-2">
                                            หากลบแล้วจะไม่สามารถกู้คืนข้อมูลเดิมได้
                                        </div>
                                    </div>
                                    <div class="px-5 pb-8 text-center">
                                        <button type="button" data-tw-dismiss="modal"
                                            class="btn btn-outline-secondary w-24 mr-1">
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

            </div>
        </div>
    </div>

    @include('admin.inc_footer')

    <script>
        const provinceDistrictOptions = @json($provinceDistrictOptions);
        const oldDistrictIds = @json($oldDistrictIds);
        const oldDistrictMode = @json($oldDistrictMode);

        function openDeleteServiceAreaModal(id) {
            document.getElementById('delete-service-area-id').value = id;

            const modalEl = document.querySelector('#delete-service-area-modal');
            const modal = tailwind.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }

        function openDeleteTimeSlotModal(id) {
            document.getElementById('delete-time-slot-id').value = id;

            const modalEl = document.querySelector('#delete-time-slot-modal');
            const modal = tailwind.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }

        function getSelectedDistrictMode() {
            const checked = document.querySelector('input[name="district_mode"]:checked');

            return checked ? checked.value : 'all';
        }

        function updateDistrictBoxVisibility() {
            const mode = getSelectedDistrictMode();
            const box = document.getElementById('districtSelectBox');

            if (!box) {
                return;
            }

            box.classList.toggle('hidden', mode !== 'selected');
        }

        function renderDistrictCheckboxes() {
            const provinceSelect = document.getElementById('thai_province_id');
            const list = document.getElementById('districtCheckboxList');

            if (!provinceSelect || !list) {
                return;
            }

            const provinceId = String(provinceSelect.value || '');
            const province = provinceDistrictOptions.find(function(item) {
                return String(item.id) === provinceId;
            });

            list.innerHTML = '';

            if (!provinceId) {
                list.innerHTML = '<div class="col-span-12 text-slate-500 text-sm">กรุณาเลือกจังหวัดก่อน</div>';
                return;
            }

            if (!province || !province.districts || province.districts.length <= 0) {
                list.innerHTML = '<div class="col-span-12 text-slate-500 text-sm">จังหวัดนี้ยังไม่มีข้อมูลอำเภอ</div>';
                return;
            }

            province.districts.forEach(function(district) {
                const districtId = String(district.id);
                const checked = oldDistrictMode === 'selected' && oldDistrictIds.includes(districtId);

                const wrapper = document.createElement('label');
                wrapper.className =
                    'col-span-12 sm:col-span-6 flex items-center gap-2 rounded-md bg-white border border-slate-200 px-3 py-2 cursor-pointer';

                wrapper.innerHTML = `
                    <input type="checkbox" name="district_ids[]" value="${districtId}" class="form-check-input" ${checked ? 'checked' : ''}>
                    <span class="text-sm">${district.name_th}</span>
                `;

                list.appendChild(wrapper);
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const provinceSelect = document.getElementById('thai_province_id');
            const districtModeInputs = document.querySelectorAll('input[name="district_mode"]');

            if (provinceSelect) {
                provinceSelect.addEventListener('change', function() {
                    renderDistrictCheckboxes();
                });

                renderDistrictCheckboxes();
            }

            districtModeInputs.forEach(function(input) {
                input.addEventListener('change', function() {
                    updateDistrictBoxVisibility();
                });
            });

            updateDistrictBoxVisibility();

            jQuery(function($) {
                $('#service-areas-table').DataTable({
                    responsive: true,
                    order: [
                        [1, "asc"],
                        [2, "asc"]
                    ],
                    language: {
                        url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Thai.json"
                    }
                });

                $('#service-time-slots-table').DataTable({
                    responsive: true,
                    order: [
                        [4, "asc"],
                        [2, "asc"]
                    ],
                    language: {
                        url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Thai.json"
                    }
                });
            });
        });
    </script>
</body>

</html>
