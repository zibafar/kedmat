<?php

namespace App\Data\Models;

use App\Foundation\Enums\TablesEnum;
use App\Services\Book\Builders\BookBuilder;
use App\Services\Book\Database\Factories\BookFactory;


class Book extends __Model
{

    use BookAttributes,
    BookRelationships;

protected $table = TablesEnum::BOOKS;

const COLUMNS = [
    'id',
    'title',
    'author',
    'isbn',
    'category',
    'updated_at',
    'created_at'
];

protected $fillable = [
    'id',
    'title',
    'author',
    'isbn',
    'category',
    'updated_at',
    'created_at'
];

protected $casts = [
    'created_at' => 'timestamp',
    'updated_at' => 'timestamp',
];


protected static function newFactory(): BookFactory
{
    return BookFactory::new();
}

public function newEloquentBuilder($query): BookBuilder
{
    return new BookBuilder($query);
}


}

trait BookAttributes
{

}

trait BookRelationships
{
    public function versions()
    {
        return $this->hasMany(BookVersion::class);
    }

}

