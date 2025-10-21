<?php

namespace App\Models;

use PDO;

class Documento
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAll(): array
    {
        $sql = "SELECT d.*, p.PRO_NOMBRE, p.PRO_PREFIJO, t.TIP_NOMBRE, t.TIP_PREFIJO
                FROM DOC_DOCUMENTO d
                INNER JOIN PRO_PROCESO p ON d.DOC_ID_PROCESO = p.PRO_ID
                INNER JOIN TIP_TIPO_DOC t ON d.DOC_ID_TIPO = t.TIP_ID
                ORDER BY d.DOC_ID DESC";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function search(string $query): array
    {
        $sql = "SELECT d.*, p.PRO_NOMBRE, p.PRO_PREFIJO, t.TIP_NOMBRE, t.TIP_PREFIJO
                FROM DOC_DOCUMENTO d
                INNER JOIN PRO_PROCESO p ON d.DOC_ID_PROCESO = p.PRO_ID
                INNER JOIN TIP_TIPO_DOC t ON d.DOC_ID_TIPO = t.TIP_ID
                WHERE d.DOC_NOMBRE LIKE ? 
                   OR d.DOC_CONTENIDO LIKE ?
                   OR CONCAT(t.TIP_PREFIJO, '-', p.PRO_PREFIJO, '-', d.DOC_CODIGO) LIKE ?
                ORDER BY d.DOC_ID DESC";
        
        $searchTerm = "%{$query}%";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $sql = "SELECT d.*, p.PRO_NOMBRE, p.PRO_PREFIJO, t.TIP_NOMBRE, t.TIP_PREFIJO
                FROM DOC_DOCUMENTO d
                INNER JOIN PRO_PROCESO p ON d.DOC_ID_PROCESO = p.PRO_ID
                INNER JOIN TIP_TIPO_DOC t ON d.DOC_ID_TIPO = t.TIP_ID
                WHERE d.DOC_ID = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function getNextConsecutivo(int $tipoId, int $procesoId): int
    {
        $stmt = $this->db->prepare(
            "SELECT MAX(DOC_CODIGO) as max_codigo 
             FROM DOC_DOCUMENTO 
             WHERE DOC_ID_TIPO = ? AND DOC_ID_PROCESO = ?"
        );
        $stmt->execute([$tipoId, $procesoId]);
        $result = $stmt->fetch();
        
        return ($result['max_codigo'] ?? 0) + 1;
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO DOC_DOCUMENTO 
                (DOC_NOMBRE, DOC_CODIGO, DOC_CONTENIDO, DOC_ID_TIPO, DOC_ID_PROCESO) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['nombre'],
            $data['codigo'],
            $data['contenido'],
            $data['tipo_id'],
            $data['proceso_id']
        ]);
        
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE DOC_DOCUMENTO 
                SET DOC_NOMBRE = ?, 
                    DOC_CODIGO = ?, 
                    DOC_CONTENIDO = ?, 
                    DOC_ID_TIPO = ?, 
                    DOC_ID_PROCESO = ?
                WHERE DOC_ID = ?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['nombre'],
            $data['codigo'],
            $data['contenido'],
            $data['tipo_id'],
            $data['proceso_id'],
            $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM DOC_DOCUMENTO WHERE DOC_ID = ?");
        return $stmt->execute([$id]);
    }

    public function getCodigoCompleto(array $documento): string
    {
        return sprintf(
            "%s-%s-%d",
            $documento['TIP_PREFIJO'],
            $documento['PRO_PREFIJO'],
            $documento['DOC_CODIGO']
        );
    }
}