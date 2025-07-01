<div class="tab-pane {{ $config['active'] ?? false ? 'active' : '' }}" id="tab-{{ Str::slug($title) }}" role="tabpanel">
    <div class="card-body">
        @foreach($fields as $field)
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
        @endforeach
    </div>
</div>
