<?php

declare(strict_types=1);

namespace Takielias\TablarKit\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Routing\Controller;

final class FilePondController extends Controller
{
    public function process(Request $request): string
    {
        // We don't know the name of the file input, so we need to grab
        // all the files from the request and grab the first file.
        /** @var UploadedFile[] $files */
        $files = $request->allFiles();

        if (empty($files)) {
            abort(422, 'No files were uploaded.');
        }

        if (count($files) > 1) {
            abort(422, 'Only 1 file can be uploaded at a time.');
        }

        // Now that we know there's only one key, we can grab it to get
        // the file from the request.
        $requestKey = array_key_first($files);

        // If we are allowing multiple files to be uploaded, the field in the
        // request will be an array with a single file rather than just a
        // single file (e.g. - `csv[]` rather than `csv`). So we need to
        // grab the first file from the array. Otherwise, we can assume
        // the uploaded file is for a single file input, and we can
        // grab it directly from the request.
        $file = is_array($request->input($requestKey))
            ? $request->file($requestKey)[0]
            : $request->file($requestKey);

        // Store the file in a temporary location and return the location
        // for FilePond to use.
        return $file->store(
            path: 'tmp/' . now()->timestamp . '-' . Str::random(20)
        );
    }

    /**
     * FilePond ./revert route logic.
     *
     * @return \Illuminate\Http\Response
     */
    public function revert(Request $request)
    {
        return Response::make('Ok', 200, ['content-type' => 'text/plain']);
    }

}
