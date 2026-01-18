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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('location');
            $table->string('employment_type');
            $table->integer('salary_min');
            $table->integer('salary_max');
            $table->string('tipe');
            $table->text('description')->nullable();
            $table->string('gambar')->nullable();
            $table->string('status')->default('draft');

            $table->foreignId('company_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
