<?php

/** @noinspection PhpParamsInspection */

namespace Tests\Feature;

use App\Domain\NYTimes\NYTimesClient;
use App\Http\Requests\GetBestsellersRequest;
use Config;
use Illuminate\Support\Facades\Http;
use Mockery\MockInterface;
use Tests\TestCase;

class NYTimesClientTest extends TestCase
{
    private NYTimesClient $client;

    public function test_constructor_injections()
    {
        Config::set('http_clients.nytimes.api_key', 'injected');
        Config::set('http_clients.nytimes.endpoints', ['bestsellers' => 'http://injected.injected']);
        $client = $this->app->make(NYTimesClient::class);
        $mockRequest = $this->mock(GetBestsellersRequest::class, function (MockInterface $mock) {
            $mock->shouldReceive('validated')->once()->andReturn([]);
        });
        $response = $client->getBestsellers($mockRequest);
        parse_str($response->effectiveUri()->getQuery(), $responseQuery);

        $this->assertEquals(['api-key' => 'injected'], $responseQuery);
        $this->assertEquals('injected.injected', $response->effectiveUri()->getHost());
    }

    public function test_without_params()
    {
        $mockRequest = $this->mock(GetBestsellersRequest::class, function (MockInterface $mock) {
            $mock->shouldReceive('validated')->once()->andReturn([]);
        });
        $response = $this->client->getBestsellers($mockRequest);

        $this->assertTrue($response->ok());
    }

    public function test_parsing_params_without_error()
    {
        $mockRequest = $this->mock(GetBestsellersRequest::class, function (MockInterface $mock) {
            $mock->shouldReceive('validated')->once()
                ->andReturn([
                    'isbn' => ['test1', 'test2'],
                    'offset' => 20,
                    'title' => 'test title',
                ]);
        });
        $response = $this->client->getBestsellers($mockRequest);

        $this->assertTrue($response->ok());
    }

    public function test_url_has_api_key()
    {
        $mockRequest = $this->mock(GetBestsellersRequest::class, function (MockInterface $mock) {
            $mock->shouldReceive('validated')->once()
                ->andReturn([]);
        });
        $response = $this->client->getBestsellers($mockRequest);
        parse_str($response->effectiveUri()->getQuery(), $responseQuery);

        $this->assertEquals(['api-key' => 'test_api_key'], $responseQuery);
    }

    public function test_url_has_multiple_isbns_with_semicolon()
    {
        $mockRequest = $this->mock(GetBestsellersRequest::class, function (MockInterface $mock) {
            $mock->shouldReceive('validated')->once()
                ->andReturn(['isbn' => ['test1', 'test2']]);
        });
        $response = $this->client->getBestsellers($mockRequest);
        parse_str($response->effectiveUri()->getQuery(), $responseQuery);

        $this->assertEquals(['isbn' => 'test1;test2', 'api-key' => 'test_api_key'], $responseQuery);
    }

    public function test_url_has_all_passed_params()
    {
        $mockRequest = $this->mock(
            GetBestsellersRequest::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('validated')->once()
                    ->andReturn([
                        'author' => 'test author',
                        'isbn' => ['test_isbn'],
                        'title' => 'test_title',
                        'offset' => 40,
                    ]);
            });
        $response = $this->client->getBestsellers($mockRequest);
        parse_str($response->effectiveUri()->getQuery(), $responseQuery);

        $this->assertEquals([
            'author' => 'test author',
            'isbn' => 'test_isbn',
            'title' => 'test_title',
            'offset' => '40',
            'api-key' => 'test_api_key',
        ], $responseQuery);
    }

    protected function setUp(): void
    {
        parent::setUp();
        Http::fake();
        $this->client = new NYTimesClient('test_api_key', ['bestsellers' => 'http://test.test']);
    }
}
