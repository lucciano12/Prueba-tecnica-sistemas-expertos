CREATE TABLE bodegas (
    id          SERIAL PRIMARY KEY,
    codigo      VARCHAR(5)   NOT NULL UNIQUE,
    nombre      VARCHAR(100)  NOT NULL,
    ubicacion   VARCHAR(200)  NOT NULL,
    dotacion    INTEGER       NOT NULL DEFAULT 0,
    estado      VARCHAR(15)   NOT NULL DEFAULT 'Activada'
                CONSTRAINT bodegas_estado_check CHECK (estado IN ('Activada', 'Desactivada')),
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
);

CREATE TABLE encargados (
    id          SERIAL PRIMARY KEY,
    bodega_id   INTEGER NOT NULL REFERENCES bodegas(id) ON DELETE CASCADE,
    run         VARCHAR(12)  NOT NULL,
    nombre      VARCHAR(100) NOT NULL,
    apellido1   VARCHAR(100) NOT NULL,
    apellido2   VARCHAR(100),
    direccion   VARCHAR(200),
    telefono    VARCHAR(20),
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Datos de prueba: bodegas
INSERT INTO bodegas (codigo, nombre, ubicacion, dotacion, estado) VALUES
('BOD01', 'Bodega Norte',   'Av. Peru 523',   10, 'Activada'),
('BOD02', 'Bodega Sur',     'Union 2525',       5, 'Activada'),
('BOD03', 'Bodega Central', 'Alvarez 800',     15, 'Desactivada');

-- Datos de prueba: encargados (direccion corresponde al domicilio personal del encargado)
-- Bodega Norte (id=1) tiene 2 encargados para validar la relacion uno a muchos
INSERT INTO encargados (bodega_id, run, nombre, apellido1, apellido2, direccion, telefono) VALUES
(1, '12345678-9', 'Carlos',  'Gonzalez', 'Perez',  'Av. Libertad 1250, Viña del Mar',      '+56912345678'),
(1, '22222222-2', 'Ana',     'Torres',   'Munoz',  'Calle Valparaíso 345, Viña del Mar',   '+56922222222'),
(2, '98765432-1', 'Maria',   'Lopez',    'Silva',  'Av. España 890, Viña del Mar',         '+56987654321'),
(3, '11111111-1', 'Pedro',   'Martinez', 'Rojas',  'Pasaje Ecuador 78, Viña del Mar',      '+56911111111');
