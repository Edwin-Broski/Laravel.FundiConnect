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
    Schema::create('providers', function (Blueprint $table) {
        $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
        $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
        $table->text('bio')->nullable();
        $table->string('location_area')->nullable();
        $table->string('location_district')->nullable();
        $table->boolean('is_available')->default(true);
        $table->boolean('is_verified')->default(false);
        $table->decimal('avg_rating', 3, 2)->default(0.00);
        $table->integer('jobs_completed')->default(0);
        $table->string('id_photo')->nullable();
        $table->string('certificate_photo')->nullable();
        $table->enum('status', ['pending', 'approved', 'rejected', 'suspended'])
              ->default('pending');
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('providers');
    }
};
