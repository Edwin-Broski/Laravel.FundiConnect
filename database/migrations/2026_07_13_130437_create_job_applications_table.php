<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('job_applications', function (Blueprint $table) {
        $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
        $table->foreignUuid('job_request_id')->constrained()->onDelete('cascade');
        $table->foreignUuid('provider_id')->constrained()->onDelete('cascade');
        $table->text('message')->nullable();
        $table->enum('status', ['pending', 'accepted', 'declined'])->default('pending');
        $table->timestamps();
    });
}
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
