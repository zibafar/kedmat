<?php

namespace App\Data\Resources\Book;

use App\Data\Resources\VersionBook\VersionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            "title" =>  $this->title,
            "author" =>  $this->author,
            "versions"=>  VersionResource::collection($this->versions)

        ];
    }
}
