<div class="col-md-{{ $config['width'] ?? 12 }} {{ $config['class'] ?? '' }}">
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
                {{-- Pass the full data array to nested fields --}}
                @php $field->setData($data)@endphp
                {!! $field->render($data[$field->getName()] ?? null, $globalConfig) !!}
            @else
                {!! $field !!}
            @endif
            @if($field->getHelp())
                <div class="form-text">{{ $field->getHelp() }}</div>
            @endif
            @if(method_exists($field, 'getName'))
                @error($field->getName())
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            @endif
        </div>
    @endforeach
</div>
