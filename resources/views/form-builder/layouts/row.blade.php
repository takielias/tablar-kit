<div class="row {{ $config['class'] ?? '' }}">
    @foreach($fields as $field)
        <div
            class="col-md-{{ method_exists($field, 'getColumnWidth') ? ($field->getColumnWidth() ?? (12 / count($fields))) : (12 / count($fields)) }}">
            <div class="mb-3">
                {!! $field->render($globalConfig['data'] ?? null, $globalConfig) !!}
            </div>
        </div>
    @endforeach
</div>


