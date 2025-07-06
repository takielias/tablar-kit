@php
    if (!isset($attributes['method'])) {
        $attributes['method'] = 'POST';
    }
    $originalMethod = $attributes['method'];
    if (!in_array($originalMethod, ['PUT', 'PATCH', 'DELETE'])) {
        $method = $originalMethod;
    } else {
        $method = 'POST';
    }
    unset($attributes['method']);
@endphp


<form {!! collect($attributes)->map(fn($value, $key) => "{$key}=\"{$value}\"")->implode(' ') !!}
      method="{{$method}}">
    @csrf
    @if(in_array($originalMethod, ['PUT', 'PATCH', 'DELETE']))
        @method($originalMethod)
    @endif

    @if(count($tabs) > 0)
        {{-- Render tabs --}}
        <ul class="nav nav-tabs" role="tablist">
            @foreach($tabs as $index => $tab)
                <li class="nav-item" role="presentation">
                    <button class="nav-link @if($index === 0) active @endif"
                            id="tab-{{ $index }}"
                            data-bs-toggle="tab"
                            data-bs-target="#tab-content-{{ $index }}"
                            type="button"
                            role="tab">
                        {{ $tab->getTitle() }}
                    </button>
                </li>
            @endforeach
        </ul>
        <div class="tab-content">
            @foreach($tabs as $index => $tab)
                <div class="tab-pane fade @if($index === 0) show active @endif"
                     id="tab-content-{{ $index }}"
                     role="tabpanel">
                    {!! $tab->render($config) !!}
                </div>
            @endforeach
        </div>
    @endif

    @if(count($sections) > 0)
        {{-- Render sections --}}
        @foreach($sections as $section)
            {!! $section->render($config) !!}
        @endforeach

    @endif
    {{-- Render regular fields --}}
    @if(count($fields) > 0)
        @foreach($fields as $field)
            <div class="{{$field->getTopGap()}}">
                @if(method_exists($field, 'render'))
                    @if($field->hasLabel() && !$form->hasCard())
                        <x-tablar-kit::forms.label :for="$field->getId()">
                            {!! $field->getLabel() !!}
                            @if($field->isRequired())
                                <span class="text-danger">*</span>
                            @endif
                        </x-tablar-kit::forms.label>
                    @endif
                    {!! $field->render($data[$field->getName()] ?? null, $config) !!}
                    @if($field->getHelp())
                        <div class="form-text">{{ $field->getHelp() }}</div>
                    @endif
                    @error($field->getName())
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                @else
                    {!! $field !!}
                @endif
            </div>
        @endforeach
    @endif

</form>
