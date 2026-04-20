<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Bodega</title>
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/form.css">
</head>
<body>
<div class="container-narrow">

    <p class="breadcrumb">
        <a href="index.php?action=index">Bodegas</a> &rsaquo; Editar Bodega
    </p>

    <h1>Editar Bodega</h1>

    <?php if (!empty($error)): ?>
        <div class="alert-error">
            ⚠️ <?= $error ?>
        </div>
    <?php endif; ?>

    <form action="index.php?action=update&id=<?= $bodega['id'] ?>" method="POST" id="formEditar" novalidate>

        <div class="form-row">
            <div class="form-group">
                <label for="codigo">Codigo <span class="required">*</span></label>
                <input type="text" id="codigo" name="codigo"
                       placeholder="Ej: BOD01"
                       maxlength="5"
                       value="<?= htmlspecialchars($bodega['codigo']) ?>">
                <span class="error-msg" id="err-codigo">El codigo es obligatorio</span>
            </div>

            <div class="form-group">
                <label for="nombre">Nombre <span class="required">*</span></label>
                <input type="text" id="nombre" name="nombre"
                       placeholder="Ej: Bodega Central"
                       maxlength="100"
                       value="<?= htmlspecialchars($bodega['nombre']) ?>">
                <span class="error-msg" id="err-nombre">El nombre es obligatorio</span>
            </div>
        </div>

        <div class="form-group">
            <label for="ubicacion">Direccion / Ubicacion <span class="required">*</span></label>
            <input type="text" id="ubicacion" name="ubicacion"
                   placeholder="Ej: Av. Principal 123, Vina del Mar"
                   maxlength="200"
                   value="<?= htmlspecialchars($bodega['ubicacion']) ?>">
            <span class="error-msg" id="err-ubicacion">La ubicacion es obligatoria</span>
        </div>

        <div class="form-group">
            <label for="dotacion">Dotacion (N° personas) <span class="required">*</span></label>
            <input type="number" id="dotacion" name="dotacion"
                   placeholder="Ej: 10"
                   min="0" max="9999"
                   value="<?= htmlspecialchars($bodega['dotacion']) ?>">
            <span class="error-msg" id="err-dotacion">La dotacion es obligatoria</span>
        </div>

        <div class="form-group">
            <label for="estado">Estado <span class="required">*</span></label>
            <select id="estado" name="estado">
                <option value="">-- Seleccionar --</option>
                <option value="Activada"    <?= ($bodega['estado'] === 'Activada')    ? 'selected' : '' ?>>Activada</option>
                <option value="Desactivada" <?= ($bodega['estado'] === 'Desactivada') ? 'selected' : '' ?>>Desactivada</option>
            </select>
            <span class="error-msg" id="err-estado">Debes seleccionar un estado</span>
        </div>

        <div class="form-actions">
            <a href="index.php?action=index" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Actualizar Bodega</button>
        </div>

    </form>
</div>

<script>
document.getElementById('formEditar').addEventListener('submit', function(e) {
    let valido = true;
    const campos = [
        { id: 'codigo',    err: 'err-codigo' },
        { id: 'nombre',    err: 'err-nombre' },
        { id: 'ubicacion', err: 'err-ubicacion' },
        { id: 'dotacion',  err: 'err-dotacion' },
        { id: 'estado',    err: 'err-estado' }
    ];
    campos.forEach(function(campo) {
        const input = document.getElementById(campo.id);
        const errSpan = document.getElementById(campo.err);
        if (!input.value.trim()) {
            input.classList.add('error');
            errSpan.classList.add('show');
            valido = false;
        } else {
            input.classList.remove('error');
            errSpan.classList.remove('show');
        }
    });
    if (!valido) {
        e.preventDefault();
        document.querySelector('.error').focus();
    }
});

document.querySelectorAll('input, select').forEach(function(el) {
    el.addEventListener('input', function() {
        this.classList.remove('error');
        const errSpan = document.getElementById('err-' + this.id);
        if (errSpan) errSpan.classList.remove('show');
    });
});
</script>
</body>
</html>
