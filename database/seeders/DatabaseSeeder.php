<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\FormData;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Setting::create([
            'receiver_email' => 'lydiebrins@gmail.com',
        ]);

        User::factory()->create([
            'name' => 'admin',
            'email' => 'winnerk088@gmail.com',
        ]);

        FormData::factory(30)->create();
    }
}
