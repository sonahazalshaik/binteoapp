<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Api\ApiSearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    protected ApiSearchService $searchService;

    public function __construct(ApiSearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function suggestions(Request $request)
    {
        return $this->searchService->suggestions($request);
    }

    public function mentions(Request $request)
    {
        return $this->searchService->mentions($request);
    }
}
