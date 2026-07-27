<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <title>{{ $mode === 'edit' ? 'แก้ไขพื้นที่ให้บริการ' : 'เพิ่มพื้นที่ให้บริการ' }}</title>
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
                        <a href="{{ route('admin.branches.index') }}">สาขา/พื้นที่ให้บริการ</a>
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
                        {{ $mode === 'edit' ? 'แก้ไขพื้นที่ให้บริการ' : 'เพิ่มพื้นที่ให้บริการ' }}
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
                        action="{{ $mode === 'edit' ? route('admin.branches.update', $serviceArea->id) : route('admin.branches.store') }}">
                        @csrf

                        <div class="grid grid-cols-12 gap-6">
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label">
                                    จังหวัด <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="province"
                                    value="{{ old('province', $serviceArea->province) }}" class="form-control"
                                    placeholder="เช่น กรุงเทพมหานคร">
                            </div>

                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label">
                                    เขต / อำเภอ <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="district"
                                    value="{{ old('district', $serviceArea->district) }}" class="form-control"
                                    placeholder="เช่น บางแค">
                            </div>
                        </div>

                        <div class="mt-5 flex items-center">
                            <a href="{{ route('admin.branches.index') }}" class="btn btn-outline-secondary w-24 mr-2">
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
