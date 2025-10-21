<?php

namespace App\Controllers;

use App\Models\Documento;
use App\Models\Proceso;
use App\Models\TipoDoc;
use App\Models\Database;
use App\Services\DocumentCodeGenerator;
use Exception;

class DocumentController
{
    private Documento $documentoModel;
    private Proceso $procesoModel;
    private TipoDoc $tipoDocModel;
    private DocumentCodeGenerator $codeGenerator;

    public function __construct()
    {
        AuthController::requireAuth();
        
        $this->documentoModel = new Documento();
        $this->procesoModel = new Proceso();
        $this->tipoDocModel = new TipoDoc();
        $this->codeGenerator = new DocumentCodeGenerator(
            $this->documentoModel,
            $this->tipoDocModel,
            $this->procesoModel
        );
    }

    public function index(): void
    {
        $search = $_GET['search'] ?? '';
        
        if (!empty($search)) {
            $documentos = $this->documentoModel->search($search);
        } else {
            $documentos = $this->documentoModel->getAll();
        }

        foreach ($documentos as &$doc) {
            $doc['CODIGO_COMPLETO'] = $this->documentoModel->getCodigoCompleto($doc);
        }

        require __DIR__ . '/../Views/documents/index.php';
    }

    public function create(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->store();
            return;
        }

        $procesos = $this->procesoModel->getAll();
        $tipos = $this->tipoDocModel->getAll();

        require __DIR__ . '/../Views/documents/create.php';
    }

    private function store(): void
    {
        try {
            $nombre = trim($_POST['nombre'] ?? '');
            $contenido = trim($_POST['contenido'] ?? '');
            $tipoId = (int) ($_POST['tipo_id'] ?? 0);
            $procesoId = (int) ($_POST['proceso_id'] ?? 0);

            if (empty($nombre) || $tipoId <= 0 || $procesoId <= 0) {
                throw new Exception("Todos los campos obligatorios deben completarse");
            }

            Database::beginTransaction();

            $codigo = $this->codeGenerator->generateCode($tipoId, $procesoId);

            $this->documentoModel->create([
                'nombre' => $nombre,
                'codigo' => $codigo['codigo_numerico'],
                'contenido' => $contenido,
                'tipo_id' => $tipoId,
                'proceso_id' => $procesoId
            ]);

            Database::commit();

            $_SESSION['success'] = "Documento creado exitosamente con código: {$codigo['codigo_completo']}";
            header('Location: /documents');
            exit;
        } catch (Exception $e) {
            Database::rollback();
            $_SESSION['error'] = "Error al crear documento: " . $e->getMessage();
            header('Location: /documents/create');
            exit;
        }
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        
        if ($id <= 0) {
            header('Location: /documents');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->update($id);
            return;
        }

        $documento = $this->documentoModel->findById($id);
        
        if (!$documento) {
            $_SESSION['error'] = "Documento no encontrado";
            header('Location: /documents');
            exit;
        }

        $documento['CODIGO_COMPLETO'] = $this->documentoModel->getCodigoCompleto($documento);
        $procesos = $this->procesoModel->getAll();
        $tipos = $this->tipoDocModel->getAll();

        require __DIR__ . '/../Views/documents/edit.php';
    }

    private function update(int $id): void
    {
        try {
            $nombre = trim($_POST['nombre'] ?? '');
            $contenido = trim($_POST['contenido'] ?? '');
            $tipoId = (int) ($_POST['tipo_id'] ?? 0);
            $procesoId = (int) ($_POST['proceso_id'] ?? 0);

            if (empty($nombre) || $tipoId <= 0 || $procesoId <= 0) {
                throw new Exception("Todos los campos obligatorios deben completarse");
            }

            $documentoActual = $this->documentoModel->findById($id);
            
            if (!$documentoActual) {
                throw new Exception("Documento no encontrado");
            }

            Database::beginTransaction();

            $codigoNumerico = $documentoActual['DOC_CODIGO'];
            
            if ($tipoId != $documentoActual['DOC_ID_TIPO'] || 
                $procesoId != $documentoActual['DOC_ID_PROCESO']) {
                $codigo = $this->codeGenerator->generateCode($tipoId, $procesoId);
                $codigoNumerico = $codigo['codigo_numerico'];
            }

            $this->documentoModel->update($id, [
                'nombre' => $nombre,
                'codigo' => $codigoNumerico,
                'contenido' => $contenido,
                'tipo_id' => $tipoId,
                'proceso_id' => $procesoId
            ]);

            Database::commit();

            $_SESSION['success'] = "Documento actualizado exitosamente";
            header('Location: /documents');
            exit;
        } catch (Exception $e) {
            Database::rollback();
            $_SESSION['error'] = "Error al actualizar documento: " . $e->getMessage();
            header("Location: /documents/edit?id={$id}");
            exit;
        }
    }

    public function delete(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        
        if ($id <= 0) {
            header('Location: /documents');
            exit;
        }

        try {
            Database::beginTransaction();
            
            if ($this->documentoModel->delete($id)) {
                $_SESSION['success'] = "Documento eliminado exitosamente";
            } else {
                throw new Exception("No se pudo eliminar el documento");
            }

            Database::commit();
        } catch (Exception $e) {
            Database::rollback();
            $_SESSION['error'] = "Error al eliminar documento: " . $e->getMessage();
        }

        header('Location: /documents');
        exit;
    }
}