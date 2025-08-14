<?php

namespace App\Repository;

use App\Model\Person;
use Core\Database;
use Core\Repository;

class PersonRepository extends Repository {
    public function __construct(
    ) {
        parent::__construct(new Database(), Person::class);
    }

    public function findByWithDateRange(
        array $criteria = [],
        ?array $orderBy = [],
        ?string $dateTo = null
    ): array {
        $qb = $this->createQueryBuilder('p')
            ->addSelect('p.*')
            ->from($this->table, 'p');
    
        foreach ($criteria as $field => $value) {
            $qb->andWhere("p.{$field} = ?", $value);
        }
    
        if ($dateTo !== null) {
            $qb->andWhere('p.valid_from <= ?', $dateTo);
        }
    
        foreach ($orderBy as $field => $direction) {
            $qb->orderBy("p.{$field}", strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC');
        }

        return $this->mapResults($qb->fetchAll());
    }    
}
