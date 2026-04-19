@php
    $id = $attributes['id'] ?? $field->getName() . '_' . uniqid();
    $attributes['id'] = $id;
    $attrString = collect($attributes)
        ->map(fn($v, $k) => is_bool($v) ? ($v ? $k : '') : $k . '="' . e($v) . '"')
        ->filter()
        ->implode(' ');
@endphp

<textarea {!! $attrString !!}>{{ old($field->getName(), $value) }}</textarea>

@push('js')
    <script type="module">
        (function () {
            if (typeof window.Jodit === 'undefined') {
                console.warn('[tablar-kit] Jodit is not loaded. Import vendor/takielias/tablar-kit/resources/js/plugins/jodit-editor.js in tabler-init.js.');
                return;
            }
            const options = @json($options, JSON_UNESCAPED_SLASHES);
            window.Jodit.make('#{{ $id }}', options);
        })();
    </script>
@endpush
