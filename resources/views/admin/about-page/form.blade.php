<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <title>จัดการหน้าเกี่ยวกับเรา</title>
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
                        จัดการหน้าเกี่ยวกับเรา
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
                        จัดการหน้าเกี่ยวกับเรา
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

                    @php
                        $getImageUrl = function ($path, $fallback = '') {
                            if (empty($path)) {
                                return !empty($fallback) ? asset($fallback) : '';
                            }

                            if (strpos($path, 'assets/') === 0) {
                                return asset($path);
                            }

                            return asset('storage/' . $path);
                        };
                    @endphp

                    <form method="POST" action="{{ route('admin.about-page.update') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- HERO --}}
                        <div class="border rounded-md p-5 mb-5 bg-white">
                            <div class="flex items-start justify-between gap-4 mb-5">
                                <div>
                                    <h3 class="text-base font-medium">ส่วน Hero ด้านบน</h3>
                                    <p class="text-slate-500 text-sm mt-1">
                                        ใช้สำหรับแบนเนอร์พื้นหลังด้านบนของหน้าเกี่ยวกับเรา อัปโหลดรูปเดียวพอ
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-12 gap-4">
                                <div class="col-span-12 md:col-span-6">
                                    <label class="form-label">หัวข้อใหญ่</label>
                                    <input type="text" name="hero_title" class="form-control"
                                        value="{{ old('hero_title', $about->hero_title ?? '') }}"
                                        placeholder="เช่น เกี่ยวกับเรา">
                                </div>

                                <div class="col-span-12 md:col-span-6">
                                    <label class="form-label">ข้อความรอง</label>
                                    <input type="text" name="hero_subtitle" class="form-control"
                                        value="{{ old('hero_subtitle', $about->hero_subtitle ?? '') }}"
                                        placeholder="เช่น แพลตฟอร์มรับซื้อสินค้ามือสองที่สะดวก รวดเร็ว">
                                </div>

                                <div class="col-span-12 md:col-span-4">
                                    <label class="form-label">สีพื้นหลัง กรณียังไม่มีรูป</label>
                                    <input type="text" name="hero_background_color" class="form-control"
                                        value="{{ old('hero_background_color', $about->hero_background_color ?? '#DFF3EA') }}"
                                        placeholder="#DFF3EA">
                                </div>

                                <div class="col-span-12 md:col-span-8">
                                    <label class="form-label">รูป Hero Banner</label>
                                    <input type="file" name="hero_banner_image" class="form-control"
                                        accept="image/*">

                                    <div class="form-help">
                                        แนะนำรูปแนวนอน เช่น 1920x680px หรือสัดส่วนใกล้เคียง รองรับ jpg, jpeg, png, webp
                                    </div>

                                    @if (!empty($about->hero_banner_image))
                                        <div class="mt-3">
                                            <img src="{{ $getImageUrl($about->hero_banner_image) }}" alt="hero banner"
                                                class="h-32 rounded border object-cover bg-white">
                                        </div>
                                    @else
                                        <div class="mt-3">
                                            <img src="{{ asset('assets/media/hero/hero-silder-img1.png') }}"
                                                alt="hero banner default"
                                                class="h-32 rounded border object-cover bg-white">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- ABOUT --}}
                        <div class="border rounded-md p-5 mb-5 bg-white">
                            <h3 class="text-base font-medium mb-4">ส่วนเกี่ยวกับเรา</h3>

                            <div class="grid grid-cols-12 gap-4">
                                <div class="col-span-12 md:col-span-6">
                                    <label class="form-label">หัวข้อ Section</label>
                                    <input type="text" name="about_section_title" class="form-control"
                                        value="{{ old('about_section_title', $about->about_section_title ?? '') }}"
                                        placeholder="เช่น เกี่ยวกับเรา">
                                </div>

                                <div class="col-span-12 md:col-span-6">
                                    <label class="form-label">ชื่อบริษัท / หัวข้อย่อย</label>
                                    <input type="text" name="about_company_title" class="form-control"
                                        value="{{ old('about_company_title', $about->about_company_title ?? '') }}"
                                        placeholder="เช่น FinFin Phone.com">
                                </div>

                                <div class="col-span-12">
                                    <label class="form-label">รายละเอียดเกี่ยวกับบริษัท</label>
                                    <textarea name="about_description" class="form-control" rows="7" placeholder="กรอกรายละเอียดเกี่ยวกับบริษัท">{{ old('about_description', $about->about_description ?? '') }}</textarea>
                                </div>

                                <div class="col-span-12 md:col-span-6">
                                    <label class="form-label">รูปประกอบ Section (280 * 188 px)</label>
                                    <input type="file" name="about_image" class="form-control" accept="image/*">

                                    <div class="form-help">
                                        รูปนี้ใช้ในกล่องเนื้อหาเกี่ยวกับเรา
                                    </div>

                                    @if (!empty($about->about_image))
                                        <div class="mt-3">
                                            <img src="{{ $getImageUrl($about->about_image) }}" alt="about image"
                                                class="h-24 rounded border object-cover bg-white">
                                        </div>
                                    @else
                                        <div class="mt-3">
                                            <img src="{{ asset('assets/media/image-02.png') }}"
                                                alt="about image default"
                                                class="h-24 rounded border object-cover bg-white">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- WHY CHOOSE US --}}
                        <div class="border rounded-md p-5 mb-5 bg-white">
                            <h3 class="text-base font-medium mb-4">ส่วนทำไมถึงเลือกเรา</h3>

                            <div class="grid grid-cols-12 gap-4 mb-5">
                                <div class="col-span-12 md:col-span-6">
                                    <label class="form-label">หัวข้อ</label>
                                    <input type="text" name="why_choose_title" class="form-control"
                                        value="{{ old('why_choose_title', $about->why_choose_title ?? '') }}"
                                        placeholder="เช่น ทำไมถึงเลือกเรา">
                                </div>

                                <div class="col-span-12 md:col-span-6">
                                    <label class="form-label">คำอธิบายใต้หัวข้อ</label>
                                    <input type="text" name="why_choose_description" class="form-control"
                                        value="{{ old('why_choose_description', $about->why_choose_description ?? '') }}"
                                        placeholder="คำอธิบายใต้หัวข้อ">
                                </div>
                            </div>

                            <div class="alert alert-secondary mb-4">
                                ไอคอนในหน้าบ้านใช้ SVG เดิมตามดีไซน์ ไม่ต้องเลือกหรืออัปโหลดไอคอนใหม่
                            </div>

                            @for ($i = 1; $i <= 4; $i++)
                                <div class="border rounded-md p-4 mb-4 bg-slate-50">
                                    <h4 class="font-medium mb-3">
                                        จุดเด่นรายการที่ {{ $i }}
                                    </h4>

                                    <div class="grid grid-cols-12 gap-4">
                                        <div class="col-span-12 md:col-span-6">
                                            <label class="form-label">หัวข้อ</label>
                                            <input type="text" name="feature_{{ $i }}_title"
                                                class="form-control"
                                                value="{{ old('feature_' . $i . '_title', $about->{'feature_' . $i . '_title'} ?? '') }}"
                                                placeholder="หัวข้อจุดเด่น">
                                        </div>

                                        <div class="col-span-12 md:col-span-6">
                                            <label class="form-label">คำอธิบาย</label>
                                            <input type="text" name="feature_{{ $i }}_description"
                                                class="form-control"
                                                value="{{ old('feature_' . $i . '_description', $about->{'feature_' . $i . '_description'} ?? '') }}"
                                                placeholder="คำอธิบายจุดเด่น">
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                        {{-- FOOTER --}}
                        <div class="border rounded-md p-5 mb-5 bg-white">
                            <h3 class="text-base font-medium mb-4">ส่วนท้ายเว็บไซต์ / ศูนย์ดูแลลูกค้า</h3>

                            <div class="grid grid-cols-12 gap-4">
                                <div class="col-span-12 md:col-span-6">
                                    <label class="form-label">ชื่อบริษัทใน Footer</label>
                                    <input type="text" name="footer_company_name" class="form-control"
                                        value="{{ old('footer_company_name', $about->footer_company_name ?? '') }}"
                                        placeholder="เช่น Cashkub Co., Ltd.">
                                </div>

                                <div class="col-span-12">
                                    <label class="form-label">รายละเอียดบริษัทใน Footer</label>
                                    <textarea name="footer_company_description" class="form-control" rows="5"
                                        placeholder="รายละเอียดบริษัทที่แสดงใน Footer">{{ old('footer_company_description', $about->footer_company_description ?? '') }}</textarea>
                                </div>

                                <div class="col-span-12">
                                    <div class="alert alert-secondary">
                                        เมนูศูนย์ดูแลลูกค้าในหน้าบ้านจะแสดงเฉพาะ 3 รายการ:
                                        วิธีการยกเลิกการขาย, วิธีการขายสินค้า, บริการรับเงิน
                                    </div>
                                </div>

                                <div class="col-span-12 md:col-span-6">
                                    <label class="form-label">เบอร์โทร</label>
                                    <input type="text" name="contact_phone" class="form-control"
                                        value="{{ old('contact_phone', $about->contact_phone ?? '') }}"
                                        placeholder="เช่น 0812345678">
                                </div>

                                <div class="col-span-12 md:col-span-6">
                                    <label class="form-label">อีเมล</label>
                                    <input type="text" name="contact_email" class="form-control"
                                        value="{{ old('contact_email', $about->contact_email ?? '') }}"
                                        placeholder="เช่น hello@example.com">
                                </div>

                                <div class="col-span-12 md:col-span-6">
                                    <label class="form-label">Facebook URL</label>
                                    <input type="text" name="social_facebook" class="form-control"
                                        value="{{ old('social_facebook', $about->social_facebook ?? '') }}"
                                        placeholder="https://facebook.com/...">
                                </div>

                                <div class="col-span-12 md:col-span-6">
                                    <label class="form-label">Instagram URL</label>
                                    <input type="text" name="social_instagram" class="form-control"
                                        value="{{ old('social_instagram', $about->social_instagram ?? '') }}"
                                        placeholder="https://instagram.com/...">
                                </div>

                                <div class="col-span-12 md:col-span-6">
                                    <label class="form-label">Line URL</label>
                                    <input type="text" name="social_line" class="form-control"
                                        value="{{ old('social_line', $about->social_line ?? '') }}"
                                        placeholder="https://line.me/...">
                                </div>

                                <div class="col-span-12 md:col-span-6">
                                    <label class="form-label">YouTube URL</label>
                                    <input type="text" name="social_youtube" class="form-control"
                                        value="{{ old('social_youtube', $about->social_youtube ?? '') }}"
                                        placeholder="https://youtube.com/...">
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 flex items-center">
                            <a href="{{ url('admin/') }}" class="btn btn-outline-secondary w-24 mr-2">
                                ยกเลิก
                            </a>

                            <button type="submit" class="btn btn-primary w-24">
                                บันทึก
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
