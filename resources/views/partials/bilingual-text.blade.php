@php
    $englishText = \Illuminate\Support\Facades\Lang::get($key, [], 'en');
    $urduText = \Illuminate\Support\Facades\Lang::get($key, [], 'ur');
    $wrapperTag = $tag ?? 'span';
@endphp
<{{ $wrapperTag }} @if(! empty($for)) for="{{ $for }}" @endif class="{{ $class ?? '' }} bilingual-text bilingual-label {{ $wrapperClass ?? '' }}">
    <span>{{ $englishText }}</span>
    <span class="text-muted mx-1">/</span>
    <span class="urdu-text ur-label" dir="rtl" lang="ur">{{ $urduText }}</span>
</{{ $wrapperTag }}>
