<?php

namespace App\Services\Book\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Services\Book\Features\V1\ListBooksFeature;
use Illuminate\Http\JsonResponse;
use App\Services\Book\Http\Requests\BooksRequest;

class BookController extends Controller
{
    public function index(BooksRequest $request, ListBooksFeature $feature): JsonResponse
    {
        return $feature->serve($request->getFilters(),$request->getLimit());
    }
}
