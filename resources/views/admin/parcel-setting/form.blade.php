<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <title>ตั้งค่าศูนย์ใหญ่รับพัสดุ</title>
    @include('admin.inc_header')
</head>

<body class="main">
    @include('admin.inc_mobilemenu')

    <div class="top-bar-boxed h-[70px] z-[51] relative border-b border-white/[0.08] -mt-7 md:-mt-5 -mx-3 sm:-mx-8 px-3 sm:px-8 md:pt-0 mb-12">
        <div class="h-full flex items-center">
            <a href="{{ url('admin/') }}" class="-intro-x hidden md:flex">
                <img alt="Logo" class="w-6" src="{{ asset('dist/images/logo.svg') }}">
            </a>

            <nav aria-label="breadcrumb" class="-intro-x h-full mr-auto">
                <ol class="breadcrumb breadcrumb-light">
                    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">หน้าหลัก</a></li>
                    <li class="breadcrumb-item active" aria-current="page">ตั้งค่าศูนย์ใหญ่รับพัสดุ</li>
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
                    <h2 class="text-lg font-medium mr-auto">ตั้งค่าศูนย์ใหญ่รับพัสดุ</h2>
                </div>

                @if (session('success'))
                <div class="alert alert-success mb-4">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                <div class="alert alert-danger mb-4">{{ session('error') }}</div>
                @endif

                @if ($errors->any())
                <div class="alert alert-danger mb-4">{{ $errors->first() }}</div>
                @endif

                <div class="intro-y box p-5 mb-6">
                    <h3 class="text-base font-medium mb-5">ข้อมูลที่อยู่จัดส่งศูนย์ใหญ่</h3>

                    <form method="POST" action="{{ route('admin.parcel-setting.update') }}">
                        @csrf

                        <div class="grid grid-cols-12 gap-6">
                            <div class="col-span-12 md:col-span-6 hidden">
                                <label class="form-label">ชื่อผู้รับ / ชื่อศูนย์ <span class="text-danger">*</span></label>
                                <input type="text"
                                    name="receiver_name"
                                    value="{{ old('receiver_name', $parcelSetting->receiver_name) }}"
                                    class="form-control"
                                    placeholder="เช่น Cashkub สำนักงานใหญ่">
                            </div>

                            <div class="col-span-12 md:col-span-6 hidden">
                                <label class="form-label">เบอร์โทร</label>
                                <input type="text"
                                    name="phone"
                                    value="{{ old('phone', $parcelSetting->phone) }}"
                                    class="form-control"
                                    placeholder="เช่น 098-950-9222">
                            </div>

                            <div class="col-span-12">
                                <label class="form-label">ที่อยู่จัดส่ง <span class="text-danger">*</span></label>
                                <textarea name="address"
                                    rows="4"
                                    class="form-control"
                                    placeholder="กรอกที่อยู่จัดส่ง">{{ old('address', $parcelSetting->address) }}</textarea>
                            </div>

                            <div class="col-span-12 md:col-span-6 hidden">
                                <label class="form-label">แขวง / ตำบล</label>
                                <input type="text"
                                    name="subdistrict"
                                    value="{{ old('subdistrict', $parcelSetting->subdistrict) }}"
                                    class="form-control"
                                    placeholder="เช่น หลักสอง">
                            </div>

                            <div class="col-span-12 md:col-span-6 hidden">
                                <label class="form-label">เขต / อำเภอ</label>
                                <input type="text"
                                    name="district"
                                    value="{{ old('district', $parcelSetting->district) }}"
                                    class="form-control"
                                    placeholder="เช่น บางแค">
                            </div>

                            <div class="col-span-12 md:col-span-6 hidden">
                                <label class="form-label">จังหวัด</label>
                                <input type="text"
                                    name="province"
                                    value="{{ old('province', $parcelSetting->province) }}"
                                    class="form-control"
                                    placeholder="เช่น กรุงเทพมหานคร">
                            </div>

                            <div class="col-span-12 md:col-span-6 hidden">
                                <label class="form-label">รหัสไปรษณีย์</label>
                                <input type="text"
                                    name="postcode"
                                    value="{{ old('postcode', $parcelSetting->postcode) }}"
                                    class="form-control"
                                    placeholder="เช่น 10160">
                            </div>

                            <div class="col-span-12">
                                <label class="form-label">หมายเหตุเพิ่มเติม</label>
                                <textarea name="remark"
                                    rows="10"
                                    class="form-control"
                                    placeholder="เช่น บริษัทดูแลค่าใช้จ่ายในการส่งสินค้า">{{ old('remark', $parcelSetting->remark) }}</textarea>
                            </div>

                            <div class="col-span-12 md:col-span-6 hidden">
                                <label class="form-label">สถานะ</label>
                                <select name="is_active" class="form-control">
                                    <option value="1" {{ (string) old('is_active', $parcelSetting->is_active ?? 1) === '1' ? 'selected' : '' }}>
                                        เปิดใช้งาน
                                    </option>
                                    <option value="0" {{ (string) old('is_active', $parcelSetting->is_active ?? 1) === '0' ? 'selected' : '' }}>
                                        ปิดใช้งาน
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-5">
                            <button type="submit" class="btn btn-primary w-32">
                                บันทึก
                            </button>
                        </div>
                    </form>
                </div>

                <div class="intro-y box p-5">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-base font-medium mr-6">รายการเอกสารที่ต้องส่งมาด้วย</h3>

                        <button type="button" class="btn btn-primary" onclick="openCreateDocumentModal()">
                            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                            เพิ่มเอกสาร
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-report mt-2" id="documents-table">
                            <thead>
                                <tr>
                                    <th class="text-center">ลำดับ</th>
                                    <th>ชื่อเอกสาร</th>
                                    <th>รายละเอียด</th>
                                    <th class="text-center">จำเป็นต้องส่ง</th>
                                    <th class="text-center">ลำดับแสดงผล</th>
                                    <th class="text-center">สถานะ</th>
                                    <th class="text-center">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($documents as $key => $document)
                                <tr>
                                    <td class="text-center">{{ $key + 1 }}</td>

                                    <td>
                                        <div class="font-medium whitespace-nowrap">
                                            {{ $document->document_name }}
                                        </div>
                                    </td>

                                    <td>
                                        {{ $document->description ?: '-' }}
                                    </td>

                                    <td class="text-center">
                                        @if ($document->is_required)
                                        <span class="px-2 py-1 rounded-full text-xs bg-danger/10 text-danger">
                                            จำเป็น
                                        </span>
                                        @else
                                        <span class="px-2 py-1 rounded-full text-xs bg-slate-100 text-slate-500">
                                            ไม่จำเป็น
                                        </span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        {{ $document->sort_order }}
                                    </td>

                                    <td class="text-center">
                                        @if ($document->is_active)
                                        <span class="px-2 py-1 rounded-full text-xs bg-success/10 text-success">
                                            เปิด
                                        </span>
                                        @else
                                        <span class="px-2 py-1 rounded-full text-xs bg-danger/10 text-danger">
                                            ปิด
                                        </span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <div class="flex justify-center items-center gap-3">
                                            <a href="javascript:void(0);"
                                                class="flex items-center"
                                                onclick="openEditDocumentModal(
                                                        {{ $document->id }},
                                                        @js($document->document_name),
                                                        @js($document->description),
                                                        {{ $document->is_required ? 1 : 0 }},
                                                        {{ $document->sort_order ?? 0 }},
                                                        {{ $document->is_active ? 1 : 0 }}
                                                    )">
                                                <i data-lucide="check-square" class="w-4 h-4 mr-1"></i>
                                                แก้ไข
                                            </a>

                                            <a href="javascript:void(0);"
                                                class="flex items-center text-danger"
                                                onclick="openDeleteDocumentModal({{ $document->id }})">
                                                <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i>
                                                ลบ
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-slate-500 py-5">
                                        ยังไม่มีรายการเอกสาร
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div id="document-modal" class="modal" tabindex="-1" aria-hidden="true" data-tw-backdrop="static">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form method="POST" id="document-form" action="{{ route('admin.parcel-setting.documents.store') }}">
                                    @csrf

                                    <div class="modal-header">
                                        <h2 class="font-medium text-base mr-auto" id="document-modal-title">
                                            เพิ่มเอกสาร
                                        </h2>
                                    </div>

                                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-5">
                                        <div class="col-span-12 md:col-span-6">
                                            <label class="form-label">ชื่อเอกสาร <span class="text-danger">*</span></label>
                                            <input type="text"
                                                name="document_name"
                                                id="document_name"
                                                class="form-control"
                                                placeholder="เช่น สำเนาบัตรประชาชน">
                                        </div>

                                        <div class="col-span-12 md:col-span-6">
                                            <label class="form-label">รายละเอียดเพิ่มเติม</label>
                                            <input type="text"
                                                name="description"
                                                id="description"
                                                class="form-control"
                                                placeholder="เช่น เขียนกำกับและเซ็นชื่อ">
                                        </div>

                                        <div class="col-span-12 md:col-span-4">
                                            <label class="form-label">จำเป็นต้องส่งหรือไม่</label>
                                            <select name="is_required" id="is_required" class="form-control">
                                                <option value="1">จำเป็น</option>
                                                <option value="0">ไม่จำเป็น</option>
                                            </select>
                                        </div>

                                        <div class="col-span-12 md:col-span-4">
                                            <label class="form-label">ลำดับการแสดงผล</label>
                                            <input type="number"
                                                name="sort_order"
                                                id="sort_order"
                                                value="0"
                                                class="form-control">
                                        </div>

                                        <div class="col-span-12 md:col-span-4">
                                            <label class="form-label">สถานะ</label>
                                            <select name="is_active" id="is_active" class="form-control">
                                                <option value="1">เปิดใช้งาน</option>
                                                <option value="0">ปิดใช้งาน</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">
                                            ยกเลิก
                                        </button>
                                        <button type="submit" class="btn btn-primary w-24">
                                            บันทึก
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div id="delete-document-modal" class="modal" tabindex="-1" aria-hidden="true" data-tw-backdrop="static">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body p-0">
                                    <form method="POST" action="{{ route('admin.parcel-setting.documents.delete') }}">
                                        @csrf

                                        <div class="p-5 text-center">
                                            <input type="hidden" id="delete-document-id" name="id">
                                            <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                                            <div class="text-3xl mt-5">คุณต้องการลบเอกสาร?</div>
                                            <div class="text-slate-500 mt-2">
                                                หากลบแล้วจะไม่สามารถกู้คืนข้อมูลเดิมได้
                                            </div>
                                        </div>

                                        <div class="px-5 pb-8 text-center">
                                            <button type="button" data-tw-dismiss="modal"
                                                class="btn btn-outline-secondary w-24 mr-1">ยกเลิก</button>
                                            <button type="submit" class="btn btn-danger w-24">ลบข้อมูล</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @include('admin.inc_footer')

    <script>
        function openCreateDocumentModal() {
            const form = document.getElementById('document-form');

            form.action = "{{ route('admin.parcel-setting.documents.store') }}";

            document.getElementById('document-modal-title').innerText = 'เพิ่มเอกสาร';
            document.getElementById('document_name').value = '';
            document.getElementById('description').value = '';
            document.getElementById('is_required').value = '1';
            document.getElementById('sort_order').value = '0';
            document.getElementById('is_active').value = '1';

            const modalEl = document.querySelector('#document-modal');
            const modal = tailwind.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }

        function openEditDocumentModal(id, documentName, description, isRequired, sortOrder, isActive) {
            const form = document.getElementById('document-form');

            form.action = "{{ url('admin/parcel-setting/documents') }}/" + id + "/update";

            document.getElementById('document-modal-title').innerText = 'แก้ไขเอกสาร';
            document.getElementById('document_name').value = documentName || '';
            document.getElementById('description').value = description || '';
            document.getElementById('is_required').value = String(isRequired);
            document.getElementById('sort_order').value = sortOrder || 0;
            document.getElementById('is_active').value = String(isActive);

            const modalEl = document.querySelector('#document-modal');
            const modal = tailwind.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }

        function openDeleteDocumentModal(id) {
            document.getElementById('delete-document-id').value = id;

            const modalEl = document.querySelector('#delete-document-modal');
            const modal = tailwind.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }

        jQuery(document).ready(function($) {
            $('#documents-table').DataTable({
                responsive: true,
                order: [
                    [0, "asc"]
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Thai.json"
                }
            });
        });
    </script>
</body>

</html>