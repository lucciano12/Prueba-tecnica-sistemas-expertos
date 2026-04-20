<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Bodegas</title>
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/tabla.css">
</head>
<body>
<div class="container">
    <h1>Gestión de Bodegas</h1>

    <a href="index.php?action=create" class="btn btn-primary">+ Nueva Bodega</a>

    <div class="filtros">
        <label for="filtroEstado"><strong>Filtrar por estado:</strong></label>
        <select id="filtroEstado" onchange="filtrarEstado(this.value)">
            <option value="todos"       <?= ($filtro === 'todos')       ? 'selected' : '' ?>>Todos</option>
            <option value="Activada"    <?= ($filtro === 'Activada')    ? 'selected' : '' ?>>Activada</option>
            <option value="Desactivada" <?= ($filtro === 'Desactivada') ? 'selected' : '' ?>>Desactivada</option>
        </select>
    </div>

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
                    <td><?= htmlspecialchars($bodega['encargado_nombre'] ?? 'Sin encargado') ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($bodega['created_at'])) ?></td>
                    <td>
                        <?php if ($bodega['estado'] === 'Activada'): ?>
                            <span class="badge-activa">Activada</span>
                        <?php else: ?>
                            <span class="badge-desactiva">Desactivada</span>
                        <?php endif; ?>
                    </td>
                    <td class="acciones">
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
    function filtrarEstado(valor) {
        window.location.href = 'index.php?action=index&estado=' + valor;
    }

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
