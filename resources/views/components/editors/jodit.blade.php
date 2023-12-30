<textarea
    name="{{ $name }}"
    id="{{ $id }}"
    {{ $attributes }}
>{{ old($name, $slot) }}</textarea>

@php($path = $options['path']??'')
@php($source = $options['source']??'default')

@push('js')

    <script type="module">
        window.Jodit.make('#{{$id}}', {
            height: 400,
            uploader: {
                url: '{{route('jodit.upload')}}',
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}',
                },

                prepareData: function (formdata) {
                    formdata.append('path', '{{$path}}');
                },
            },
            filebrowser: {
                ajax: {
                    contentType: 'application/json',
                    // processData: true,
                    url: '{{route('jodit.browse')}}', // this parameter is required
                    prepareData: function (data) {
                        data.source = '{{$source}}';
                        data.path = '{{$path}}';
                        data._token = '{{csrf_token()}}';
                        return data;
                    },
                    withCredentials: true,
                    method: 'POST'
                }
            }
        });
    </script>

@endpush
