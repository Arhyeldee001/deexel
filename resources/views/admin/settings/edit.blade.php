@extends('admin.layouts.app')

@section('title', 'Settings')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-sections.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-crud.css') }}">
@endpush

@section('content')
    @if(session('ok'))<div class="flash-seed" data-msg="{{ session('ok') }}"></div>@endif
    @if($errors->any())<div class="form-errors">{{ $errors->first() }}</div>@endif

    <div class="tabs">
        @foreach($schema as $key => $group)
            <button type="button" class="tab {{ $loop->first ? 'active' : '' }}" data-tab="{{ $key }}">{{ $group['label'] }}</button>
        @endforeach
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="crud-form" style="max-width:760px;">
        @csrf @method('PUT')

        @foreach($schema as $groupKey => $group)
            <div class="tab-panel {{ $loop->first ? 'active' : '' }}" data-panel="{{ $groupKey }}">

                @if($groupKey === 'appearance')
                    @php
                        $accType = $values['accent_type'] ?: 'solid';
                        $acc1 = $values['accent_color'] ?: '#ff6a00';
                        $acc2 = $values['accent_color_2'] ?: '#ff9500';
                        $angle = (int) ($values['accent_angle'] ?: 90);
                        $logoH = (int) ($values['logo_height'] ?: 32);
                    @endphp

                    <div class="fld full">
                        <label>Logo</label>
                        <div class="logo-stage">
                            <img id="logoPreview" src="{{ $values['logo'] ? asset('storage/'.$values['logo']) : '' }}"
                                 style="height:{{ $logoH }}px;{{ $values['logo'] ? '' : 'display:none;' }}" alt="logo preview">
                            <span id="logoPlaceholder" class="mut" style="{{ $values['logo'] ? 'display:none;' : '' }}">Upload a logo to preview it</span>
                        </div>
                        @if($values['logo'])
                            <label class="checkline"><input type="checkbox" name="remove_logo" value="1"> Remove logo (show site name as text)</label>
                        @endif
                        <input type="file" name="logo" accept="image/*" id="logoFile">
                    </div>

                    <div class="fld full">
                        <label>Logo size — <span id="logoSizeVal">{{ $logoH }}</span>px</label>
                        <input type="range" name="logo_height" min="16" max="120" value="{{ $logoH }}" id="logoSlider" class="range">
                    </div>

                    <div class="fld full">
                        <label>Favicon</label>
                        <p class="field-hint">Browser-tab icon — 32×32 or 48×48 PNG, ICO, or SVG.</p>
                        <div class="fav-row">
                            @if($values['favicon'])
                                <img src="{{ asset('storage/'.$values['favicon']) }}" alt="favicon" class="fav-preview" id="favPreview">
                            @else
                                <span class="fav-preview fav-empty" id="favPreview"></span>
                            @endif
                            <input type="file" name="favicon" accept="image/png,image/svg+xml,image/x-icon,.ico" id="faviconFile">
                        </div>
                        @if($values['favicon'])
                            <label class="checkline" style="margin-top:10px;"><input type="checkbox" name="remove_favicon" value="1"> Remove favicon</label>
                        @endif
                    </div>

                    <div class="fld full">
                        <label>Accent style</label>
                        <div class="seg">
                            <label class="seg-opt"><input type="radio" name="accent_type" value="solid" {{ $accType === 'solid' ? 'checked' : '' }}><span>Solid</span></label>
                            <label class="seg-opt"><input type="radio" name="accent_type" value="gradient" {{ $accType === 'gradient' ? 'checked' : '' }}><span>Gradient</span></label>
                        </div>
                    </div>

                    <div class="fld">
                        <label>Accent color</label>
                        <div class="color-row">
                            <input type="color" name="accent_color" value="{{ $acc1 }}" data-accent>
                            <input type="text" value="{{ $acc1 }}" data-accent-hex readonly>
                        </div>
                    </div>

                    <div class="fld grad-only">
                        <label>Second color</label>
                        <div class="color-row">
                            <input type="color" name="accent_color_2" value="{{ $acc2 }}" data-accent>
                            <input type="text" value="{{ $acc2 }}" data-accent-hex readonly>
                        </div>
                    </div>

                    <div class="fld grad-only full">
                        <label>Gradient angle — <span id="angleVal">{{ $angle }}</span>°</label>
                        <input type="range" name="accent_angle" min="0" max="360" value="{{ $angle }}" id="angleSlider" class="range" data-accent>
                    </div>

                    <div class="fld full">
                        <label>Preview</label>
                        <div class="accent-stage">
                            <div id="accentPreview" class="accent-swatch"></div>
                            <button type="button" class="btn-acc" style="pointer-events:none;">Sample button</button>
                        </div>
                    </div>

                @elseif($groupKey === 'maintenance')
                    @php $mOn = filter_var($values['maintenance_mode'] ?? false, FILTER_VALIDATE_BOOLEAN); @endphp
                    <div class="maint-card {{ $mOn ? 'on' : '' }}" id="maintCard">
                        <div class="maint-head">
                            <div>
                                <h3>Maintenance mode</h3>
                                <p class="field-hint" style="margin-bottom:0;">When on, visitors see a maintenance page. You and other admins can still browse the site normally.</p>
                            </div>
                            <label class="toggle big" title="Maintenance on/off">
                                <input type="checkbox" name="maintenance_mode" value="1" id="maintToggle" {{ $mOn ? 'checked' : '' }}>
                                <span class="track"></span>
                            </label>
                        </div>
                    </div>
                    <div class="fld full" style="margin-top:18px;">
                        <label>Heading</label>
                        <input type="text" name="maintenance_heading" value="{{ $values['maintenance_heading'] ?? '' }}">
                    </div>
                    <div class="fld full">
                        <label>Message</label>
                        <textarea name="maintenance_message" rows="3">{{ $values['maintenance_message'] ?? '' }}</textarea>
                    </div>

                @else
                    <div class="form-grid">
                        @foreach($group['fields'] as $key => $def)
                            @php $val = $values[$key] ?? ''; @endphp
                            @if($def['type'] === 'textarea')
                                <div class="fld full">
                                    <label>{{ $def['label'] }}</label>
                                    <textarea name="{{ $key }}" rows="2">{{ $val }}</textarea>
                                </div>
                            @elseif($def['type'] === 'image')
                                <div class="fld full">
                                    <label>{{ $def['label'] }}</label>
                                    @if(!empty($def['hint']))<p class="field-hint">{{ $def['hint'] }}</p>@endif
                                    @if($val)
                                        <div class="img-preview">
                                            <img src="{{ asset('storage/'.$val) }}" alt="" style="max-height:70px;border-radius:8px;">
                                            <label class="checkline"><input type="checkbox" name="remove_{{ $key }}" value="1"> Remove</label>
                                        </div>
                                    @endif
                                    <input type="file" name="{{ $key }}" accept="image/*">
                                </div>
                            @else
                                <div class="fld">
                                    <label>{{ $def['label'] }}</label>
                                    <input type="text" name="{{ $key }}" value="{{ $val }}" placeholder="{{ $def['placeholder'] ?? '' }}">
                                </div>
                            @endif
                        @endforeach
                    </div>

                    @if($groupKey === 'seo')
                        <div class="page-seo-list">
                            <h3>Per-page SEO</h3>
                            <p class="field-hint">Set a custom title, description, keywords, and share image for each page. New pages appear here automatically; blank fields fall back to the defaults above.</p>
                            @foreach($pages as $p)
                                <div class="ps-row">
                                    <div class="ps-info">
                                        <strong>{{ $p->title }}</strong>
                                        <span class="mut">/{{ $p->slug }}</span>
                                        @if($p->meta_title || $p->meta_description || $p->og_image)<span class="chip">customised</span>@endif
                                    </div>
                                    @can('manage settings')
                                        <a href="{{ route('admin.settings.pageSeo.edit', $p) }}" class="btn-sm">Edit →</a>
                                    @endcan
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif

            </div>
        @endforeach

        <div class="form-actions" style="margin-top:20px;">
            <button type="submit" class="btn-acc">Save settings</button>
        </div>
    </form>

    <style>
    .tabs{display:flex;gap:6px;flex-wrap:wrap;margin-bottom:20px;border-bottom:1px solid var(--bd);}
    .tab{background:none;border:0;border-bottom:2px solid transparent;color:var(--mut);padding:10px 14px;font-size:14px;font-family:inherit;cursor:pointer;transition:color .2s,border-color .2s;}
    .tab:hover{color:var(--tx);}
    .tab.active{color:var(--tx);border-bottom-color:var(--acc);}
    .tab-panel{display:none;animation:fadeIn .3s ease;}
    .tab-panel.active{display:block;}
    @keyframes fadeIn{from{opacity:0;transform:translateY(6px);}to{opacity:1;transform:none;}}
    .tab-panel .fld{margin-bottom:18px;}

    .tab-panel .hint{position:static !important;display:inline !important;background:none !important;border:0 !important;padding:0 !important;margin:0 !important;box-shadow:none !important;font-size:12px;color:var(--mut);white-space:normal;}
    .field-hint{font-size:12px;color:var(--mut);margin:2px 0 10px;line-height:1.5;}

    .color-row{display:flex;gap:10px;align-items:center;}
    .color-row input[type=color]{width:48px;height:42px;padding:2px;border:1px solid var(--bd);border-radius:9px;background:var(--panel-2);cursor:pointer;}
    .color-row input[type=text]{max-width:120px;}

    .logo-stage{display:flex;align-items:center;justify-content:center;min-height:90px;background:#0d0d12;border:1px dashed var(--bd);border-radius:12px;padding:16px;margin-bottom:10px;}
    #logoPreview{width:auto;max-width:100%;}

    .range{-webkit-appearance:none;appearance:none;width:100%;max-width:420px;height:6px;border-radius:99px;background:var(--bd);outline:none;}
    .range::-webkit-slider-thumb{-webkit-appearance:none;appearance:none;width:20px;height:20px;border-radius:50%;background:var(--acc);cursor:pointer;border:3px solid #0d0d12;box-shadow:0 0 0 1px var(--acc);}
    .range::-moz-range-thumb{width:20px;height:20px;border-radius:50%;background:var(--acc);cursor:pointer;border:3px solid #0d0d12;}

    .fav-row{display:flex;align-items:center;gap:14px;}
    .fav-preview{width:40px;height:40px;border-radius:9px;border:1px solid var(--bd);object-fit:cover;flex:none;background:#0d0d12;}
    .fav-empty{display:inline-block;}

    .seg{display:inline-flex;background:var(--panel-2);border:1px solid var(--bd);border-radius:10px;padding:3px;}
    .seg-opt{position:relative;}
    .seg-opt input{position:absolute;opacity:0;}
    .seg-opt span{display:block;padding:8px 18px;border-radius:8px;font-size:14px;color:var(--mut);cursor:pointer;transition:.2s;}
    .seg-opt input:checked + span{background:var(--acc);color:#1a1206;}

    .accent-stage{display:flex;align-items:center;gap:16px;flex-wrap:wrap;}
    .accent-swatch{width:130px;height:52px;border-radius:12px;border:1px solid var(--bd);}

    .maint-card{background:var(--panel);border:1px solid var(--bd);border-radius:14px;padding:22px;transition:border-color .2s,box-shadow .2s;}
    .maint-card.on{border-color:color-mix(in srgb,var(--acc) 55%,transparent);box-shadow:0 0 0 1px color-mix(in srgb,var(--acc) 25%,transparent);}
    .maint-head{display:flex;align-items:center;justify-content:space-between;gap:16px;}
    .maint-head h3{font-size:17px;}
    .toggle.big{transform:scale(1.15);}

    .page-seo-list{margin-top:26px;padding-top:22px;border-top:1px solid var(--bd);}
    .page-seo-list h3{font-size:15px;margin-bottom:4px;}
    .ps-row{display:flex;align-items:center;justify-content:space-between;gap:14px;background:var(--panel-2);border:1px solid var(--bd);border-radius:10px;padding:12px 14px;margin-top:10px;}
    .ps-info{display:flex;align-items:center;gap:10px;flex-wrap:wrap;font-size:14px;}
    .ps-info .mut{font-size:13px;}
    </style>

    <script>
    document.querySelectorAll('.tab').forEach(function (tab) {
        tab.addEventListener('click', function () {
            document.querySelectorAll('.tab').forEach(function (t) { t.classList.remove('active'); });
            document.querySelectorAll('.tab-panel').forEach(function (p) { p.classList.remove('active'); });
            tab.classList.add('active');
            document.querySelector('[data-panel="' + tab.dataset.tab + '"]').classList.add('active');
        });
    });

    var logoSlider = document.getElementById('logoSlider');
    var logoPreview = document.getElementById('logoPreview');
    var logoSizeVal = document.getElementById('logoSizeVal');
    if (logoSlider) {
        logoSlider.addEventListener('input', function () {
            logoSizeVal.textContent = logoSlider.value;
            if (logoPreview) logoPreview.style.height = logoSlider.value + 'px';
        });
    }
    var logoFile = document.getElementById('logoFile');
    if (logoFile) {
        logoFile.addEventListener('change', function () {
            var f = logoFile.files[0];
            if (!f || !logoPreview) return;
            logoPreview.src = URL.createObjectURL(f);
            logoPreview.style.display = '';
            var ph = document.getElementById('logoPlaceholder');
            if (ph) ph.style.display = 'none';
        });
    }
    var faviconFile = document.getElementById('faviconFile');
    if (faviconFile) {
        faviconFile.addEventListener('change', function () {
            var f = faviconFile.files[0];
            var prev = document.getElementById('favPreview');
            if (!f || !prev) return;
            if (prev.tagName === 'IMG') { prev.src = URL.createObjectURL(f); }
            else { prev.style.background = 'center/cover no-repeat url(' + URL.createObjectURL(f) + ')'; prev.classList.remove('fav-empty'); }
        });
    }

    var maintToggle = document.getElementById('maintToggle');
    if (maintToggle) {
        maintToggle.addEventListener('change', function () {
            document.getElementById('maintCard').classList.toggle('on', maintToggle.checked);
        });
    }

    function accentState() {
        var type = (document.querySelector('input[name=accent_type]:checked') || {}).value || 'solid';
        var c1 = document.querySelector('input[name=accent_color]').value;
        var c2El = document.querySelector('input[name=accent_color_2]');
        var angEl = document.querySelector('input[name=accent_angle]');
        var c2 = c2El ? c2El.value : c1;
        var ang = angEl ? angEl.value : 90;
        var grad = type === 'gradient' ? 'linear-gradient(' + ang + 'deg, ' + c1 + ', ' + c2 + ')' : c1;
        return { type: type, c1: c1, c2: c2, ang: ang, grad: grad };
    }
    function applyAccent() {
        var a = accentState();
        var root = document.documentElement.style;
        root.setProperty('--acc', a.c1);
        root.setProperty('--acc-2', a.c2);
        root.setProperty('--acc-grad', a.grad);
        var sw = document.getElementById('accentPreview');
        if (sw) sw.style.background = a.grad;
        var av = document.getElementById('angleVal');
        if (av) av.textContent = a.ang;
        document.querySelectorAll('[data-accent-hex]').forEach(function (t) {
            var c = t.closest('.color-row').querySelector('input[type=color]');
            if (c) t.value = c.value;
        });
        document.querySelectorAll('.grad-only').forEach(function (el) {
            el.style.display = a.type === 'gradient' ? '' : 'none';
        });
    }
    document.querySelectorAll('input[name=accent_type], [data-accent]').forEach(function (el) {
        el.addEventListener('input', applyAccent);
        el.addEventListener('change', applyAccent);
    });
    applyAccent();
    </script>
@endsection
