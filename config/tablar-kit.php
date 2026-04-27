<?php

use TakiElias\TablarKit\Actions\FileMove;
use TakiElias\TablarKit\Actions\FileRemove;
use TakiElias\TablarKit\Actions\FileRename;
use TakiElias\TablarKit\Actions\Files;
use TakiElias\TablarKit\Actions\FileUploadAction;
use TakiElias\TablarKit\Actions\FolderCreate;
use TakiElias\TablarKit\Actions\FolderMove;
use TakiElias\TablarKit\Actions\FolderRemove;
use TakiElias\TablarKit\Actions\FolderRename;
use TakiElias\TablarKit\Actions\Folders;
use TakiElias\TablarKit\Actions\Permissions;
use TakiElias\TablarKit\Components\Alerts\Alert;
use TakiElias\TablarKit\Components\Buttons\Button;
use TakiElias\TablarKit\Components\Buttons\FormButton;
use TakiElias\TablarKit\Components\Buttons\Logout;
use TakiElias\TablarKit\Components\Cards\Card;
use TakiElias\TablarKit\Components\Cards\CardBody;
use TakiElias\TablarKit\Components\Cards\CardButton;
use TakiElias\TablarKit\Components\Cards\CardFooter;
use TakiElias\TablarKit\Components\Cards\CardHeader;
use TakiElias\TablarKit\Components\Cards\CardRibbon;
use TakiElias\TablarKit\Components\Cards\CardStamp;
use TakiElias\TablarKit\Components\Editors\Jodit;
use TakiElias\TablarKit\Components\Forms\Error;
use TakiElias\TablarKit\Components\Forms\Form;
use TakiElias\TablarKit\Components\Forms\Inputs\Checkbox;
use TakiElias\TablarKit\Components\Forms\Inputs\DependentSelect;
use TakiElias\TablarKit\Components\Forms\Inputs\Email;
use TakiElias\TablarKit\Components\Forms\Inputs\FilePond;
use TakiElias\TablarKit\Components\Forms\Inputs\FlatPicker;
use TakiElias\TablarKit\Components\Forms\Inputs\Input;
use TakiElias\TablarKit\Components\Forms\Inputs\LitePicker;
use TakiElias\TablarKit\Components\Forms\Inputs\Password;
use TakiElias\TablarKit\Components\Forms\Inputs\Radio;
use TakiElias\TablarKit\Components\Forms\Inputs\Select;
use TakiElias\TablarKit\Components\Forms\Inputs\Textarea;
use TakiElias\TablarKit\Components\Forms\Inputs\Toggle;
use TakiElias\TablarKit\Components\Forms\Inputs\TomSelect;
use TakiElias\TablarKit\Components\Forms\Label;
use TakiElias\TablarKit\Components\Modals\Modal;
use TakiElias\TablarKit\Components\Modals\ModalForm;
use TakiElias\TablarKit\Components\Table\Tabulator;
use TakiElias\TablarKit\Http\Controllers\FilePondController;

return [

    /* --------------------------------------------------------------------------------------------
    * Tablar Kit Components
    * --------------------------------------------------------------------------------------------
    * This section lists all components available in the Tablar Kit package. You can enable or
    * disable components by including or excluding them from this list, thus customizing the
    * components available in your application.
    */

    'components' => [
        'alert' => Alert::class,
        'button' => Button::class,
        'card' => Card::class,
        'card-header' => CardHeader::class,
        'card-body' => CardBody::class,
        'card-footer' => CardFooter::class,
        'card-stamp' => CardStamp::class,
        'card-button' => CardButton::class,
        'card-ribbon' => CardRibbon::class,
        'checkbox' => Checkbox::class,
        'filepond' => FilePond::class,
        'radio' => Radio::class,
        'tabulator' => Tabulator::class,
        'toggle' => Toggle::class,
        'select' => Select::class,
        'dependent-select' => DependentSelect::class,
        'tom-select' => TomSelect::class,
        'email' => Email::class,
        'modal' => Modal::class,
        'modal-form' => ModalForm::class,
        'error' => Error::class,
        'lite-picker' => LitePicker::class,
        'flat-picker' => FlatPicker::class,
        'form' => Form::class,
        'form-button' => FormButton::class,
        'input' => Input::class,
        'label' => Label::class,
        'logout' => Logout::class,
        'password' => Password::class,
        'textarea' => Textarea::class,
        'jodit' => Jodit::class,
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
            'svg',
        ],
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
        'dir_url' => env('APP_URL').'/assets/images/jodit/',

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
        FileUploadAction::class,
    ],

    'file_actions' => [
        Files::class,
        FileRename::class,
        FileMove::class,
        FileRemove::class,
        Folders::class,
        FolderCreate::class,
        FolderMove::class,
        FolderRemove::class,
        FolderRename::class,
        Permissions::class,
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
        'controller' => FilePondController::class,
    ],

];
