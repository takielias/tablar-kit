<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Components\Forms\Inputs;

use Illuminate\Support\Str;
use TakiElias\TablarKit\Components\TablarComponent;
use Illuminate\Contracts\View\View;

class FilePond extends TablarComponent
{
    /** @var string */
    public string $name;

    /** @var boolean */
    public bool $chunk_upload = false;

    /** @var boolean */
    public bool $image_manipulation = true;

    /** @var string */
    public string $id;

    /** @var string */
    public string $type;

    /** @var string */
    public mixed $value;

    public function __construct(
        string  $name,
        string  $id = null,
        string  $type = 'file',
        bool    $chunkUpload = false,
        bool    $imageManipulation = true,
        ?string $value = '',
    )
    {
        $this->name = $name;
        $this->id = $id ?? Str::random();
        $this->type = $type;
        $this->value = old($name, $value ?? '');
        $this->chunk_upload = $chunkUpload;
        $this->image_manipulation = $imageManipulation;
    }

    public function render(): View
    {
        return view('tablar-kit::components.forms.inputs.filepond');
    }

    public function data(): array
    {
        return [
            'name' => $this->name,
            'id' => $this->id,
            'type' => $this->type,
            'value' => $this->value,
            'chunk_upload' => $this->chunk_upload,
            'image_manipulation' => $this->image_manipulation,
        ];
    }
}
