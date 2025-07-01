{{ $title }}
@foreach($fields as $field)
    @if($field->getLabel())
        <x-tablar-kit::forms.label :for="$field->getId()">
            {!! $field->getLabel() !!}
            @if($field->isRequired())
                <span class="text-danger">*</span>
            @endif
        </x-tablar-kit::forms.label>
    @endif
    {!! $field->render($globalConfig['data'] ?? null, $globalConfig) !!}
    @if($field->getHelp())
        <div class="form-text">{{ $field->getHelp() }}</div>
    @endif
    @error($field->getName())
    <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
@endforeach
