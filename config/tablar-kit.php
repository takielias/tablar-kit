<?php

return [

    /* --------------------------------------------------------------------------------------------
    * Tablar Kit Components
    * --------------------------------------------------------------------------------------------
    * This section lists all components available in the Tablar Kit package. You can enable or
    * disable components by including or excluding them from this list, thus customizing the
    * components available in your application.
    */

    'components' => [
        'alert' => Takielias\TablarKit\Components\Alerts\Alert::class,
        'button' => Takielias\TablarKit\Components\Buttons\Button::class,
        'card' => Takielias\TablarKit\Components\Cards\Card::class,
        'card-header' => Takielias\TablarKit\Components\Cards\CardHeader::class,
        'card-body' => Takielias\TablarKit\Components\Cards\CardBody::class,
        'card-footer' => Takielias\TablarKit\Components\Cards\CardFooter::class,
        'card-stamp' => Takielias\TablarKit\Components\Cards\CardStamp::class,
        'card-button' => Takielias\TablarKit\Components\Cards\CardButton::class,
        'card-ribbon' => Takielias\TablarKit\Components\Cards\CardRibbon::class,
        'checkbox' => Takielias\TablarKit\Components\Forms\Inputs\Checkbox::class,
        'filepond' => Takielias\TablarKit\Components\Forms\Inputs\FilePond::class,
        'radio' => Takielias\TablarKit\Components\Forms\Inputs\Radio::class,
        'tabulator' => Takielias\TablarKit\Components\Table\Tabulator::class,
        'toggle' => Takielias\TablarKit\Components\Forms\Inputs\Toggle::class,
        'select' => Takielias\TablarKit\Components\Forms\Inputs\Select::class,
        'dependent-select' => Takielias\TablarKit\Components\Forms\Inputs\DependentSelect::class,
        'tom-select' => Takielias\TablarKit\Components\Forms\Inputs\TomSelect::class,
        'email' => Takielias\TablarKit\Components\Forms\Inputs\Email::class,
        'error' => Takielias\TablarKit\Components\Forms\Error::class,
        'lite-picker' => Takielias\TablarKit\Components\Forms\Inputs\LitePicker::class,
        'flat-picker' => Takielias\TablarKit\Components\Forms\Inputs\FlatPicker::class,
        'form' => Takielias\TablarKit\Components\Forms\Form::class,
        'form-button' => Takielias\TablarKit\Components\Buttons\FormButton::class,
        'input' => Takielias\TablarKit\Components\Forms\Inputs\Input::class,
        'label' => Takielias\TablarKit\Components\Forms\Label::class,
        'logout' => Takielias\TablarKit\Components\Buttons\Logout::class,
        'password' => Takielias\TablarKit\Components\Forms\Inputs\Password::class,
        'textarea' => Takielias\TablarKit\Components\Forms\Inputs\Textarea::class,
        'jodit' => Takielias\TablarKit\Components\Editors\Jodit::class,
    ],


    'default-class' => 'form-control',

    /*
    *--------------------------------------------------------------------------
    * Components Prefix
    *--------------------------------------------------------------------------
    * This value will set a prefix for all Tablar Kit components.
    * By default it's empty. This is useful if you want to avoid
    * collision with components from other libraries.
    * If set with "tablar", for example, you can reference components like:
    * <x-tablar-filepond />
    */

    'prefix' => '',

    /* --------------------------------------------------------------------------------------------
    * Jodit Editor Configuration
    * --------------------------------------------------------------------------------------------
    * Specific settings for the Jodit Editor component, including root directories, middleware,
    * file types, and upload actions.
    */

    'root' => 'filebrowser',

    /*
     * Root directory name, without spaces
     */

    'root_name' => 'default',


    'middleware' => [
        'web', 'auth',
    ],

    /*
     * digits after decimal point in file size
     */

    'file_size_accuracy' => 3,

    /*
     * Allowed to upload Image types
     */

    'mimetypes' => [
        'images' => [
            'jpeg',
            'jpg',
            'gif',
            'png',
            'bmp',
            'svg'
        ]
    ],

    'cache' => [
        'key' => 'filebrowser',

        'duration' => 3600,
    ],

    'duplicate_file' => true,

    /*
     * List of file types that jodit editor renames on upload
     */

    'jodit_broken_extension' => explode(
        ',',
        env('JODIT_BROKEN_EXTENSION', 'vnd,plain,msword')
    ),

    'thumb' => [
        'dir_url' => env('APP_URL') . '/assets/images/jodit/',

        'mask' => 'thumb-%s.svg',

        'unknown_extension' => 'thumb-unknown.svg',

        /*
        * In case the icons are located on another server
        */

        'exists' => explode(
            ',',
            ''
        ),
    ],


    /*
     * Directory nesting limit
     */

    'nesting_limit' => 3,

    'upload_actions' => [
        Takielias\TablarKit\Actions\FileUploadAction::class,
    ],

    'file_actions' => [
        Takielias\TablarKit\Actions\Files::class,
        Takielias\TablarKit\Actions\FileRename::class,
        Takielias\TablarKit\Actions\FileMove::class,
        Takielias\TablarKit\Actions\FileRemove::class,
        Takielias\TablarKit\Actions\Folders::class,
        Takielias\TablarKit\Actions\FolderCreate::class,
        Takielias\TablarKit\Actions\FolderMove::class,
        Takielias\TablarKit\Actions\FolderRemove::class,
        Takielias\TablarKit\Actions\FolderRename::class,
        Takielias\TablarKit\Actions\Permissions::class,
    ],


    /* --------------------------------------------------------------------------------------------
    * FilePond Configuration
    * --------------------------------------------------------------------------------------------
    * Configuration for the FilePond file upload library, including server URLs, disk settings,
    * and temporary folder configurations.
    */

    'filepond' => [
        'server' => [
            'url' => env('FILEPOND_URL', '/filepond/upload'),
        ],
        'disk' => env('FILEPOND_DISK', 'public'),
        'temp_disk' => 'local',
        'temp_folder' => 'filepond/temp',
        'controller' => Takielias\TablarKit\Http\Controllers\FilePondController::class,
    ],

];
