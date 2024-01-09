<input
    name="{{ $name }}"
    type={{$type}}
    id="{{ $id }}"
    @if($value)value="{{ $value }}"@endif
    {{ $attributes->merge(['class' => config('tablar-kit.default-class')]) }}
/>

@once
    @push('css')
        <style>
            .filepond--panel {
                left: inherit;
            }
        </style>
    @endpush
@endonce
@push('js')
    <script type="module">
        document.addEventListener("DOMContentLoaded", function () {
            // Create the FilePond instance
            const inputElement = document.querySelector('input[name="{{$name}}"]');
            const pond = FilePond.create(inputElement, {
                server: {
                    url: "{{ config('tablar-kit.filepond.server.url') }}",
                    headers: {
                        'X-CSRF-TOKEN': "{{ @csrf_token() }}",
                    }
                },
                allowMultiple: false,
                credits: false,
                storeAsFile: true,
                @if($image_manipulation)
                imageEditEditor: {
                    open: (file, instructions) => {
                        console.log('Open editor', file, instructions);
                        openFileRobotImageEditor(file, instructions);
                    },
                    onconfirm: (output) => {
                        console.log('Confirm editor', output);
                        handleImageEditConfirm(output);
                    },
                    oncancel: () => {
                        console.log('Cancel editor');
                        handleImageEditCancel();
                    },
                    onclose: () => {
                        console.log('Close editor');
                        handleImageEditClose();
                    }
                }
                @endif
            });

            function openFileRobotImageEditor(file, instructions) {
                const imageURL = URL.createObjectURL(file);
                const config = {
                    source: imageURL,
                    onSave: (updatedImage) => {
                        confirmCallback(updatedImage);
                    },
                    annotationsCommon: {
                        fill: '#ff0000'
                    },
                    translations: {
                        profile: 'Profile',
                        coverPhoto: 'Cover photo',
                        facebook: 'Facebook',
                        socialMedia: 'Social Media',
                        fbProfileSize: '180x180px',
                        fbCoverPhotoSize: '820x312px',
                    },
                    Text: {
                        text: 'Add your text here',
                        font: 'inherit'
                    }, // Set font to inherit from the page body
                    Rotate: {
                        angle: instructions.rotation,
                        componentType: 'slider'
                    },
                    Crop: {
                        presetsItems: [
                            {
                                titleKey: 'classicTv',
                                descriptionKey: '4:3',
                                ratio: 4 / 3,
                                // icon: CropClassicTv, // optional, CropClassicTv is a React Function component. Possible (React Function component, string or HTML Element)
                            },
                            {
                                titleKey: 'cinemascope',
                                descriptionKey: '21:9',
                                ratio: 21 / 9,
                                // icon: CropCinemaScope, // optional, CropCinemaScope is a React Function component.  Possible (React Function component, string or HTML Element)
                            },
                        ],
                        presetsFolders: [
                            {
                                titleKey: 'socialMedia', // will be translated into Social Media as backend contains this translation key
                                // icon: Social, // optional, Social is a React Function component. Possible (React Function component, string or HTML Element)
                                groups: [
                                    {
                                        titleKey: 'facebook',
                                        items: [
                                            {
                                                titleKey: 'profile',
                                                width: 180,
                                                height: 180,
                                                descriptionKey: 'fbProfileSize',
                                            },
                                            {
                                                titleKey: 'coverPhoto',
                                                width: 820,
                                                height: 312,
                                                descriptionKey: 'fbCoverPhotoSize',
                                            },
                                        ],
                                    },
                                ],
                            },
                        ],
                    },
                    tabsIds: [
                        'Adjust',
                        'Annotate',
                        'Finetune',
                        'Watermark',
                        'Filters',
                        'Resize'
                    ],
                    defaultTabId: 'Adjust'
                };

                const editorContainer = document.createElement('div');
                editorContainer.id = 'editor_container';
                document.body.appendChild(editorContainer);

                const fileRobotImageEditor = new window.FileRobotImageEditor(editorContainer, config);

                const confirmCallback = (output) => {
                    console.log('Confirmed:', output);

                    const dataURL = output.imageBase64;
                    const file = dataURLToFile(dataURL, output.name);

                    // Add the file to FilePond
                    pond.addFiles([file]);

                    document.body.removeChild(editorContainer); // Remove the editor container
                };

                function dataURLToFile(dataURL, fileName) {
                    const arr = dataURL.split(',');
                    const mime = arr[0].match(/:(.*?);/)[1];
                    const fileExtension = mime.split('/')[1];
                    const updatedFileName = fileName + '.' + fileExtension;
                    const bstr = atob(arr[1]);
                    const n = bstr.length;
                    const u8arr = new Uint8Array(n);
                    for (let i = 0; i < n; i++) {
                        u8arr[i] = bstr.charCodeAt(i);
                    }
                    return new File([u8arr], updatedFileName, {type: mime});
                }

                const cancelCallback = () => {
                    console.log('Canceled');
                    document.body.removeChild(editorContainer); // Remove the editor container
                };

                const closeButton = document.createElement('button');
                closeButton.textContent = 'Close';
                closeButton.addEventListener('click', () => {
                    fileRobotImageEditor.onClose();
                });

                const buttonContainer = document.createElement('div');
                buttonContainer.appendChild(closeButton);

                editorContainer.appendChild(buttonContainer);

                fileRobotImageEditor.render({
                    onClose: (closingReason) => {
                        console.log('Closing reason', closingReason);
                        document.body.removeChild(editorContainer);
                        fileRobotImageEditor.terminate();
                    },
                });
            }

            function handleImageEditConfirm(output) {
                console.log('Image edit confirmed:', output);
                // Handle the confirmed output here
            }

            function handleImageEditCancel() {
                console.log('Image edit canceled');
                // Handle the canceled edit here
                document.body.removeChild(editorContainer); // Remove the editor container
            }

            function handleImageEditClose() {
                console.log('Image editor closed');
                // Handle the editor close here
            }


            // Query all elements with the .filepond--root class
            const filepondRootElements = document.querySelectorAll('.filepond--root');
            // Iterate over the NodeList and remove the 'position' attribute
            filepondRootElements.forEach(function (element) {
                element.style.position = 'absolute'; // This will remove the inline style if set
                element.style.backgroundColor = '#ffffff'; // This will remove the inline style if set
                element.style.fontSize = 'inherit'; // This will remove the inline style if set
            });

        });
    </script>
@endpush
