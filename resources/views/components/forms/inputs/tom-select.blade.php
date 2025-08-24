<select name="{{ $name }}"
        {{ $attributes->merge(['class' => 'form-select']) }}
        id="{{ $id }}">
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

@push('js')
    <script type="module">
        document.addEventListener("DOMContentLoaded", function () {
            let el = $('#{{$id}}');
            let customTomSelectOptions = @json($tomSelectOptions);

            let tomSelectOptions = {
                plugins: {
                    remove_button:{
                        title:'Remove this item',
                    }
                },
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
            };

            // Merge customTomSelectOptions into tomSelectOptions
            let mergedOptions = {...tomSelectOptions, ...customTomSelectOptions};

            // Initialize TomSelect with merged options
            if (window.TomSelect) {
                new window.TomSelect(el, mergedOptions);
            }
        });
    </script>

@endpush
