@extends('layouts.app')

@section('title', ($article->meta_title ?? null ?: $article->title ?? 'บทความ') . ' | Cashkub')

@section('content')
    @php
        $articleTitle = $article->title ?? '-';

        $articleImage = !empty($article->image)
            ? asset('storage/' . $article->image)
            : asset('assets/media/hero/hero-phone-right.png');

        $articleExcerpt = $article->short_description ?? null;

        if (empty($articleExcerpt)) {
            $articleExcerpt = \Illuminate\Support\Str::limit(strip_tags($article->detail ?? ''), 180);
        }

        $dateText = $article->published_at
            ? $article->published_at->format('d/m/Y')
            : optional($article->created_at)->format('d/m/Y');
    @endphp

    <section class="bg-[#f8faf8] pt-10 sm:pt-12 lg:pt-16 pb-16 sm:pb-20">
        <div class="max-w-[1100px] mx-auto px-4 sm:px-6 lg:px-8">
            

            <article
                class="bg-white rounded-[30px] shadow-[0_18px_50px_rgba(15,23,42,0.08)] overflow-hidden border border-[#eef2f0]">
                <div class="aspect-[16/8] sm:aspect-[16/7] bg-gray-100 overflow-hidden">
                    <img src="{{ $articleImage }}" alt="{{ $articleTitle }}" class="w-full h-full object-cover">
                </div>

                <div class="px-5 sm:px-8 lg:px-12 py-8 sm:py-10 lg:py-12">
                    <div class="flex items-center gap-3 flex-wrap mb-5">
                        <span
                            class="inline-flex items-center rounded-full bg-[#EEF6F1] text-[#285F43] text-[12px] font-semibold px-3 py-1.5">
                            ข่าวสาร
                        </span>

                        @if (!empty($dateText))
                            <span class="text-gray-400 text-[14px]">
                                {{ $dateText }}
                            </span>
                        @endif
                    </div>

                    <h1 class="text-[#1f2937] font-extrabold text-3xl sm:text-4xl lg:text-5xl leading-tight">
                        {{ $articleTitle }}
                    </h1>

                    @if (!empty($articleExcerpt))
                        <p class="mt-5 text-gray-600 text-base sm:text-lg leading-8">
                            {{ $articleExcerpt }}
                        </p>
                    @endif

                    @if (!empty($article->detail))
                        <div class="article-content mt-8 sm:mt-10 prose prose-lg max-w-none text-gray-700">
                            {!! $article->detail !!}
                        </div>
                    @else
                        <div class="mt-8 sm:mt-10 rounded-[20px] bg-[#F8FAF9] border border-[#E5ECE8] p-6 text-gray-500">
                            ยังไม่มีรายละเอียดบทความ
                        </div>
                    @endif

                    <div class="mt-10 rounded-[24px] bg-[#F4F8F5] p-5 sm:p-6 border border-[#E3ECE6]">
                        <h3 class="text-[#285F43] font-bold text-xl sm:text-2xl">
                            สนใจขายมือถือของคุณ?
                        </h3>

                        <p class="mt-3 text-gray-600 leading-8">
                            ส่งรายละเอียดรุ่น สภาพเครื่อง และอุปกรณ์ที่มีมาให้เราเพื่อประเมินราคาเบื้องต้นได้เลย
                        </p>

                        <div class="mt-5 flex flex-wrap gap-3">
                            <a href="{{ route('sell.product') }}"
                                class="inline-flex items-center justify-center rounded-full bg-[#285F43] text-white font-semibold px-6 h-12 hover:opacity-90 transition">
                                ประเมินราคาสินค้า
                            </a>

                            <a href="{{ route('articles') }}"
                                class="inline-flex items-center justify-center rounded-full bg-white text-[#285F43] border border-[#285F43] font-semibold px-6 h-12 hover:bg-[#285F43] hover:text-white transition">
                                ดูบทความอื่น
                            </a>
                        </div>
                    </div>
                </div>
            </article>

            @if (isset($relatedArticles) && $relatedArticles->count() > 0)
                <div class="mt-14 sm:mt-16">
                    <div class="flex items-center justify-between gap-4 mb-6">
                        <h2 class="text-[#285F43] font-bold text-2xl sm:text-3xl">
                            บทความที่เกี่ยวข้อง
                        </h2>

                        <a href="{{ route('articles') }}" class="text-[#285F43] font-medium hover:underline">
                            ดูทั้งหมด
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach ($relatedArticles as $item)
                            @php
                                $relatedTitle = $item->title ?? '-';

                                $relatedImage = !empty($item->image)
                                    ? asset('storage/' . $item->image)
                                    : asset('assets/media/hero/hero-phone-right.png');

                                $relatedExcerpt = $item->short_description ?? null;

                                if (empty($relatedExcerpt)) {
                                    $relatedExcerpt = \Illuminate\Support\Str::limit(
                                        strip_tags($item->detail ?? ''),
                                        120,
                                    );
                                }
                            @endphp

                            <article
                                class="group bg-white rounded-[24px] overflow-hidden shadow-[0_10px_30px_rgba(15,23,42,0.06)] hover:shadow-[0_18px_45px_rgba(15,23,42,0.12)] transition duration-300 border border-[#eef2f0]">
                                <a href="{{ route('articles.detail', $item->slug) }}" class="block">
                                    <div class="aspect-[16/10] overflow-hidden bg-gray-100">
                                        <img src="{{ $relatedImage }}" alt="{{ $relatedTitle }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    </div>

                                    <div class="p-5">
                                        <span
                                            class="inline-flex items-center rounded-full bg-[#EEF6F1] text-[#285F43] text-[12px] font-semibold px-3 py-1.5 mb-3">
                                            ข่าวสาร
                                        </span>

                                        <h3 class="text-[#1f2937] text-lg font-bold leading-7 line-clamp-2">
                                            {{ $relatedTitle }}
                                        </h3>

                                        <p class="mt-3 text-gray-600 text-sm leading-7 line-clamp-3">
                                            {{ $relatedExcerpt }}
                                        </p>

                                        <div class="mt-4 inline-flex items-center text-[#285F43] font-semibold text-sm">
                                            อ่านเพิ่มเติม
                                            <svg class="ml-2 w-4 h-4 transition-transform duration-300 group-hover:translate-x-1"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </div>
                                    </div>
                                </a>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    <style>
        .article-content {
            color: #374151;
            font-size: 16px;
            line-height: 1.85;
        }

        .article-content p {
            margin-bottom: 1.25rem;
            line-height: 1.85;
        }

        .article-content img {
            max-width: 100%;
            height: auto;
            border-radius: 18px;
            margin: 1.5rem auto;
            display: block;
        }

        .article-content h1,
        .article-content h2,
        .article-content h3,
        .article-content h4 {
            color: #1f2937;
            font-weight: 800;
            line-height: 1.35;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }

        .article-content h2 {
            font-size: 1.75rem;
        }

        .article-content h3 {
            font-size: 1.35rem;
        }

        .article-content ul,
        .article-content ol {
            padding-left: 1.5rem;
            margin-bottom: 1.25rem;
        }

        .article-content ul {
            list-style: disc;
        }

        .article-content ol {
            list-style: decimal;
        }

        .article-content a {
            color: #285F43;
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        .article-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 1.5rem 0;
            overflow: hidden;
            border-radius: 12px;
        }

        .article-content table th,
        .article-content table td {
            border: 1px solid #E5ECE8;
            padding: 10px 12px;
            vertical-align: top;
        }

        .article-content blockquote {
            border-left: 4px solid #285F43;
            padding-left: 1rem;
            color: #4b5563;
            background: #F4F8F5;
            padding-top: 1rem;
            padding-bottom: 1rem;
            border-radius: 12px;
        }
    </style>
@endsection
