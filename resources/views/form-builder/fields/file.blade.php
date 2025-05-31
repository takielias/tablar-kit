<div class="mb-3">
    @if($field->getLabel())
        <label for="{{ $field->getId() }}" class="form-label">
            {{ $field->getLabel() }}
            @if($field->isRequired())
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <input type="file"
           name="{{ $field->getName() }}{{ $multiple ? '[]' : '' }}"
           id="{{ $field->getId() }}"
           class="form-control @error($field->getName()) is-invalid @enderror"
           @if($acceptedTypes) accept="{{ is_array($acceptedTypes) ? implode(',', $acceptedTypes) : $acceptedTypes }}"
           @endif
           @if($multiple) multiple @endif
        {!! $attributes !!} />

    @if($field->getHelp())
        <div class="form-text">{{ $field->getHelp() }}</div>
    @endif

    @if($maxSize)
        <small class="text-muted">Maximum file size: {{ $maxSize }}</small>
    @endif

    @error($field->getName())
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
