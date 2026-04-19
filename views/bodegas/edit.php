<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Bodega</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; color: #333; }

        .container { max-width: 680px; margin: 40px auto; background: #fff; padding: 32px; border-radius: 8px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); }

        h1 { font-size: 1.4rem; margin-bottom: 8px; }
        .breadcrumb { font-size: 13px; color: #888; margin-bottom: 24px; }
        .breadcrumb a { color: #4a90d9; text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }

        .badge-id { display: inline-block; background: #f0f0f0; color: #888; font-size: 12px; padding: 2px 8px; border-radius: 20px; margin-left: 8px; vertical-align: middle; }

        .form-group { margin-bottom: 20px; }
        label { display: block; font-size: 14px; font-weight: 600; margin-bottom: 6px; color: #444; }
        label span.required { color: #e74c3c; margin-left: 3px; }

        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: #fff;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #4a90d9;
            box-shadow: 0 0 0 3px rgba(74,144,217,0.15);
        }
        input.error { border-color: #e74c3c; }
        .error-msg { font-size: 12px; color: #e74c3c; margin-top: 4px; display: none; }
        .error-msg.show { display: block; }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

        .form-actions { display: flex; gap: 12px; justify-content: flex-end; margin-top: 28px; padding-top: 20px; border-top: 1px solid #eee; }
        .btn { padding: 10px 20px; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer; border: none; text-decoration: none; display: inline-block; }
        .btn-primary   { background: #4a90d9; color: #fff; }
        .btn-primary:hover { background: #3a7bc8; }
        .btn-secondary { background: #f0f0f0; color: #555; }
        .btn-secondary:hover { background: #e0e0e0; }

        .info-created { font-size: 12px; color: #aaa; margin-top: 16px; text-align: right; }
    </style>
</head>
<body>
<div class="container">

    <!-- Breadcrumb -->
    <p class="breadcrumb">
        <a href="index.php?action=index">Bodegas</a> &rsaquo; Editar Bodega
    </p>

    <h1>
        Editar Bodega
        <!-- Muestra el ID de la bodega como referencia -->
        <span class="badge-id">#<?= htmlspecialchars($bodega['id']) ?></span>
    </h1>

    <!--
        El formulario envía por POST al método update().
        Se pasa el id como campo oculto porque POST no lleva parámetros en la URL.
    -->
    <form action="index.php?action=update" method="POST" id="formEditar" novalidate>
        <input type="hidden" name="id" value="<?= htmlspecialchars($bodega['id']) ?>">

        <!-- Fila 1: Código y Nombre -->
        <div class="form-row">
            <div class="form-group">
                <label for="codigo">Código <span class="required">*</span></label>
                <input type="text" id="codigo" name="codigo"
                       maxlength="20"
                       value="<?= htmlspecialchars($bodega['codigo']) ?>">
                <span class="error-msg" id="err-codigo">El código es obligatorio</span>
            </div>

            <div class="form-group">
                <label for="nombre">Nombre <span class="required">*</span></label>
                <input type="text" id="nombre" name="nombre"
                       maxlength="100"
                       value="<?= htmlspecialchars($bodega['nombre']) ?>">
                <span class="error-msg" id="err-nombre">El nombre es obligatorio</span>
            </div>
        </div>

        <!-- Fila 2: Ubicación -->
        <div class="form-group">
            <label for="ubicacion">Dirección / Ubicación <span class="required">*</span></label>
            <input type="text" id="ubicacion" name="ubicacion"
                   maxlength="200"
                   value="<?= htmlspecialchars($bodega['ubicacion']) ?>">
            <span class="error-msg" id="err-ubicacion">La ubicación es obligatoria</span>
        </div>

        <!-- Fila 3: Dotación y Encargado -->
        <div class="form-row">
            <div class="form-group">
                <label for="dotacion">Dotación (N° personas)</label>
                <input type="number" id="dotacion" name="dotacion"
                       min="0" max="9999"
                       value="<?= htmlspecialchars($bodega['dotacion'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="encargado">Encargado</label>
                <input type="text" id="encargado" name="encargado"
                       maxlength="100"
                       value="<?= htmlspecialchars($bodega['encargado'] ?? '') ?>">
            </div>
        </div>

        <!-- Fila 4: Estado -->
        <div class="form-group">
            <label for="estado">Estado <span class="required">*</span></label>
            <select id="estado" name="estado">
                <option value="">-- Seleccionar --</option>
                <option value="Activada"
                    <?= ($bodega['estado'] === 'Activada') ? 'selected' : '' ?>>
                    Activada
                </option>
                <option value="Desactivada"
                    <?= ($bodega['estado'] === 'Desactivada') ? 'selected' : '' ?>>
                    Desactivada
                </option>
            </select>
            <span class="error-msg" id="err-estado">Debes seleccionar un estado</span>
        </div>

        <!-- Botones -->
        <div class="form-actions">
            <a href="index.php?action=index" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Actualizar Bodega</button>
        </div>

    </form>

    <!-- Fecha de creación del registro como referencia -->
    <?php if (!empty($bodega['created_at'])): ?>
        <p class="info-created">
            Creado el <?= date('d/m/Y \a \l\a\s H:i', strtotime($bodega['created_at'])) ?>
        </p>
    <?php endif; ?>

</div>

<script>
// Validación del formulario de edición (misma lógica que create)
document.getElementById('formEditar').addEventListener('submit', function(e) {
    let valido = true;

    const campos = [
        { id: 'codigo',    err: 'err-codigo' },
        { id: 'nombre',    err: 'err-nombre' },
        { id: 'ubicacion', err: 'err-ubicacion' },
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

// Limpiar error al escribir
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