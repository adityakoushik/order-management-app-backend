<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		Schema::create('products', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('admin_id'); // which admin uploaded it
			$table->string('name');
			$table->text('desc')->nullable();
			$table->string('image')->nullable();
			$table->string('thumb')->nullable();
			$table->enum('status', ['active', 'inactive'])->default('active');
			$table->timestamps();

			$table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('products');
	}

};
