<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <title>{{ $mode === 'edit' ? 'แก้ไขผู้ใช้งานหลังบ้าน' : 'เพิ่มผู้ใช้งานหลังบ้าน' }}</title>
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
                    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">หน้าหลัก</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('admin/user') }}">บัญชีผู้ใช้งาน</a></li>
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
                        {{ $mode === 'edit' ? 'แก้ไขผู้ใช้งานหลังบ้าน' : 'เพิ่มผู้ใช้งานหลังบ้าน' }}
                    </h2>
                </div>

                <div class="intro-y box p-5">
                    @if (session('success'))
                        <div class="alert alert-success mb-4">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger mb-4">{{ session('error') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">{{ $errors->first() }}</div>
                    @endif

                    <form method="POST"
                        action="{{ $mode === 'edit' ? url('admin/user/' . $user->id . '/update') : url('admin/user-store') }}">
                        @csrf

                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label">ชื่อผู้ใช้งาน <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $user->name ?? '') }}" placeholder="กรอกชื่อผู้ใช้งาน">
                            </div>

                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label">อีเมล <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $user->email ?? '') }}" placeholder="กรอกอีเมล">
                            </div>

                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label">
                                    รหัสผ่าน
                                    @if ($mode === 'create')
                                        <span class="text-danger">*</span>
                                    @endif
                                </label>
                                <input type="password" name="password" class="form-control"
                                    placeholder="{{ $mode === 'edit' ? 'ถ้าไม่เปลี่ยนให้เว้นว่าง' : 'กรอกรหัสผ่าน' }}">
                            </div>

                            <div class="col-span-12 md:col-span-3">
                                <label class="form-label">สถานะ</label>
                                <select name="status" class="form-select">
                                    <option value="1"
                                        {{ old('status', isset($user->status) ? (int) $user->status : 1) == 1 ? 'selected' : '' }}>
                                        เปิดใช้งาน
                                    </option>
                                    <option value="0"
                                        {{ old('status', isset($user->status) ? (int) $user->status : 1) == 0 ? 'selected' : '' }}>
                                        ปิดใช้งาน
                                    </option>
                                </select>
                            </div>

                            <div class="col-span-12 md:col-span-3">
                                <label class="form-label">สิทธิ์ระดับสูง</label>
                                <select name="is_super_admin" class="form-select">
                                    <option value="0"
                                        {{ old('is_super_admin', isset($user->is_super_admin) ? (int) $user->is_super_admin : 0) == 0 ? 'selected' : '' }}>
                                        แอดมินทั่วไป
                                    </option>
                                    <option value="1"
                                        {{ old('is_super_admin', isset($user->is_super_admin) ? (int) $user->is_super_admin : 0) == 1 ? 'selected' : '' }}>
                                        Super Admin
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-8">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-lg font-medium">สิทธิ์การใช้งานเมนู</h3>
                                <button type="button" class="btn btn-outline-secondary"
                                    onclick="toggleAllPermissions(true)">
                                    เลือกทั้งหมด
                                </button>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="table table-bordered table-hover mt-2">
                                    <thead class="bg-slate-50 text-slate-600">
                                        <tr>
                                            <th class="whitespace-nowrap w-64">ชื่อเมนู / ระบบ</th>
                                            <th class="text-center">ดู (View)</th>
                                            <th class="text-center">เพิ่ม (Create)</th>
                                            <th class="text-center">แก้ไข (Edit)</th>
                                            <th class="text-center">ลบ (Delete)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $parentMenus = $menus->where('parent_id', null)->sortBy('sort_order');
                                        @endphp

                                        @foreach ($parentMenus as $parent)
                                            {{-- แถวเมนูหลัก --}}
                                            <tr class="bg-slate-50/50">
                                                <td class="font-bold">
                                                    <div class="flex items-center">
                                                        <i data-lucide="chevron-right"
                                                            class="w-4 h-4 mr-2 text-slate-400"></i>
                                                        {{ $parent->menu_name }}
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <input type="checkbox" class="form-check-input permission-checkbox"
                                                        name="permissions[{{ $parent->id }}][can_view]"
                                                        value="1"
                                                        {{ old("permissions.$parent->id.can_view", $permissionMap[$parent->id]['can_view'] ?? 0) ? 'checked' : '' }}>
                                                </td>
                                                <td class="text-center">
                                                    <input type="checkbox" class="form-check-input permission-checkbox"
                                                        name="permissions[{{ $parent->id }}][can_create]"
                                                        value="1"
                                                        {{ old("permissions.$parent->id.can_create", $permissionMap[$parent->id]['can_create'] ?? 0) ? 'checked' : '' }}>
                                                </td>
                                                <td class="text-center">
                                                    <input type="checkbox" class="form-check-input permission-checkbox"
                                                        name="permissions[{{ $parent->id }}][can_update]"
                                                        value="1"
                                                        {{ old("permissions.$parent->id.can_update", $permissionMap[$parent->id]['can_update'] ?? 0) ? 'checked' : '' }}>
                                                </td>
                                                <td class="text-center">
                                                    <input type="checkbox"
                                                        class="form-check-input permission-checkbox"
                                                        name="permissions[{{ $parent->id }}][can_delete]"
                                                        value="1"
                                                        {{ old("permissions.$parent->id.can_delete", $permissionMap[$parent->id]['can_delete'] ?? 0) ? 'checked' : '' }}>
                                                </td>
                                            </tr>

                                            {{-- แถวเมนูย่อย --}}
                                            @foreach ($menus->where('parent_id', $parent->id)->sortBy('sort_order') as $child)
                                                <tr>
                                                    <td class="pl-10">
                                                        <div class="flex items-center text-slate-600">
                                                            <span
                                                                class="w-2 h-2 rounded-full bg-slate-300 mr-3"></span>
                                                            {{ $child->menu_name }}
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="checkbox"
                                                            class="form-check-input permission-checkbox"
                                                            name="permissions[{{ $child->id }}][can_view]"
                                                            value="1"
                                                            {{ old("permissions.$child->id.can_view", $permissionMap[$child->id]['can_view'] ?? 0) ? 'checked' : '' }}>
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="checkbox"
                                                            class="form-check-input permission-checkbox"
                                                            name="permissions[{{ $child->id }}][can_create]"
                                                            value="1"
                                                            {{ old("permissions.$child->id.can_create", $permissionMap[$child->id]['can_create'] ?? 0) ? 'checked' : '' }}>
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="checkbox"
                                                            class="form-check-input permission-checkbox"
                                                            name="permissions[{{ $child->id }}][can_update]"
                                                            value="1"
                                                            {{ old("permissions.$child->id.can_update", $permissionMap[$child->id]['can_update'] ?? 0) ? 'checked' : '' }}>
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="checkbox"
                                                            class="form-check-input permission-checkbox"
                                                            name="permissions[{{ $child->id }}][can_delete]"
                                                            value="1"
                                                            {{ old("permissions.$child->id.can_delete", $permissionMap[$child->id]['can_delete'] ?? 0) ? 'checked' : '' }}>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="mt-5 flex items-center">
                            <a href="{{ url('admin/user') }}" class="btn btn-outline-secondary w-24 mr-2">
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

    <script>
        function toggleAllPermissions(checked) {
            document.querySelectorAll('.permission-checkbox').forEach(el => {
                el.checked = checked;
            });
        }
    </script>
</body>

</html>
