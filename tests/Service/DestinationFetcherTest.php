<?php
namespace App\Tests\Service;

use App\Service\DestinationFetcher;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class DestinationFetcherTest extends TestCase
{
    
    private $httpClientMock;
    private $responseMock;
    private $path = 'http://localhost:8000/api/destinations';
    protected function setUp(): void
    {
        $this->httpClientMock = $this->createMock(HttpClientInterface::class);
        $this->responseMock = $this->createMock(ResponseInterface::class);
    }
    public function testFetchAllSuccess(): void
    {
        $this->httpClientMock->method('request')->willReturn($this->responseMock);
        $this->responseMock->method('getStatusCode')->willReturn(200);
        $this->responseMock->method('toArray')->willReturn([
            ['id' => 1, 'name' => 'Destination 1', 'description' => 'Description 1', 'price' => 100, 'duration' => '2 days', 'image' => 'image1.jpg'],
        ]);

        $fetcher = new DestinationFetcher($this->httpClientMock);
        $destinations = $fetcher->fetchAll($this->path);

        $this->assertCount(1, $destinations);
        $this->assertEquals('Destination 1', $destinations[0]['name']);
    }

    public function testFetchAllFailure(): void
    {
        $this->httpClientMock->method('request')->willReturn($this->responseMock);
        $this->responseMock->method('getStatusCode')->willReturn(500);

        $this->expectException(\Exception::class);

        $fetcher = new DestinationFetcher($this->httpClientMock);
        $fetcher->fetchAll($this->path);
    }
}
