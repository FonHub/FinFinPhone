<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <title>{{ $mode === 'edit' ? 'แก้ไขแบรนด์สินค้า' : 'เพิ่มแบรนด์สินค้า' }}</title>
    @include('admin/inc_header')
</head>

<body class="main">
    @include('admin/inc_mobilemenu')

    @php
        $sortOrder = old(
            'sort_order',
            !empty($brand->sort_order)
                ? $brand->sort_order
                : ($nextSortOrder ?? 1)
        );
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
                    <li class="breadcrumb-item">
                        <a href="{{ url('admin/') }}">
                            สินค้า
                        </a>
                    </li>

                    <li class="breadcrumb-item">
                        <a href="{{ url('admin/mobile-brands') }}">
                            แบรนด์สินค้า
                        </a>
                    </li>

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
                        {{ $mode === 'edit' ? 'แก้ไขแบรนด์สินค้า' : 'เพิ่มแบรนด์สินค้า' }}
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

                    <form
                        method="POST"
                        action="{{ $mode === 'edit'
                            ? url('admin/mobile-brands/' . $brand->id . '/update')
                            : url('admin/mobile-brands/store') }}">

                        @csrf

                        <div class="grid grid-cols-12 gap-4">

                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label">
                                    ชื่อแบรนด์
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    class="form-control"
                                    value="{{ old('name', $brand->name ?? '') }}"
                                    placeholder="กรอกชื่อแบรนด์สินค้า"
                                    required>
                            </div>

                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label">
                                    ลำดับการแสดงผล
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="number"
                                    name="sort_order"
                                    class="form-control"
                                    value="{{ $sortOrder }}"
                                    min="1"
                                    step="1"
                                    placeholder="เช่น 1"
                                    required>

                                <div class="text-slate-500 text-xs mt-1">
                                    หากระบุลำดับซ้ำ รายการเดิมจะถูกขยับลำดับให้อัตโนมัติ
                                </div>
                            </div>

                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label">
                                    สถานะ
                                </label>

                                <select
                                    name="status"
                                    class="form-select">

                                    <option
                                        value="1"
                                        {{ old(
                                            'status',
                                            isset($brand->status)
                                                ? (int) $brand->status
                                                : 1
                                        ) == 1 ? 'selected' : '' }}>
                                        เปิดใช้งาน
                                    </option>

                                    <option
                                        value="0"
                                        {{ old(
                                            'status',
                                            isset($brand->status)
                                                ? (int) $brand->status
                                                : 1
                                        ) == 0 ? 'selected' : '' }}>
                                        ปิดใช้งาน
                                    </option>

                                </select>
                            </div>

                        </div>

                        <div class="mt-5 flex items-center">

                            <a
                                href="{{ url('admin/mobile-brands') }}"
                                class="btn btn-outline-secondary w-24 mr-2">
                                ยกเลิก
                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary w-24">

                                {{ $mode === 'edit'
                                    ? 'บันทึก'
                                    : 'เพิ่มข้อมูล' }}

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