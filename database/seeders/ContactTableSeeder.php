<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contact;

class ContactTableSeeder extends Seeder
{
    public function run()
    {
        Contact::factory()
            ->count(300)
            ->create();
    }
}
