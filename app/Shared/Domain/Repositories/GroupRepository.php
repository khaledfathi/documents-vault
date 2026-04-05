<?php
declare(strict_types=1);

namespace App\Shared\Domain\Repositories;

use App\Shared\Domain\Entities\User\GroupEntity;
use App\Shared\Domain\ValuObjects\EntitiesWithPagination;

interface  GroupRepository {
    /**
     * @return EntitiesWithPagination<GroupEntity>
     */
    public function index():EntitiesWithPagination;
    public function show (int $groupId):GroupEntity|null;
    public function showByUserId(int $userId):GroupEntity|null;
    public function store(GroupEntity $groupEntity):GroupEntity;
    public function update(GroupEntity $groupEntity):bool;
    public function delete(int $groupId):bool;
}
