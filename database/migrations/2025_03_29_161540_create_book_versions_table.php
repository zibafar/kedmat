<?php

use App\Foundation\Enums\TablesEnum;
use App\Services\Book\Enums\VersionStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(TablesEnum::BOOK_VERSIONS, function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->string('condition');
            $table->json('repair_history')->nullable();
            $table->tinyInteger('status')->default(VersionStatusEnum::AVAILABLE->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(TablesEnum::BOOK_VERSIONS);
    }
};
