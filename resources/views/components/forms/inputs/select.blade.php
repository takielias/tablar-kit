<select name="{{ $name }}"
        {{ $attributes->merge(['class' => 'form-select']) }}
        id="{{ $id }}">
    @if($placeholder)
        <option value="" disabled selected>{{$placeholder}}</option>
    @endif
    @foreach($options() as $key => $option)
        <option value="{{$key}}" @selected($value == $key) >{{$option}}</option>
    @endforeach
</select>

