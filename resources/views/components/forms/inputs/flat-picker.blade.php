<input
    name="{{ $name }}"
    type="text"
    id="{{ $id }}"
    placeholder="{{ $placeholder }}"
    @if($value) value="{{ $value }}" @endif
    {{ $attributes->merge(['class' => config('tablar-kit.default-class'). ' flatpickr']) }}
/>

@once
    @push('js')
        <script type="module">
            document.addEventListener('DOMContentLoaded', function () {
                window.flatpickr(".flatpickr");
            });
        </script>
    @endpush
    @push('css')
        <style>

        </style>
    @endpush
@endonce
