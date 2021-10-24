<?php

namespace App\Service\FileContentHandler;

use App\Exception\UnhandledFileContentException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class InitialHandler extends AbstractFileContentHandler
{

    public function handle(UploadedFile $file): FileContentHandler
    {
        if ($this->isItEmpty($file)) {
            throw new UnhandledFileContentException(sprintf("File [%s] is empty", $file->getClientOriginalName()));
        }

        return parent::handle($file);
    }

    private function isItEmpty(UploadedFile $file): bool
    {
        return empty(trim($file->getContent()));
    }
}