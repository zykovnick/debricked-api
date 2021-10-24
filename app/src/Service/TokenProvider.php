<?php

namespace App\Service;

use App\Dto\Token;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Request;


class TokenProvider
{
    public function __construct(
        private string              $debrickedUsername,
        private string              $debrickedPassword,
        private string              $debrickedLoginURL,
        private HttpClientInterface $httpClient,
        private SerializerInterface $serializer
    )
    {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function provide(): Token
    {
        $result = $this->httpClient->request(Request::METHOD_POST, $this->debrickedLoginURL, [
            'body' => [
                '_username' => $this->debrickedUsername,
                '_password' => $this->debrickedPassword
            ]
        ]);

        return $this->serializer->deserialize(
            $result->getContent(),
            Token::class,
            'json'
        );
    }
}
