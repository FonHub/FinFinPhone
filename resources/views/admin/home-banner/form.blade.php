<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <title>{{ $mode === 'edit' ? 'แก้ไขแบนเนอร์หน้าแรก' : 'เพิ่มแบนเนอร์หน้าแรก' }}</title>
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
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.home-banner.index') }}">รายการแบนเนอร์หน้าแรก</a></li>
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
                        {{ $mode === 'edit' ? 'แก้ไขแบนเนอร์หน้าแรก' : 'เพิ่มแบนเนอร์หน้าแรก' }}
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
                        action="{{ $mode === 'edit' ? route('admin.home-banner.update', $banner->id) : route('admin.home-banner.store') }}"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-12 gap-6">
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label">
                                    รูปแบนเนอร์สำหรับเดสก์ท็อป ( ขนาดแนะนำ 1440*796 )
                                    @if ($mode === 'create')
                                        <span class="text-danger">*</span>
                                    @endif
                                </label>
                                <input type="file" name="desktop_image" class="form-control"
                                    accept=".jpg,.jpeg,.png,.webp">

                                @if (!empty($banner->desktop_image))
                                    <div class="mt-3">
                                        <div class="text-slate-500 text-sm mb-2">รูปปัจจุบัน</div>
                                        <img src="{{ asset('storage/' . $banner->desktop_image) }}"
                                            alt="Desktop Banner" class="max-h-40 rounded-lg border">
                                    </div>
                                @endif
                            </div>

                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label">
                                    รูปแบนเนอร์สำหรับมือถือ ( ขนาดแนะนำ 390*427 )
                                    @if ($mode === 'create')
                                        <span class="text-danger">*</span>
                                    @endif
                                </label>
                                <input type="file" name="mobile_image" class="form-control"
                                    accept=".jpg,.jpeg,.png,.webp">

                                @if (!empty($banner->mobile_image))
                                    <div class="mt-3">
                                        <div class="text-slate-500 text-sm mb-2">รูปปัจจุบัน</div>
                                        <img src="{{ asset('storage/' . $banner->mobile_image) }}" alt="Mobile Banner"
                                            class="max-h-40 rounded-lg border">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-5 flex items-center">
                            <a href="{{ route('admin.home-banner.index') }}"
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
