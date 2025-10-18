<label class="form-check">
    <input
        name="{{ $name }}"
        type="radio"
        id="{{ $id }}"
        @if($value)value="{{ $value }}"@endif
        {{ $checked ? 'checked' : '' }}
        {{ $attributes->merge(['class' => 'form-check-input']) }}
    />
    <div class="invalid-feedback"></div>
    <span class="form-check-label">{{$label}}</span>
</label>
