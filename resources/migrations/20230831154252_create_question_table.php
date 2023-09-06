<?php

declare(strict_types=1);

use App\Infrastructure\Database\Migration;

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

/**
 * @SuppressWarnings(PHPMD.ShortMethodNames)
 */
final class CreateQuestionTable extends Migration
{
    public function up(): void
    {
        $this->schema->create('question', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->increments('id');
            $table->string('text', 512);
            $table->unsignedInteger('test_id');

            $table
                ->foreign('test_id')
                ->references('id')
                ->on('test')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        $this->schema->drop('question');
    }
}
