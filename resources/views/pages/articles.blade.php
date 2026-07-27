@extends('layouts.app')

@section('title', 'บทความ | Cashkub')

@section('content')
    <section class="bg-[#f8faf8] py-14 sm:py-16 lg:py-10">
        <div class="max-w-[1320px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="w-full mb-8 sm:mb-10 lg:mb-14 text-center">
                <p
                    class="inline-flex items-center rounded-full bg-[#E7F3EC] text-[#285F43] text-xs sm:text-sm font-medium px-4 py-2 mb-4">
                    บทความและสาระน่ารู้
                </p>

                <h1 class="text-[#285F43] font-extrabold text-3xl sm:text-4xl lg:text-5xl leading-tight">
                    บทความเกี่ยวกับมือถือและการขายเครื่อง
                </h1>

                <p class="mt-4 text-gray-600 text-base sm:text-lg leading-8">
                    รวมบทความแนะนำ เทคนิคการเช็กสภาพเครื่อง วิธีเตรียมมือถือก่อนขาย
                    และสาระน่ารู้ที่ช่วยให้คุณตัดสินใจได้ง่ายขึ้น
                </p>
            </div>

            @if ($pagedArticles->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 lg:gap-7">
                    @foreach ($pagedArticles as $article)
                        @php
                            $imageUrl = !empty($article->image)
                                ? asset('storage/' . $article->image)
                                : asset('assets/media/hero/hero-phone-right.png');

                            $title = $article->title ?? '-';

                            $excerpt = $article->short_description;

                            if (empty($excerpt)) {
                                $excerpt = \Illuminate\Support\Str::limit(strip_tags($article->detail ?? ''), 140);
                            }

                            $dateText = $article->published_at
                                ? $article->published_at->format('d/m/Y')
                                : optional($article->created_at)->format('d/m/Y');
                        @endphp

                        <article
                            class="group bg-white rounded-[26px] overflow-hidden shadow-[0_10px_30px_rgba(15,23,42,0.06)] hover:shadow-[0_18px_45px_rgba(15,23,42,0.12)] transition duration-300 border border-[#eef2f0]">
                            <a href="{{ route('articles.detail', $article->slug) }}" class="block">
                                <div class="aspect-[16/10] overflow-hidden bg-gray-100">
                                    <img src="{{ $imageUrl }}" alt="{{ $title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                </div>

                                <div class="p-5 sm:p-6">
                                    <div class="flex items-center justify-between gap-3 mb-3">
                                        <span
                                            class="inline-flex items-center rounded-full bg-[#E7F3EC] text-[#285F43] text-[12px] font-semibold px-3 py-1">
                                            ข่าวสาร
                                        </span>

                                        @if (!empty($dateText))
                                            <span class="text-[12px] text-gray-400">
                                                {{ $dateText }}
                                            </span>
                                        @endif
                                    </div>

                                    <h2
                                        class="text-[#1f2937] text-lg sm:text-xl font-bold leading-7 line-clamp-2 min-h-[56px]">
                                        {{ $title }}
                                    </h2>

                                    <p
                                        class="mt-3 text-gray-600 text-sm sm:text-[15px] leading-7 line-clamp-3 min-h-[84px]">
                                        {{ $excerpt }}
                                    </p>

                                    <div
                                        class="mt-5 inline-flex items-center text-[#285F43] font-semibold text-sm sm:text-base">
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

                <div class="mt-10 sm:mt-12 flex justify-center">
                    {{ $pagedArticles->links() }}
                </div>
            @else
                <div class="bg-white rounded-[26px] border border-[#eef2f0] shadow-sm px-6 py-14 text-center">
                    <div class="text-[#285F43] text-[24px] font-bold">
                        ยังไม่มีบทความ
                    </div>
                    <p class="mt-3 text-gray-500 text-[15px] leading-7">
                        เมื่อมีการเพิ่มข่าวสารหรือบทความ รายการจะแสดงที่หน้านี้
                    </p>
                </div>
            @endif
        </div>
    </section>
@endsection
