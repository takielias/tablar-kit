<?php

namespace Takielias\TablarKit\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule as Rule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FilepondRule implements DataAwareRule, Rule, ValidatorAwareRule
{
    protected $validator;
    protected array $data;
    protected array|string $rules;
    protected array $messages = [];

    public function __construct(array|string $rules)
    {
        $this->rules = $rules;
    }

    public function setValidator($validator): static
    {
        $this->validator = $validator;
        return $this;
    }

    public function setData(array $data): static
    {
        $this->data = $data;
        return $this;
    }

    public function message(): array
    {
        return $this->messages;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            // Create an UploadedFile object from the temporary file path
            $file = $this->createFileObject($value);

            // Set the file object in the data for validation
            data_set($this->data, $attribute, $file);

            // Perform validation
            $validator = Validator::make(
                $this->data,
                [$attribute => $this->rules],
                $this->validator->customMessages,
                $this->validator->customAttributes
            );

            if ($validator->fails()) {
                $errorMessage = implode(' ', $validator->errors()->all());
                $fail($errorMessage);
                // Delete the file if validation fails
                $this->deleteTempFile($value);
            } else {
                // Validation passed, update the attribute with the valid file path
                $validFilePath = $file->getPath();
                data_set($this->data, $attribute, $validFilePath);
            }
        } catch (\Exception $e) {
            $this->messages[] = $e->getMessage();
            $fail($e->getMessage());
            $this->deleteTempFile($value);
        }
    }

    /**
     * @param string $tempFilePath
     * @return UploadedFile
     * @throws \Exception
     */
    private function createFileObject(string $tempFilePath): UploadedFile
    {
        $tempDisk = config('tablar-kit.filepond.temp_disk', 'local');

        if (Storage::disk($tempDisk)->exists($tempFilePath)) {
            $filename = basename($tempFilePath);
            $mimeType = Storage::mimeType($tempFilePath);

            return new UploadedFile(
                Storage::disk($tempDisk)->path($tempFilePath),
                $filename,
                $mimeType,
                null,
                true
            );
        } else {
            throw new \Exception("File does not exist or is not readable: " . $tempFilePath);
        }
    }

    /**
     * Deletes the temporary file from storage.
     *
     * @param string $tempFilePath The path of the temporary file.
     */
    private function deleteTempFile(string $tempFilePath): void
    {
        Storage::disk(config('tablar-kit.filepond.temp_disk', 'local'))->delete($tempFilePath);
    }

}
