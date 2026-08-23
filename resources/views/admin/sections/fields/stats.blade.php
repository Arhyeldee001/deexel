@php $v = fn ($k, $d = '') => $section->value($k, $d); $items = $section->value('items', []); @endphp
<div class="form-grid">
    <label class="full sub-label">Stat items</label>
    <div class="repeat-wrap full">
        <div class="repeat" data-repeat data-next="{{ count($items) }}">
            @foreach($items as $i => $item)
                <div class="repeat-row" data-repeat-row>
                    <input type="text" name="content[items][{{ $i }}][value]" value="{{ $item['value'] ?? '' }}" placeholder="120+">
                    <input type="text" name="content[items][{{ $i }}][label]" value="{{ $item['label'] ?? '' }}" placeholder="Projects delivered">
                    <button type="button" class="icon-btn danger" data-repeat-remove>✕</button>
                </div>
            @endforeach
        </div>
        <template data-repeat-template>
            <div class="repeat-row" data-repeat-row>
                <input type="text" name="content[items][__i__][value]" placeholder="120+">
                <input type="text" name="content[items][__i__][label]" placeholder="Projects delivered">
                <button type="button" class="icon-btn danger" data-repeat-remove>✕</button>
            </div>
        </template>
        <button type="button" class="btn-sm" data-repeat-add>+ Add stat</button>
    </div>
</div>
