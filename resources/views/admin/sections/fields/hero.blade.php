@php $v = fn ($k, $d = '') => $section->value($k, $d); @endphp
<div class="form-grid">
    <div class="fld">
        <label>Badge label</label>
        <input type="text" name="content[badge_label]" value="{{ $v('badge_label') }}" placeholder="What's new">
    </div>
    <div class="fld">
        <label>Badge text</label>
        <input type="text" name="content[badge_text]" value="{{ $v('badge_text') }}" placeholder="Q3 case studies">
    </div>
    <div class="fld full">
        <label>Heading</label>
        <input type="text" name="content[heading]" value="{{ $v('heading') }}" placeholder="Software solutions, powered by engineering.">
    </div>
    <div class="fld full">
        <label>Subheading</label>
        <textarea name="content[subheading]" rows="2" placeholder="Short supporting line…">{{ $v('subheading') }}</textarea>
    </div>
    <div class="fld">
        <label>Primary button label</label>
        <input type="text" name="content[primary_label]" value="{{ $v('primary_label') }}" placeholder="Get a quote">
    </div>
    <div class="fld">
        <label>Primary button URL</label>
        <input type="text" name="content[primary_url]" value="{{ $v('primary_url') }}" placeholder="/contact">
    </div>
    <div class="fld">
        <label>Secondary button label</label>
        <input type="text" name="content[secondary_label]" value="{{ $v('secondary_label') }}" placeholder="Read more">
    </div>
    <div class="fld">
        <label>Secondary button URL</label>
        <input type="text" name="content[secondary_url]" value="{{ $v('secondary_url') }}" placeholder="/about">
    </div>

    <div class="fld full">
        <label>Hero image <span class="hint">(replaces the dashboard mock)</span></label>
        @if($v('image'))
            <div class="img-preview">
                <img src="{{ asset('storage/'.$v('image')) }}" alt="Hero image">
                <label class="checkline"><input type="checkbox" name="remove_image" value="1"> Remove current image</label>
            </div>
        @endif
        <input type="file" name="image" accept="image/*">
    </div>
</div>
