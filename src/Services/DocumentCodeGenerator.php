<?php

namespace App\Services;

use App\Models\Documento;
use App\Models\TipoDoc;
use App\Models\Proceso;

class DocumentCodeGenerator
{
    private Documento $documentoModel;
    private TipoDoc $tipoDocModel;
    private Proceso $procesoModel;

    public function __construct(
        Documento $documentoModel,
        TipoDoc $tipoDocModel,
        Proceso $procesoModel
    ) {
        $this->documentoModel = $documentoModel;
        $this->tipoDocModel = $tipoDocModel;
        $this->procesoModel = $procesoModel;
    }

    public function generateCode(int $tipoId, int $procesoId): array
    {
        $tipo = $this->tipoDocModel->findById($tipoId);
        $proceso = $this->procesoModel->findById($procesoId);

        if (!$tipo || !$proceso) {
            throw new \InvalidArgumentException("Tipo o Proceso no válido");
        }

        $consecutivo = $this->documentoModel->getNextConsecutivo($tipoId, $procesoId);

        return [
            'codigo_numerico' => $consecutivo,
            'codigo_completo' => sprintf(
                "%s-%s-%d",
                $tipo['TIP_PREFIJO'],
                $proceso['PRO_PREFIJO'],
                $consecutivo
            )
        ];
    }
}