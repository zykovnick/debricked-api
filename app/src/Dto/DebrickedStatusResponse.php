<?php

namespace App\Dto;

class DebrickedStatusResponse
{
    private int $progress;

    private int $vulnerabilitiesFound;

    public function getVulnerabilitiesFound(): int
    {
        return $this->vulnerabilitiesFound;
    }

    public function setVulnerabilitiesFound(int $vulnerabilitiesFound): void
    {
        $this->vulnerabilitiesFound = $vulnerabilitiesFound;
    }

    public function getProgress(): int
    {
        return $this->progress;
    }

    public function setProgress(int $progress): void
    {
        $this->progress = $progress;
    }

    public function isProcessed(): bool
    {
        return $this->getProgress() == 0;
    }
}