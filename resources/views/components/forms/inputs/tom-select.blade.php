<select name="{{ $name }}"
        {{ $attributes->merge(['class' => 'form-select']) }}
        id="{{ $id }}">
    @foreach($options() as $key => $option)
        <option value="{{$key}}" @selected($value == $key) >{{$option}}</option>
    @endforeach
</select>

@push('js')
    <script type="module">
        document.addEventListener("DOMContentLoaded", function () {
            let el = $('#{{$id}}');
            let customTomSelectOptions = @json($tomSelectOptions);

            let tomSelectOptions = {
                @if($remoteData)
                load: function (query, callback) {
                    const url = '{{ route($itemSearchRoute) }}?q=' + encodeURIComponent(query);
                    axios.get(url)
                        .then(response => {
                            if (response.data) {
                                console.log(response.data)
                                callback(response.data);
                            } else {
                                console.error('Unexpected response structure:', response.data);
                            }
                        })
                        .catch(error => {
                            console.error('Axios fetch error:', error);
                            callback();
                        });
                },
                onItemAdd: function () {
                    this.close();
                },
                @endif
                // ...other predefined options
            };

            // Merge customTomSelectOptions into tomSelectOptions
            let mergedOptions = {...tomSelectOptions, ...customTomSelectOptions};

            // Initialize TomSelect with merged options
            if (window.TomSelect) {
                new TomSelect(el, mergedOptions);
            }
        });
    </script>

@endpush
