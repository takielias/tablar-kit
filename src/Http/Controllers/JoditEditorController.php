<?php

namespace TakiElias\TablarKit\Http\Controllers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Controller;
use TakiElias\TablarKit\Factories\FileManipulationFactory;
use TakiElias\TablarKit\Factories\FileUploadFactory;
use TakiElias\TablarKit\Factories\NotFoundActionException;
use TakiElias\TablarKit\Http\Requests\FileBrowserRequest;
use TakiElias\TablarKit\Http\Requests\FileUploadRequest;

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
