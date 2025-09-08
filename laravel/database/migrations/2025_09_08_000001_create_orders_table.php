<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		Schema::create('orders', function (Blueprint $table) {
			$table->id();
			$table->foreignId('user_id')->constrained()->onDelete('cascade');
			$table->string('order_number')->unique();
			$table->string('status')->default('pending');
			$table->date('delivery_date');
			$table->text('notes')->nullable();

			// Optional shipping snapshot (recommended)
			$table->string('shipping_name')->nullable();
			$table->string('shipping_phone')->nullable();
			$table->string('shipping_address_1')->nullable();
			$table->string('shipping_address_2')->nullable();
			$table->string('shipping_city')->nullable();
			$table->string('shipping_state')->nullable();
			$table->string('shipping_postal_code')->nullable();
			$table->string('shipping_country')->nullable();
			$table->string('channel')->nullable();
			$table->json('meta')->nullable();

			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('orders');
	}
};
