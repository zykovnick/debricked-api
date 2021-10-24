<?php

namespace App\Service;

use App\Dto\DebrickedStatusResponse;
use App\Entity\VulnFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FilePinger
{

    public function __construct(
        private string              $debrickedStatusUrl,
        private HttpClientInterface $httpClient,
        private TokenProvider       $tokenProvider,
        private SerializerInterface $serializer
    )
    {
    }

    public function ping(VulnFile $vulnFile): DebrickedStatusResponse
    {
        $result = $this->httpClient->request(Request::METHOD_GET,
            sprintf($this->debrickedStatusUrl, $vulnFile->getCiUploadId()),
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => "Bearer " . $this->tokenProvider->provide()->getToken()
                ]
            ]
        );

        return $this->serializer->deserialize(
            $result->getContent(),
            DebrickedStatusResponse::class,
            'json'
        );
    }
}