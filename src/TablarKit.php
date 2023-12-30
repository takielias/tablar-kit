<?php

namespace Takielias\TablarKit;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RahulHaque\Filepond\Models\Filepond;
use RahulHaque\Filepond\Models\Filepond as FilepondModel;
use Symfony\Component\Mime\MimeTypes;

class TablarKit
{
    /** @var array */
    private static array $styles = [];

    /** @var array */
    private static array $scripts = [];

    // Build wonderful things

    public static function addStyle(string $style): void
    {
        if (!in_array($style, static::$styles)) {
            static::$styles[] = $style;
        }
    }

    public static function styles(): array
    {
        return static::$styles;
    }

    public static function outputStyles(bool $force = false): string
    {
        if (!$force && static::disableScripts()) {
            return '';
        }

        return collect(static::$styles)->map(function (string $style) {
            return '<link href="' . $style . '" rel="stylesheet" />';
        })->implode(PHP_EOL);
    }

    public static function addScript(string $script): void
    {
        if (!in_array($script, static::$scripts)) {
            static::$scripts[] = $script;
        }
    }

    public static function scripts(): array
    {
        return static::$scripts;
    }

    public static function outputScripts(bool $force = false): string
    {
        if (!$force && static::disableScripts()) {
            return '';
        }

        return collect(static::$scripts)->map(function (string $script) {
            return '<script src="' . $script . '"></script>';
        })->implode(PHP_EOL);
    }

    private static function disableScripts(): bool
    {
        return !config('app.debug');
    }

    public static function searchItem(Builder $eloquentBuilder, array $attributes = []): Collection|array
    {
        $eloquentBuilder->limit(10);

        $selectFields = array_map(function ($attribute, $alias) {
            return "{$attribute} as {$alias}";
        }, array_values($attributes), array_keys($attributes));

        $eloquentBuilder->select($selectFields);

        $q = \request('q');
        if (is_numeric($q)) {
            $eloquentBuilder->where($attributes['item_code'], 'LIKE', "%{$q}%");
        } else {
            $eloquentBuilder->where($attributes['item_name'], 'LIKE', "%{$q}%");
        }
        return $eloquentBuilder->get();
    }


    public static function getSingleItem(Builder $eloquentBuilder, Request $request)
    {
        $item = $eloquentBuilder->find($request->id);
        return view('tablar-kit::components.pos.pos-item', compact('item'));
    }

    public function moveFile_Old(string $storePath, string $filePath)
    {
        return Storage::putFile(
            path: $storePath,
            file: new File(Storage::path($filePath))
        );
    }

    /**
     * Moves a file from its current location to a new location.
     *
     * @param string $sourcePath Source path of the file.
     * @param string $destinationDir Destination directory.
     * @param string|null $newFileName New file name (optional).
     * @return string|false The new file path if successful, or false on failure.
     */

    public function moveFile(string $sourcePath, string $destinationDir, ?string $newFileName = null)
    {
        $destinationDisk = config('tablar-kit.filepond.disk', 'public');
        $sourceDisk = config('tablar-kit.filepond.temp_disk', 'local');

        if (!Storage::disk($sourceDisk)->exists($sourcePath)) {
            Log::error("File does not exist at path: $sourcePath on disk: $sourceDisk");
            return false;
        }

        // Extract the file extension from the source path
        $fileExtension = pathinfo($sourcePath, PATHINFO_EXTENSION);
//        $fileExtension = Storage::disk($sourceDisk)->path($sourcePath);

        // Determine the new file name, ensuring the extension is included
        $newFileName = $newFileName ?
            (str_ends_with($newFileName, ".$fileExtension") ? $newFileName : $newFileName . ".$fileExtension") :
            basename($sourcePath);

        $newFilePath = $destinationDir . '/' . $newFileName;

        // Move the file to the new location
        if (Storage::disk($sourceDisk)->move($sourcePath, $newFilePath)) {
            return $newFilePath;
        } else {
            Log::error("Failed to move file from $sourcePath to $newFilePath on disk: $destinationDisk");
            return false;
        }
    }

}


