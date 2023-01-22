<?php

namespace App\Domain\NYTimes;

use App\Http\Requests\GetBestsellersRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

readonly class NYTimesClient
{
    public function __construct(private string $apiKey, private array $endpoints)
    {
    }

    public function getBestsellers(GetBestsellersRequest $request): Response
    {
        return $this->makeRequest('bestsellers', $this->prepareQueryParams($request));
    }

    private function prepareQueryParams(GetBestsellersRequest $request): array
    {
        $queryParams = $this->replaceIsbn($request->validated());
        $queryParams['api-key'] = $this->apiKey;

        return $queryParams;
    }

    private function makeRequest(string $endpoint, array $queryParams): Response
    {
        $url = $this->endpoints[$endpoint];

        return Http::asJson()->get($url, $queryParams);
    }

    private function replaceIsbn(array $queryParams): array
    {
        if (array_key_exists('isbn', $queryParams)) {
            $queryParams['isbn'] = implode(';', $queryParams['isbn']);
        }

        return $queryParams;
    }
}
