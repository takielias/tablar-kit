<div class="row {{ $config['class'] ?? '' }}">
    @foreach($fields as $field)
        <div
            class="col-md-{{ method_exists($field, 'getColumnWidth') ? ($field->getColumnWidth() ?? (12 / count($fields))) : (12 / count($fields)) }}">
            <div class="mb-3">
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
            </div>
        </div>
    @endforeach
</div>


