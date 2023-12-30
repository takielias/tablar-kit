<?php

namespace Takielias\TablarKit\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
use Takielias\TablarKit\Dto\UploadedFileDto;
use Takielias\TablarKit\Dto\UploadedFilesInfoDto;

/**
 * @property UploadedFilesInfoDto $resource
 */
class FileUploadResource extends JsonResource
{
    public function toArray($request): array
    {
        $uploadedFilesInfo = $this->resource;

        return [
            'success' => true,
            'time' => now(),
            'data' => [
                'baseurl' => $uploadedFilesInfo->getUrl(),
                'code' => 220,
                'files' => $this->getFiles($uploadedFilesInfo),
                'isImages' => $this->getImageVerifications($uploadedFilesInfo),
                'messages' => [],
            ],
        ];
    }

    protected function getFiles(UploadedFilesInfoDto $uploadedFilesInfo): array
    {
        $files = array_map(
            static fn(UploadedFileDto $fileDto) => $fileDto->getName(),
            $uploadedFilesInfo->getFiles()
        );

        return array_values($files);
    }

    protected function getImageVerifications(UploadedFilesInfoDto $uploadedFilesInfo): array
    {
        $isImages = array_map(
            static fn(UploadedFileDto $infoDto) => $infoDto->isImage(),
            $uploadedFilesInfo->getFiles()
        );

        return array_values($isImages);
    }
}
