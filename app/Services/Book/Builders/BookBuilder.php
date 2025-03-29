<?php

namespace App\Services\Book\Builders;

use App\Foundation\Abstracts\BaseBuilder;

class BookBuilder extends BaseBuilder
{
    public function filter(array $filters): static
    {

        if (isset($filters['id'])) {
            $this->filterEqual('id', $filters['id']);
        }

        if (isset($filters['s'])) {
            //whereAny //https://techvblogs.com/blog/laravel-10-47-whereany-whereall
            $this->filterMultiLike(['name'], $filters['s']);
        }

        if (isset($filters['from'])) {
            $this->filterFromDate('updated_at', $filters['from']);
        }

        if (isset($filters['to'])) {
            $this->filterToDate('updated_at', $filters['to']);
        }

        if (isset($filters['sort'])) {
            $this->sort($filters['sort']);
        }


        return $this;
    }

    public static function getDefaultFilters($filters = [], bool $isAdmin = false, $auth_id = null): array
    {

        if (!$isAdmin) {
        }

        return $filters;
    }

    public function sort(string $sortBy): static
    {

        $sort_arr = extractSort($sortBy);
        $column = $sort_arr['column'];
        $direction = $sort_arr['direction'];
        $this->orderBy($column, $direction);
        return $this;
    }
}