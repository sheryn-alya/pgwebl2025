<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'sherynapgwebl',
            'email' => 'sherynpgwebl25@gmail.com',
            'password' => bcrypt('sheryn25'),  
        ]);
        
        User::factory()->create([
            'name' => 'nadinepgwebl',
            'email' => 'nadinepgwebl@gmail.com',
            'password' => bcrypt('nadine25'),  
        ]);

        User::factory()->create([
            'name' => 'zidninapgwebl',
            'email' => 'zidninapgwebl@gmail.com',
            'password' => bcrypt('zidnina25'),  
        ]);
    }
}
