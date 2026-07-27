<meta charset="utf-8">
<link href="{{ asset('dist/images/logo.svg') }}" rel="shortcut icon">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content=" ">
<meta name="keywords" content="">
<meta name="author" content="LEFT4CODE">
<!-- BEGIN: CSS Assets-->
<link rel="stylesheet" href="{{ asset('dist/css/app.css') }}" />
<!-- END: CSS Assets-->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

<!-- DataTables Responsive CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<!-- DataTables Responsive JS -->
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">
<script src="https://unpkg.com/lucide@latest"></script>
<style>
    /* ปรับความกว้างของ dropdown จำนวนแถว */
    .dataTables_length label select {
        width: 80px;
        /* กำหนดความกว้างตามต้องการ */
        display: inline-block;
        margin-left: 5px;
        margin-right: 5px;
    }

    /* แถบค้นหาด้านบน */

    .nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        font-size: 24px;
        color: white;
        background: rgba(0, 0, 0, 0.5);
        /* สีพื้นหลังโปร่งใส */
        padding: 10px 15px;
        border-radius: 50%;
        cursor: pointer;
        z-index: 1000;
    }

    .prev-slide {
        left: -30px;
        /* ให้ลูกศรด้านซ้ายชิดขอบจอ */
    }

    .next-slide {
        right: -30px;
        /* ให้ลูกศรด้านขวาชิดขอบจอ */
    }

    .owl-carousel:hover .nav-btn {
        opacity: 1;
        /* ทำให้ลูกศรแสดงชัดเมื่อโฮเวอร์ */
    }
</style>
<!-- เพิ่ม SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.3.2/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- <script src="https://cdn.ckeditor.com/ckeditor5/23.0.0/classic/ckeditor.js"></script> --}}
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.0/classic/ckeditor.js"></script>

<style>
    .ck-editor__editable_inline {
        min-height: 200px !important;
        max-height: 400px !important;
        height: 200px !important;
        overflow-y: auto;
        /* เพิ่มการเลื่อนถ้ามีเนื้อหายาวเกินไป */
    }
</style>
