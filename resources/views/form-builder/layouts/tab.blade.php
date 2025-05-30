<div class="tab-pane {{ $config['active'] ?? false ? 'active' : '' }}" id="tab-{{ Str::slug($title) }}" role="tabpanel">
    <div class="card-body">
        @foreach($fields as $field)
            <div class="mb-3">
                {!! $field->render($globalConfig['data'] ?? null, $globalConfig) !!}
            </div>
        @endforeach
    </div>
</div>
