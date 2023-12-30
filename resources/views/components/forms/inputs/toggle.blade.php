<label class="form-check form-switch">
    <input
        name="{{ $name }}"
        {{ $attributes->merge(['class' => 'form-check-input']) }}
        type="checkbox"
        {{ $checked ? 'checked' : '' }}
        id="{{ $id }}"
        @if($value)value="{{ $value }}"@endif><span class="form-check-label">{{$label??''}}</span>
</label>
