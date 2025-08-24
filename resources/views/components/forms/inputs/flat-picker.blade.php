<input
    name="{{ $name }}"
    type="text"
    id="{{ $id }}"
    placeholder="{{ $placeholder }}"
    @if($value) value="{{ $value }}" @endif
    {{ $attributes->merge(['class' => config('tablar-kit.default-class'). ' flatpickr']) }}
/>
<div class="invalid-feedback"></div>
@push('js')
    <script type="module">
        document.addEventListener('DOMContentLoaded', function () {
            const optional_config = @if(isset($jsonOptions)) {!! $jsonOptions !!}@endif;
            window.flatpickr("#{{$id}}", optional_config)
        });
    </script>
@endpush
