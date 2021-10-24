<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class FileUploader
{

    public function __construct(
        private string              $debrickedUploadUrl,
        private TokenProvider       $tokenProvider,
        private HttpClientInterface $httpClient
    )
    {
    }

    /**
     * @param UploadedFile $file
     * @return ResponseInterface
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function upload(UploadedFile $file): ResponseInterface
    {
        $body = $this->buildBody($file);
        return $this->httpClient->request(Request::METHOD_POST, $this->debrickedUploadUrl, [
            'headers' =>
                array_merge([
                    'Authorization' => "Bearer " . $this->tokenProvider->provide()->getToken(),
                    'Content-Type' => 'multipart/form-data',
                    'Accept' => 'application/json'],
                    $body->getPreparedHeaders()->toArray()
                )
            ,
            'body' => $body->bodyToString()
        ]);
    }

    private function buildBody(UploadedFile $file): FormDataPart
    {
        return new FormDataPart(
            [
                'fileData' => DataPart::fromPath($file->getRealPath()),
                'commitName' => 'commit-' . rand(0, 1000),
                'productName' => 'product-' . rand(0, 1000),
                'repositoryUrl' => 'repository-' . rand(0, 1000),
            ]
        );
    }


}