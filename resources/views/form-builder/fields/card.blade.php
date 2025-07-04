<div class="card">
    @if($title)
        <div class="card-header">
            <h3 class="card-title">{{ $title }}</h3>
        </div>
    @endif

    <div class="card-body">
        @foreach($fields as $field)
            <div class="mb-3">
                @if(method_exists($field, 'render'))
                    @if($field->hasLabel())
                        <x-tablar-kit::forms.label :for="$field->getId()">
                            {!! $field->getLabel() !!}
                            @if($field->isRequired())
                                <span class="text-danger">*</span>
                            @endif
                        </x-tablar-kit::forms.label>
                    @endif
                    {!! $field->render($value[$field->getName()] ?? null, $globalConfig) !!}
                @else
                    {!! $field !!}
                @endif

                @if($field->getHelp())
                    <div class="form-text">{{ $field->getHelp() }}</div>
                @endif

                @error($field->getName())
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        @endforeach
    </div>
</div>
