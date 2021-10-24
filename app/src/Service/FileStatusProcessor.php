<?php

namespace App\Service;

use App\Dto\DebrickedStatusResponse;
use App\Entity\VulnFile;
use Symfony\Component\Notifier\NotifierInterface;

class FileStatusProcessor
{

    public function __construct(
        private array             $notificationSettings,
        private FilePinger        $filePinger,
        private NotifierInterface $notifier
    )
    {
    }

    public function process(VulnFile $vulnFile): DebrickedStatusResponse
    {
        $status = $this->filePinger->ping($vulnFile);

        $this->checkProgressTrigger($status, $vulnFile);
        $this->checkVulnsAmountTrigger($status, $vulnFile);

        return $status;
    }

    private function checkProgressTrigger(DebrickedStatusResponse $status, VulnFile $vulnFile): void
    {
        if ($status->getProgress()) {
            if ($this->notificationSettings['uploadInProgress']) {
                $this->notifier->send(
                    sprintf(
                        "File [CiUploadId = %s] in the progress",
                        $vulnFile->getCiUploadId()
                    )
                );
            }
        }
    }

    private function checkVulnsAmountTrigger(DebrickedStatusResponse $status, VulnFile $vulnFile): void
    {
        if ($status->getVulnerabilitiesFound() >= $this->notificationSettings['triggerVulnAmount']) {
            $this->notifier->send(sprintf("File [CiUploadId = %s] has %s vulns", $vulnFile->getCiUploadId(), $status->getVulnerabilitiesFound()));
        }
    }

}