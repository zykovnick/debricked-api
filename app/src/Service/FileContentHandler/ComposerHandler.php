<?php

namespace App\Service\FileContentHandler;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class ComposerHandler extends AbstractFileContentHandler
{

    const FILENAME = "composer";

    public function handle(UploadedFile $file): FileContentHandler
    {
        json_decode($file->getContent(), true);

        if ($this->isItComposer($file)) {
            return $this;
        }

        return parent::handle($file);
    }

    public function isItComposer(UploadedFile $file): bool
    {
        return json_last_error() == 0 and str_starts_with($file->getClientOriginalName(), self::FILENAME);
    }

}