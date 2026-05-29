<?php

namespace Database\Seeders;

use App\Shared\Domain\Enums\User\PermissionType;
use App\Shared\Infrastructure\Models\AppInfo;
use App\Shared\Infrastructure\Models\Category;
use App\Shared\Infrastructure\Models\Group;
use App\Shared\Infrastructure\Models\GroupPermission;
use App\Shared\Infrastructure\Models\Permission;
use App\Shared\Infrastructure\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    private $now;
    private $adminGroup;
    private $defaultGroup;
    private $defaultCategory;
    public function __construct()
    {
        $this->now = Carbon::now();
    }

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $this->createDefaultGroups();
            $this->createPermissions();
            $this->setPermissionsForAdminGroup();
            $this->setDefaultCategory();
            $this->createRootUser();
            $this->createAppInfo();
        });
    }

    private function createDefaultGroups()
    {
        $this->adminGroup = Group::updateOrCreate([
            'name' => 'admin',
            "created_at" => $this->now,
            "updated_at" => $this->now
        ]);
        $this->defaultGroup = Group::updateOrCreate([
            'name' => 'default',
            "created_at" => $this->now,
            "updated_at" => $this->now
        ]);
    }
    private function createPermissions()
    {
        $permissions = array_map(fn($permission) => [
            "permission" => $permission->value,
            "created_at" => $this->now,
            "updated_at" => $this->now
        ], PermissionType::cases());
        Permission::insert($permissions);
    }
    public function setPermissionsForAdminGroup()
    {
        $groupPermissions = [];
        $allPermissionIds = Permission::pluck('id');
        foreach ($allPermissionIds as $permissionId) {
            $groupPermissions[] = [
                'group_id' => $this->adminGroup->id,
                'permission_id' => $permissionId,
                'created_at' => $this->now,
                'updated_at' => $this->now
            ];
        }
        GroupPermission::insert($groupPermissions);
    }
    public function setDefaultCategory()
    {
        $this->defaultCategory = Category::updateOrCreate([
            'name' => 'default',
            'description' => 'Default Category',
        ]);
    }
    public function createRootUser()
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('admin'),
            'group_id' => $this->adminGroup->id,
        ]);
    }
    public function createAppInfo()
    {
        $infoData = [
            ['key' => 'App Name', 'value' => 'kidev Document Vault'],
            ['key' => 'Version', 'value' => '0.1.0'],
            ['key' => 'Licenes', 'value' => 'GPL3'],
            ['key' => 'Source Code', 'value' => 'https://github.com/khaledfathi/documents-vault'],
            ['key' => 'Contact', 'value' => 'dev@khaledfathi.com'],
        ];
        AppInfo::insert($infoData);
    }
}
