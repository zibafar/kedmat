<?php

namespace App\Data\Resources\Book;

use App\Foundation\Composables\Resources\PaginationResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BookPaginateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        $pagination = new PaginationResource($this);

        return [
            'books' =>BookResource::collection($this),
            $pagination::$wrap => $pagination,
        ];
    }
}
