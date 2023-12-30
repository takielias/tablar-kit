<?php

declare(strict_types=1);

namespace Takielias\TablarKit\Components\Forms\Inputs;

use Illuminate\Support\Str;
use Takielias\TablarKit\Components\TablarComponent;
use Illuminate\Contracts\View\View;

class FilePond extends TablarComponent
{
    /** @var string */
    public string $name;

    /** @var boolean */
    public bool $chunk_upload = false;

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
        ?string $value = '',
    )
    {
        $this->name = $name;
        $this->id = $id ?? Str::random();
        $this->type = $type;
        $this->value = old($name, $value ?? '');
        $this->chunk_upload = $chunkUpload;
    }

    public function render(): View
    {
        return view('tablar-kit::components.forms.inputs.filepond');
    }
}
