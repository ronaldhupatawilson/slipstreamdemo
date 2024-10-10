<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Timmy Tester',
            'email' => 'timmy@tester.com',
            'password' => Hash::make('timmyTester'),
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        

        // Insert default data
        DB::table('categories')->insert([
            ['id' => 1, 'category' => 'Gold', 'created_at' => '2024-10-10 13:55:41', 'updated_at' => '2024-10-10 13:55:41'],
            ['id' => 2, 'category' => 'Silver', 'created_at' => '2024-10-10 13:55:44', 'updated_at' => '2024-10-10 13:55:44'],
            ['id' => 3, 'category' => 'Bronze', 'created_at' => '2024-10-10 13:55:48', 'updated_at' => '2024-10-10 13:55:48'],
        ]);

        // $this->call(CategoryTableSeeder::class);
        $this->call(ContactTableSeeder::class);
        $this->call(CustomerTableSeeder::class);
    }
}
