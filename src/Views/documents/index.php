<?php $title = 'Lista de Documentos'; ?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="header-actions">
    <h2>Documentos</h2>
    <a href="/documents/create" class="btn btn-success">Nuevo Documento</a>
</div>

<div class="search-bar">
    <form method="GET" action="/documents" style="display: flex; gap: 10px; width: 100%;">
        <input 
            type="text" 
            name="search" 
            placeholder="Buscar por nombre, contenido o código..." 
            value="<?php echo htmlspecialchars($search ?? ''); ?>"
        >
        <button type="submit" class="btn btn-primary">Buscar</button>
        <?php if (!empty($search)): ?>
            <a href="/documents" class="btn btn-warning">Limpiar</a>
        <?php endif; ?>
    </form>
</div>

<?php if (empty($documentos)): ?>
    <div class="card">
        <p>No se encontraron documentos.</p>
    </div>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Código</th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Proceso</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($documentos as $doc): ?>
                <tr>
                    <td><?php echo htmlspecialchars($doc['DOC_ID']); ?></td>
                    <td><strong><?php echo htmlspecialchars($doc['CODIGO_COMPLETO']); ?></strong></td>
                    <td><?php echo htmlspecialchars($doc['DOC_NOMBRE']); ?></td>
                    <td><?php echo htmlspecialchars($doc['TIP_NOMBRE']); ?></td>
                    <td><?php echo htmlspecialchars($doc['PRO_NOMBRE']); ?></td>
                    <td class="actions">
                        <a href="/documents/edit?id=<?php echo $doc['DOC_ID']; ?>" class="btn btn-warning">Editar</a>
                        <form method="POST" action="/documents/delete" style="display: inline;" 
                              onsubmit="return confirm('¿Está seguro de eliminar este documento?');">
                            <input type="hidden" name="id" value="<?php echo $doc['DOC_ID']; ?>">
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

</div>
</body>
</html>