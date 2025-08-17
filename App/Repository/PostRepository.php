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
        $qb = $this->buildGroupAndDateRangeQuery($groupId, $fromDate, $toDate);
    
        $qb->clearSelect()
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
            ->andWhere('1=1'); // dummy condition
    
        $qb->innerJoin(
            'person',
            'pe',
            'pe.base_id = p.person_base_id AND pe.valid_from = (
                SELECT MAX(pe2.valid_from)
                FROM person AS pe2
                WHERE pe2.base_id = p.person_base_id
                    AND pe2.valid_from < p.created_at
            )'
        )
        ->innerJoin('groups', 'g', 'g.id = pe.group_id');
        
        if ($groupId) {
            $qb->andWhere('pe.group_id = ?', $groupId);
        }
    
        if ($fromDate) {
            $from = (new \DateTimeImmutable($fromDate))->setTime(0, 0, 0);
            $qb->andWhere('p.created_at >= ?', $from->format('Y-m-d H:i:s'));
        }
    
        if ($toDate) {
            $to = (new \DateTimeImmutable($toDate))->setTime(23, 59, 59);
            $qb->andWhere('p.created_at <= ?', $to->format('Y-m-d H:i:s'));
        }
    
        return $qb;
    }    
}
