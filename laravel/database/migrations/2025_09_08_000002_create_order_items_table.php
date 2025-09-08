<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		Schema::create('order_items', function (Blueprint $table) {
			$table->id();
			$table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
			$table->foreignId('product_id')->constrained()->onDelete('cascade');
			$table->string('name'); // snapshot
			$table->unsignedInteger('qty');
			$table->string('thumb_url')->nullable();
			$table->string('image_url')->nullable();
			$table->json('meta')->nullable();
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('order_items');
	}
};
