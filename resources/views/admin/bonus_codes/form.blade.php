<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <title>{{ $mode === 'edit' ? 'แก้ไขโค้ดบวกราคา' : 'เพิ่มโค้ดบวกราคา' }}</title>
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
                        <a href="{{ url('admin/bonus-codes') }}">โค้ดบวกราคา</a>
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
                        {{ $mode === 'edit' ? 'แก้ไขโค้ดบวกราคา' : 'เพิ่มโค้ดบวกราคา' }}
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

                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label">
                                    รหัสโค้ด <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    name="code"
                                    class="form-control uppercase"
                                    value="{{ old('code', $code->code ?? '') }}"
                                    placeholder="เช่น NEWCUSTOMER"
                                    required>
                                <div class="text-xs text-slate-500 mt-1">
                                    ระบบจะบันทึกเป็นตัวพิมพ์ใหญ่ให้อัตโนมัติ
                                </div>
                            </div>

                            <div class="col-span-12 md:col-span-8">
                                <label class="form-label">
                                    ชื่อโค้ด / ชื่อแคมเปญ <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    name="name"
                                    class="form-control"
                                    value="{{ old('name', $code->name ?? '') }}"
                                    placeholder="เช่น ลูกค้าใหม่ รับเพิ่ม 500"
                                    required>
                            </div>

                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label">
                                    ประเภทการบวกเพิ่ม <span class="text-danger">*</span>
                                </label>
                                <select name="bonus_type" id="bonus_type" class="form-select" required
                                    onchange="toggleBonusType()">
                                    <option value="fixed"
                                        {{ old('bonus_type', $code->bonus_type ?? 'fixed') === 'fixed' ? 'selected' : '' }}>
                                        บวกเป็นจำนวนเงิน
                                    </option>
                                    <option value="percent"
                                        {{ old('bonus_type', $code->bonus_type ?? 'fixed') === 'percent' ? 'selected' : '' }}>
                                        บวกเป็นเปอร์เซ็นต์
                                    </option>
                                </select>
                            </div>

                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label">
                                    มูลค่าที่บวกเพิ่ม <span class="text-danger">*</span>
                                </label>
                                <input type="number"
                                    step="0.01"
                                    min="0"
                                    name="bonus_value"
                                    id="bonus_value"
                                    class="form-control"
                                    value="{{ old('bonus_value', $code->bonus_value ?? 0) }}"
                                    required>
                                <div class="text-xs text-slate-500 mt-1" id="bonus_value_hint">
                                    ถ้าเลือกจำนวนเงิน จะเป็นหน่วยบาท
                                </div>
                            </div>

                            <div class="col-span-12 md:col-span-4" id="max_bonus_amount_wrap">
                                <label class="form-label">
                                    บวกสูงสุด
                                </label>
                                <input type="number"
                                    step="0.01"
                                    min="0"
                                    name="max_bonus_amount"
                                    class="form-control"
                                    value="{{ old('max_bonus_amount', $code->max_bonus_amount ?? '') }}"
                                    placeholder="ใช้กับแบบเปอร์เซ็นต์ เช่น สูงสุด 1000">
                                <div class="text-xs text-slate-500 mt-1">
                                    เว้นว่างได้ ถ้าไม่ต้องจำกัดยอดสูงสุด
                                </div>
                            </div>

                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label">
                                    ราคาประเมินขั้นต่ำ
                                </label>
                                <input type="number"
                                    step="0.01"
                                    min="0"
                                    name="min_estimate_price"
                                    class="form-control"
                                    value="{{ old('min_estimate_price', $code->min_estimate_price ?? 0) }}"
                                    placeholder="เช่น 5000">
                                <div class="text-xs text-slate-500 mt-1">
                                    ถ้ากรอก 0 คือใช้ได้ทุกยอดประเมิน
                                </div>
                            </div>

                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label">
                                    จำนวนครั้งที่ใช้ได้ทั้งหมด
                                </label>
                                <input type="number"
                                    min="1"
                                    name="usage_limit"
                                    class="form-control"
                                    value="{{ old('usage_limit', $code->usage_limit ?? '') }}"
                                    placeholder="เว้นว่าง = ไม่จำกัด">
                                <div class="text-xs text-slate-500 mt-1">
                                    จำกัดจำนวนการใช้รวมทุก user
                                </div>
                            </div>

                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label">
                                    จำนวนครั้งต่อผู้ใช้
                                </label>
                                <input type="number"
                                    min="1"
                                    name="per_user_limit"
                                    class="form-control"
                                    value="{{ old('per_user_limit', $code->per_user_limit ?? '') }}"
                                    placeholder="เช่น 1">
                                <div class="text-xs text-slate-500 mt-1">
                                    เช่น ลูกค้าใหม่ใช้ได้คนละ 1 ครั้ง ให้กรอก 1
                                </div>
                            </div>

                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label">
                                    สถานะ <span class="text-danger">*</span>
                                </label>
                                <select name="status" class="form-select" required>
                                    <option value="1"
                                        {{ old('status', isset($code->status) ? (int) $code->status : 1) == 1 ? 'selected' : '' }}>
                                        เปิดใช้งาน
                                    </option>
                                    <option value="0"
                                        {{ old('status', isset($code->status) ? (int) $code->status : 1) == 0 ? 'selected' : '' }}>
                                        ปิดใช้งาน
                                    </option>
                                </select>
                            </div>

                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label">
                                    วันเริ่มใช้งาน
                                </label>
                                <input type="datetime-local"
                                    name="starts_at"
                                    class="form-control"
                                    value="{{ old('starts_at', !empty($code->starts_at) ? $code->starts_at->format('Y-m-d\TH:i') : '') }}">
                                <div class="text-xs text-slate-500 mt-1">
                                    เว้นว่างได้ ถ้าให้เริ่มใช้ได้ทันที
                                </div>
                            </div>

                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label">
                                    วันหมดอายุ
                                </label>
                                <input type="datetime-local"
                                    name="ends_at"
                                    class="form-control"
                                    value="{{ old('ends_at', !empty($code->ends_at) ? $code->ends_at->format('Y-m-d\TH:i') : '') }}">
                                <div class="text-xs text-slate-500 mt-1">
                                    เว้นว่างได้ ถ้าไม่มีวันหมดอายุ
                                </div>
                            </div>

                            <div class="col-span-12">
                                <label class="form-label">
                                    รายละเอียดเพิ่มเติม
                                </label>
                                <textarea name="description"
                                    class="form-control"
                                    rows="4"
                                    placeholder="เช่น เงื่อนไขการใช้โค้ด หรือหมายเหตุสำหรับแอดมิน">{{ old('description', $code->description ?? '') }}</textarea>
                            </div>

                            @if ($mode === 'edit')
                                <div class="col-span-12">
                                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                                        <div class="text-sm text-slate-600">
                                            ใช้ไปแล้วรวมทั้งหมด:
                                            <b>{{ number_format((int) ($code->used_count ?? 0)) }}</b>
                                            ครั้ง
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>

                        <div class="flex gap-2 mt-6">
                            <a href="{{ url('admin/bonus-codes') }}"
                                class="btn btn-outline-secondary">
                                ยกเลิก
                            </a>

                            <button type="submit" class="btn btn-primary">
                                {{ $mode === 'edit' ? 'บันทึกการแก้ไข' : 'บันทึกข้อมูล' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('admin/inc_footer')

    <script>
        function toggleBonusType() {
            const bonusType = document.getElementById('bonus_type');
            const maxBonusWrap = document.getElementById('max_bonus_amount_wrap');
            const bonusHint = document.getElementById('bonus_value_hint');

            if (!bonusType || !maxBonusWrap || !bonusHint) {
                return;
            }

            if (bonusType.value === 'percent') {
                maxBonusWrap.style.display = '';
                bonusHint.innerText = 'ถ้าเลือกเปอร์เซ็นต์ เช่น 10 = บวกเพิ่ม 10%';
            } else {
                maxBonusWrap.style.display = 'none';
                bonusHint.innerText = 'ถ้าเลือกจำนวนเงิน จะเป็นหน่วยบาท';
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            toggleBonusType();
        });
    </script>
</body>

</html>