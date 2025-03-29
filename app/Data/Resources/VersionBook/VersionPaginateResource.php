<?php

namespace App\Data\Resources\VersionBook;

use App\Foundation\Composables\Resources\PaginationResource;
use Illuminate\Http\Resources\Json\JsonResource;

class VersionPaginateResource extends JsonResource
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
            'books' =>VersionResource::collection($this),
            $pagination::$wrap => $pagination,
        ];
    }
}
