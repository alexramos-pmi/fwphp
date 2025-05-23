<?php

namespace App\Models\Conn;

use PDO;
use PDOException;

class DB
{
    protected static ?PDO $pdo = null;

    protected static function connect(): void
    {
        if (self::$pdo === null) {
            $dbConn = env('DB_CONNECTION');
            $dbHost = env('DB_HOST');
            $dbName = env('DB_NAME');
            $dbUsername = env('DB_USERNAME');
            $dbPassword = env('DB_PASSWORD');

            $dsn = "{$dbConn}:host={$dbHost};dbname={$dbName};charset=utf8mb4";

            try {
                self::$pdo = new PDO($dsn, $dbUsername, $dbPassword, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                throw new \RuntimeException('Erro na conexão com o banco de dados: ' . $e->getMessage());
            }
        }
    }

    protected static function getConnection(): PDO
    {
        self::connect();
        return self::$pdo;
    }

    public static function table(string $table): DbQueryBuilder
    {
        return new DbQueryBuilder(self::getConnection(), $table);
    }

    public static function raw(string $sql, array $bindings = []): array
    {
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute($bindings);
        return $stmt->fetchAll();
    }

    public static function execute(string $sql, array $bindings = []): bool
    {
        $stmt = self::getConnection()->prepare($sql);
        return $stmt->execute($bindings);
    }
}