<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <title>{{ $mode === 'edit' ? 'แก้ไขสถานีรถไฟฟ้า' : 'เพิ่มสถานีรถไฟฟ้า' }}</title>
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
                        <a href="{{ route('admin.transit-stations.index') }}">รายการสถานีรถไฟฟ้า</a>
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
                        {{ $mode === 'edit' ? 'แก้ไขสถานีรถไฟฟ้า' : 'เพิ่มสถานีรถไฟฟ้า' }}
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
                        action="{{ $mode === 'edit' ? route('admin.transit-stations.update', $station->id) : route('admin.transit-stations.store') }}">
                        @csrf

                        <div class="grid grid-cols-12 gap-6">
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label">
                                    สายรถไฟฟ้า <span class="text-danger">*</span>
                                </label>
                                <select name="line_id" class="form-control">
                                    <option value="">-- เลือกสายรถไฟฟ้า --</option>
                                    @foreach ($lines as $line)
                                        <option value="{{ $line->id }}"
                                            {{ (string) old('line_id', $station->line_id) === (string) $line->id ? 'selected' : '' }}>
                                            {{ $line->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label">
                                    รหัสสถานี
                                </label>
                                <input type="text" name="station_code"
                                    value="{{ old('station_code', $station->station_code) }}" class="form-control"
                                    placeholder="เช่น N8, E4, BL22">
                            </div>

                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label">
                                    ชื่อสถานีภาษาไทย <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name_th" value="{{ old('name_th', $station->name_th) }}"
                                    class="form-control" placeholder="เช่น อโศก">
                            </div>

                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label">
                                    ชื่อสถานีภาษาอังกฤษ
                                </label>
                                <input type="text" name="name_en" value="{{ old('name_en', $station->name_en) }}"
                                    class="form-control" placeholder="เช่น Asok">
                            </div>

                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label">
                                    จังหวัด
                                </label>
                                <input type="text" name="province_name"
                                    value="{{ old('province_name', $station->province_name) }}" class="form-control"
                                    placeholder="เช่น กรุงเทพมหานคร">
                            </div>

                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label">
                                    เขต / อำเภอ
                                </label>
                                <input type="text" name="district_name"
                                    value="{{ old('district_name', $station->district_name) }}" class="form-control"
                                    placeholder="เช่น วัฒนา">
                            </div>

                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label">
                                    Latitude
                                </label>
                                <input type="text" name="latitude" value="{{ old('latitude', $station->latitude) }}"
                                    class="form-control" placeholder="เช่น 13.736717">
                            </div>

                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label">
                                    Longitude
                                </label>
                                <input type="text" name="longitude"
                                    value="{{ old('longitude', $station->longitude) }}" class="form-control"
                                    placeholder="เช่น 100.561843">
                            </div>

                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label">
                                    ลำดับการแสดงผล
                                </label>
                                <input type="number" name="sort_order"
                                    value="{{ old('sort_order', $station->sort_order ?? 0) }}" class="form-control"
                                    placeholder="0">
                            </div>

                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label">
                                    สถานะ
                                </label>
                                <select name="is_active" class="form-control">
                                    <option value="1"
                                        {{ (string) old('is_active', $station->is_active ?? 1) === '1' ? 'selected' : '' }}>
                                        เปิดใช้งาน
                                    </option>
                                    <option value="0"
                                        {{ (string) old('is_active', $station->is_active ?? 1) === '0' ? 'selected' : '' }}>
                                        ปิดใช้งาน
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-5 flex items-center">
                            <a href="{{ route('admin.transit-stations.index') }}"
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
</body>

</html>
