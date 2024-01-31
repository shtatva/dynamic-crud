<?php

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
        Schema::create('table_fields', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('length_value')->nullable();
            $table->string('default')->nullable();
            $table->string('default_value')->nullable();
            $table->string('attributes')->nullable();
            $table->boolean('isNull')->default(false);
            $table->string('index')->nullable();
            $table->boolean('is_dirty')->default(false);
            $table->string('is_dirty_rename_old')->nullable();
            $table->boolean('is_dirty_deleted')->default(false);
            $table->boolean('is_dirty_new_created')->default(false);
            $table->enum('form_type', ['input', 'select', 'textarea']);
            $table->enum('input_type', ['text', 'number', 'time', 'date', 'checkbox', 'file'])->nullable();
            $table->foreignId('table_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_fields');
    }
};
