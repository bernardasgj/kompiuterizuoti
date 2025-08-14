<?php
namespace Core;

use mysqli;

class QueryBuilder
{
    private mysqli $connection;
    private array $select = [];
    private string $from = '';
    private array $joins = [];
    private array $where = [];
    private array $orderBy = [];
    private ?int $limit = null;
    private ?int $offset = null;

    private array $params = [];
    private string $paramTypes = '';

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function addSelect(string $select): self
    {
        $this->select[] = $select;
        return $this;
    }

    public function from(string $table, ?string $alias = null): self
    {
        $this->from = $alias ? "`$table` AS $alias" : "`$table`";
        return $this;
    }

    public function innerJoin(string $table, string $alias, string $on): self
    {
        $this->joins[] = "INNER JOIN `$table` AS $alias ON $on";
        return $this;
    }

    public function leftJoin(string $table, string $alias, string $on): self
    {
        $this->joins[] = "LEFT JOIN `$table` AS $alias ON $on";
        return $this;
    }

    public function andWhere(string $condition, $value = null): self
    {
        $this->where[] = $condition;

        if (func_num_args() > 1) {
            $this->params[] = $value;
            $this->paramTypes .= $this->detectType($value);
        }
        return $this;
    }

    public function orderBy(string $field, string $direction = 'ASC'): self
    {
        $this->orderBy[] = "$field " . strtoupper($direction);
        return $this;
    }

    public function setLimit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function setOffset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    public function fetchAll(): array
    {
        $stmt = $this->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function fetchOne(): ?array
    {
        $this->setLimit(1);
        $stmt = $this->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc() ?: null;
    }

    private function execute()
    {
        $sql = $this->buildSQL();

        $stmt = $this->connection->prepare($sql);
        if (!$stmt) {
            throw new \RuntimeException("MySQL prepare failed: " . $this->connection->error);
        }

        if (!empty($this->params)) {
            $stmt->bind_param($this->paramTypes, ...$this->params);
        }

        if (!$stmt->execute()) {
            throw new \RuntimeException("MySQL execute failed: " . $stmt->error);
        }

        return $stmt;
    }

    private function buildSQL(): string
    {
        $sql = "SELECT " . ($this->select ? implode(', ', $this->select) : '*');
        $sql .= " FROM " . $this->from;

        if ($this->joins) {
            $sql .= " " . implode(' ', $this->joins);
        }

        if ($this->where) {
            $sql .= " WHERE " . implode(' AND ', $this->where);
        }

        if ($this->orderBy) {
            $sql .= " ORDER BY " . implode(', ', $this->orderBy);
        }

        if ($this->limit !== null) {
            $sql .= " LIMIT ?";
            $this->params[] = $this->limit;
            $this->paramTypes .= 'i';
        }

        if ($this->offset !== null) {
            $sql .= " OFFSET ?";
            $this->params[] = $this->offset;
            $this->paramTypes .= 'i';
        }

        return $sql;
    }

    private function detectType($var): string
    {
        return match (true) {
            is_int($var) => 'i',
            is_float($var) => 'd',
            default => 's',
        };
    }
}
