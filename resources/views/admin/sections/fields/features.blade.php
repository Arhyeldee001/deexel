@php
    $v = fn ($k, $d = '') => $section->value($k, $d);
    $checks = $section->value('checks', []);
    $rows = $section->value('rows', []);
    $icons = ['layers', 'chat', 'rocket', 'shield', 'code', 'cloud', 'design', 'mobile', 'chart'];
@endphp
<div class="form-grid">
    <div class="fld full">
        <label>Section heading</label>
        <input type="text" name="content[heading]" value="{{ $v('heading') }}" placeholder="Powerful capabilities">
    </div>
    <div class="fld full">
        <label>Section subheading</label>
        <textarea name="content[subheading]" rows="2">{{ $v('subheading') }}</textarea>
    </div>
    <div class="fld full">
        <label>Left column heading</label>
        <input type="text" name="content[left_heading]" value="{{ $v('left_heading') }}">
    </div>
    <div class="fld full">
        <label>Left column body</label>
        <textarea name="content[left_body]" rows="2">{{ $v('left_body') }}</textarea>
    </div>
    <div class="fld">
        <label>Left button label</label>
        <input type="text" name="content[left_cta_label]" value="{{ $v('left_cta_label') }}">
    </div>
    <div class="fld">
        <label>Left button URL</label>
        <input type="text" name="content[left_cta_url]" value="{{ $v('left_cta_url') }}">
    </div>

    <label class="full sub-label">Checklist items</label>
    <div class="repeat-wrap full">
        <div class="repeat" data-repeat data-next="{{ count($checks) }}">
            @foreach($checks as $check)
                <div class="repeat-row" data-repeat-row>
                    <input type="text" name="content[checks][]" value="{{ $check }}" placeholder="Custom architecture for every project.">
                    <button type="button" class="icon-btn danger" data-repeat-remove>✕</button>
                </div>
            @endforeach
        </div>
        <template data-repeat-template>
            <div class="repeat-row" data-repeat-row>
                <input type="text" name="content[checks][]" placeholder="Another selling point…">
                <button type="button" class="icon-btn danger" data-repeat-remove>✕</button>
            </div>
        </template>
        <button type="button" class="btn-sm" data-repeat-add>+ Add checklist item</button>
    </div>

    <label class="full sub-label">Feature rows (in the glass card)</label>
    <div class="repeat-wrap full">
        <div class="repeat" data-repeat data-next="{{ count($rows) }}">
            @foreach($rows as $i => $row)
                <div class="repeat-row wide" data-repeat-row>
                    <select name="content[rows][{{ $i }}][icon]">
                        @foreach($icons as $ic)
                            <option value="{{ $ic }}" {{ ($row['icon'] ?? '') === $ic ? 'selected' : '' }}>{{ $ic }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="content[rows][{{ $i }}][title]" value="{{ $row['title'] ?? '' }}" placeholder="Discovery">
                    <input type="text" name="content[rows][{{ $i }}][text]" value="{{ $row['text'] ?? '' }}" placeholder="Scope, goals, and a clear plan.">
                    <button type="button" class="icon-btn danger" data-repeat-remove>✕</button>
                </div>
            @endforeach
        </div>
        <template data-repeat-template>
            <div class="repeat-row wide" data-repeat-row>
                <select name="content[rows][__i__][icon]">
                    @foreach($icons as $ic)
                        <option value="{{ $ic }}">{{ $ic }}</option>
                    @endforeach
                </select>
                <input type="text" name="content[rows][__i__][title]" placeholder="Title">
                <input type="text" name="content[rows][__i__][text]" placeholder="Short description">
                <button type="button" class="icon-btn danger" data-repeat-remove>✕</button>
            </div>
        </template>
        <button type="button" class="btn-sm" data-repeat-add>+ Add feature row</button>
    </div>
</div>
