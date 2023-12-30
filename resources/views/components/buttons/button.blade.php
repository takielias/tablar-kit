<div class="d-flex">
    <button type="{{$type}}"
        {{ $attributes->merge(['class' => 'btn btn-primary ms-auto']) }}
    >
        @if($value)
            {{ $value }}
        @else
            {{ $slot }}
        @endif
    </button>
</div>
