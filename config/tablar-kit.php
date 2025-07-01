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
        'alert' => TakiElias\TablarKit\Components\Alerts\Alert::class,
        'button' => TakiElias\TablarKit\Components\Buttons\Button::class,
        'card' => TakiElias\TablarKit\Components\Cards\Card::class,
        'card-header' => TakiElias\TablarKit\Components\Cards\CardHeader::class,
        'card-body' => TakiElias\TablarKit\Components\Cards\CardBody::class,
        'card-footer' => TakiElias\TablarKit\Components\Cards\CardFooter::class,
        'card-stamp' => TakiElias\TablarKit\Components\Cards\CardStamp::class,
        'card-button' => TakiElias\TablarKit\Components\Cards\CardButton::class,
        'card-ribbon' => TakiElias\TablarKit\Components\Cards\CardRibbon::class,
        'checkbox' => TakiElias\TablarKit\Components\Forms\Inputs\Checkbox::class,
        'filepond' => TakiElias\TablarKit\Components\Forms\Inputs\FilePond::class,
        'radio' => TakiElias\TablarKit\Components\Forms\Inputs\Radio::class,
        'tabulator' => TakiElias\TablarKit\Components\Table\Tabulator::class,
        'toggle' => TakiElias\TablarKit\Components\Forms\Inputs\Toggle::class,
        'select' => TakiElias\TablarKit\Components\Forms\Inputs\Select::class,
        'dependent-select' => TakiElias\TablarKit\Components\Forms\Inputs\DependentSelect::class,
        'tom-select' => TakiElias\TablarKit\Components\Forms\Inputs\TomSelect::class,
        'email' => TakiElias\TablarKit\Components\Forms\Inputs\Email::class,
        'modal' => TakiElias\TablarKit\Components\Modals\Modal::class,
        'modal-form' => TakiElias\TablarKit\Components\Modals\ModalForm::class,
        'error' => TakiElias\TablarKit\Components\Forms\Error::class,
        'lite-picker' => TakiElias\TablarKit\Components\Forms\Inputs\LitePicker::class,
        'flat-picker' => TakiElias\TablarKit\Components\Forms\Inputs\FlatPicker::class,
        'form' => TakiElias\TablarKit\Components\Forms\Form::class,
        'form-button' => TakiElias\TablarKit\Components\Buttons\FormButton::class,
        'input' => TakiElias\TablarKit\Components\Forms\Inputs\Input::class,
        'label' => TakiElias\TablarKit\Components\Forms\Label::class,
        'logout' => TakiElias\TablarKit\Components\Buttons\Logout::class,
        'password' => TakiElias\TablarKit\Components\Forms\Inputs\Password::class,
        'textarea' => TakiElias\TablarKit\Components\Forms\Inputs\Textarea::class,
        'jodit' => TakiElias\TablarKit\Components\Editors\Jodit::class,
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
        TakiElias\TablarKit\Actions\FileUploadAction::class,
    ],

    'file_actions' => [
        TakiElias\TablarKit\Actions\Files::class,
        TakiElias\TablarKit\Actions\FileRename::class,
        TakiElias\TablarKit\Actions\FileMove::class,
        TakiElias\TablarKit\Actions\FileRemove::class,
        TakiElias\TablarKit\Actions\Folders::class,
        TakiElias\TablarKit\Actions\FolderCreate::class,
        TakiElias\TablarKit\Actions\FolderMove::class,
        TakiElias\TablarKit\Actions\FolderRemove::class,
        TakiElias\TablarKit\Actions\FolderRename::class,
        TakiElias\TablarKit\Actions\Permissions::class,
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
        'controller' => TakiElias\TablarKit\Http\Controllers\FilePondController::class,
    ],

];
