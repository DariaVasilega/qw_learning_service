<?php

declare(strict_types=1);

use App\Infrastructure\Database\Migration;

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

/**
 * @SuppressWarnings(PHPMD.ShortMethodNames)
 */
final class CreateScoreTable extends Migration
{
    public function up(): void
    {
        $this->schema->create('score', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->unsignedInteger('test_id');
            $table->unsignedInteger('question_id');
            $table->unsignedInteger('answer_id');
            $table->float('points');

            $table
                ->foreign('test_id')
                ->references('id')
                ->on('test')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table
                ->foreign('question_id')
                ->references('id')
                ->on('question')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table
                ->foreign('answer_id')
                ->references('id')
                ->on('answer')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unique(['email', 'test_id', 'question_id']);
        });
    }

    public function down(): void
    {
        $this->schema->drop('score');
    }
}
