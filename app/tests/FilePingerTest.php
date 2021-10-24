<?php

namespace App\Tests;

use App\Dto\DebrickedStatusResponse;
use App\Dto\Token;
use App\Entity\VulnFile;
use App\Service\FilePinger;
use App\Service\TokenProvider;
use Generator;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FilePingerTest extends TestCase
{
    use ProphecyTrait;

    const URL = 'http://test/%s';

    private HttpClientInterface|ObjectProphecy $httpClient;
    private TokenProvider|ObjectProphecy $tokenProvider;
    private SerializerInterface|ObjectProphecy $serializer;

    public function setUp(): void
    {
        $this->httpClient = $this->prophesize(HttpClientInterface::class);
        $this->tokenProvider = $this->prophesize(TokenProvider::class);
        $this->serializer = $this->prophesize(SerializerInterface::class);
    }

    public function testItShouldPingAndGetStatusResponse()
    {
        $token = new Token();
        $token->setToken(1);
        $token->setRefreshToken(1);

        $curlResponse = [
            "ciUploadId" => 1,
            "uploadProgramsFileId" => 1
        ];

        $debrickedResponse = new DebrickedStatusResponse();
        $debrickedResponse->setProgress(0);
        $debrickedResponse->setVulnerabilitiesFound(0);

        $vulnFile = new VulnFile();
        $vulnFile->setCiUploadId(1);

        $this->tokenProvider->provide()->shouldBeCalled()
            ->willReturn($token);


        $httpClient = new MockHttpClient((function () use ($vulnFile, $token, $curlResponse): Generator {
            $expectedRequests = [
                ['GET', sprintf(self::URL, $vulnFile->getCiUploadId()),
                    [
                        'headers' => [
                            'Accept' => 'application/json',
                            'Authorization' => "Bearer " . $token->getToken()
                        ]
                    ]]
            ];

            foreach ($expectedRequests as [$expectedMethod, $expectedUrl, $expectedHeader]) {
                yield function (string $method, string $url)
                use ($expectedMethod, $expectedUrl, $expectedHeader, $curlResponse): MockResponse {
                    $this->assertSame($expectedMethod, $method);
                    $this->assertSame($expectedUrl, $url);
                    return new MockResponse(json_encode($curlResponse));
                };
            }
        })());

        $this->serializer
            ->deserialize(json_encode($curlResponse), DebrickedStatusResponse::class, 'json')
            ->shouldBeCalled()
            ->willReturn($debrickedResponse);

        $filePinger = new FilePinger(
            self::URL,
            $httpClient,
            $this->tokenProvider->reveal(),
            $this->serializer->reveal()
        );

        $filePinger->ping($vulnFile);
    }

}
