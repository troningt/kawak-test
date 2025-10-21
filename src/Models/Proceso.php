<?php

namespace App\Models;

use PDO;

class Proceso
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM PRO_PROCESO ORDER BY PRO_NOMBRE");
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM PRO_PROCESO WHERE PRO_ID = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findByPrefijo(string $prefijo): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM PRO_PROCESO WHERE PRO_PREFIJO = ?");
        $stmt->execute([$prefijo]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
}