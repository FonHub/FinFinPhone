<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <title>แก้ไข{{ $supportPage->page_title ?? 'หน้าศูนย์ดูแลลูกค้า' }}</title>
    @include('admin.inc_header')

    <style>
        .support-form-nav {
            position: sticky;
            top: 24px;
        }

        .support-form-nav a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 14px;
            border-radius: 12px;
            color: #64748b;
            font-weight: 600;
            font-size: 14px;
            transition: all .18s ease;
        }

        .support-form-nav a:hover,
        .support-form-nav a.is-active {
            background: #eefcf5;
            color: #0f9f6e;
        }

        .support-card {
            border: 1px solid #e5ece8;
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 10px 30px rgba(15, 23, 42, .035);
        }

        .support-card-header {
            padding: 18px 20px;
            border-bottom: 1px solid #edf2ef;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
        }

        .support-card-body {
            padding: 20px;
        }

        .section-row {
            border: 1px solid #e6ece8;
            border-radius: 18px;
            background: #fbfdfc;
            overflow: hidden;
        }

        .section-row-header {
            padding: 16px 18px;
            background: #f5faf7;
            border-bottom: 1px solid #e6ece8;
        }

        .section-row-body {
            padding: 18px;
        }

        .item-table th {
            background: #f8faf9;
            color: #475569;
            font-size: 13px;
            font-weight: 700;
        }

        .item-table td {
            vertical-align: top;
        }

        .form-help-soft {
            color: #94a3b8;
            font-size: 12px;
            margin-top: 6px;
            line-height: 1.5;
        }

        .badge-soft-green {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 6px 12px;
            background: #eaf7f1;
            color: #13885c;
            font-size: 12px;
            font-weight: 700;
        }

        .quick-key {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 4px 10px;
            background: #f1f5f9;
            color: #64748b;
            font-size: 12px;
            font-weight: 700;
        }

        @media (max-width: 1023px) {
            .support-form-nav {
                position: static;
            }
        }

        .support-panel {
            display: none;
        }

        .support-panel.is-active {
            display: block;
        }
    </style>
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
                        <a
                            href="{{ route('admin.support-pages.edit', ['slug' => $supportPage->slug]) }}">ศูนย์ดูแลลูกค้า</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        แก้ไขข้อมูล
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
                <div class="intro-y flex flex-col md:flex-row md:items-center gap-3 mt-8 mb-5">
                    <div class="mr-auto">
                        <h2 class="text-xl font-semibold">
                            แก้ไข{{ $supportPage->page_title ?? 'หน้าศูนย์ดูแลลูกค้า' }}
                        </h2>

                        <div class="text-slate-500 mt-1">
                            จัดการข้อมูลหน้าบ้าน แถบเมนู Hero Section รายการย่อย และกล่องติดต่อ
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ url('admin/') }}" class="btn btn-outline-secondary">
                            กลับ
                        </a>

                        @if ($supportPage->slug === 'cancel-selling')
                            <a href="{{ route('cancel.selling') }}" target="_blank" class="btn btn-primary">
                                ดูหน้าบ้าน
                            </a>
                        @elseif ($supportPage->slug === 'how-to-sell')
                            <a href="{{ route('how.to.sell') }}" target="_blank" class="btn btn-primary">
                                ดูหน้าบ้าน
                            </a>
                        @elseif ($supportPage->slug === 'how-to-get-paid')
                            <a href="{{ route('how.to.get.paid') }}" target="_blank" class="btn btn-primary">
                                ดูหน้าบ้าน
                            </a>
                        @endif
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
                    $sectionList = $supportPage->sections ?? collect();

                    $iconOptions = [
                        'check' => 'check',
                        'alert' => 'alert',
                        'phone' => 'phone',
                        'phone_call' => 'phone_call',
                        'money' => 'money',
                        'wallet' => 'wallet',
                        'bank' => 'bank',
                        'chat' => 'chat',
                        'calendar' => 'calendar',
                        'flash' => 'flash',
                        'shield' => 'shield',
                        'lock' => 'lock',
                        'location' => 'location',
                        'gift' => 'gift',
                        'mail' => 'mail',
                        'clock' => 'clock',
                    ];

                    $sectionKeyExamples = [
                        'cancel-selling' => [
                            'cancel_info' => 'กล่องข้อมูลสำคัญก่อนยกเลิกการขาย',
                            'summary' => 'สรุปภาพรวมด้านขวา',
                        ],
                        'how-to-sell' => [
                            'tips' => 'กล่องก่อนเริ่มขาย',
                            'steps' => 'ขั้นตอนการขาย',
                            'payment_info' => 'กล่องข้อมูลการรับเงินด้านขวา',
                            'summary' => 'สรุปภาพรวมด้านขวา',
                        ],
                        'how-to-get-paid' => [
                            'important_notes' => 'กล่องสิ่งที่ควรรู้ก่อนรับเงิน',
                            'payment_steps' => 'ขั้นตอนการรับเงิน',
                            'payment_channels' => 'ช่องทางการรับเงิน',
                            'summary' => 'สรุปภาพรวมด้านขวา',
                        ],
                    ];

                    $currentKeyExamples = $sectionKeyExamples[$supportPage->slug] ?? [];
                @endphp

                <form method="POST"
                    action="{{ route('admin.support-pages.update', ['slug' => $supportPage->slug]) }}">
                    @csrf


                    <div class="grid grid-cols-12 gap-5">
                        {{-- LEFT NAV --}}
                        <div class="col-span-12 lg:col-span-3">
                            <div class="support-card support-form-nav">
                                <div class="support-card-body">
                                    <div class="mb-4">
                                        <div class="badge-soft-green">
                                            {{ $supportPage->slug }}
                                        </div>

                                        <div class="text-slate-500 text-sm mt-3 leading-6">
                                            แนะนำให้แก้จากบนลงล่าง แล้วกดบันทึกท้ายฟอร์ม
                                        </div>
                                    </div>

                                    <div class="space-y-1">
                                        <a href="javascript:void(0);" class="js-panel-link is-active"
                                            data-panel="page-info">
                                            <i data-lucide="file-text" class="w-4 h-4"></i>
                                            ข้อมูลหน้า
                                        </a>

                                        <a href="javascript:void(0);" class="js-panel-link" data-panel="hero-info">
                                            <i data-lucide="layout-template" class="w-4 h-4"></i>
                                            Hero ด้านบน
                                        </a>

                                        <a href="javascript:void(0);" class="js-panel-link" data-panel="contact-info">
                                            <i data-lucide="headphones" class="w-4 h-4"></i>
                                            กล่องติดต่อ
                                        </a>

                                        <a href="javascript:void(0);" class="js-panel-link" data-panel="note-info">
                                            <i data-lucide="message-square" class="w-4 h-4"></i>
                                            หมายเหตุ / CTA
                                        </a>

                                        <a href="javascript:void(0);" class="js-panel-link" data-panel="sections-info">
                                            <i data-lucide="list-plus" class="w-4 h-4"></i>
                                            Section เนื้อหา
                                        </a>
                                    </div>

                                    @if (!empty($currentKeyExamples))
                                        <div class="mt-6 pt-5 border-t border-slate-200">
                                            <div class="font-semibold text-slate-700 mb-3">
                                                Section key ที่หน้านี้ใช้
                                            </div>

                                            <div class="space-y-2">
                                                @foreach ($currentKeyExamples as $key => $desc)
                                                    <div class="rounded-lg bg-slate-50 border border-slate-200 p-3">
                                                        <div class="quick-key">{{ $key }}</div>
                                                        <div class="text-slate-500 text-xs mt-2 leading-5">
                                                            {{ $desc }}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <div class="mt-6 pt-5 border-t border-slate-200">
                                        <button type="submit" class="btn btn-primary w-full">
                                            บันทึกข้อมูล
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- RIGHT CONTENT --}}
                        <div class="col-span-12 lg:col-span-9 space-y-5">
                            {{-- ข้อมูลหน้า --}}
                            <div id="page-info" class="support-card support-panel is-active">
                                <div class="support-card-header">
                                    <div>
                                        <h3 class="text-lg font-semibold">ข้อมูลหน้า</h3>
                                        <p class="text-slate-500 text-sm mt-1">
                                            ข้อมูลพื้นฐานที่ใช้แสดงในเมนู Breadcrumb และสถานะหน้า
                                        </p>
                                    </div>

                                    <span class="badge-soft-green">Page</span>
                                </div>

                                <div class="support-card-body">
                                    <div class="grid grid-cols-12 gap-4">
                                        <div class="col-span-12 md:col-span-4">
                                            <label class="form-label">Slug</label>
                                            <input type="text" class="form-control" value="{{ $supportPage->slug }}"
                                                disabled>
                                            <div class="form-help-soft">ใช้สำหรับ URL ไม่แนะนำให้แก้</div>
                                        </div>

                                        <div class="col-span-12 md:col-span-4">
                                            <label class="form-label">ชื่อเมนู</label>
                                            <input type="text" name="menu_label" class="form-control"
                                                value="{{ old('menu_label', $supportPage->menu_label) }}">
                                        </div>

                                        <div class="col-span-12 md:col-span-4">
                                            <label class="form-label">ชื่อหน้า</label>
                                            <input type="text" name="page_title" class="form-control"
                                                value="{{ old('page_title', $supportPage->page_title) }}">
                                        </div>

                                        <div class="col-span-12 md:col-span-4">
                                            <label class="form-label">Breadcrumb</label>
                                            <input type="text" name="breadcrumb_title" class="form-control"
                                                value="{{ old('breadcrumb_title', $supportPage->breadcrumb_title) }}">
                                        </div>

                                        <div class="col-span-12 md:col-span-4">
                                            <label class="form-label">สถานะ</label>
                                            <select name="status" class="form-select">
                                                <option value="1"
                                                    {{ (string) old('status', (int) $supportPage->status) === '1' ? 'selected' : '' }}>
                                                    เปิดใช้งาน
                                                </option>
                                                <option value="0"
                                                    {{ (string) old('status', (int) $supportPage->status) === '0' ? 'selected' : '' }}>
                                                    ปิดใช้งาน
                                                </option>
                                            </select>
                                        </div>

                                        <div class="col-span-12 md:col-span-4">
                                            <label class="form-label">เรียงลำดับ</label>
                                            <input type="number" name="sort_order" class="form-control"
                                                value="{{ old('sort_order', $supportPage->sort_order ?? 0) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Hero --}}
                            <div id="hero-info" class="support-card support-panel">
                                <div class="support-card-header">
                                    <div>
                                        <h3 class="text-lg font-semibold">Hero ด้านบน</h3>
                                        <p class="text-slate-500 text-sm mt-1">
                                            ส่วนหัวของหน้าบ้าน เช่น ป้ายกำกับ หัวข้อ คำอธิบาย และปุ่ม
                                        </p>
                                    </div>

                                    <span class="badge-soft-green">Hero</span>
                                </div>

                                <div class="support-card-body">
                                    <div class="grid grid-cols-12 gap-4">
                                        <div class="col-span-12 md:col-span-4">
                                            <label class="form-label">Badge</label>
                                            <input type="text" name="badge_text" class="form-control"
                                                value="{{ old('badge_text', $supportPage->badge_text) }}">
                                        </div>

                                        <div class="col-span-12 md:col-span-8">
                                            <label class="form-label">หัวข้อใหญ่</label>
                                            <input type="text" name="hero_title" class="form-control"
                                                value="{{ old('hero_title', $supportPage->hero_title) }}">
                                        </div>

                                        <div class="col-span-12">
                                            <label class="form-label">รายละเอียด Hero</label>
                                            <textarea name="hero_description" class="form-control" rows="5">{{ old('hero_description', $supportPage->hero_description) }}</textarea>
                                        </div>

                                        <div class="col-span-12 md:col-span-3">
                                            <label class="form-label">ข้อความปุ่มหลัก</label>
                                            <input type="text" name="primary_button_text" class="form-control"
                                                value="{{ old('primary_button_text', $supportPage->primary_button_text) }}">
                                        </div>

                                        <div class="col-span-12 md:col-span-3">
                                            <label class="form-label">ลิงก์ปุ่มหลัก</label>
                                            <input type="text" name="primary_button_url" class="form-control"
                                                value="{{ old('primary_button_url', $supportPage->primary_button_url) }}"
                                                placeholder="/sell-product หรือ #customer-care">
                                        </div>

                                        <div class="col-span-12 md:col-span-3">
                                            <label class="form-label">ข้อความปุ่มรอง</label>
                                            <input type="text" name="secondary_button_text" class="form-control"
                                                value="{{ old('secondary_button_text', $supportPage->secondary_button_text) }}">
                                        </div>

                                        <div class="col-span-12 md:col-span-3">
                                            <label class="form-label">ลิงก์ปุ่มรอง</label>
                                            <input type="text" name="secondary_button_url" class="form-control"
                                                value="{{ old('secondary_button_url', $supportPage->secondary_button_url) }}"
                                                placeholder="#faq">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Contact --}}
                            <div id="contact-info" class="support-card support-panel">
                                <div class="support-card-header">
                                    <div>
                                        <h3 class="text-lg font-semibold">กล่องติดต่อเจ้าหน้าที่</h3>
                                        <p class="text-slate-500 text-sm mt-1">
                                            ข้อมูลติดต่อที่แสดงด้านขวาของหน้าบ้าน
                                        </p>
                                    </div>

                                    <span class="badge-soft-green">Contact</span>
                                </div>

                                <div class="support-card-body">
                                    <div class="grid grid-cols-12 gap-4">
                                        <div class="col-span-12 md:col-span-6">
                                            <label class="form-label">หัวข้อกล่องติดต่อ</label>
                                            <input type="text" name="contact_title" class="form-control"
                                                value="{{ old('contact_title', $supportPage->contact_title) }}">
                                        </div>

                                        <div class="col-span-12 md:col-span-6">
                                            <label class="form-label">คำอธิบายกล่องติดต่อ</label>
                                            <input type="text" name="contact_description" class="form-control"
                                                value="{{ old('contact_description', $supportPage->contact_description) }}">
                                        </div>

                                        <div class="col-span-12 md:col-span-4">
                                            <label class="form-label">Label เบอร์โทร</label>
                                            <input type="text" name="contact_phone_label" class="form-control"
                                                value="{{ old('contact_phone_label', $supportPage->contact_phone_label) }}">
                                        </div>

                                        <div class="col-span-12 md:col-span-8">
                                            <label class="form-label">เบอร์โทร</label>
                                            <input type="text" name="contact_phone" class="form-control"
                                                value="{{ old('contact_phone', $supportPage->contact_phone) }}">
                                        </div>

                                        <div class="col-span-12 md:col-span-4">
                                            <label class="form-label">Label อีเมล</label>
                                            <input type="text" name="contact_email_label" class="form-control"
                                                value="{{ old('contact_email_label', $supportPage->contact_email_label) }}">
                                        </div>

                                        <div class="col-span-12 md:col-span-8">
                                            <label class="form-label">อีเมล</label>
                                            <input type="text" name="contact_email" class="form-control"
                                                value="{{ old('contact_email', $supportPage->contact_email) }}">
                                        </div>

                                        <div class="col-span-12 md:col-span-4">
                                            <label class="form-label">Label เวลาทำการ / การประสานงาน</label>
                                            <input type="text" name="contact_time_label" class="form-control"
                                                value="{{ old('contact_time_label', $supportPage->contact_time_label) }}">
                                        </div>

                                        <div class="col-span-12 md:col-span-8">
                                            <label class="form-label">ข้อความเวลาทำการ / การประสานงาน</label>
                                            <input type="text" name="contact_time" class="form-control"
                                                value="{{ old('contact_time', $supportPage->contact_time) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Note / CTA --}}
                            <div id="note-info" class="support-card support-panel">
                                <div class="support-card-header">
                                    <div>
                                        <h3 class="text-lg font-semibold">หมายเหตุ / ปุ่มด้านขวา</h3>
                                        <p class="text-slate-500 text-sm mt-1">
                                            กล่องหมายเหตุและปุ่ม Call to Action ใน Sidebar
                                        </p>
                                    </div>

                                    <span class="badge-soft-green">CTA</span>
                                </div>

                                <div class="support-card-body">
                                    <div class="grid grid-cols-12 gap-4">
                                        <div class="col-span-12 md:col-span-3">
                                            <label class="form-label">Icon หมายเหตุ</label>
                                            <select name="note_icon" class="form-select">
                                                <option value="">-- เลือก --</option>
                                                @foreach ($iconOptions as $iconKey => $iconLabel)
                                                    <option value="{{ $iconKey }}"
                                                        {{ old('note_icon', $supportPage->note_icon) === $iconKey ? 'selected' : '' }}>
                                                        {{ $iconLabel }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-span-12 md:col-span-9">
                                            <label class="form-label">หัวข้อหมายเหตุ</label>
                                            <input type="text" name="note_title" class="form-control"
                                                value="{{ old('note_title', $supportPage->note_title) }}">
                                        </div>

                                        <div class="col-span-12">
                                            <label class="form-label">รายละเอียดหมายเหตุ</label>
                                            <textarea name="note_description" class="form-control" rows="4">{{ old('note_description', $supportPage->note_description) }}</textarea>
                                        </div>

                                        <div class="col-span-12 md:col-span-6">
                                            <label class="form-label">ข้อความปุ่ม</label>
                                            <input type="text" name="call_button_text" class="form-control"
                                                value="{{ old('call_button_text', $supportPage->call_button_text) }}">
                                        </div>

                                        <div class="col-span-12 md:col-span-6">
                                            <label class="form-label">ลิงก์ปุ่ม</label>
                                            <input type="text" name="call_button_url" class="form-control"
                                                value="{{ old('call_button_url', $supportPage->call_button_url) }}"
                                                placeholder="/sell-product หรือ tel:0989509222">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Sections --}}
                            <div id="sections-info" class="support-card support-panel">
                                <div class="support-card-header">
                                    <div>
                                        <h3 class="text-lg font-semibold">Section เนื้อหา</h3>
                                        <p class="text-slate-500 text-sm mt-1">
                                            แก้ไขกล่องข้อมูล ขั้นตอน รายการสรุป ช่องทางรับเงิน
                                            และรายการย่อยเดิมที่มีอยู่
                                        </p>
                                    </div>
                                </div>

                                <div class="support-card-body">
                                    <div id="sectionRows" class="space-y-5">
                                        @foreach ($sectionList as $sectionIndex => $section)
                                            <div class="section-row">
                                                <input type="hidden" name="sections[{{ $sectionIndex }}][id]"
                                                    value="{{ $section->id }}">

                                                <div class="section-row-header">
                                                    <div class="flex flex-col md:flex-row md:items-center gap-3">
                                                        <div class="mr-auto">
                                                            <div class="flex items-center gap-2 flex-wrap">
                                                                <h4 class="font-semibold text-base">
                                                                    {{ $section->title ?: 'Section #' . ($sectionIndex + 1) }}
                                                                </h4>

                                                                <span class="quick-key">
                                                                    {{ $section->section_key ?: 'no-key' }}
                                                                </span>
                                                            </div>

                                                            <div class="text-slate-500 text-sm mt-1">
                                                                รายการย่อย {{ $section->items->count() }} รายการ
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="section-row-body">
                                                    <div class="grid grid-cols-12 gap-4">
                                                        <div class="col-span-12 md:col-span-3">
                                                            <label class="form-label">Section Key</label>
                                                            <input type="text"
                                                                name="sections[{{ $sectionIndex }}][section_key]"
                                                                class="form-control"
                                                                value="{{ old('sections.' . $sectionIndex . '.section_key', $section->section_key) }}"
                                                                placeholder="เช่น steps, tips, summary">
                                                        </div>

                                                        <div class="col-span-12 md:col-span-3">
                                                            <label class="form-label">Label</label>
                                                            <input type="text"
                                                                name="sections[{{ $sectionIndex }}][label]"
                                                                class="form-control"
                                                                value="{{ old('sections.' . $sectionIndex . '.label', $section->label) }}">
                                                        </div>

                                                        <div class="col-span-12 md:col-span-6">
                                                            <label class="form-label">หัวข้อ Section</label>
                                                            <input type="text"
                                                                name="sections[{{ $sectionIndex }}][title]"
                                                                class="form-control"
                                                                value="{{ old('sections.' . $sectionIndex . '.title', $section->title) }}">
                                                        </div>

                                                        <div class="col-span-12">
                                                            <label class="form-label">รายละเอียด Section</label>
                                                            <textarea name="sections[{{ $sectionIndex }}][description]" class="form-control" rows="3">{{ old('sections.' . $sectionIndex . '.description', $section->description) }}</textarea>
                                                        </div>

                                                        <div class="col-span-12 md:col-span-4">
                                                            <label class="form-label">Layout Type / Icon หลัก</label>
                                                            <select name="sections[{{ $sectionIndex }}][layout_type]"
                                                                class="form-select">
                                                                <option value="">-- เลือก --</option>
                                                                @foreach ($iconOptions as $iconKey => $iconLabel)
                                                                    <option value="{{ $iconKey }}"
                                                                        {{ old('sections.' . $sectionIndex . '.layout_type', $section->layout_type) === $iconKey ? 'selected' : '' }}>
                                                                        {{ $iconLabel }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-span-12 md:col-span-4">
                                                            <label class="form-label">สถานะ</label>
                                                            <select name="sections[{{ $sectionIndex }}][status]"
                                                                class="form-select">
                                                                <option value="1"
                                                                    {{ (string) old('sections.' . $sectionIndex . '.status', (int) $section->status) === '1' ? 'selected' : '' }}>
                                                                    เปิด
                                                                </option>
                                                                <option value="0"
                                                                    {{ (string) old('sections.' . $sectionIndex . '.status', (int) $section->status) === '0' ? 'selected' : '' }}>
                                                                    ปิด
                                                                </option>
                                                            </select>
                                                        </div>

                                                        <div class="col-span-12 md:col-span-4">
                                                            <label class="form-label">เรียงลำดับ</label>
                                                            <input type="number"
                                                                name="sections[{{ $sectionIndex }}][sort_order]"
                                                                class="form-control"
                                                                value="{{ old('sections.' . $sectionIndex . '.sort_order', $section->sort_order ?? 0) }}">
                                                        </div>
                                                    </div>

                                                    <div class="mt-6">
                                                        <div
                                                            class="flex flex-col md:flex-row md:items-center gap-3 mb-3">
                                                            <div class="mr-auto">
                                                                <h5 class="font-semibold">
                                                                    รายการย่อยของ Section
                                                                </h5>
                                                                <div class="text-slate-500 text-sm mt-1">
                                                                    เช่น ขั้นตอน, ข้อควรระวัง, รายการสรุป
                                                                    หรือช่องทางรับเงิน
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div
                                                            class="overflow-x-auto rounded-md border border-slate-200 bg-white">
                                                            <table class="table table-bordered item-table mb-0">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="min-width: 90px;">เลขข้อ</th>
                                                                        <th style="min-width: 135px;">Icon</th>
                                                                        <th style="min-width: 240px;">หัวข้อ</th>
                                                                        <th style="min-width: 360px;">รายละเอียด</th>
                                                                        <th style="min-width: 160px;">ข้อความลิงก์</th>
                                                                        <th style="min-width: 180px;">URL</th>
                                                                        <th style="width: 110px;">สถานะ</th>
                                                                        <th style="width: 100px;">ลำดับ</th>
                                                                    </tr>
                                                                </thead>

                                                                <tbody class="item-rows">
                                                                    @foreach ($section->items as $itemIndex => $item)
                                                                        <tr>
                                                                            <td>
                                                                                <input type="hidden"
                                                                                    name="sections[{{ $sectionIndex }}][items][{{ $itemIndex }}][id]"
                                                                                    value="{{ $item->id }}">

                                                                                <input type="text"
                                                                                    name="sections[{{ $sectionIndex }}][items][{{ $itemIndex }}][item_no]"
                                                                                    class="form-control"
                                                                                    value="{{ old('sections.' . $sectionIndex . '.items.' . $itemIndex . '.item_no', $item->item_no) }}"
                                                                                    placeholder="01">
                                                                            </td>

                                                                            <td>
                                                                                <select
                                                                                    name="sections[{{ $sectionIndex }}][items][{{ $itemIndex }}][icon]"
                                                                                    class="form-select">
                                                                                    <option value="">-- เลือก --
                                                                                    </option>
                                                                                    @foreach ($iconOptions as $iconKey => $iconLabel)
                                                                                        <option
                                                                                            value="{{ $iconKey }}"
                                                                                            {{ old('sections.' . $sectionIndex . '.items.' . $itemIndex . '.icon', $item->icon) === $iconKey ? 'selected' : '' }}>
                                                                                            {{ $iconLabel }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </td>

                                                                            <td>
                                                                                <input type="text"
                                                                                    name="sections[{{ $sectionIndex }}][items][{{ $itemIndex }}][title]"
                                                                                    class="form-control"
                                                                                    value="{{ old('sections.' . $sectionIndex . '.items.' . $itemIndex . '.title', $item->title) }}">
                                                                            </td>

                                                                            <td>
                                                                                <textarea name="sections[{{ $sectionIndex }}][items][{{ $itemIndex }}][description]" class="form-control"
                                                                                    rows="3">{{ old('sections.' . $sectionIndex . '.items.' . $itemIndex . '.description', $item->description) }}</textarea>
                                                                            </td>

                                                                            <td>
                                                                                <input type="text"
                                                                                    name="sections[{{ $sectionIndex }}][items][{{ $itemIndex }}][link_text]"
                                                                                    class="form-control"
                                                                                    value="{{ old('sections.' . $sectionIndex . '.items.' . $itemIndex . '.link_text', $item->link_text) }}">
                                                                            </td>

                                                                            <td>
                                                                                <input type="text"
                                                                                    name="sections[{{ $sectionIndex }}][items][{{ $itemIndex }}][link_url]"
                                                                                    class="form-control"
                                                                                    value="{{ old('sections.' . $sectionIndex . '.items.' . $itemIndex . '.link_url', $item->link_url) }}">
                                                                            </td>

                                                                            <td>
                                                                                <select
                                                                                    name="sections[{{ $sectionIndex }}][items][{{ $itemIndex }}][status]"
                                                                                    class="form-select">
                                                                                    <option value="1"
                                                                                        {{ (string) old('sections.' . $sectionIndex . '.items.' . $itemIndex . '.status', (int) $item->status) === '1' ? 'selected' : '' }}>
                                                                                        เปิด
                                                                                    </option>
                                                                                    <option value="0"
                                                                                        {{ (string) old('sections.' . $sectionIndex . '.items.' . $itemIndex . '.status', (int) $item->status) === '0' ? 'selected' : '' }}>
                                                                                        ปิด
                                                                                    </option>
                                                                                </select>
                                                                            </td>

                                                                            <td>
                                                                                <input type="number"
                                                                                    name="sections[{{ $sectionIndex }}][items][{{ $itemIndex }}][sort_order]"
                                                                                    class="form-control"
                                                                                    value="{{ old('sections.' . $sectionIndex . '.items.' . $itemIndex . '.sort_order', $item->sort_order ?? 0) }}">
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="support-card">
                                <div class="support-card-body">
                                    <div class="flex flex-col md:flex-row gap-3 md:items-center">
                                        <div class="mr-auto text-slate-500">
                                            ตรวจสอบข้อมูลให้ครบก่อนกดบันทึก
                                        </div>

                                        <a href="{{ url('admin/') }}" class="btn btn-outline-secondary">
                                            ยกเลิก
                                        </a>

                                        <button type="submit" class="btn btn-primary">
                                            บันทึกข้อมูล
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('admin.inc_footer')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const panelLinks = document.querySelectorAll('.js-panel-link');
            const panels = document.querySelectorAll('.support-panel');

            function showPanel(panelId) {
                panels.forEach(function(panel) {
                    panel.classList.remove('is-active');
                });

                panelLinks.forEach(function(link) {
                    link.classList.remove('is-active');
                });

                const activePanel = document.getElementById(panelId);
                const activeLink = document.querySelector('.js-panel-link[data-panel="' + panelId + '"]');

                if (activePanel) {
                    activePanel.classList.add('is-active');
                }

                if (activeLink) {
                    activeLink.classList.add('is-active');
                }

                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }

            panelLinks.forEach(function(link) {
                link.addEventListener('click', function() {
                    const panelId = this.dataset.panel;

                    if (!panelId) {
                        return;
                    }

                    showPanel(panelId);
                });
            });

            showPanel('page-info');
        });
    </script>
</body>

</html>
