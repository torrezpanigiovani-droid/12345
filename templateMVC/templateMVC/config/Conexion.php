<?php

class Conexion
{
    private string $driver;
    private string $host;
    private string $port;
    private string $db;
    private string $user;
    private string $pass;
    private string $charset = 'utf8mb4';
    private ?PDO $pdo = null;

    public function __construct()
    {
        // Cambia DB_DRIVER a mysql o pgsql según el servidor que quieras utilizar.
        $this->driver = strtolower(getenv('DB_DRIVER') ?: 'mysql');
        $this->host   = getenv('DB_HOST') ?: 'localhost';
        $this->port   = getenv('DB_PORT') ?: ($this->driver === 'pgsql' ? '5432' : '3306');
        $this->db     = getenv('DB_NAME') ?: 'db_mundial2026';
        $this->user   = getenv('DB_USER') ?: ($this->driver === 'pgsql' ? 'postgres' : 'root');
        $this->pass   = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';
    }

    public function conectar(): PDO
    {
        if ($this->pdo instanceof PDO) {
            return $this->pdo;
        }

        try {
            if ($this->driver === 'pgsql') {
                $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db}";
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];
            } else {
                $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db};charset={$this->charset}";
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];
            }

            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
            return $this->pdo;
        } catch (PDOException $e) {
            throw new RuntimeException('Error de conexión a la base de datos: ' . $e->getMessage(), 0, $e);
        }
    }

    public function getDriver(): string
    {
        return $this->driver;
    }
}
