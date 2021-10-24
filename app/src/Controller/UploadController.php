<?php

namespace App\Controller;

use App\Dto\DebrickedUploadResponse;
use App\Dto\PackageListFileItem;

use App\Entity\VulnFile;
use App\Form\PackageListFileItemType;

use App\Repository\VulnFileRepository;
use App\Service\FileProcessor;
use App\Service\NotificationProcessor;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class UploadController extends AbstractController
{
    #[Route('/upload', name: 'upload', methods: ['POST'])]
    public function index
    (
        Request               $request,
        FileProcessor         $fileProcessor,
        SerializerInterface   $serializer,
        ValidatorInterface    $validator,
        VulnFileRepository    $vulnFileRepository,
        NotificationProcessor $notificationProcessor
    ): Response
    {
        $form = $this->createForm(PackageListFileItemType::class, new PackageListFileItem(), [
            'mimeTypes' => [
                'application/text',
                'application/json',
                'application/yml',
                'application/octet-stream',
                'text/plain',
            ]
        ]);

        $form->handleRequest($request);

        $errors = $this->fetchErrors($form);
        $response = [];

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($form['file']->getData() as $file) {

                try {
                    $uploadedResult = $fileProcessor->process($file);

                    $debrickedUploadedResponse = $serializer
                        ->deserialize(
                            $uploadedResult->getContent(),
                            DebrickedUploadResponse::class,
                            'json'
                        );

                    $debrickedUploadedResponseErrors = $validator->validate($debrickedUploadedResponse);

                    if (!empty($debrickedUploadedResponseErrors->count())) {
                        throw new Exception('There is some errors');
                    }

                    $vulnFile = $this->createFromDebrickedUploadResponse($debrickedUploadedResponse);
                    $vulnFileRepository->save($vulnFile);

                    $response[] = $debrickedUploadedResponse;
                } catch (Exception $exception) {
                    $errors[] = $exception->getMessage();
                } catch (TransportExceptionInterface $exception) {
                    $this->processFailedUpload($notificationProcessor, $exception);
                }
            };
        }

        return $this->json([
            'uploadedFiles' => $response,
            'errors' => $errors
        ]);
    }

    private function fetchErrors(FormInterface $form, $errors = []): array
    {
        foreach ($form->all() as $subForm) {
            if ($subForm->all() && !$subForm->isValid()) {
                $errors[$subForm->getName()] = self::fetchErrors($subForm);
            } else {
                foreach ($subForm->getErrors() as $error) {
                    $errors[$error->getOrigin()->getName()] = $error->getMessage();
                }
            }
        }

        return $errors;
    }

    private function createFromDebrickedUploadResponse(DebrickedUploadResponse $debrickedUploadResponse): VulnFile
    {
        return (new VulnFile())
            ->setCiUploadId($debrickedUploadResponse->getCiUploadId())
            ->setUploadProgramsFileId($debrickedUploadResponse->getUploadProgramsFileId())
            ->setStatus(VulnFile::UPLOADED_STATUS);
    }

    /**
     * @param NotificationProcessor $notificationProcessor
     * @param TransportExceptionInterface|Exception $exception
     */
    private function processFailedUpload(
        NotificationProcessor                 $notificationProcessor,
        TransportExceptionInterface|Exception $exception
    ): void
    {
        if ($this->getParameter('app.notificationSettings.rules.uploadFailed')) {
            $notificationProcessor->notify($exception->getMessage());
        }
    }
}
