<select name="{{ $name }}"
        {{ $attributes->merge(['class' => 'form-select']) }}
        id="{{ $id }}">
    @if($placeholder)
        <option value="" disabled selected>{{$placeholder}}</option>
    @endif
    @php
        $optionsList = null;
        if (function_exists('options') || (isset($options) && is_callable($options))) {
            $optionsList = $options();
        } elseif (isset($options)) {
            $optionsList = $options;
        }
    @endphp
    @foreach($optionsList as $key => $option)
        <option value="{{$key}}" @selected($value == $key) >{{$option}}</option>
    @endforeach
</select>
<div class="invalid-feedback"></div>

