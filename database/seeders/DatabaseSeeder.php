<?php

namespace Database\Seeders;

use App\Shared\Infrastructure\Models\Group;
use App\Shared\Infrastructure\Models\GroupPermission;
use App\Shared\Infrastructure\Models\Permission;
use App\Shared\Infrastructure\Models\User;
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
            ['name' => 'admin'],
            ['name' => 'normal'],
            ['name' => 'reader'],
        ]);
        Permission::insert([
            ['permission' => "view_user",],
            ['permission' => "create_user",],
            ['permission' => "edit_user",],
            ['permission' => "delete_user",],
            ['permission' => "view_group",],
            ['permission' => "create_group",],
            ['permission' => "edit_group",],
            ['permission' => "delete_group",],
            ['permission' => "view_category",],
            ['permission' => "create_category",],
            ['permission' => "edit_category",],
            ['permission' => "delete_category",],
            ['permission' => "view_document",],
            ['permission' => "create_document",],
            ['permission' => "edit_document",],
            ['permission' => "delete_document",],
        ]);
        //set all permision to admin 
        GroupPermission::insert([
            ['group_id' => 1 , 'permission_id' => 1],
            ['group_id' => 1 , 'permission_id' => 2],
            ['group_id' => 1 , 'permission_id' => 3],
            ['group_id' => 1 , 'permission_id' => 4],
            ['group_id' => 1 , 'permission_id' => 5],
            ['group_id' => 1 , 'permission_id' => 6],
            ['group_id' => 1 , 'permission_id' => 7],
            ['group_id' => 1 , 'permission_id' => 8],
            ['group_id' => 1 , 'permission_id' => 9],
            ['group_id' => 1 , 'permission_id' => 10],
            ['group_id' => 1 , 'permission_id' => 11],
            ['group_id' => 1 , 'permission_id' => 12],
            ['group_id' => 1 , 'permission_id' => 13],
            ['group_id' => 1 , 'permission_id' => 14],
            ['group_id' => 1 , 'permission_id' => 15],
            ['group_id' => 1 , 'permission_id' => 16],
        ]);
        User::create([
            'name' => 'admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('admin'),
            'group_id' => 1 //admin
        ]);
    }
}
