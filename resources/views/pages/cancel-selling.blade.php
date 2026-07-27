@extends('layouts.app')

@section('title', ($supportPage->page_title ?? 'วิธียกเลิกการขาย') . ' | Cashkub')

@section('content')
@php
$supportTabs = $supportTabs ?? collect();
$randomFaqs = $randomFaqs ?? collect();

$sections = $supportPage->sections ?? collect();

$infoSection =
$sections->firstWhere('section_key', 'cancel_info') ??
($sections->firstWhere('section_key', 'info') ?? null);

$infoItems = $infoSection ? $infoSection->items : collect();

$summarySection = $sections->firstWhere('section_key', 'summary') ?? null;

$summaryItems = $summarySection ? $summarySection->items : collect();

$contactItems = collect([
[
'label' => $supportPage->contact_phone_label ?: 'เบอร์ติดต่อ',
'value' => $supportPage->contact_phone ?: '',
'icon' => 'phone',
],
[
'label' => $supportPage->contact_email_label ?: 'อีเมล',
'value' => $supportPage->contact_email ?: '',
'icon' => 'mail',
],
[
'label' => $supportPage->contact_time_label ?: 'เวลาทำการ',
'value' => $supportPage->contact_time ?: '',
'icon' => 'clock',
],
])
->filter(function ($item) {
return trim((string) $item['value']) !== '';
})
->values();

$phoneForTel = preg_replace('/[^0-9+]/', '', (string) ($supportPage->contact_phone ?? ''));

function iconSvg($key)
{
$icons = [
'shield' =>
'<svg viewBox="0 0 24 24" fill="none" class="w-5 h-5">
    <path d="M12 3l7 3v5c0 4.8-2.8 8.4-7 10-4.2-1.6-7-5.2-7-10V6l7-3Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
    <path d="m9.4 12 1.7 1.7 3.7-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
</svg>',
'money' =>
'<svg viewBox="0 0 24 24" fill="none" class="w-5 h-5">
    <rect x="3" y="6" width="18" height="12" rx="2.5" stroke="currentColor" stroke-width="1.8" />
    <circle cx="12" cy="12" r="2.7" stroke="currentColor" stroke-width="1.8" />
    <path d="M7 10h.01M17 14h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
</svg>',
'location' =>
'<svg viewBox="0 0 24 24" fill="none" class="w-5 h-5">
    <path d="M12 21s6-5.2 6-11a6 6 0 1 0-12 0c0 5.8 6 11 6 11Z" stroke="currentColor" stroke-width="1.8" />
    <circle cx="12" cy="10" r="2.5" stroke="currentColor" stroke-width="1.8" />
</svg>',
'gift' =>
'<svg viewBox="0 0 24 24" fill="none" class="w-5 h-5">
    <path d="M4 10h16v10H4z" stroke="currentColor" stroke-width="1.8" />
    <path d="M12 10v10M3 10h18M12 10H7.8a2.3 2.3 0 1 1 0-4.6c2.2 0 4.2 4.6 4.2 4.6Zm0 0h4.2a2.3 2.3 0 1 0 0-4.6c-2.2 0-4.2 4.6-4.2 4.6Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
</svg>',
'phone' =>
'<svg viewBox="0 0 24 24" fill="none" class="w-5 h-5">
    <path d="M5 4.8A1.8 1.8 0 0 1 6.8 3h2.1c.8 0 1.5.5 1.7 1.2l.8 3a1.8 1.8 0 0 1-.5 1.8l-1.2 1.2a14.8 14.8 0 0 0 4.1 4.1l1.2-1.2a1.8 1.8 0 0 1 1.8-.5l3 .8c.7.2 1.2.9 1.2 1.7v2.1A1.8 1.8 0 0 1 19.2 21H18C10.8 21 5 15.2 5 8V4.8Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
</svg>',
'mail' =>
'<svg viewBox="0 0 24 24" fill="none" class="w-5 h-5">
    <rect x="3" y="5" width="18" height="14" rx="2.5" stroke="currentColor" stroke-width="1.8" />
    <path d="m5.5 7.5 6.5 5 6.5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
</svg>',
'clock' =>
'<svg viewBox="0 0 24 24" fill="none" class="w-5 h-5">
    <circle cx="12" cy="12" r="8.5" stroke="currentColor" stroke-width="1.8" />
    <path d="M12 8v4l2.8 1.8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
</svg>',
'check' =>
'<svg viewBox="0 0 24 24" fill="none" class="w-5 h-5">
    <path d="M20 7L10 17l-5-5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
</svg>',
'alert' =>
'<svg viewBox="0 0 24 24" fill="none" class="w-5 h-5">
    <path d="M12 3.8 20 18a1.4 1.4 0 0 1-1.2 2H5.2A1.4 1.4 0 0 1 4 18l8-14.2Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
    <path d="M12 9v4m0 3h.01" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
</svg>',
];

return $icons[$key] ?? $icons['check'];
}
@endphp

<section class="min-h-screen bg-[#F5F7F6]" id="hero">
   

    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-10" style="max-width: 1140px;">
        <div class="text-[14px] text-[#94A3B8] mb-5">
            <a href="{{ url('/') }}" class="hover:text-[#10A36A] transition">หน้าแรก</a>
            <span class="mx-2">/</span>
            <span class="text-[#64748B]">
                {{ $supportPage->breadcrumb_title ?: $supportPage->page_title ?: 'ศูนย์ดูแลลูกค้า' }}
            </span>
        </div>

        <div class="flex flex-wrap gap-3 mb-7">
            @foreach ($supportTabs as $tab)
            <a href="{{ $tab['url'] }}"
                class="inline-flex items-center rounded-full px-4 h-11 text-[14px] font-semibold transition
                    {{ $tab['active'] ? 'bg-[#10A36A] text-white shadow-[0_12px_30px_rgba(16,163,106,0.18)]' : 'bg-white text-[#5F6B76] border border-[#E5ECE8] hover:border-[#10A36A] hover:text-[#10A36A]' }}">
                {{ $tab['label'] }}
            </a>
            @endforeach

            <a href="#faq"
                class="inline-flex items-center rounded-full px-4 h-11 text-[14px] font-semibold transition bg-white text-[#5F6B76] border border-[#E5ECE8] hover:border-[#10A36A] hover:text-[#10A36A]">
                คำถามบ่อยๆ
            </a>

            <a href="#customer-care"
                class="inline-flex items-center rounded-full px-4 h-11 text-[14px] font-semibold transition bg-white text-[#5F6B76] border border-[#E5ECE8] hover:border-[#10A36A] hover:text-[#10A36A]">
                ศูนย์ดูแลลูกค้า
            </a>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 xl:gap-8 items-start">
            <div class="xl:col-span-8">
                <div
                    class="rounded-[28px] border border-[#E6ECE8] bg-white shadow-[0_20px_60px_rgba(15,23,42,0.06)] overflow-hidden">
                    <div
                        class="px-5 sm:px-8 lg:px-10 pt-8 pb-7 bg-[linear-gradient(135deg,#F7FCF9_0%,#FFFFFF_45%,#F2FAF6_100%)] border-b border-[#EEF2EF]">
                        @if (!empty($supportPage->badge_text))
                        <div
                            class="inline-flex items-center rounded-full bg-[#EAF7F1] text-[#13885C] text-[13px] font-bold px-4 h-10 mb-5">
                            {{ $supportPage->badge_text }}
                        </div>
                        @endif

                        <h1
                            class="text-[#111827] text-[30px] sm:text-[38px] lg:text-[46px] font-bold leading-tight tracking-tight">
                            {{ $supportPage->hero_title ?: $supportPage->page_title }}
                        </h1>

                        @if (!empty($supportPage->hero_description))
                        <p class="mt-4 text-[#6B7280] text-[16px] sm:text-[17px] leading-8 max-w-[760px]">
                            {{ $supportPage->hero_description }}
                        </p>
                        @endif

                        <div class="mt-6 flex flex-wrap gap-3">
                            @if (!empty($supportPage->primary_button_text) && !empty($supportPage->primary_button_url))
                            <a href="{{ $supportPage->primary_button_url }}"
                                class="inline-flex items-center justify-center rounded-full bg-[#10A36A] hover:bg-[#0E8C5B] text-white text-[15px] font-bold px-6 h-12 shadow-[0_12px_30px_rgba(16,163,106,0.22)] transition">
                                {{ $supportPage->primary_button_text }}
                            </a>
                            @endif

                            @if (!empty($supportPage->secondary_button_text) && !empty($supportPage->secondary_button_url))
                            <a href="{{ $supportPage->secondary_button_url }}"
                                class="inline-flex items-center justify-center rounded-full border border-[#DCE6E0] bg-white text-[#314155] hover:border-[#10A36A] hover:text-[#10A36A] text-[15px] font-semibold px-6 h-12 transition">
                                {{ $supportPage->secondary_button_text }}
                            </a>
                            @endif
                        </div>
                    </div>

                    <div class="px-5 sm:px-8 lg:px-10 py-8">
                        @if ($infoSection)
                        <div class="rounded-[22px] border border-[#E8EFEB] bg-[#F9FBFA] p-5 sm:p-6">
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-12 h-12 rounded-full bg-[#EAF7F1] text-[#10A36A] flex items-center justify-center shrink-0">
                                    {!! iconSvg($infoSection->layout_type ?: 'alert') !!}
                                </div>

                                <div class="w-full">
                                    <h2 class="text-[#111827] text-[20px] font-bold">
                                        {{ $infoSection->title }}
                                    </h2>

                                    @if (!empty($infoSection->description))
                                    <p class="mt-3 text-[#66727D] text-[15px] leading-7">
                                        {{ $infoSection->description }}
                                    </p>
                                    @endif

                                    @if ($infoItems->count() > 0)
                                    <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-3">
                                        @foreach ($infoItems as $item)
                                        <div
                                            class="rounded-[16px] bg-white border border-[#E5ECE8] px-4 py-4">
                                            <div
                                                class="flex items-center gap-3 text-[#111827] font-semibold">
                                                <span
                                                    class="w-10 h-10 rounded-full bg-[#EAF7F1] text-[#10A36A] flex items-center justify-center">
                                                    {!! iconSvg($item->icon ?: 'check') !!}
                                                </span>
                                                {{ $item->title }}
                                            </div>

                                            @if (!empty($item->description))
                                            <p class="mt-3 text-[#6B7280] text-[14px] leading-6">
                                                {{ $item->description }}
                                            </p>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="mt-10" id="faq">
                            <div class="mb-5">
                                <div class="text-[#16A36C] text-[18px] mb-2">คำถามที่พบบ่อย</div>
                                <h2 class="text-[#111827] text-[26px] sm:text-[30px] font-bold leading-tight">
                                    FAQ สำหรับการขายสินค้า
                                </h2>
                            </div>

                            <div class="space-y-4">
                                @foreach ($randomFaqs as $index => $faq)
                                <div
                                    class="rounded-[18px] border border-[#E5ECE8] bg-white overflow-hidden faq-item">
                                    <button type="button"
                                        class="w-full flex items-center justify-between gap-4 px-5 sm:px-6 py-5 text-left faq-trigger"
                                        data-faq-trigger aria-expanded="{{ $index === 0 ? 'true' : 'false' }}">
                                        <div class="pr-4">
                                            <h3
                                                class="text-[#111827] text-[17px] sm:text-[18px] font-bold leading-7">
                                                {{ $faq['q'] }}
                                            </h3>
                                        </div>

                                        <span class="relative block w-5 h-5 shrink-0">
                                            <span
                                                class="absolute left-1/2 top-1/2 w-4 h-[1.8px] bg-[#6B7280] rounded-full -translate-x-1/2 -translate-y-1/2"></span>
                                            <span
                                                class="faq-vertical absolute left-1/2 top-1/2 h-4 w-[1.8px] bg-[#6B7280] rounded-full -translate-x-1/2 -translate-y-1/2 transition duration-300 {{ $index === 0 ? 'scale-y-0' : '' }}"></span>
                                        </span>
                                    </button>

                                    <div class="faq-content overflow-hidden transition-all duration-300 {{ $index === 0 ? 'pb-6' : 'max-h-0' }}"
                                        data-faq-content
                                        @if ($index !==0) style="max-height:0px;" @endif>
                                        <div class="px-5 sm:px-6">
                                            <p class="text-[#66727D] text-[15px] leading-8">
                                                {{ $faq['a'] }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                                @if ($randomFaqs->count() === 0)
                                <div
                                    class="rounded-[18px] border border-[#E5ECE8] bg-white px-5 py-8 text-center text-[#6B7280]">
                                    ยังไม่มีคำถามที่พบบ่อย
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <aside class="xl:col-span-4" id="customer-care">
                <div class="xl:sticky xl:top-6 space-y-4">
                    <div
                        class="rounded-[24px] border border-[#E6ECE8] bg-white shadow-[0_20px_60px_rgba(15,23,42,0.06)] overflow-hidden">
                        <div class="px-5 sm:px-6 pt-6 pb-4">
                            <h3 class="text-[#16A36C] text-[24px] font-extrabold tracking-tight">
                                {{ $supportPage->contact_title ?: 'ติดต่อเจ้าหน้าที่' }}
                            </h3>

                            @if (!empty($supportPage->contact_description))
                            <p class="mt-2 text-[#6B7280] text-[14px] leading-7">
                                {{ $supportPage->contact_description }}
                            </p>
                            @endif
                        </div>

                        <div class="px-5 sm:px-6 pb-6">
                            <div class="rounded-[20px] border border-[#E5ECE8] bg-[#FCFDFC] p-4 sm:p-5">
                                @if ($contactItems->count() > 0)
                                <div class="space-y-3">
                                    @foreach ($contactItems as $item)
                                    <div class="rounded-[16px] border border-[#E7EEEA] bg-white px-4 py-4">
                                        <div class="flex items-start gap-3">
                                            <span
                                                class="w-10 h-10 rounded-full bg-[#EAF7F1] text-[#10A36A] flex items-center justify-center shrink-0">
                                                {!! iconSvg($item['icon']) !!}
                                            </span>

                                            <div>
                                                <div class="text-[#8A97A2] text-[13px] font-medium">
                                                    {{ $item['label'] }}
                                                </div>
                                                <div
                                                    class="mt-1 text-[#111827] text-[16px] font-bold leading-7">
                                                    {{ $item['value'] }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endif

                                @if (!empty($supportPage->note_title) || !empty($supportPage->note_description))
                                <div class="my-5 border-t border-[#E9EFEB]"></div>

                                <div class="rounded-[18px] bg-[#F7FBF9] border border-[#E3ECE7] p-5">
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-[#EAF7F1] text-[#10A36A] flex items-center justify-center shrink-0">
                                            {!! iconSvg($supportPage->note_icon ?: 'alert') !!}
                                        </div>

                                        <div>
                                            @if (!empty($supportPage->note_title))
                                            <div class="text-[#111827] font-bold text-[16px]">
                                                {{ $supportPage->note_title }}
                                            </div>
                                            @endif

                                            @if (!empty($supportPage->note_description))
                                            <p class="mt-2 text-[#66727D] text-[14px] leading-7">
                                                {{ $supportPage->note_description }}
                                            </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if (!empty($supportPage->call_button_text))
                                <div class="mt-5">
                                    <a href="{{ $supportPage->call_button_url ?: (!empty($phoneForTel) ? 'tel:' . $phoneForTel : '#') }}"
                                        class="inline-flex w-full items-center justify-center rounded-full bg-[#10A36A] hover:bg-[#0E8C5B] text-white text-[16px] font-bold px-6 h-12 shadow-[0_12px_30px_rgba(16,163,106,0.22)] transition">
                                        {{ $supportPage->call_button_text }}
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if ($summarySection && $summaryItems->count() > 0)
                    <div
                        class="rounded-[24px] border border-[#E6ECE8] bg-white shadow-[0_20px_60px_rgba(15,23,42,0.06)] overflow-hidden">
                        <div class="px-5 sm:px-6 py-5 border-b border-[#EEF2EF]">
                            <div class="text-[#111827] text-[18px] font-bold">
                                {{ $summarySection->title ?: 'สรุปภาพรวม' }}
                            </div>
                        </div>

                        <div class="px-5 sm:px-6 py-5">
                            <ul class="space-y-3 text-[#66727D] text-[14px] leading-7">
                                @foreach ($summaryItems as $item)
                                <li class="flex items-start gap-3">
                                    <span
                                        class="w-8 h-8 rounded-full bg-[#EAF7F1] text-[#10A36A] flex items-center justify-center shrink-0 mt-0.5">
                                        {!! iconSvg($item->icon ?: 'check') !!}
                                    </span>
                                    {{ $item->title ?: $item->description }}
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif
                </div>
            </aside>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const faqItems = Array.from(document.querySelectorAll('.faq-item'));

        function getTrigger(item) {
            return item ? item.querySelector('[data-faq-trigger]') : null;
        }

        function getContent(item) {
            return item ? item.querySelector('[data-faq-content]') : null;
        }

        function getVertical(item) {
            return item ? item.querySelector('.faq-vertical') : null;
        }

        function setContentHeight(content, expanded) {
            if (!content) return;

            if (expanded) {
                content.classList.remove('max-h-0');
                content.classList.add('pb-6');
                content.style.maxHeight = content.scrollHeight + 'px';
            } else {
                content.classList.remove('pb-6');
                content.classList.add('max-h-0');
                content.style.maxHeight = '0px';
            }
        }

        function openFaq(itemToOpen) {
            faqItems.forEach(function(item) {
                const trigger = getTrigger(item);
                const content = getContent(item);
                const vertical = getVertical(item);
                const isTarget = item === itemToOpen;

                if (trigger) {
                    trigger.setAttribute('aria-expanded', isTarget ? 'true' : 'false');
                }

                setContentHeight(content, isTarget);

                if (vertical) {
                    vertical.classList.toggle('scale-y-0', isTarget);
                }
            });
        }

        faqItems.forEach(function(item) {
            const trigger = getTrigger(item);
            if (!trigger) return;

            trigger.addEventListener('click', function() {
                const isExpanded = trigger.getAttribute('aria-expanded') === 'true';

                if (isExpanded) {
                    const content = getContent(item);
                    const vertical = getVertical(item);

                    trigger.setAttribute('aria-expanded', 'false');
                    setContentHeight(content, false);

                    if (vertical) {
                        vertical.classList.remove('scale-y-0');
                    }

                    return;
                }

                openFaq(item);
            });
        });

        window.addEventListener('resize', function() {
            faqItems.forEach(function(item) {
                const trigger = getTrigger(item);
                const content = getContent(item);

                if (trigger && content && trigger.getAttribute('aria-expanded') === 'true') {
                    content.style.maxHeight = content.scrollHeight + 'px';
                }
            });
        });
    });
</script>
@endsection