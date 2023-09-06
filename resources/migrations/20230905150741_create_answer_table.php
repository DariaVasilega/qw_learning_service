<?php

declare(strict_types=1);

use App\Infrastructure\Database\Migration;

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

/**
 * @SuppressWarnings(PHPMD.ShortMethodNames)
 */
final class CreateAnswerTable extends Migration
{
    public function up(): void
    {
        $this->schema->create('answer', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->increments('id');
            $table->string('text', 255);
            $table->unsignedInteger('question_id');
            $table->float('points');

            $table
                ->foreign('question_id')
                ->references('id')
                ->on('question')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        $this->schema->drop('answer');
    }
}
