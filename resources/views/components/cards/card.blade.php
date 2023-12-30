@props(['type' => 'info'])
<div {{ $attributes->merge(['class' => 'card ']) }}>

    @isset($stamp)
        <x-card-stamp>
            {{$stamp}}
        </x-card-stamp>
    @endisset

    @isset($ribbon)
        {{$ribbon}}
    @endisset

    @isset($header)
        <x-card-header>
            {{$header}}
        </x-card-header>
    @endisset

    @isset($body)
        <x-card-body>
            {{$body}}
        </x-card-body>
    @endisset

    @isset($footer)
        <x-card-footer>
            {{$footer}}
        </x-card-footer>
    @endisset

</div>
