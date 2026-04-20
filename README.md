# Prueba Técnica — Mantenedor de Bodegas

Módulo web desarrollado en PHP 7 con arquitectura MVC para administrar bodegas.
Permite registrar, listar, editar y eliminar bodegas, con filtro por estado y reasignación de encargado.

---

## Requisitos del entorno

| Componente   | Versión        |
|--------------|----------------|
| Apache       | 2.4+           |
| PHP          | 7.x            |
| PostgreSQL   | 12+            |

**Extensiones PHP necesarias:**
- `pdo`
- `pdo_pgsql`

---

## Instalación

**1. Clonar el proyecto**

```bash
git clone https://github.com/lucciano12/Prueba-tecnica-sistemas-expertos.git
```

**2. Crear la base de datos**

```bash
psql -U postgres -c "CREATE DATABASE sistemas_expertos_db;"
```

**3. Restaurar el schema**

```bash
psql -U postgres -d sistemas_expertos_db -f DB/schema.sql
```

**4. Ajustar la conexión**

Editar `config/database.php` con los datos de tu entorno local:

```php
define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_NAME', 'sistemas_expertos_db');
define('DB_USER', 'postgres');
define('DB_PASS', '');  // agregar contraseña si corresponde
```

**5. Levantar con Apache**

Copiar la carpeta del proyecto dentro de `htdocs/` (XAMPP) o `www/` (Laragon) y acceder en:

```
http://localhost/Prueba-tecnica-sistemas-expertos/index.php
```

---

## Estructura

```
├── index.php                        # Router: lee ?action= y llama al controlador
├── config/
│   └── database.php                 # Conexión PDO a PostgreSQL
├── controllers/
│   └── BodegaController.php         # Acciones: listar, crear, guardar, editar, actualizar, eliminar
├── models/
│   ├── Bodega.php                   # Consultas SQL de bodegas
│   └── Encargado.php                # Consultas SQL de encargados (listar, reasignar)
├── views/
│   └── bodegas/
│       ├── index.php                # Listado con filtro por estado
│       ├── create.php               # Formulario nueva bodega
│       └── edit.php                 # Formulario editar bodega (incluye select de encargado)
├── assets/
│   └── css/
│       ├── base.css                 # Reset, variables, botones, contenedor
│       ├── tabla.css                # Estilos del listado y badges de estado
│       └── form.css                 # Estilos de formularios create y edit
└── DB/
    └── schema.sql                   # Tablas + datos de prueba
```

---

## Patrón de arquitectura

El proyecto sigue el patrón **MVC (Modelo-Vista-Controlador)**:

- **Model** (`models/`) — encapsula todas las consultas SQL a PostgreSQL mediante PDO
- **View** (`views/bodegas/`) — plantillas HTML/PHP que solo renderizan datos, sin lógica de negocio
- **Controller** (`controllers/BodegaController.php`) — recibe la acción, llama al modelo y carga la vista
- **Router** (`index.php`) — lee el parámetro `?action=` de la URL y delega al controlador

---

## Funcionalidades implementadas

| # | Requerimiento | Estado |
|---|---|---|
| 1 | Agregar nueva bodega | ✅ |
| 2 | Listar bodegas con filtro por estado | ✅ |
| 3 | Editar bodega (nombre, dirección, dotación, encargado, estado) | ✅ |
| 4 | Eliminar bodega con confirmación | ✅ |

**Detalles adicionales:**
- Estado por defecto `Activada` al crear una bodega
- Validación doble: cliente (JavaScript) y servidor (PHP)
- Filtro por estado en el listado (`Activada` / `Desactivada` / Todos)
- Badge visual de estado en el listado
- CSS separado por responsabilidad (`base.css`, `tabla.css`, `form.css`)
- Reasignación de encargado desde el formulario de edición mediante `<select>` dinámico

---

## Modelo de datos

### Tabla `bodegas`

| Columna      | Tipo          | Descripción                               |
|--------------|---------------|-------------------------------------------|
| `id`         | SERIAL PK     | Identificador único                       |
| `codigo`     | VARCHAR(5)    | Código alfanumérico (máx. 5 caracteres)   |
| `nombre`     | VARCHAR(100)  | Nombre de la bodega                       |
| `ubicacion`  | VARCHAR(200)  | Dirección física                          |
| `dotacion`   | INTEGER       | Cantidad de personas que trabajan         |
| `estado`     | VARCHAR(15)   | `Activada` o `Desactivada`                |
| `created_at` | TIMESTAMP     | Fecha y hora de creación (automática)     |
| `updated_at` | TIMESTAMP     | Fecha y hora de última modificación       |

### Tabla `encargados`

| Columna      | Tipo          | Descripción                               |
|--------------|---------------|-------------------------------------------|
| `id`         | SERIAL PK     | Identificador único                       |
| `bodega_id`  | INTEGER FK    | Referencia a `bodegas.id` (ON DELETE CASCADE) |
| `run`        | VARCHAR(12)   | RUN del encargado                         |
| `nombre`     | VARCHAR(100)  | Nombre                                    |
| `apellido1`  | VARCHAR(100)  | Primer apellido                           |
| `apellido2`  | VARCHAR(100)  | Segundo apellido (opcional)               |
| `direccion`  | VARCHAR(200)  | Dirección personal del encargado          |
| `telefono`   | VARCHAR(20)   | Teléfono de contacto                      |

`encargados` tiene relación N:1 con `bodegas` — una bodega puede tener más de un encargado.
Al eliminar una bodega, sus encargados se eliminan automáticamente (`ON DELETE CASCADE`).

---

## Sin dependencias externas

No se usaron frameworks ni librerías de terceros. Todo está construido con PHP nativo, HTML, CSS y JavaScript vanilla.

---

## Notas técnicas

- El `index.php` actúa como front controller: recibe `?action=` y delega al `BodegaController`.
- La validación se aplica tanto en el cliente (JS) como en el servidor (PHP).
- El estado por defecto al crear una bodega es `Activada`, según lo indicado en el requerimiento.
- El campo `updated_at` se actualiza automáticamente con `CURRENT_TIMESTAMP` en cada edición.
- Al editar una bodega, el `<select>` de encargados muestra todos los registros de la tabla `encargados` y marca el/los encargado(s) actual(es) de esa bodega. Al seleccionar uno y guardar, se actualiza el `bodega_id` de ese encargado.
