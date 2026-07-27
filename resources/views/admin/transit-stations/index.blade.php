<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <title>รายการสถานีรถไฟฟ้า</title>
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
                    <li class="breadcrumb-item active" aria-current="page">รายการสถานีรถไฟฟ้า</li>
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
                    <h2 class="text-lg font-medium mr-auto">รายการสถานีรถไฟฟ้า</h2>
                </div>

                <div class="intro-y box p-5">
                    @if (session('success'))
                        <div class="alert alert-success mb-4">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger mb-4">{{ session('error') }}</div>
                    @endif

                    <div class="flex justify-end mb-4">
                        <a href="{{ route('admin.transit-stations.create') }}" class="btn btn-primary">
                            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                            เพิ่มสถานี
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-report mt-2" id="transit-stations-table">
                            <thead>
                                <tr>
                                    <th class="text-center">ลำดับ</th>
                                    <th>สายรถไฟฟ้า</th>
                                    <th class="text-center">รหัสสถานี</th>
                                    <th>ชื่อสถานี TH</th>
                                    <th>ชื่อสถานี EN</th>
                                    <th>จังหวัด</th>
                                    <th class="text-center">สถานะ</th>
                                    <th class="text-center">วันที่สร้าง</th>
                                    <th class="text-center">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($stations as $key => $station)
                                    <tr>
                                        <td class="text-center">{{ $key + 1 }}</td>

                                        <td>
                                            <div class="font-medium whitespace-nowrap">
                                                {{ optional($station->line)->name ?? '-' }}
                                            </div>
                                            @if (!empty(optional($station->line)->operator_name))
                                                <div class="text-slate-500 text-xs mt-1">
                                                    {{ optional($station->line)->operator_name }}
                                                </div>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            {{ $station->station_code ?: '-' }}
                                        </td>

                                        <td>
                                            <div class="font-medium whitespace-nowrap">
                                                {{ $station->name_th }}
                                            </div>
                                        </td>

                                        <td>
                                            {{ $station->name_en ?: '-' }}
                                        </td>

                                        <td>
                                            {{ $station->province_name ?: '-' }}
                                        </td>

                                        <td class="text-center">
                                            @if ($station->is_active)
                                                <span class="px-2 py-1 rounded-full text-xs bg-success/10 text-success">
                                                    เปิดใช้งาน
                                                </span>
                                            @else
                                                <span class="px-2 py-1 rounded-full text-xs bg-danger/10 text-danger">
                                                    ปิดใช้งาน
                                                </span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            {{ optional($station->created_at)->format('d/m/Y H:i') }}
                                        </td>

                                        <td class="text-center">
                                            <div class="flex justify-center items-center gap-3">
                                                <a href="{{ route('admin.transit-stations.edit', $station->id) }}"
                                                    class="flex items-center">
                                                    <i data-lucide="check-square" class="w-4 h-4 mr-1"></i>
                                                    แก้ไข
                                                </a>

                                                <a href="javascript:void(0);" class="flex items-center text-danger"
                                                    onclick="openDeleteModal({{ $station->id }})">
                                                    <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i>
                                                    ลบข้อมูล
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-slate-500 py-5">
                                            ยังไม่มีข้อมูลสถานีรถไฟฟ้า
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div id="delete-confirmation-modal" class="modal" tabindex="-1" aria-hidden="true"
                        data-tw-backdrop="static">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body p-0">
                                    <form method="POST" action="{{ route('admin.transit-stations.delete') }}">
                                        @csrf
                                        <div class="p-5 text-center">
                                            <input type="hidden" id="delete-id" name="id">
                                            <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                                            <div class="text-3xl mt-5">คุณต้องการลบข้อมูล?</div>
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
        function openDeleteModal(id) {
            document.getElementById('delete-id').value = id;

            const modalEl = document.querySelector('#delete-confirmation-modal');
            const modal = tailwind.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }

        jQuery(document).ready(function($) {
            $('#transit-stations-table').DataTable({
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
