<?php

namespace TakiElias\TablarKit\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use TakiElias\TablarKit\Actions\FolderCreate;
use TakiElias\TablarKit\Actions\FolderRemove;
use TakiElias\TablarKit\Actions\FolderRename;
use TakiElias\TablarKit\Dto\FileBrowserDto;
use TakiElias\TablarKit\Validators\PathValidator;

/**
 * @property string action
 * @property string source
 * @property string|null path
 * @property string|null from
 * @property string|null name
 * @property string|null newname
 */
class FileBrowserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'action' => ['required', 'string'],
            'path' => ['nullable', 'string', new PathValidator()],
            'source' => ['required', 'string'],
            'from' => ['sometimes', 'string'],
            'name' => [$this->nameRule(), 'string'],
            'newname' => [$this->newNameRule(), 'string'],
        ];
    }

    private function nameRule(): string
    {
        return 'required_if:action,'
            . implode(
                ',',
                [
                    FolderCreate::getActionName(),
                    FolderRemove::getActionName(),
                ]
            );
    }

    private function newNameRule(): string
    {
        return 'required_if:action,' . FolderRename::getActionName();
    }

    public function getDto(): FileBrowserDto
    {
        return FileBrowserDto::byRequest($this);
    }

    protected function failedValidation($validator)
    {
        $response = response()->json([
            'success' => false,
            'data' => [
                'code' => 422,
                'messages' => $validator->errors()->all() // This returns a flat array of messages
            ],
        ], 422);

        throw new HttpResponseException($response);
    }
}
