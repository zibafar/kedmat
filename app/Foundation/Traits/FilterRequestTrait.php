<?php

namespace App\Foundation\Traits;


trait FilterRequestTrait
{
    protected function prepareForValidation(): void
    {

        $filters = $this->filters ?? [];

        $filters['sort'] = $this->sort ?? $filters['sort'] ?? 'updated_at_desc';

        $limit = $this->limit ?? 12;
        $page = $this->page ?? 1;
        //init
        $this->replace(
            compact('filters','limit','page')
        );
    }

    public function getLimit(): int
    {
        return $this->validated('limit') ?? 12;
    }

    public function getFilters(): array
    {
        return $this->validated('filters') ?? [];
    }
}
