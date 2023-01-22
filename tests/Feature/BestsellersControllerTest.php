<?php

namespace Tests\Feature;

use Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BestsellersControllerTest extends TestCase
{
    public function test_call_without_params()
    {
        $response = $this->getJson('/api/1/nyt/best-sellers');

        $response->assertStatus(200);
    }

    public function test_call_with_string_params()
    {
        $response = $this->getJson('/api/1/nyt/best-sellers?author=test author&title=test title');

        $response->assertStatus(200);
    }

    public function test_single_isbn()
    {
        $response = $this->getJson('/api/1/nyt/best-sellers?isbn[]=9780671003548');

        $response->assertStatus(200);
    }

    public function test_multiple_isbn()
    {
        $response = $this->getJson('/api/1/nyt/best-sellers?isbn[]=9780671003548&isbn[]=9780446579933');

        $response->assertStatus(200);
    }

    public function test_single_wrong_isbn()
    {
        $response = $this->getJson('/api/1/nyt/best-sellers?isbn[]=wrong');

        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('isbn');
    }

    public function test_multiple_wrong_isbn()
    {
        $response = $this->getJson('/api/1/nyt/best-sellers?isbn[]=9780671003548&isbn[]=wrong');

        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('isbn');
    }

    public function test_offset_0()
    {
        $response = $this->getJson('/api/1/nyt/best-sellers?offset=0');

        $response->assertStatus(200);
    }

    public function test_offset_positive()
    {
        $response = $this->getJson('/api/1/nyt/best-sellers?offset=40');

        $response->assertStatus(200);
    }

    public function test_offset_negative()
    {
        $response = $this->getJson('/api/1/nyt/best-sellers?offset=-20');

        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('offset');
    }

    public function test_offset_not_divisible()
    {
        $response = $this->getJson('/api/1/nyt/best-sellers?offset=7');

        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('offset');
    }

    public function test_offset_not_integer()
    {
        $response = $this->getJson('/api/1/nyt/best-sellers?offset=test');

        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('offset');
    }

    protected function setUp(): void
    {
        parent::setUp();
        Http::fake();
        Config::set('http_clients.nytimes.api_key', 'test_api_key');
    }
}
