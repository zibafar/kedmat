<?php

namespace Tests\Feature\Services\Book\Http\Controllers\V1\BookControllerTest;

use App\Data\Models\Book;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Passport\ClientRepository;
use Tests\Feature\_TestCase;

class BookControllerTest extends _TestCase
{
    private array $structure;

    protected string $routeName;

    protected function setUp(): void
    {
        parent::setUp();
        $this->routeName = 'books.';

        $this->structure = [
            'ability',
            'success',
            'message',

        ];
    }





    /*-----------------------Different sort of index method's tests begin here----------------------------------*/

    private function doTestSorting($target, $sort)
    {
        $this->createUserTest();
        $user_test=$this->user_test;
        $token = $user_test->createToken('api-token')->plainTextToken;

        $this
            ->withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])
            ->get(route($this->routeName . 'index', compact('sort')))
            ->assertSuccessful()
            ->assertJsonStructure($this->structure)
            ->assertJson(
                fn(AssertableJson $json) => $json->has(
                        'result.books',
                        fn(AssertableJson $json1) => $json1
                            ->where('0.title', $target->title)
                            ->etc()
                    )->etc()
            );
    }

    public function test_sorting_book_according_to_descending_title_works_correctly()
    {
        Book::factory()->createOne(['title' => 'aaaaaa']);

        $targetService = Book::factory()->createOne(['title' => 'zzzzzzz']);
        $this->doTestSorting($targetService, 'title_desc');
    }

    public function test_sorting_book_according_to_ascending_title_works_correctly()
    {

        Book::factory()->createOne(['title' => 'zzzz']);



        $targetService = Book::factory()->createOne(['title' => 'aaaa']);

        $this->doTestSorting($targetService, 'title_asc');
    }
}
