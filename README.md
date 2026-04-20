# Prueba Técnica — Mantenedor de Bodegas

Módulo web desarrollado en PHP 7 con arquitectura MVC para administrar bodegas.
Permite registrar, listar, editar y eliminar bodegas, con filtro por estado.

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
│   └── BodegaController.php         # Maneja las acciones: listar, crear, guardar, editar, actualizar, eliminar
├── models/
│   └── Bodega.php                   # Consultas SQL encapsuladas
├── views/
│   └── bodegas/
│       ├── index.php                # Listado con filtro por estado
│       ├── create.php               # Formulario nueva bodega
│       └── edit.php                 # Formulario editar bodega
└── DB/
    └── schema.sql                   # Tablas + datos de prueba (encargados)
```

---

## Patrón de arquitectura

El proyecto sigue el patrón **MVC (Modelo-Vista-Controlador)**:

- **Model** (`models/Bodega.php`) — encapsula todas las consultas SQL a PostgreSQL
- **View** (`views/bodegas/`) — plantillas HTML/PHP que solo renderizan datos, sin lógica de negocio
- **Controller** (`controllers/BodegaController.php`) — recibe la acción, llama al modelo y carga la vista correspondiente
- **Router** (`index.php`) — lee el parámetro `?action=` de la URL y delega al controlador

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
| `estado`     | VARCHAR(20)   | `Activada` o `Desactivada`                |
| `created_at` | TIMESTAMP     | Fecha y hora de creación (automática)     |
| `updated_at` | TIMESTAMP     | Fecha y hora de última modificación       |

### Tabla `encargados`

| Columna      | Tipo          | Descripción                               |
|--------------|---------------|-------------------------------------------|
| `id`         | SERIAL PK     | Identificador único                       |
| `bodega_id`  | INTEGER FK    | Referencia a `bodegas.id`                 |
| `run`        | VARCHAR(12)   | RUN del encargado                         |
| `nombre`     | VARCHAR(100)  | Nombre                                    |
| `apellido1`  | VARCHAR(100)  | Primer apellido                           |
| `apellido2`  | VARCHAR(100)  | Segundo apellido                          |
| `direccion`  | VARCHAR(200)  | Dirección del encargado                   |
| `telefono`   | VARCHAR(20)   | Teléfono de contacto                      |

`encargados` tiene relación N:1 con `bodegas` — una bodega puede tener más de un encargado.
Los encargados se gestionan directamente por base de datos; no tienen formulario en esta versión.

---

## Sin dependencias externas

No se usaron frameworks ni librerías de terceros. Todo está construido con PHP nativo, HTML, CSS y JavaScript vanilla.

---

## Notas

- El `index.php` actúa como front controller: recibe el parámetro `?action=` y delega al `BodegaController`.
- La validación se aplica tanto en el cliente (JS) como en el servidor (PHP) antes de ejecutar cualquier operación en BD.
- El estado por defecto al crear una bodega es `Activada`, según lo indicado en el requerimiento.
