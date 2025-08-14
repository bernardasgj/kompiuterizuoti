<?php

namespace App\Repository;

use App\Model\Post;
use Core\Database;
use Core\Repository;

class PostRepository extends Repository
{
    public function __construct()
    {
        parent::__construct(new Database(), Post::class);
    }

    public function findByGroupAndDateRange(
        ?int $groupId = null,
        ?string $fromDate = null,
        ?string $toDate = null,
        ?int $limit = null,
        ?int $offset = null
    ): array {
        $qb = $this->buildGroupAndDateRangeQuery($groupId, $fromDate, $toDate)
            ->orderBy('p.created_at', 'DESC');

        if ($limit !== null) {
            $qb->setLimit($limit);
        }

        if ($offset !== null) {
            $qb->setOffset($offset);
        }

        return $this->mapResults($qb->fetchAll());
    }

    public function countByGroupAndDateRange(
        ?int $groupId = null,
        ?string $fromDate = null,
        ?string $toDate = null
    ): int {
        $qb = $this->buildGroupAndDateRangeQuery($groupId, $fromDate, $toDate)
            ->addSelect('COUNT(*) AS total');

        $result = $qb->fetchOne();
        return (int) ($result['total'] ?? 0);
    }

    private function buildGroupAndDateRangeQuery(
        ?int $groupId,
        ?string $fromDate,
        ?string $toDate
    ) {
        $qb = $this->createQueryBuilder('p')
            ->from($this->table, 'p')
            ->andWhere('1=1');
            ;

        if ($groupId) {
            $qb->innerJoin('person', 'pe', 'p.person_base_id = pe.id')
               ->andWhere('pe.group_id = ?', $groupId);
        }

        if ($fromDate) {
            $qb->andWhere('p.created_at >= ?', $fromDate);
        }

        if ($toDate) {
            $qb->andWhere('p.created_at <= ?', $toDate);
        }

        return $qb;
    }
}
