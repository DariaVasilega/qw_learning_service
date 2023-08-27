<?php

declare(strict_types=1);

use App\Infrastructure\Database\Migration;

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

/**
 * @SuppressWarnings(PHPMD.ShortMethodNames)
 */
final class CreateLectionTable extends Migration
{
    public function up(): void
    {
        $this->schema->create('lection', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->increments('id');
            $table->text('text');
        });
    }

    public function down(): void
    {
        $this->schema->drop('lection');
    }
}
