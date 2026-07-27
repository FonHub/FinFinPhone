<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <title>{{ $isEdit ? 'แก้ไขประเภทสินค้า' : 'เพิ่มประเภทสินค้า' }}</title>
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
                    <li class="breadcrumb-item">
                        <a href="{{ url('admin/mobile-product-categories') }}">ประเภทสินค้า</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ $isEdit ? 'แก้ไขประเภทสินค้า' : 'เพิ่มประเภทสินค้า' }}
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
                        {{ $isEdit ? 'แก้ไขประเภทสินค้า' : 'เพิ่มประเภทสินค้า' }}
                    </h2>
                </div>

                <div class="intro-y box p-5">
                    @if ($errors->any())
                        <div class="alert alert-danger mb-5">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success mb-5">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger mb-5">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ $action }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label">
                                    ชื่อประเภทสินค้า <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="category_name" class="form-control"
                                    value="{{ old('category_name', $category->category_name ?? '') }}"
                                    placeholder="กรอกชื่อประเภทสินค้า" required>
                            </div>

                            <div class="col-span-12 md:col-span-3">
                                <label class="form-label">เรียงลำดับ</label>
                                <input type="number" name="sort_order" class="form-control"
                                    value="{{ old('sort_order', $category->sort_order ?? 0) }}" min="0">
                            </div>

                            <div class="col-span-12 md:col-span-3">
                                <label class="form-label">
                                    สถานะ <span class="text-danger">*</span>
                                </label>
                                <select name="status" class="form-select" required>
                                    <option value="1"
                                        {{ old('status', (string) ($category->status ?? 1)) == '1' ? 'selected' : '' }}>
                                        เปิดใช้งาน
                                    </option>
                                    <option value="0"
                                        {{ old('status', (string) ($category->status ?? 1)) == '0' ? 'selected' : '' }}>
                                        ปิดใช้งาน
                                    </option>
                                </select>
                            </div>

                            <div class="col-span-12">
                                <label class="form-label">ไอคอนประเภทสินค้า</label>

                                @if (isset($icons) && $icons->count() > 0)
                                    <div class="grid grid-cols-12 gap-3">
                                        @foreach ($icons as $icon)
                                            @php
                                                $checked = old('icon', $category->icon ?? '') === $icon->icon_key;
                                            @endphp

                                            <label class="col-span-12 md:col-span-6 xl:col-span-4 cursor-pointer">
                                                <input type="radio" name="icon" value="{{ $icon->icon_key }}"
                                                    class="hidden peer" {{ $checked ? 'checked' : '' }}>

                                                <div
                                                    class="border rounded-xl p-4 bg-white hover:border-primary peer-checked:border-primary peer-checked:ring-2 peer-checked:ring-primary/30 transition">
                                                    <div class="flex items-center gap-3">
                                                        <img src="{{ asset($icon->icon_default) }}"
                                                            class="w-12 h-12 object-contain" alt="{{ $icon->name }}">

                                                        <div>
                                                            <div class="font-medium text-slate-700">
                                                                {{ $icon->label_th }}
                                                            </div>
                                                            <div class="text-xs text-slate-500">
                                                                {{ $icon->name }}
                                                            </div>
                                                            <div class="text-xs text-slate-400 mt-1">
                                                                {{ $icon->icon_key }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>

                                    <div class="mt-3">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="radio" name="icon" value=""
                                                class="form-check-input mr-2"
                                                {{ old('icon', $category->icon ?? '') === '' || old('icon', $category->icon ?? '') === null ? 'checked' : '' }}>
                                            <span class="text-slate-600">ไม่เลือกไอคอน</span>
                                        </label>
                                    </div>
                                @else
                                    <div class="alert alert-warning">
                                        ยังไม่มีข้อมูลไอคอน กรุณาเพิ่มข้อมูลในตาราง product_category_icons ก่อน
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex gap-2 mt-6">
                            <a href="{{ url('admin/mobile-product-categories') }}" class="btn btn-outline-secondary">
                                ยกเลิก
                            </a>

                            <button type="submit" class="btn btn-primary">
                                {{ $isEdit ? 'บันทึกการแก้ไข' : 'บันทึกข้อมูล' }}
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
