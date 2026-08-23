@php $v = fn ($k, $d = '') => $section->value($k, $d); @endphp
<div class="form-grid">
    <div class="fld full">
        <label>Heading</label>
        <input type="text" name="content[heading]" value="{{ $v('heading') }}" placeholder="Have a project in mind? Let's build it.">
    </div>
    <div class="fld">
        <label>Button label</label>
        <input type="text" name="content[button_label]" value="{{ $v('button_label') }}" placeholder="Book a free consultation">
    </div>
    <div class="fld">
        <label>Button URL</label>
        <input type="text" name="content[button_url]" value="{{ $v('button_url') }}" placeholder="/contact">
    </div>
</div>
