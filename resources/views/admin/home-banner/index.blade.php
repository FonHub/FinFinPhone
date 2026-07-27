<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <title>รายการแบนเนอร์หน้าแรก</title>
    @include('admin.inc_header')
</head>

<body class="main">
    @include('admin.inc_mobilemenu')

    <div class="top-bar-boxed h-[70px] z-[51] relative border-b border-white/[0.08] -mt-7 md:-mt-5 -mx-3 sm:-mx-8 px-3 sm:px-8 md:pt-0 mb-12">
        <div class="h-full flex items-center">
            <a href="{{ url('admin/') }}" class="-intro-x hidden md:flex">
                <img alt="Logo" class="w-6" src="{{ asset('dist/images/logo.svg') }}">
                <span class="text-white text-lg ml-3"></span>
            </a>

            <nav aria-label="breadcrumb" class="-intro-x h-full mr-auto">
                <ol class="breadcrumb breadcrumb-light">
                    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">หน้าหลัก</a></li>
                    <li class="breadcrumb-item active" aria-current="page">รายการแบนเนอร์หน้าแรก</li>
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
                    <h2 class="text-lg font-medium mr-auto">รายการแบนเนอร์หน้าแรก</h2>
                </div>

                <div class="intro-y box p-5">
                    @if (session('success'))
                        <div class="alert alert-success mb-4">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger mb-4">{{ session('error') }}</div>
                    @endif

                    <div class="flex justify-end mb-4">
                        <a href="{{ route('admin.home-banner.create') }}" class="btn btn-primary">
                            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                            เพิ่มแบนเนอร์
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-report mt-2" id="banners-table">
                            <thead>
                                <tr>
                                    <th class="text-center">ลำดับ</th>
                                    <th>รูปเดสก์ท็อป</th>
                                    <th>รูปมือถือ</th>
                                    <th class="text-center">วันที่สร้าง</th>
                                    <th class="text-center">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($banners as $key => $banner)
                                    <tr>
                                        <td class="text-center">{{ $key + 1 }}</td>

                                        <td>
                                            @if ($banner->desktop_image)
                                                <img src="{{ asset('storage/' . $banner->desktop_image) }}" alt="Desktop Banner"
                                                    class="h-20 rounded-lg border">
                                            @else
                                                <span class="text-slate-400">-</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if ($banner->mobile_image)
                                                <img src="{{ asset('storage/' . $banner->mobile_image) }}" alt="Mobile Banner"
                                                    class="h-20 rounded-lg border">
                                            @else
                                                <span class="text-slate-400">-</span>
                                            @endif
                                        </td>

                                        <td class="text-center">{{ optional($banner->created_at)->format('d/m/Y H:i') }}</td>

                                        <td class="text-center">
                                            <div class="flex justify-center items-center gap-3">
                                                <a href="{{ route('admin.home-banner.edit', $banner->id) }}" class="flex items-center">
                                                    <i data-lucide="check-square" class="w-4 h-4 mr-1"></i>
                                                    แก้ไข
                                                </a>

                                                <a href="javascript:void(0);"
                                                    class="flex items-center text-danger"
                                                    onclick="openDeleteModal({{ $banner->id }})">
                                                    <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i>
                                                    ลบข้อมูล
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-slate-500 py-5">
                                            ยังไม่มีข้อมูลแบนเนอร์
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div id="delete-confirmation-modal" class="modal" tabindex="-1" aria-hidden="true" data-tw-backdrop="static">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body p-0">
                                    <form method="POST" action="{{ route('admin.home-banner.delete') }}">
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
            $('#banners-table').DataTable({
                responsive: true,
                order: [[0, "desc"]],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Thai.json"
                }
            });
        });
    </script>
</body>

</html>