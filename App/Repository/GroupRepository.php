<?php

namespace App\Repository;

use App\Model\Group;
use Core\Database;
use Core\Repository;

class GroupRepository extends Repository {
    public function __construct(
    ) {
        parent::__construct(new Database(), Group::class);
    }
}
