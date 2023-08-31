<?php

declare(strict_types=1);

use App\Infrastructure\Database\Migration;

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

/**
 * @SuppressWarnings(PHPMD.ShortMethodNames)
 */
final class CreateTestTable extends Migration
{
    public function up(): void
    {
        $this->schema->create('test', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->increments('id');
            $table->string('label', 255);
            $table->float('passing_score');
            $table->unsignedInteger('lection_id');

            $table
                ->foreign('lection_id')
                ->references('id')
                ->on('lection')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        $this->schema->drop('test');
    }
}
