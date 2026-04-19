<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Bodegas</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        h1 { color: #333; }
        .container { max-width: 1100px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background: #4a90d9; color: white; padding: 10px; text-align: left; }
        td { padding: 10px; border-bottom: 1px solid #ddd; }
        tr:hover { background: #f0f7ff; }
        .btn { padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 13px; }
        .btn-primary { background: #4a90d9; color: white; }
        .btn-warning { background: #f0a500; color: white; }
        .btn-danger  { background: #e74c3c; color: white; }
        .badge-activa    { background: #27ae60; color: white; padding: 3px 8px; border-radius: 12px; font-size: 12px; }
        .badge-desactiva { background: #e74c3c; color: white; padding: 3px 8px; border-radius: 12px; font-size: 12px; }
        .filtros { margin: 15px 0; display: flex; gap: 10px; align-items: center; }
        .filtros select { padding: 6px 10px; border-radius: 4px; border: 1px solid #ccc; }
    </style>
</head>
<body>
<div class="container">
    <h1>Gestión de Bodegas</h1>

    <!-- Botón crear nueva bodega -->
    <a href="index.php?action=create" class="btn btn-primary">+ Nueva Bodega</a>

    <!-- Filtro por estado -->
    <div class="filtros">
        <label for="filtroEstado"><strong>Filtrar por estado:</strong></label>
        <select id="filtroEstado" onchange="filtrarEstado(this.value)">
            <option value="todos"      <?= ($filtro === 'todos')       ? 'selected' : '' ?>>Todos</option>
            <option value="Activada"   <?= ($filtro === 'Activada')    ? 'selected' : '' ?>>Activada</option>
            <option value="Desactivada"<?= ($filtro === 'Desactivada') ? 'selected' : '' ?>>Desactivada</option>
        </select>
    </div>

    <!-- Tabla de bodegas -->
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Dotación</th>
                <th>Encargado</th>
                <th>Fecha Creación</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($bodegas)): ?>
                <tr>
                    <td colspan="8" style="text-align:center; color:#999;">No hay bodegas registradas</td>
                </tr>
            <?php else: ?>
                <?php foreach ($bodegas as $bodega): ?>
                <tr>
                    <td><?= htmlspecialchars($bodega['codigo']) ?></td>
                    <td><?= htmlspecialchars($bodega['nombre']) ?></td>
                    <td><?= htmlspecialchars($bodega['ubicacion']) ?></td>
                    <td><?= htmlspecialchars($bodega['dotacion']) ?></td>
                    <td><?= htmlspecialchars($bodega['encargado'] ?? 'Sin encargado') ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($bodega['created_at'])) ?></td>
                    <td>
                        <?php if ($bodega['estado'] === 'Activada'): ?>
                            <span class="badge-activa">Activada</span>
                        <?php else: ?>
                            <span class="badge-desactiva">Desactivada</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="index.php?action=edit&id=<?= $bodega['id'] ?>" class="btn btn-warning">Editar</a>
                        <a href="index.php?action=delete&id=<?= $bodega['id'] ?>" 
                           class="btn btn-danger"
                           onclick="return confirmarEliminar(event)">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    // Filtra el listado por estado redirigiendo con el parámetro en la URL
    function filtrarEstado(valor) {
        window.location.href = 'index.php?action=index&estado=' + valor;
    }

    // Muestra alerta de confirmación antes de eliminar una bodega
    function confirmarEliminar(e) {
        if (!confirm('¿Estás seguro que deseas eliminar esta bodega?\nEsta acción no se puede deshacer.')) {
            e.preventDefault();
            return false;
        }
        return true;
    }
</script>
</body>
</html>