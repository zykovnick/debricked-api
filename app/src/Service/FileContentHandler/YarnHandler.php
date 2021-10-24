<?php

namespace App\Service\FileContentHandler;

use ErrorException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class YarnHandler extends AbstractFileContentHandler
{
    const FILENAME = "yarn";

    public function handle(UploadedFile $file) : FileContentHandler
    {

        try {

            $result = yaml_parse($file->getContent());

            if ($this->isItYarn($result, $file)) {
                return $this;
            }
        } catch (ErrorException $errorException) {
            return parent::handle($file);
        }

    }

    /**
     * @param string|bool|array $result
     * @param UploadedFile $file
     * @return bool
     */
    public function isItYarn(mixed $result, UploadedFile $file): bool
    {
        return !empty($result) and str_starts_with($file->getClientOriginalName(), self::FILENAME);
    }

}