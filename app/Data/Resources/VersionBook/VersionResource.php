<?php

namespace App\Data\Resources\VersionBook;

use App\Services\Book\Enums\VersionStatusEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VersionResource extends JsonResource
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
            'book_id' => $this->book_id,
            'book' => @$this->book->title,
            "condition" =>  $this->condition,
            "repair_history" =>  $this->repair_history,
            "status" =>  VersionStatusEnum::from($this->status)->label()

        ];
    }
}
