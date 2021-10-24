<?php

namespace App\Service\FileContentHandler;

use App\Exception\UnhandledFileContentException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AbstractFileContentHandler implements FileContentHandler
{

    private FileContentHandler $nextHandler;

    public function setNext(FileContentHandler $contentHandler): FileContentHandler
    {
        $this->nextHandler = $contentHandler;
        return $contentHandler;
    }

    /**
     * @throws UnhandledFileContentException
     */
    public function handle(UploadedFile $file): FileContentHandler
    {
        if (isset($this->nextHandler)) {
            return $this->nextHandler->handle($file);
        }

        throw new UnhandledFileContentException(sprintf("There is no handler for file [%s]", $file->getClientOriginalName()));
    }

    public function validate(UploadedFile $file): bool
    {
        return true;
    }
}