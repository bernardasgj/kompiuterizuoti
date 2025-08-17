<?php
namespace Core;

use Core\Attributes\Entity;
use mysqli;
use ReflectionClass;

abstract class Repository
{
    protected mysqli $connection;
    protected string $table;
    protected string $modelClass;

    public function __construct(Database $db, string $modelClass)
    {
        $this->connection = $db->getConnection();
        $this->modelClass = $modelClass;

        $reflection = new ReflectionClass($modelClass);
        $attributes = $reflection->getAttributes(Entity::class);

        if (empty($attributes)) {
            throw new \RuntimeException("Entity {$modelClass} must have #[Entity] attribute.");
        }

        /** @var Entity $tableAttr */
        $tableAttr = $attributes[0]->newInstance();
        $this->table = $tableAttr->name;
    }

    public function createQueryBuilder(string $alias): QueryBuilder
    {
        return (new QueryBuilder($this->connection))
            ->from($this->table, $alias)
            ->addSelect("$alias.*");
    }

    public function findAll(): array
    {
        return $this->mapResults(
            $this->createQueryBuilder('t')
                ->addSelect('t.*')
                ->fetchAll()
        );
    }

    public function count(): int
    {
        $row = $this->createQueryBuilder('t')
            ->addSelect('COUNT(*) AS total')
            ->fetchOne();

        return (int)($row['total'] ?? 0);
    }

    public function findOneBy(array $criteria, ?array $orderBy = null): ?object
    {
        $qb = $this->applyCriteria(
            $this->createQueryBuilder('t')->addSelect('t.*'),
            $criteria,
            $orderBy
        )->setLimit(1);

        $row = $qb->fetchOne();
        return $row ? $this->mapRow($row) : null;
    }

    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        $qb = $this->applyCriteria(
            $this->createQueryBuilder('t')->addSelect('t.*'),
            $criteria,
            $orderBy
        );

        if ($limit !== null) {
            $qb->setLimit($limit);
        }

        if ($offset !== null) {
            $qb->setOffset($offset);
        }

        return $this->mapResults($qb->fetchAll());
    }

    /**
     * Generic applyCriteria to avoid repetition
     */
    protected function applyCriteria(QueryBuilder $qb, array $criteria, ?array $orderBy): QueryBuilder
    {
        foreach ($criteria as $field => $value) {
            $qb->andWhere("t.$field = ?", $value);
        }

        if (!empty($orderBy)) {
            foreach ($orderBy as $field => $dir) {
                $qb->orderBy("t.$field", $dir);
            }
        }

        return $qb;
    }

    protected function mapResults(array $rows): array
    {
        return array_map(fn($row) => $this->mapRow($row), $rows);
    }

    protected function mapRow(array $row): object
    {
        return call_user_func([$this->modelClass, 'fromArray'], $row);
    }

    public function save(object $entity): object
    {
        $data = $entity->toArray();

        $id = $data['id'] ?? null;
        unset($data['id']);

        if ($id) {
            $sets = [];
            $params = [];
            $types = '';

            foreach ($data as $field => $value) {
                $sets[] = "$field = ?";
                $params[] = $value;
                $types .= $this->getParamType($value);
            }

            $params[] = $id;
            $types .= 'i'; // assuming id is int

            $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE id = ?";

            $stmt = $this->connection->prepare($sql);
            if (!$stmt) {
                throw new \RuntimeException("MySQL prepare failed: " . $this->connection->error);
            }

            $stmt->bind_param($types, ...$params);

            if (!$stmt->execute()) {
                throw new \RuntimeException("MySQL execute failed: " . $stmt->error);
            }

            return $entity;

        }

        // INSERT new record
        $fields = array_keys($data);
        $placeholders = array_fill(0, count($fields), '?');
        $params = array_values($data);
        $types = '';

        foreach ($params as $value) {
            $types .= $this->getParamType($value);
        }

        $sql = "INSERT INTO `{$this->table}` (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";

        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            throw new \RuntimeException("MySQL prepare failed: " . $this->connection->error);
        }

        $stmt->bind_param($types, ...$params);

        if (!$stmt->execute()) {
            throw new \RuntimeException("MySQL execute failed: " . $stmt->error);
        }

        if (method_exists($entity, 'setId')) {
            $entity->setId($this->connection->insert_id);
        }

        return $entity;
    }


    public function delete(object $entity): bool
    {
        if (!method_exists($entity, 'getId')) {
            throw new \InvalidArgumentException("Entity must have getId() method to delete");
        }

        $id = $entity->getId();

        if (!$id) {
            throw new \InvalidArgumentException("Entity ID is missing or invalid");
        }

        $sql = "DELETE FROM {$this->table} WHERE id = ?";

        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            throw new \RuntimeException("MySQL prepare failed: " . $this->connection->error);
        }

        $stmt->bind_param('i', $id);

        return $stmt->execute();
    }

    /**
     * Detect correct bind_param type for mysqli
     */
    protected function getParamType($var): string
    {
        return match (true) {
            is_int($var) => 'i',
            is_float($var) => 'd',
            default => 's',
        };
    }
}
