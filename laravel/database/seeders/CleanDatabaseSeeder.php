<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CleanDatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		// Delete all users except admins
		User::where('role', '!=', 'superadmin')->delete();

		DB::statement('SET FOREIGN_KEY_CHECKS=0;');

		// Truncate other tables if necessary
		// Child Tables
		DB::table('cart_items')->truncate();
		DB::table('orders')->truncate();
		DB::table('carts')->truncate();
		DB::table('order_items')->truncate();

		// Parent Tables
		DB::table('products')->truncate();


		DB::statement('SET FOREIGN_KEY_CHECKS=1;');

		$this->command->info('Database cleaned successfully, only superadmin remains!');

	}
}
