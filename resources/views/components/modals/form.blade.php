@props([
    'id' => 'modal-form',
    'action' => '',
    'method' => 'POST'
])

<form action="{{ $action }}" method="{{ $method === 'GET' ? 'GET' : 'POST' }}">
    @csrf
    @if(!in_array($method, ['GET', 'POST']))
        @method($method)
    @endif

    <x-modal :id="$id" {{ $attributes }}>
        {{ $slot }}
    </x-modal>
</form>
