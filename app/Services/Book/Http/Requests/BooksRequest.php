<?php

namespace App\Services\Book\Http\Requests;

use App\Foundation\Rules\SortDirection;
use Illuminate\Foundation\Http\FormRequest;

class BooksRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'filters' => 'sometimes|array',
            "filters.from" => 'sometimes|date_format:"Y-m-d"',
            "filters.to" => 'sometimes|date_format:"Y-m-d"|after_or_equal:filters.from',
            "filters.sort" => ['sometimes','string',new SortDirection()],
            "sort" => ['sometimes','string',new SortDirection()],
            "limit" => 'sometimes|integer',
            'filters.s' => 'string|max:200',
        ];

    }

    protected function prepareForValidation()
    {
        $filters = $this->filters ?? [];

        $filters['sort'] = $this->sort ?? $filters['sort'] ?? 'title_desc';

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
