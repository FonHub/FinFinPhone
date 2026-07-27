<!DOCTYPE html>

<html lang="en" class="light">
<!-- BEGIN: Head -->

<head>
    <title> หน้าแรก - home </title>
    @include('admin/inc_header')
</head>
<!-- END: Head -->

<body class="main">
    <!-- BEGIN: Mobile Menu -->
    @include('admin/inc_mobilemenu')
    <!-- END: Mobile Menu -->
    <!-- BEGIN: Top Bar -->
    <div
        class="top-bar-boxed h-[70px] z-[51] relative border-b border-white/[0.08] -mt-7 md:-mt-5 -mx-3 sm:-mx-8 px-3 sm:px-8 md:pt-0 mb-12">
        <div class="h-full flex items-center">
            <!-- BEGIN: Logo -->
            <a href="{{ url('admin/') }}" class="-intro-x hidden md:flex">
                <img alt="Midone - HTML Admin Template" class="w-6" src="{{ asset('dist/images/logo.svg') }}">
                <span class="text-white text-lg ml-3"> </span>
            </a>
            <!-- END: Logo -->
            <!-- BEGIN: Breadcrumb -->
            <nav aria-label="breadcrumb" class="-intro-x h-full mr-auto">
                <ol class="breadcrumb breadcrumb-light">
                    <li class="breadcrumb-item"><a href="{{ url('admin/') }}">ข้อมูลหน้าแรก</a></li>
                    {{-- <li class="breadcrumb-item active" aria-current="page">{{ $user->name }}</li> --}}
                </ol>
            </nav>
            <!-- END: Breadcrumb -->


            <!-- BEGIN: Account Menu -->
            @include('admin/inc_account')
            <!-- END: Account Menu -->
        </div>
    </div>
    <!-- END: Top Bar -->
    <div class="wrapper">
        <div class="wrapper-box">
            <!-- BEGIN: Side Menu -->
            @include('admin/inc_sidemenu')
            <!-- END: Side Menu -->
            <!-- BEGIN: Content -->
            <div class="content">
                <div class="intro-y flex items-center mt-8 mb-2">
                    <h2 class="text-lg font-medium mr-auto">
                        หน้าแรก
                    </h2>
                </div>

            </div>
            <!-- END: Content -->
        </div>
    </div>
    @include('admin/inc_footer')
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.0/super-build/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#text_home'), {
                toolbar: [
                    'heading',
                    '|',
                    'undo', 'redo',
                    '|',
                    'bold', 'italic', 'underline', 'strikethrough',
                    '|',
                    'fontSize',
                    '|',
                    'link',
                    '|',
                    'bulletedList', 'numberedList',
                ],
                heading: {
                    options: [{
                            model: 'paragraph',
                            title: 'Paragraph',
                            class: 'ck-heading_paragraph'
                        },
                        {
                            model: 'heading1',
                            view: 'h1',
                            title: 'Heading 1',
                            class: 'ck-heading_heading1'
                        },
                        {
                            model: 'heading2',
                            view: 'h2',
                            title: 'Heading 2',
                            class: 'ck-heading_heading2'
                        },
                        {
                            model: 'heading3',
                            view: 'h3',
                            title: 'Heading 3',
                            class: 'ck-heading_heading3'
                        },
                        {
                            model: 'heading4',
                            view: 'h4',
                            title: 'Heading 4',
                            class: 'ck-heading_heading4'
                        },
                        {
                            model: 'heading5',
                            view: 'h5',
                            title: 'Heading 5',
                            class: 'ck-heading_heading5'
                        },
                        {
                            model: 'heading6',
                            view: 'h6',
                            title: 'Heading 6',
                            class: 'ck-heading_heading6'
                        },
                    ]
                }

            })
            .then(editor => console.log('Editor initialized', editor))
            .catch(error => console.error(error));
    </script>


    <script>
        ClassicEditor
            .create(document.querySelector('#text_1'), {
                toolbar: [
                    'heading',
                    '|',
                    'undo', 'redo',
                    '|',
                    'bold', 'italic', 'underline', 'strikethrough',
                    '|',
                    'fontSize',
                    '|',
                    'link',
                    '|',
                    'bulletedList', 'numberedList',
                ],
                heading: {
                    options: [{
                            model: 'paragraph',
                            title: 'Paragraph',
                            class: 'ck-heading_paragraph'
                        },
                        {
                            model: 'heading1',
                            view: 'h1',
                            title: 'Heading 1',
                            class: 'ck-heading_heading1'
                        },
                        {
                            model: 'heading2',
                            view: 'h2',
                            title: 'Heading 2',
                            class: 'ck-heading_heading2'
                        },
                        {
                            model: 'heading3',
                            view: 'h3',
                            title: 'Heading 3',
                            class: 'ck-heading_heading3'
                        },
                        {
                            model: 'heading4',
                            view: 'h4',
                            title: 'Heading 4',
                            class: 'ck-heading_heading4'
                        },
                        {
                            model: 'heading5',
                            view: 'h5',
                            title: 'Heading 5',
                            class: 'ck-heading_heading5'
                        },
                        {
                            model: 'heading6',
                            view: 'h6',
                            title: 'Heading 6',
                            class: 'ck-heading_heading6'
                        },
                    ]
                }

            })
            .then(editor => console.log('Editor initialized', editor))
            .catch(error => console.error(error));
    </script>
    <script>
        ClassicEditor
            .create(document.querySelector('#text_2'), {
                toolbar: [
                    'heading',
                    '|',
                    'undo', 'redo',
                    '|',
                    'bold', 'italic', 'underline', 'strikethrough',
                    '|',
                    'fontSize',
                    '|',
                    'link',
                    '|',
                    'bulletedList', 'numberedList',
                ],
                heading: {
                    options: [{
                            model: 'paragraph',
                            title: 'Paragraph',
                            class: 'ck-heading_paragraph'
                        },
                        {
                            model: 'heading1',
                            view: 'h1',
                            title: 'Heading 1',
                            class: 'ck-heading_heading1'
                        },
                        {
                            model: 'heading2',
                            view: 'h2',
                            title: 'Heading 2',
                            class: 'ck-heading_heading2'
                        },
                        {
                            model: 'heading3',
                            view: 'h3',
                            title: 'Heading 3',
                            class: 'ck-heading_heading3'
                        },
                        {
                            model: 'heading4',
                            view: 'h4',
                            title: 'Heading 4',
                            class: 'ck-heading_heading4'
                        },
                        {
                            model: 'heading5',
                            view: 'h5',
                            title: 'Heading 5',
                            class: 'ck-heading_heading5'
                        },
                        {
                            model: 'heading6',
                            view: 'h6',
                            title: 'Heading 6',
                            class: 'ck-heading_heading6'
                        },
                    ]
                }
            })
            .then(editor => console.log('Editor initialized', editor))
            .catch(error => console.error(error));
    </script>
</body>


</html>
