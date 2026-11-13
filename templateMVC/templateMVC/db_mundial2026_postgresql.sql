-- PostgreSQL: crear primero la base de datos db_mundial2026 y luego ejecutar este archivo.
-- CREATE DATABASE db_mundial2026;

DROP TABLE IF EXISTS partidos CASCADE;
DROP TABLE IF EXISTS jugadores CASCADE;
DROP TABLE IF EXISTS equipos CASCADE;
DROP TABLE IF EXISTS sedes CASCADE;

CREATE TABLE sedes (
  idSede SERIAL PRIMARY KEY,
  sede VARCHAR(100) NOT NULL,
  ciudad VARCHAR(100) NOT NULL,
  pais VARCHAR(50) NOT NULL,
  capacidad INTEGER NOT NULL
);

CREATE TABLE equipos (
  idEquipo SERIAL PRIMARY KEY,
  equipo VARCHAR(50) NOT NULL,
  grupo CHAR(1),
  confederacion VARCHAR(20) NOT NULL
);

CREATE TABLE jugadores (
  idJugador SERIAL PRIMARY KEY,
  jugador VARCHAR(100) NOT NULL,
  posicion VARCHAR(50) NOT NULL,
  dorsal INTEGER,
  edad INTEGER,
  club VARCHAR(50) NOT NULL,
  equipo_id INTEGER NOT NULL,
  foto VARCHAR(255),
  CONSTRAINT fk_jugadores_equipo
    FOREIGN KEY (equipo_id) REFERENCES equipos(idEquipo)
    ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE partidos (
  idPartido SERIAL PRIMARY KEY,
  fase VARCHAR(50) NOT NULL DEFAULT 'Fase de Grupos',
  fecha TIMESTAMP,
  sede_id INTEGER,
  equipo_local_id INTEGER NOT NULL,
  equipo_visitante_id INTEGER NOT NULL,
  goles_local INTEGER NOT NULL DEFAULT 0,
  goles_visitante INTEGER NOT NULL DEFAULT 0,
  estado VARCHAR(20) NOT NULL DEFAULT 'Programado',
  CONSTRAINT fk_partidos_sede
    FOREIGN KEY (sede_id) REFERENCES sedes(idSede)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT fk_partidos_local
    FOREIGN KEY (equipo_local_id) REFERENCES equipos(idEquipo)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_partidos_visitante
    FOREIGN KEY (equipo_visitante_id) REFERENCES equipos(idEquipo)
    ON UPDATE CASCADE ON DELETE CASCADE
);

INSERT INTO sedes (sede, ciudad, pais, capacidad) VALUES
('Estadio Azteca', 'Ciudad de México', 'México', 87523),
('MetLife Stadium', 'East Rutherford', 'Estados Unidos', 82500),
('BMO Field', 'Toronto', 'Canadá', 45736);

INSERT INTO equipos (equipo, grupo, confederacion) VALUES
('México', 'A', 'CONCACAF'),
('Argentina', 'A', 'CONMEBOL'),
('España', 'B', 'UEFA'),
('Francia', 'C', 'UEFA');

INSERT INTO jugadores (jugador, posicion, dorsal, edad, club, equipo_id, foto) VALUES
('Edson Álvarez', 'Centrocampista', 4, 28, 'West Ham United', 1, NULL),
('Santiago Giménez', 'Delantero', 11, 25, 'Feyenoord', 1, NULL),
('Lionel Messi', 'Delantero', 10, 38, 'Inter Miami', 2, NULL),
('Julián Álvarez', 'Delantero', 9, 26, 'Atlético de Madrid', 2, NULL),
('Emiliano Martínez', 'Portero', 23, 33, 'Aston Villa', 2, NULL),
('Lamine Yamal', 'Delantero', 19, 18, 'FC Barcelona', 3, NULL),
('Pedri', 'Centrocampista', 8, 23, 'FC Barcelona', 3, NULL),
('Kylian Mbappé', 'Delantero', 10, 27, 'Real Madrid', 4, NULL),
('Eduardo Camavinga', 'Centrocampista', 6, 23, 'Real Madrid', 4, NULL);

INSERT INTO partidos (fase, fecha, sede_id, equipo_local_id, equipo_visitante_id, goles_local, goles_visitante, estado) VALUES
('Fase de Grupos', '2026-06-11 15:00:00', 1, 1, 2, 0, 0, 'Programado'),
('Fase de Grupos', '2026-06-12 18:00:00', 2, 3, 4, 0, 0, 'Programado');
