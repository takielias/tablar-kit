<form {!! collect($attributes)->map(fn($value, $key) => "{$key}=\"{$value}\"")->implode(' ') !!}>
    @csrf
    @if(in_array($attributes['method'] ?? 'POST', ['PUT', 'PATCH', 'DELETE']))
        @method($attributes['method'])
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
                        {{ $tab->title }}
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

    @elseif(count($sections) > 0)
        {{-- Render sections --}}
        @foreach($sections as $section)
            {!! $section->render($config) !!}
        @endforeach

    @else
        {{-- Render regular fields --}}
        @foreach($fields as $field)
            @if(method_exists($field, 'render'))
                {!! $field->render($data[$field->getName()] ?? null, $config) !!}
            @else
                {!! $field !!}
            @endif
        @endforeach
    @endif
</form>
