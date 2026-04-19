CREATE TABLE bodegas (
    id          SERIAL PRIMARY KEY,
    nombre      VARCHAR(100)  NOT NULL,
    ubicacion   VARCHAR(200)  NOT NULL,
    capacidad   INTEGER       NOT NULL DEFAULT 0,
    estado      VARCHAR(10)   NOT NULL DEFAULT 'activo'
                CHECK (estado IN ('activo', 'inactivo')),
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
);


INSERT INTO bodegas (nombre, ubicacion, capacidad, estado) VALUES
('Bodega Norte',   'Av. Peru 523', 500, 'activo'),
('Bodega Sur',     'Union 2525',     300, 'activo'),
('Bodega Central', 'Alvarez 800', 750, 'inactivo');