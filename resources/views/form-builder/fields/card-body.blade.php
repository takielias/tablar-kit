@foreach($fields as $field)
    <div class="mb-3">
        @if(method_exists($field, 'getLabel') && $field->getLabel())
            <label for="{{ $field->getName() }}" class="form-label">
                {{ $field->getLabel() }}
                @if(method_exists($field, 'isRequired') && $field->isRequired())
                    <span class="text-danger">*</span>
                @endif
            </label>
        @endif

        @if(method_exists($field, 'render'))
            {!! $field->render($value[$field->getName()] ?? null, $globalConfig) !!}
        @else
            {!! $field !!}
        @endif

        @if(method_exists($field, 'getHelp') && $field->getHelp())
            <div class="form-text">{{ $field->getHelp() }}</div>
        @endif

        @error($field->getName())
        <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
@endforeach
