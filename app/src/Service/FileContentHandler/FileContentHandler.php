<?php

namespace App\Service\FileContentHandler;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileContentHandler
{
    public function setNext(FileContentHandler $contentHandler) : FileContentHandler;
    public function handle(UploadedFile $file) : FileContentHandler;
    public function validate(UploadedFile $file);
}