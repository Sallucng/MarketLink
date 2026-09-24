<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('farmer_id')->constrained('farmers')->onDelete('cascade');
            $table->foreignId('market_id')->nullable()->constrained('markets')->nullOnDelete();
            $table->string('order_number')->unique();
            $table->enum('order_status', [
                'placed',
                'accepted',
                'ready_for_pickup',
                'completed',
                'cancelled',
                'declined'
            ])->default('placed');
            $table->date('pickup_date');
            $table->string('pickup_time_slot');
            $table->decimal('total_amount', 10, 2);
            $table->string('payment_method')->default('pay_at_pickup')->comment('Strictly paid in person at pickup as per SRS');
            $table->timestamp('cutoff_time')->nullable()->comment('Pre-order cannot be modified or cancelled after this time');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
