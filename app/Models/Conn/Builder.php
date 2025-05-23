<?php

namespace App\Models\Conn;

use PDO;

class Builder
{
    protected Model $model;
    protected array $wheres = [];
    protected array $orders = [];
    protected ?int $limit = null;
    protected ?int $offset = null;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function where(string $column, $value): static
    {
        $this->wheres[] = [
            'column' => $column,
            'value' => $value,
        ];

        return $this;
    }

    public function orderBy(string $column, string $direction = 'ASC'): static
    {
        $this->orders[] = [
            'column' => $column,
            'direction' => strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC',
        ];

        return $this;
    }

    public function limit(int $limit): static
    {
        $this->limit = $limit;

        return $this;
    }

    public function offset(int $offset): static
    {
        $this->offset = $offset;

        return $this;
    }

    public function count(): int
    {
        $sql = "SELECT COUNT(*) FROM " . $this->model->getTable();

        $bindings = [];
        if ($this->wheres) {
            $conditions = array_map(fn($w) => "{$w['column']} = ?", $this->wheres);
            $sql .= " WHERE " . implode(' AND ', $conditions);

            $bindings = array_map(fn($w) => $w['value'], $this->wheres);
        }

        $stmt = Model::getDB()->prepare($sql);
        $stmt->execute($bindings);

        return (int) $stmt->fetchColumn();
    }

    public function get(): array
    {
        $sql = "SELECT * FROM " . $this->model->getTable();

        $bindings = [];

        if ($this->wheres) {
            $conditions = array_map(fn($w) => "{$w['column']} = ?", $this->wheres);
            $sql .= " WHERE " . implode(' AND ', $conditions);

            $bindings = array_map(fn($w) => $w['value'], $this->wheres);
        }

        if ($this->orders) {
            $orders = array_map(fn($o) => "{$o['column']} {$o['direction']}", $this->orders);
            $sql .= " ORDER BY " . implode(', ', $orders);
        }

        if ($this->limit !== null) {
            $sql .= " LIMIT " . $this->limit;
        }

        if ($this->offset !== null) {
            $sql .= " OFFSET " . $this->offset;
        }

        $stmt = Model::getDB()->prepare($sql);
        $stmt->execute($bindings);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $models = [];
        foreach ($results as $row) {
            $modelInstance = new ($this->model::class)();
            $modelInstance->fill($row);
            $modelInstance->exists = true;
            $models[] = $modelInstance;
        }

        return $models;
    }

    public function first(): ?Model
    {
        $this->limit(1);

        $results = $this->get();

        return $results[0] ?? null;
    }
}