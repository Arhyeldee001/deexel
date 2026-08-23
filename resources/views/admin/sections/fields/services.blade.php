@php $v = fn ($k, $d = '') => $section->value($k, $d); @endphp
<div class="form-grid">
    <div class="fld full">
        <label>Heading</label>
        <input type="text" name="content[heading]" value="{{ $v('heading') }}" placeholder="What we do">
    </div>
    <div class="fld full">
        <label>Subheading</label>
        <textarea name="content[subheading]" rows="2">{{ $v('subheading') }}</textarea>
    </div>
    <div class="fld">
        <label>How many services to show</label>
        <input type="number" name="content[limit]" value="{{ $v('limit', 6) }}" min="1" max="24">
    </div>
    <p class="muted full">The service cards themselves are managed in the <strong>Services</strong> module.</p>
</div>
