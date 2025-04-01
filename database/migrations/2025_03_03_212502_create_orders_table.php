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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('folio')->unique();
            $table->unsignedInteger('num_dinners')->nullable();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->foreignId('order_status_id')->constrained();
            $table->string('payment_type_name')->nullable();
            $table->date('payment_date')->nullable();
            $table->date('cancellation_date')->nullable();
            $table->boolean('editing')->default(false);
            $table->foreignId('payment_type_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('table_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
