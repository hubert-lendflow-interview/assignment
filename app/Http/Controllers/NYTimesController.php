<?php

namespace App\Http\Controllers;

use App\Domain\NYTimes\NYTimesClient;
use App\Http\Requests\GetBestsellersRequest;
use Illuminate\Http\Client\Response;

class NYTimesController extends Controller
{
    public function getBestsellers(GetBestsellersRequest $request, NYTimesClient $client): Response
    {
        return $client->getBestsellers($request);
    }
}
