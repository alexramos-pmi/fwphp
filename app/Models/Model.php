<?php

namespace App\Models;

use PDO;

abstract class Model
{
    protected static string $table;
    protected static array $fillable = [];
    protected static array $guarded = [];
    protected static ?PDO $db = null;

    protected array $attributes = [];
    protected static array $appends = [];
    protected bool $exists = false;
    protected bool $isSaving = false; // <- NOVO

    public function __construct(array $data = [])
    {
        $this->fill($data);
    }

    protected static function initDB(): void
    {
        $dbHost = env("DB_HOST");
        $dbName = env("DB_NAME");
        $dbUsername = env("DB_USERNAME");
        $dbPassword = env("DB_PASSWORD");

        if (self::$db === null) {
            self::$db = new PDO("mysql:host={$dbHost};dbname={$dbName}", "{$dbUsername}", "{$dbPassword}");
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }

    public static function getDB(): PDO
    {
        if (self::$db === null) {
            self::initDB();
        }

        return self::$db;
    }

    public function getTable(): string
    {
        return static::$table;
    }

    public static function create(array $data): static
    {
        $instance = new static();
        $instance->isSaving = true;
        $instance->fill($data);
        $instance->save();
        $instance->isSaving = false;

        return $instance;
    }

    public function fill(array $data): static
    {
        $autoFillables = array_merge(['id', 'created_at', 'updated_at'], static::$fillable);
        $fillable = array_diff($autoFillables, static::$guarded);

        foreach ($data as $key => $value) {
            if (in_array($key, $fillable)) {
                $this->$key = $value; // Passa pelo __set()
            }
        }

        return $this;
    }

    public function save(): bool
    {
        static::initDB();
        $now = date('Y-m-d H:i:s');
        $this->isSaving = true;

        if (isset($this->attributes['id']) && $this->exists) {
            $this->attributes['updated_at'] = $now;

            $columns = array_keys($this->attributes);
            $assignments = implode(', ', array_map(fn($col) => "$col = :$col", $columns));

            $sql = "UPDATE " . static::$table . " SET $assignments WHERE id = :id";
        } else {
            if (!isset($this->attributes['created_at'])) {
                $this->attributes['created_at'] = $now;
            }
            $this->attributes['updated_at'] = $now;

            $columns = array_keys($this->attributes);
            $placeholders = array_map(fn($col) => ":$col", $columns);

            $sql = "INSERT INTO " . static::$table . " (" . implode(',', $columns) . ") VALUES (" . implode(',', $placeholders) . ")";
        }

        $stmt = static::getDB()->prepare($sql);
        $success = $stmt->execute($this->attributes);

        if (!$this->exists && empty($this->attributes['id'])) {
            $this->attributes['id'] = static::getDB()->lastInsertId();
        }

        $this->exists = true;
        $this->isSaving = false;

        return $success;
    }

    // public static function find($id): ?static
    // {
    //     return static::where('id', $id)->first();
    // }

    public static function find($id)
    {
        static::initDB();

        $table = static::$table;
        $stmt = static::getDB()->prepare("SELECT * FROM {$table} WHERE id = ?");
        $stmt->execute([$id]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $model = new static($data);
            $model->exists = true; // ✅ ESSENCIAL para evitar INSERT indevido
            return $model;
        }

        return null;
    }

    public function __get($key)
    {
        $method = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key))) . 'Attribute';

        if (method_exists($this, $method)) {
            return $this->$method();
        }

        if (in_array($key, static::$appends) && method_exists($this, $key)) {
            return $this->$key();
        }

        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }

        return null;
    }

    public function __set($key, $value)
    {
        $method = 'set' . ucfirst($key) . 'Attribute';

        if (method_exists($this, $method)) {
            $this->$method($value);
        } else {
            $this->attributes[$key] = $value;
        }
    }

    public function delete(): bool
    {
        $stmt = static::getDB()->prepare("DELETE FROM " . static::$table . " WHERE id = ?");
        return $stmt->execute([$this->attributes['id']]);
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }

    public function toArray(): array
    {
        $data = [];

        foreach ($this->attributes as $key => $value) {
            $method = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key))) . 'Attribute';
            $data[$key] = method_exists($this, $method) ? $this->$method() : $value;
        }

        foreach (static::$appends as $append) {
            if (method_exists($this, $append)) {
                $data[$append] = $this->$append();
            }
        }

        return $data;
    }

    public static function where(string $column, $value): \App\Models\Builder
    {
        return (new \App\Models\Builder(new static()))->where($column, $value);
    }

    public static function orderBy(string $column, string $direction = 'ASC'): \App\Models\Builder
    {
        return (new \App\Models\Builder(new static()))->orderBy($column, $direction);
    }

    public static function limit(int $limit): \App\Models\Builder
    {
        return (new \App\Models\Builder(new static()))->limit($limit);
    }

    public static function offset(int $offset): \App\Models\Builder
    {
        return (new \App\Models\Builder(new static()))->offset($offset);
    }

    public static function count(): int
    {
        return (new \App\Models\Builder(new static()))->count();
    }

    public static function get(): array
    {
        return (new \App\Models\Builder(new static()))->get();
    }

    public function first(): ?static
    {
        return (new \App\Models\Builder($this))->first();
    }
}