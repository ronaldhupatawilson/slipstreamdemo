<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('category',32);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });

        // Insert default data
        DB::table('categories')->insert([
            ['id' => 1, 'category' => 'Gold', 'created_at' => '2024-10-10 13:55:41', 'updated_at' => '2024-10-10 13:55:41'],
            ['id' => 2, 'category' => 'Silver', 'created_at' => '2024-10-10 13:55:44', 'updated_at' => '2024-10-10 13:55:44'],
            ['id' => 3, 'category' => 'Bronze', 'created_at' => '2024-10-10 13:55:48', 'updated_at' => '2024-10-10 13:55:48'],
        ]);
    }
    
    /**
     * reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
