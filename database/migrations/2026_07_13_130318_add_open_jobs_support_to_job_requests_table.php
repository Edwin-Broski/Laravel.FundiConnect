<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('job_requests', function (Blueprint $table) {
        // make provider nullable for open jobs
        $table->foreignUuid('provider_id')
              ->nullable()
              ->change();

        // add open status
        // modify the status enum to include open
        $table->string('status')->default('open')->change();
    });
}
    public function down(): void
    {
        Schema::table('job_requests', function (Blueprint $table) {
            //
        });
    }
};
