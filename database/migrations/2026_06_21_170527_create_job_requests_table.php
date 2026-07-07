<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('job_requests', function (Blueprint $table) {
        $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
        $table->foreignUuid('customer_id')->constrained('users')->onDelete('cascade');
        $table->foreignUuid('provider_id')->constrained('providers')->onDelete('cascade');
        $table->foreignUuid('trade_id')->constrained()->onDelete('cascade');
        $table->text('description');
        $table->string('location_address')->nullable();
        $table->string('location_area')->nullable();
        $table->enum('status', [
            'pending',
            'accepted',
            'in_progress',
            'completed',
            'cancelled',
            'declined'
        ])->default('pending');
        $table->boolean('customer_confirmed')->default(false);
        $table->boolean('provider_confirmed')->default(false);
        $table->string('completion_photo')->nullable();
        $table->boolean('customer_no_show_flag')->default(false);
        $table->timestamp('scheduled_at')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_requests');
    }
};
