<?php

namespace App\Command;

use App\Entity\VulnFile;
use App\Repository\VulnFileRepository;
use App\Service\FileStatusProcessor;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'DebrickedPinger',
    description: 'Ping debricked for result of uploaded files',
)]
class DebrickedPingerCommand extends Command
{
    const SLEEP_DELAY = 30;

    public function __construct(
        private VulnFileRepository  $vulnFileRepository,
        private FileStatusProcessor $fileStatusProcessor
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->info('Pinger has started');

        while (true) {

            sleep(self::SLEEP_DELAY);

            $newFiles = $this->vulnFileRepository->findByStatus(VulnFile::UPLOADED_STATUS);
            /** @var VulnFile $file */
            foreach ($newFiles as $file) {
                $statusResponse = $this->fileStatusProcessor->process($file);

                if ($statusResponse->isProcessed()) {
                    $file->setStatus(VulnFile::PROCESSED_STATUS);
                    $this->vulnFileRepository->save($file);

                    $io->info(
                        sprintf(
                            "File [CiUploadId = %s] has been processed",
                            $file->getCiUploadId()
                        ));

                }

            }
        }

    }
}
