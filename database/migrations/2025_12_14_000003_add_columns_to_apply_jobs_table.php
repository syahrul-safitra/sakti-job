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
        Schema::table('apply_jobs', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->after('id');
            $table->foreignId('job_id')->constrained()->cascadeOnDelete()->after('user_id');
            $table->text('cover_letter')->nullable()->after('job_id');
            $table->string('status')->default('pending')->after('cover_letter');
            $table->string('keterangan')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('apply_jobs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['job_id']);
            $table->dropColumn(['user_id', 'job_id', 'cover_letter', 'status', 'keterangan']);
        });
    }
};
