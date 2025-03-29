<?php

namespace App\Domains\Book\Jobs;

use App\Data\Models\Book;
use App\Services\Book\Builders\BookBuilder;

class GetBooksJob
{
    public function handle(array $filters,$limit)
    {
        return Book::query()
            ->filter($filters)
            ->paginate($limit);
    }
}