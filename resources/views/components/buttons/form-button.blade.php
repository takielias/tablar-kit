<form method="POST" @isset($action) action="{{ $action }}" @endisset>
    @csrf
    @method($method)

    <button type="submit" {{ $attributes->merge(['class' => 'btn btn-primary']) }}>
        {{ $slot }}
    </button>
</form>
