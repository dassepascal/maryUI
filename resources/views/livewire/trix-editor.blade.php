<div>
    @if($label)
        <label class="label">{{ $label }}</label>
    @endif
    <div wire:ignore>
        <input id="{{ $trixId }}" type="hidden" name="content" value="{{ $content }}">
        <trix-editor input="{{ $trixId }}" wire:model.debounce.500ms="content" class="trix-content"></trix-editor>
    </div>
    @if($hint)
        <label class="label-text-alt">{{ $hint }}</label>
    @endif
</div>

<script>
    var trixEditor = document.querySelector("#{{ $trixId }}").nextElementSibling;
    trixEditor.addEventListener("trix-change", function(event) {
        @this.set('content', event.target.innerHTML);
    });
</script>