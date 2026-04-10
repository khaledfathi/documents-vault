<?php

namespace Database\Seeders;

use App\Shared\Domain\Enums\User\PermissionType;
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
            ['permission' => PermissionType::VIEW_USER->value],
            ['permission' => PermissionType::CREATE_USER->value],
            ['permission' => PermissionType::EDIT_USER->value],
            ['permission' => PermissionType::DELETE_USER->value],
            //
            ['permission' => PermissionType::VIEW_GROUP->value],
            ['permission' => PermissionType::CREATE_GROUP->value],
            ['permission' => PermissionType::EDIT_GROUP->value],
            ['permission' => PermissionType::DELETE_GROUP->value],
            //
            ['permission' => PermissionType::VIEW_CATEGORY->value],
            ['permission' => PermissionType::CREATE_CATEGORY->value],
            ['permission' => PermissionType::EDIT_CATEGORY->value],
            ['permission' => PermissionType::DELETE_CATEGORY->value],
            //
            ['permission' => PermissionType::VIEW_PERMISSION->value],
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
        ]);
        User::create([
            'name' => 'admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('admin'),
            'group_id' => 1 //admin
        ]);
    }
}
