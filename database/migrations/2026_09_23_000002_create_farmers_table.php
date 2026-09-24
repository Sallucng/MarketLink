<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('farmers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('market_id')->nullable()->constrained('markets')->nullOnDelete();
            $table->string('stall_name');
            $table->string('contact_person');
            $table->string('contact_number');
            $table->text('address')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('operating_days')->nullable()->comment('e.g. Saturday, Sunday');
            $table->text('pickup_time_windows')->nullable()->comment('JSON or comma-separated slots, e.g. 08:00 AM - 10:00 AM, 10:30 AM - 12:30 PM');
            $table->integer('cutoff_hours')->default(2)->comment('Hours before pickup window when orders lock');
            $table->text('bio')->nullable();
            $table->string('image_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('farmers');
    }
};
