CREATE DATABASE IF NOT EXISTS db_mundial2026
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_mundial2026;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS partidos;
DROP TABLE IF EXISTS jugadores;
DROP TABLE IF EXISTS equipos;
DROP TABLE IF EXISTS sedes;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE sedes (
  idSede INT AUTO_INCREMENT PRIMARY KEY,
  sede VARCHAR(100) NOT NULL,
  ciudad VARCHAR(100) NOT NULL,
  pais VARCHAR(50) NOT NULL,
  capacidad INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE equipos (
  idEquipo INT AUTO_INCREMENT PRIMARY KEY,
  equipo VARCHAR(50) NOT NULL,
  grupo CHAR(1) NULL,
  confederacion VARCHAR(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE jugadores (
  idJugador INT AUTO_INCREMENT PRIMARY KEY,
  jugador VARCHAR(100) NOT NULL,
  posicion VARCHAR(50) NOT NULL,
  dorsal INT NULL,
  edad INT NULL,
  club VARCHAR(50) NOT NULL,
  equipo_id INT NOT NULL,
  foto VARCHAR(255) NULL,
  INDEX idx_jugadores_equipo (equipo_id),
  CONSTRAINT fk_jugadores_equipo
    FOREIGN KEY (equipo_id) REFERENCES equipos(idEquipo)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE partidos (
  idPartido INT AUTO_INCREMENT PRIMARY KEY,
  fase VARCHAR(50) NOT NULL DEFAULT 'Fase de Grupos',
  fecha DATETIME NULL,
  sede_id INT NULL,
  equipo_local_id INT NOT NULL,
  equipo_visitante_id INT NOT NULL,
  goles_local INT NOT NULL DEFAULT 0,
  goles_visitante INT NOT NULL DEFAULT 0,
  estado VARCHAR(20) NOT NULL DEFAULT 'Programado',
  INDEX idx_partidos_sede (sede_id),
  INDEX idx_partidos_local (equipo_local_id),
  INDEX idx_partidos_visitante (equipo_visitante_id),
  CONSTRAINT fk_partidos_sede
    FOREIGN KEY (sede_id) REFERENCES sedes(idSede)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT fk_partidos_local
    FOREIGN KEY (equipo_local_id) REFERENCES equipos(idEquipo)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_partidos_visitante
    FOREIGN KEY (equipo_visitante_id) REFERENCES equipos(idEquipo)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
