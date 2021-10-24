<?php

namespace App\Service;

use App\Service\FileContentHandler\HandlerChainFactory;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class FileProcessor
{

    public function __construct(private HandlerChainFactory $handlerChainFactory, private FileUploader $uploader)
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function process(UploadedFile $uploadedFile): ResponseInterface
    {
        $handler = $this->handlerChainFactory->buildChain()->handle($uploadedFile);
        $handler->validate($uploadedFile);
        return $this->uploader->upload($uploadedFile);
    }

}