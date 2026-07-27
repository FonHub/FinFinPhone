<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <title>{{ isset($news) && $news ? 'แก้ไขบทความ - News' : 'เพิ่มบทความ - News' }}</title>
    @include('admin/inc_header')
</head>

<body class="main">
    @include('admin/inc_mobilemenu')

    <div
        class="top-bar-boxed h-[70px] z-[51] relative border-b border-white/[0.08] -mt-7 md:-mt-5 -mx-3 sm:-mx-8 px-3 sm:px-8 md:pt-0 mb-12">
        <div class="h-full flex items-center">
            <a href="{{ url('admin/') }}" class="-intro-x hidden md:flex">
                <img alt="Admin Logo" class="w-6" src="{{ asset('dist/images/logo.svg') }}">
                <span class="text-white text-lg ml-3"></span>
            </a>

            <nav aria-label="breadcrumb" class="-intro-x h-full mr-auto">
                <ol class="breadcrumb breadcrumb-light">
                    <li class="breadcrumb-item">
                        <a href="{{ url('admin/news') }}">บทความ / ข่าวสาร</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ isset($news) && $news ? 'แก้ไขข้อมูล' : 'เพิ่มข้อมูล' }}
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
                <div class="intro-y flex items-center mt-8 mb-2">
                    <h2 class="text-lg font-medium mr-auto">
                        {{ isset($news) && $news ? 'แก้ไขบทความ / ข่าวสาร' : 'เพิ่มบทความ / ข่าวสาร' }}
                    </h2>
                </div>

                <div class="intro-y col-span-12 lg:col-span-12">
                    <div class="intro-y box rounded-2xl shadow-lg bg-white dark:bg-slate-800">
                        <div class="p-6">
                            <div class="p-5 bg-slate-50 dark:bg-slate-700 rounded-lg">

                                @if (session('success'))
                                    <div class="alert alert-success mt-3">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                @if (session('error'))
                                    <div class="alert alert-danger mt-3">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger mt-3">
                                        <ul class="mb-0 pl-5">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @php
                                    $isEdit = isset($news) && $news;

                                    $currentStatus = old('status', $news->status ?? 1);

                                    if ($currentStatus === 'active') {
                                        $currentStatus = 1;
                                    }

                                    if ($currentStatus === 'inactive' || $currentStatus === 'draft') {
                                        $currentStatus = 0;
                                    }

                                    $currentImage = $news->image ?? null;
                                    $currentOgImage = $news->og_image ?? null;
                                @endphp

                                <form action="{{ url('admin/save-news') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf

                                    @if ($isEdit)
                                        <input type="hidden" name="id" value="{{ $news->id }}">
                                    @endif

                                    <div class="grid grid-cols-12 gap-5 mt-5">

                                        <div class="col-span-12 md:col-span-6">
                                            <label class="form-label">
                                                สถานะ <span class="text-danger">*</span>
                                            </label>
                                            <select name="status" class="form-select w-full">
                                                <option value="1"
                                                    {{ (string) $currentStatus === '1' ? 'selected' : '' }}>
                                                    เปิดใช้งาน / เผยแพร่
                                                </option>
                                                <option value="0"
                                                    {{ (string) $currentStatus === '0' ? 'selected' : '' }}>
                                                    ปิดใช้งาน
                                                </option>
                                            </select>
                                            <div class="form-help">
                                                หน้าบ้านจะแสดงเฉพาะรายการที่เปิดใช้งานเท่านั้น
                                            </div>
                                        </div>

                                        <div class="col-span-12 md:col-span-6">
                                            <label class="form-label">Slug</label>
                                            <input type="text" name="slug" class="form-control"
                                                value="{{ old('slug', $news->slug ?? '') }}"
                                                placeholder="เช่น my-news-title">
                                            <div class="form-help">
                                                ถ้าไม่กรอก ระบบจะสร้างให้อัตโนมัติจากหัวข้อ
                                            </div>
                                        </div>

                                        <div class="col-span-12">
                                            <label class="form-label">
                                                หัวข้อ <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="title" class="form-control"
                                                value="{{ old('title', $news->title ?? '') }}"
                                                placeholder="กรอกหัวข้อบทความ / ข่าวสาร">
                                        </div>

                                        <div class="col-span-12">
                                            <label class="form-label">คำอธิบายสั้น</label>
                                            <textarea name="short_description" class="form-control" rows="3"
                                                placeholder="ข้อความสรุปสั้นสำหรับหน้า list / SEO">{{ old('short_description', $news->short_description ?? '') }}</textarea>
                                        </div>

                                        <div class="col-span-12 md:col-span-6">
                                            <label class="form-label">วันที่เผยแพร่</label>
                                            <input type="text" name="published_at" id="published_at"
                                                class="form-control"
                                                value="{{ old('published_at', !empty($news->published_at) ? \Carbon\Carbon::parse($news->published_at)->format('Y-m-d H:i') : '') }}"
                                                placeholder="YYYY-MM-DD HH:mm">
                                            <div class="form-help">
                                                ถ้าไม่ระบุ จะถือว่าเผยแพร่ได้ทันทีเมื่อเปิดใช้งาน
                                            </div>
                                        </div>

                                        <div class="col-span-12 md:col-span-6">
                                            <label class="form-label">รูปภาพหลัก</label>
                                            <input type="file" name="image" class="form-control" accept="image/*">
                                            <div class="form-help">
                                                เก็บใน storage/app/public/news และแสดงผ่าน public/storage
                                            </div>
                                        </div>

                                        @if ($isEdit && !empty($currentImage))
                                            <div class="col-span-12">
                                                <label class="form-label">รูปภาพหลักปัจจุบัน</label>
                                                <div class="rounded-lg border bg-white p-3 inline-block">
                                                    <img src="{{ asset('storage/' . $currentImage) }}"
                                                        class="h-28 rounded border mx-0 object-cover" alt="news-image">
                                                </div>
                                            </div>
                                        @endif

                                        <div class="col-span-12">
                                            <label class="form-label">รายละเอียด</label>
                                            <textarea name="detail" id="detail" class="form-control" rows="20" placeholder="กรอกรายละเอียดบทความ">{{ old('detail', $news->detail ?? '') }}</textarea>
                                            <div class="form-help mt-2">
                                                สามารถแทรกรูปในเนื้อหาได้จากปุ่มรูปภาพใน editor
                                            </div>
                                        </div>

                                        <div class="col-span-12 mt-4">
                                            <div class="text-lg font-medium border-b pb-2 mb-4">
                                                SEO
                                            </div>
                                        </div>

                                        <div class="col-span-12">
                                            <label class="form-label">Meta Title</label>
                                            <input type="text" name="meta_title" class="form-control"
                                                value="{{ old('meta_title', $news->meta_title ?? '') }}"
                                                placeholder="หัวข้อสำหรับ SEO">
                                        </div>

                                        <div class="col-span-12">
                                            <label class="form-label">Meta Description</label>
                                            <textarea name="meta_description" class="form-control" rows="3" placeholder="คำอธิบายสำหรับ SEO">{{ old('meta_description', $news->meta_description ?? '') }}</textarea>
                                        </div>

                                        <div class="col-span-12">
                                            <label class="form-label">Meta Keywords</label>
                                            <textarea name="meta_keywords" class="form-control" rows="2" placeholder="เช่น ข่าว, โปรโมชั่น, สินค้า">{{ old('meta_keywords', $news->meta_keywords ?? '') }}</textarea>
                                        </div>

                                        <div class="col-span-12 md:col-span-6">
                                            <label class="form-label">OG Image</label>
                                            <input type="file" name="og_image" class="form-control"
                                                accept="image/*">
                                        </div>

                                        <div class="col-span-12 md:col-span-6">
                                            <label class="form-label">Canonical URL</label>
                                            <input type="text" name="canonical_url" class="form-control"
                                                value="{{ old('canonical_url', $news->canonical_url ?? '') }}"
                                                placeholder="https://example.com/news/slug">
                                        </div>

                                        @if ($isEdit && !empty($currentOgImage))
                                            <div class="col-span-12">
                                                <label class="form-label">OG Image ปัจจุบัน</label>
                                                <div class="rounded-lg border bg-white p-3 inline-block">
                                                    <img src="{{ asset('storage/' . $currentOgImage) }}"
                                                        class="h-24 rounded border mx-0 object-cover" alt="og-image">
                                                </div>
                                            </div>
                                        @endif

                                        <div class="col-span-12 md:col-span-6">
                                            <label class="form-label">SEO Robots</label>
                                            <input type="text" name="seo_robots" class="form-control"
                                                value="{{ old('seo_robots', $news->seo_robots ?? 'index,follow') }}"
                                                placeholder="index,follow">
                                        </div>

                                        <div class="col-span-12 mt-5">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ url('admin/news') }}" class="btn btn-outline-secondary">
                                                    ยกเลิก
                                                </a>

                                                <button type="submit" class="btn btn-primary">
                                                    <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                                                    {{ $isEdit ? 'บันทึกการแก้ไข' : 'บันทึกข้อมูล' }}
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @include('admin/inc_footer')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

    <style>
        .ck-editor__editable {
            min-height: 420px;
            background: #ffffff !important;
            color: #111827 !important;
        }

        .ck.ck-editor {
            width: 100%;
        }

        .ck.ck-toolbar {
            z-index: 20;
        }

        .ck.ck-balloon-panel {
            z-index: 99999 !important;
        }
    </style>

    <script>
        class NewsUploadAdapter {
            constructor(loader) {
                this.loader = loader;
                this.url = '{{ route('admin.news.upload-editor-image') }}';
            }

            upload() {
                return this.loader.file.then(file => {
                    return new Promise((resolve, reject) => {
                        const data = new FormData();
                        const xhr = new XMLHttpRequest();

                        xhr.open('POST', this.url, true);
                        xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                        xhr.responseType = 'json';

                        xhr.addEventListener('error', () => {
                            reject('ไม่สามารถอัปโหลดรูปภาพได้');
                        });

                        xhr.addEventListener('abort', () => {
                            reject('ยกเลิกการอัปโหลดรูปภาพ');
                        });

                        xhr.addEventListener('load', () => {
                            const response = xhr.response;

                            if (!response || xhr.status < 200 || xhr.status >= 300) {
                                reject(response && response.message ? response.message :
                                    'อัปโหลดรูปภาพไม่สำเร็จ');
                                return;
                            }

                            if (!response.location) {
                                reject('ไม่พบ URL รูปภาพที่อัปโหลด');
                                return;
                            }

                            resolve({
                                default: response.location
                            });
                        });

                        if (xhr.upload) {
                            xhr.upload.addEventListener('progress', event => {
                                if (event.lengthComputable) {
                                    this.loader.uploadTotal = event.total;
                                    this.loader.uploaded = event.loaded;
                                }
                            });
                        }

                        data.append('file', file);
                        xhr.send(data);
                    });
                });
            }

            abort() {}
        }

        function NewsUploadAdapterPlugin(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
                return new NewsUploadAdapter(loader);
            };
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (window.flatpickr) {
                flatpickr('#published_at', {
                    enableTime: true,
                    dateFormat: 'Y-m-d H:i',
                    time_24hr: true,
                    allowInput: true
                });
            }

            const detailElement = document.querySelector('#detail');

            if (detailElement) {
                ClassicEditor
                    .create(detailElement, {
                        extraPlugins: [
                            NewsUploadAdapterPlugin
                        ],
                        toolbar: [
                            'heading',
                            '|',
                            'bold',
                            'italic',
                            'underline',
                            'link',
                            '|',
                            'bulletedList',
                            'numberedList',
                            '|',
                            'imageUpload',
                            'insertTable',
                            'blockQuote',
                            '|',
                            'undo',
                            'redo'
                        ],
                        image: {
                            toolbar: [
                                'imageTextAlternative',
                                'toggleImageCaption',
                                'imageStyle:inline',
                                'imageStyle:block',
                                'imageStyle:side'
                            ]
                        }
                    })
                    .then(editor => {
                        window.newsDetailEditor = editor;
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }
        });
    </script>
</body>

</html>
