@php
    // $name expected; falls back to 'code'
    $paths = [
        'code'    => '<polyline points="8 9 5 12 8 15"/><polyline points="16 9 19 12 16 15"/><line x1="13" y1="7" x2="11" y2="17"/>',
        'mobile'  => '<rect x="7" y="3" width="10" height="18" rx="2"/><line x1="11" y1="18" x2="13" y2="18"/>',
        'cloud'   => '<path d="M7 17a4 4 0 0 1 .5-7.97 5 5 0 0 1 9.7 1.02A3.5 3.5 0 0 1 17 17Z"/>',
        'design'  => '<circle cx="12" cy="12" r="9"/><circle cx="8.5" cy="10.5" r="1"/><circle cx="12" cy="8.5" r="1"/><circle cx="15.5" cy="10.5" r="1"/><path d="M12 21a2.5 2.5 0 0 1 0-5 2 2 0 0 0 0-4"/>',
        'shield'  => '<path d="M12 3l7 3v5c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6z"/>',
        'rocket'  => '<path d="M5 15c-1 1-1 4-1 4s3 0 4-1M9 12a10 10 0 0 1 8-8c1 0 2 1 2 2a10 10 0 0 1-8 8l-2-2z"/><circle cx="14" cy="10" r="1.3"/>',
        'chart'   => '<line x1="5" y1="20" x2="5" y2="12"/><line x1="12" y1="20" x2="12" y2="6"/><line x1="19" y1="20" x2="19" y2="14"/>',
        'chat'    => '<path d="M20 15a2 2 0 0 1-2 2H8l-4 4V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2z"/>',
        'layers'  => '<polygon points="12 3 21 8 12 13 3 8"/><polyline points="3 13 12 18 21 13"/>',
        'check'   => '<polyline points="5 12 10 17 19 7"/>',
        'arrow'   => '<line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>',
        'arrow-ur'=> '<line x1="7" y1="17" x2="17" y2="7"/><polyline points="8 7 17 7 17 16"/>',
        'chevron' => '<polyline points="9 6 15 12 9 18"/>',
    ];
    $n = $name ?? 'code';
    $d = $paths[$n] ?? $paths['code'];
@endphp
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $d !!}</svg>
