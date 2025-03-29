<?php

namespace App\Data\Models;

use App\Foundation\Enums\TablesEnum;
use App\Services\Book\Builders\BookBuilder;
use App\Services\Book\Database\Factories\BookFactory;
use App\Services\Book\Database\Factories\BookVersionFactory;

class BookVersion extends __Model
{

    use BookVersionAttributes,
    BookVersionRelationships;

protected $table = TablesEnum::BOOK_VERSIONS;

const COLUMNS = [
    'id',
    'book_id',
    'condition',
    'repair_history',
    'status',
    'updated_at',
    'created_at'
];

protected $fillable = [
    'id',
    'book_id',
    'condition',
    'repair_history',
    'status',
    'updated_at',
    'created_at'
];

protected $casts = [
    'created_at' => 'timestamp',
    'updated_at' => 'timestamp',
    'status' => 'int'
];


protected static function newFactory(): BookVersionFactory
{
    return BookVersionFactory::new();
}

// public function newEloquentBuilder($query): BookVersionBuilder
// {
//     return new BookVersionBuilder($query);
// }


}

trait BookVersionAttributes
{

}

trait BookVersionRelationships
{
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

}

