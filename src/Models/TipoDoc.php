<?php

namespace App\Models;

use PDO;

class TipoDoc
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM TIP_TIPO_DOC ORDER BY TIP_NOMBRE");
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM TIP_TIPO_DOC WHERE TIP_ID = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findByPrefijo(string $prefijo): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM TIP_TIPO_DOC WHERE TIP_PREFIJO = ?");
        $stmt->execute([$prefijo]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
}