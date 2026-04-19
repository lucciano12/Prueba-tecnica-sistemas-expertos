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

INSERT INTO bodegas (codigo, nombre, ubicacion, dotacion, estado) VALUES
('BOD01', 'Bodega Norte',   'Av. Peru 523', 10, 'Activada'),
('BOD02', 'Bodega Sur',     'Union 2525',     5, 'Activada'),
('BOD03', 'Bodega Central', 'Alvarez 800', 15, 'Desactivada');