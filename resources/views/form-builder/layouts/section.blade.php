{{ $title }}
@foreach($fields as $field)
    {!! $field->render($globalConfig['data'] ?? null, $globalConfig) !!}
@endforeach
