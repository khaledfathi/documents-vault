<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Group::insert([
            [ 'name' => 'admin' ],
            [ 'name' => 'normal' ],
            [ 'name' => 'reader' ],
        ]);
        User::create([
            'name'=>'admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('admin'),
            'group_id' => 1 //admin
        ]);

    }
}
