<?php

namespace App\Http\Traits;

use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/**
 * All file upload traits should go here
 */
trait FileUploadTraits
{
    /**
     * Upload a file and return its URL.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param string|null $fileName
     * @param string $storage
     * @return string|null
     */
    protected function uploadFile(UploadedFile $file, string $directory, ?string $fileName = null, string $storage = 'public'): ?string
    {
        $fileName = $fileName ?: Str::random(25).".".$file->getClientOriginalExtension();

        $path = $file->storeAs($directory, $fileName, $storage);

        if ($path) {
            return Storage::disk($storage)->url($path);
        }

        return null;
    }

    /**
     * Check if the document has an allowed to be uploaded mime type
     *
     * @param array $allowedMimeTypes
     * @param UploadedFile $file
     * @return bool
     */
    protected function isMimeTypeAllowed(array $allowedMimeTypes, UploadedFile $file): bool
    {
        $mimeType = $file->getMimeType();

        $allowedMimeTypes = array_values(Document::TYPES);

        return in_array($mimeType, $allowedMimeTypes);
    }
}
