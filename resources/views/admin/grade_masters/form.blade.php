<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <title>{{ $mode === 'edit' ? 'แก้ไขเกรดพื้นฐาน' : 'เพิ่มเกรดพื้นฐาน' }}</title>
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
                    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">สินค้า</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('admin/grade-masters') }}">เกรดพื้นฐาน</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ $mode === 'edit' ? 'แก้ไขข้อมูล' : 'เพิ่มข้อมูล' }}
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
                <div class="intro-y flex items-center mt-8 mb-5">
                    <h2 class="text-lg font-medium mr-auto">
                        {{ $mode === 'edit' ? 'แก้ไขเกรดพื้นฐาน' : 'เพิ่มเกรดพื้นฐาน' }}
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
                        action="{{ $mode === 'edit' ? url('admin/grade-masters/' . $grade->id . '/update') : url('admin/grade-masters/store') }}">
                        @csrf

                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label">รหัสเกรด <span class="text-danger">*</span></label>
                                <input type="text" name="grade_code" class="form-control"
                                    value="{{ old('grade_code', $grade->grade_code ?? '') }}"
                                    placeholder="เช่น A หรือ FACEID">
                            </div>

                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label">ชื่อเกรด <span class="text-danger">*</span></label>
                                <input type="text" name="grade_name" class="form-control"
                                    value="{{ old('grade_name', $grade->grade_name ?? '') }}"
                                    placeholder="เช่น เกรด A">
                            </div>

                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label">ลำดับการแสดง</label>
                                <input type="number" name="sort_order" min="0" class="form-control"
                                    value="{{ old('sort_order', $grade->sort_order ?? 0) }}" placeholder="เช่น 1">
                            </div>

                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label">สถานะ</label>
                                <select name="status" class="form-select">
                                    <option value="1"
                                        {{ old('status', isset($grade->status) ? (int) $grade->status : 1) == 1 ? 'selected' : '' }}>
                                        เปิดใช้งาน
                                    </option>
                                    <option value="0"
                                        {{ old('status', isset($grade->status) ? (int) $grade->status : 1) == 0 ? 'selected' : '' }}>
                                        ปิดใช้งาน
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-5 flex items-center">
                            <a href="{{ url('admin/grade-masters') }}" class="btn btn-outline-secondary w-24 mr-2">
                                ยกเลิก
                            </a>

                            <button type="submit" class="btn btn-primary">
                                {{ $mode === 'edit' ? 'บันทึกข้อมูล' : 'เพิ่มข้อมูล' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('admin/inc_footer')
</body>

</html>
