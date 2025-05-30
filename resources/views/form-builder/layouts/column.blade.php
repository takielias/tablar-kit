<div class="col-md-{{ $config['width'] ?? 12 }} {{ $config['class'] ?? '' }}">
    @foreach($fields as $field)
        <div class="mb-3">
            {!! $field->render($globalConfig['data'] ?? null, $globalConfig) !!}
        </div>
    @endforeach
</div>
