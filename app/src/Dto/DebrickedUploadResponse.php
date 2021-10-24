<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class DebrickedUploadResponse
{

    private string $ciUploadId;

    private string $uploadProgramsFileId;

    public function getUploadProgramsFileId(): int
    {
        return $this->uploadProgramsFileId;
    }

    public function setUploadProgramsFileId(int $uploadProgramsFileId): void
    {
        $this->uploadProgramsFileId = $uploadProgramsFileId;
    }

    public function getCiUploadId(): int
    {
        return $this->ciUploadId;
    }

    public function setCiUploadId(int $ciUploadId): void
    {
        $this->ciUploadId = $ciUploadId;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('ciUploadId', new NotBlank());
        $metadata->addPropertyConstraint('uploadProgramsFileId', new NotBlank());
    }

}