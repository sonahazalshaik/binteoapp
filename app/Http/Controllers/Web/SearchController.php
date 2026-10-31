<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Services\Frontend\SearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    protected SearchService $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function index(Request $request)
    {
        if (!$request->input('q')) {
            return redirect()->back();
        }

        $data = $this->searchService->search($request);

        return view('frontend.search', $data);
    }
}


