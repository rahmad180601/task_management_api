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
        Schema::create('proyek', function (Blueprint $table) {
            $table->id();
            $table->string('name_proyek');
            $table->text('description');
            $table->timestamps();
        });

        Schema::create('proyek_task', function (Blueprint $table) {
            $table->foreignId('proyek_id');
            $table->foreignId('task_id');
            $table->primary(['proyek_id', 'task_id']);
         });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyek');
    }
};
