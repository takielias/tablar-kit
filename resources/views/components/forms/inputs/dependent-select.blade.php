<select name="{{ $name }}"
        {{ $attributes->merge(['class' => 'form-select']) }}
        id="{{ $id }}"
        @if($targetDropdown!=null)
            data-child="{{$targetDropdown}}"
    @endif
>
    @if($placeholder)
        <option value="" disabled selected>{{$placeholder}}</option>
    @endif
    @foreach($options() as $key => $option)
        <option value="{{$key}}" @selected($value == $key) >{{$option}}</option>
    @endforeach
</select>

@push('js')
    <script type="module">
        document.addEventListener('DOMContentLoaded', () => {
            const mainSelect = document.getElementById('{{ $id }}');
            @if($targetDropdown!=null)
            const targetSelect = document.getElementById('{{ $targetDropdown }}');
            @endif
            @if($targetDropdown!=null)
            mainSelect.addEventListener('change', () => {
                @if($targetDataRoute!=null)
                targetSelect.dispatchEvent(new CustomEvent('optionsRemoved'));
                @endif
                axios.get(`{{ route($targetDataRoute) }}`, {
                    params: {id: mainSelect.value}
                })
                    .then(response => {
                        // The response data is now an object, not an array
                        const products = response.data;
                        Object.entries(products).forEach(([id, value]) => {
                            const opt = document.createElement('option');
                            opt.value = id;
                            opt.textContent = value;
                            targetSelect.appendChild(opt);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                    });
            });
            targetSelect.addEventListener('optionsRemoved', function () {
                while (this.options.length > 0) {
                    this.remove(0);
                }
                const childId = this.dataset.child;
                if (childId) {
                    const childSelect = document.getElementById(childId);
                    childSelect.dispatchEvent(new CustomEvent('optionsRemoved'));
                }
            });
            @endif
        });
    </script>
@endpush
