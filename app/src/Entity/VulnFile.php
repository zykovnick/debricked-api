<?php

namespace App\Entity;

use App\Repository\VulnFileRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="vuln_files"
 * )
 * @ORM\Entity(repositoryClass=VulnFileRepository::class)
 */
class VulnFile
{

    const UPLOADED_STATUS = 1;
    const PROCESSED_STATUS = 2;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(name="ci_upload_id", type="integer")
     */
    private $ciUploadId;

    /**
     * @ORM\Column(name="upload_programs_id", type="integer")
     */
    private $uploadProgramsFileId;

    /**
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCiUploadId(): ?int
    {
        return $this->ciUploadId;
    }

    public function setCiUploadId(int $ciUploadId): self
    {
        $this->ciUploadId = $ciUploadId;

        return $this;
    }

    public function getUploadProgramsFileId(): ?int
    {
        return $this->uploadProgramsFileId;
    }

    public function setUploadProgramsFileId(int $uploadProgramsFileId): self
    {
        $this->uploadProgramsFileId = $uploadProgramsFileId;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }
}
