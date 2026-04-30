<?php

namespace App\Services;

use InvalidArgumentException;
use PDO;
use PDOException;

class DatabaseConnectionValidator
{
    public function validate(array $config): void
    {
        $pdo = $this->connect($config);

        if (! $pdo instanceof PDO) {
            return;
        }
    }

    public function databaseContainsInstalledCms(array $config): bool
    {
        $pdo = $this->connect($config);

        try {
            $statement = $pdo->prepare('SELECT `value` FROM `settings` WHERE `key` = :key LIMIT 1');
            $statement->execute([
                'key' => 'app.installed',
            ]);

            return $statement->fetchColumn() === '1';
        } catch (PDOException) {
            return false;
        }
    }

    private function connect(array $config): PDO
    {
        $driver = (string) ($config['connection'] ?? 'mysql');

        $dsn = match ($driver) {
            'mysql', 'mariadb' => sprintf(
                '%s:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                $driver === 'mariadb' ? 'mysql' : $driver,
                $config['host'],
                $config['port'],
                $config['database'],
            ),
            'pgsql' => sprintf(
                'pgsql:host=%s;port=%s;dbname=%s',
                $config['host'],
                $config['port'],
                $config['database'],
            ),
            'sqlsrv' => sprintf(
                'sqlsrv:Server=%s,%s;Database=%s',
                $config['host'],
                $config['port'],
                $config['database'],
            ),
            default => throw new InvalidArgumentException('Unsupported database driver selected.'),
        };

        try {
            return new PDO($dsn, (string) $config['username'], (string) $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 5,
            ]);
        } catch (PDOException $exception) {
            throw new InvalidArgumentException('Не удалось подключиться к базе данных: '.$exception->getMessage(), 0, $exception);
        }
    }
}
