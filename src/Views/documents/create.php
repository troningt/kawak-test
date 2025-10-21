<?php $title = 'Crear Documento'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="header-actions">
    <h2>Crear Nuevo Documento</h2>
    <a href="/documents" class="btn btn-primary">Volver al Listado</a>
</div>

<div class="card">
    <form method="POST" action="/documents/create">
        <div class="form-group">
            <label for="nombre">Nombre del Documento: *</label>
            <input 
                type="text" 
                id="nombre" 
                name="nombre" 
                required 
                maxlength="60"
                placeholder="Ej: INSTRUCTIVO DE DESARROLLO"
            >
        </div>

        <div class="form-group">
            <label for="tipo_id">Tipo de Documento: *</label>
            <select id="tipo_id" name="tipo_id" required>
                <option value="">Seleccione un tipo</option>
                <?php foreach ($tipos as $tipo): ?>
                    <option value="<?php echo $tipo['TIP_ID']; ?>">
                        <?php echo htmlspecialchars($tipo['TIP_NOMBRE']); ?> 
                        (<?php echo htmlspecialchars($tipo['TIP_PREFIJO']); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="proceso_id">Proceso: *</label>
            <select id="proceso_id" name="proceso_id" required>
                <option value="">Seleccione un proceso</option>
                <?php foreach ($procesos as $proceso): ?>
                    <option value="<?php echo $proceso['PRO_ID']; ?>">
                        <?php echo htmlspecialchars($proceso['PRO_NOMBRE']); ?> 
                        (<?php echo htmlspecialchars($proceso['PRO_PREFIJO']); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="contenido">Contenido:</label>
            <textarea 
                id="contenido" 
                name="contenido" 
                maxlength="4000"
                placeholder="Ingrese el contenido del documento..."
            ></textarea>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-success">Guardar</button>
            <a href="/documents" class="btn btn-warning">Cancelar</a>
        </div>
    </form>
</div>

</div>
</body>
</html>