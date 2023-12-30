<?php

namespace Takielias\TablarKit\Http\Controllers;

use Illuminate\Http\Resources\Json\JsonResource;
use Takielias\TablarKit\Factories\FileManipulationFactory;
use Takielias\TablarKit\Factories\FileUploadFactory;
use Takielias\TablarKit\Factories\NotFoundActionException;
use Takielias\TablarKit\Http\Requests\FileBrowserRequest;
use Takielias\TablarKit\Http\Requests\FileUploadRequest;
use Illuminate\Routing\Controller;

class JoditEditorController extends Controller
{
    /**
     * @throws NotFoundActionException
     */
    public function upload(FileUploadRequest $request, FileUploadFactory $factory): JsonResource
    {
        return $factory
            ->create($request->getDto())
            ->handle()
            ->response();
    }

    /**
     * @throws NotFoundActionException
     */
    public function browse(FileBrowserRequest $request, FileManipulationFactory $factory): JsonResource
    {
        return $factory
            ->create($request->getDto())
            ->handle()
            ->response();
    }
}
