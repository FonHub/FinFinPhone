<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <title>{{ $mode === 'edit' ? 'แก้ไขสายรถไฟฟ้า' : 'เพิ่มสายรถไฟฟ้า' }}</title>
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
                    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">หน้าหลัก</a></li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.transit-lines.index') }}">รายการสายรถไฟฟ้า</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ $mode === 'edit' ? 'แก้ไขข้อมูล' : 'เพิ่มข้อมูล' }}
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
                        {{ $mode === 'edit' ? 'แก้ไขสายรถไฟฟ้า' : 'เพิ่มสายรถไฟฟ้า' }}
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
                        action="{{ $mode === 'edit' ? route('admin.transit-lines.update', $line->id) : route('admin.transit-lines.store') }}">
                        @csrf

                        <div class="grid grid-cols-12 gap-6">
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label">
                                    รหัสสายรถไฟฟ้า <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="code" value="{{ old('code', $line->code) }}"
                                    class="form-control" placeholder="เช่น BTS_SUKHUMVIT, MRT_BLUE">
                            </div>

                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label">
                                    ชื่อสายรถไฟฟ้า <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" value="{{ old('name', $line->name) }}"
                                    class="form-control" placeholder="เช่น BTS สายสุขุมวิท">
                            </div>

                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label">
                                    ผู้ให้บริการ
                                </label>
                                <input type="text" name="operator_name"
                                    value="{{ old('operator_name', $line->operator_name) }}" class="form-control"
                                    placeholder="เช่น BTS, MRT, SRTET">
                            </div>

                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label">
                                    สีประจำสาย
                                </label>

                                <div class="flex items-center gap-3">
                                    <input type="color" id="line_color_picker"
                                        value="{{ old('line_color', $line->line_color ?: '#7AC143') }}"
                                        class="w-14 h-10 rounded border cursor-pointer">

                                    <input type="text" id="line_color" name="line_color"
                                        value="{{ old('line_color', $line->line_color ?: '#7AC143') }}"
                                        class="form-control" placeholder="เช่น #7AC143">
                                </div>

                                <div class="text-slate-500 text-xs mt-2">
                                    สามารถเลือกสีจากช่องสี หรือกรอกรหัสสี Hex เองได้ เช่น #7AC143
                                </div>
                            </div>

                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label">
                                    ลำดับการแสดงผล
                                </label>
                                <input type="number" name="sort_order"
                                    value="{{ old('sort_order', $line->sort_order ?? 0) }}" class="form-control"
                                    placeholder="0">
                            </div>

                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label">
                                    สถานะ
                                </label>
                                <select name="is_active" class="form-control">
                                    <option value="1"
                                        {{ (string) old('is_active', $line->is_active ?? 1) === '1' ? 'selected' : '' }}>
                                        เปิดใช้งาน
                                    </option>
                                    <option value="0"
                                        {{ (string) old('is_active', $line->is_active ?? 1) === '0' ? 'selected' : '' }}>
                                        ปิดใช้งาน
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-5 flex items-center">
                            <a href="{{ route('admin.transit-lines.index') }}"
                                class="btn btn-outline-secondary w-24 mr-2">
                                ยกเลิก
                            </a>
                            <button type="submit" class="btn btn-primary w-24">
                                {{ $mode === 'edit' ? 'บันทึก' : 'เพิ่ม' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('admin.inc_footer')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const picker = document.getElementById('line_color_picker');
            const input = document.getElementById('line_color');

            if (!picker || !input) {
                return;
            }

            picker.addEventListener('input', function() {
                input.value = picker.value.toUpperCase();
            });

            input.addEventListener('input', function() {
                const value = input.value.trim();

                if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
                    picker.value = value;
                }
            });
        });
    </script>
</body>

</html>
