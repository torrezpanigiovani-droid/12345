<?php
require_once 'config/Conexion.php';

class EstudianteModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = (new Conexion())->conectar();
    }

    public function listar(): array
    {
        $sql = "SELECT j.idJugador, j.jugador, j.posicion, j.dorsal, j.edad, j.club,
                       j.equipo_id, j.foto, e.equipo, e.grupo
                FROM jugadores j
                INNER JOIN equipos e ON e.idEquipo = j.equipo_id
                ORDER BY j.idJugador DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function listarEquipos(): array
    {
        return $this->pdo->query("SELECT idEquipo, equipo, grupo FROM equipos ORDER BY equipo ASC")->fetchAll();
    }

    public function obtener(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT j.idJugador, j.jugador, j.posicion, j.dorsal, j.edad, j.club,
                    j.equipo_id, j.foto, e.equipo, e.grupo
             FROM jugadores j
             INNER JOIN equipos e ON e.idEquipo = j.equipo_id
             WHERE j.idJugador = ?"
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function guardar(array $datos): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO jugadores (jugador, posicion, dorsal, edad, club, equipo_id, foto)
             VALUES (:jugador, :posicion, :dorsal, :edad, :club, :equipo_id, :foto)"
        );

        return $stmt->execute([
            ':jugador' => $datos['jugador'],
            ':posicion' => $datos['posicion'],
            ':dorsal' => $datos['dorsal'] !== '' ? $datos['dorsal'] : null,
            ':edad' => $datos['edad'] !== '' ? $datos['edad'] : null,
            ':club' => $datos['club'],
            ':equipo_id' => $datos['equipo_id'],
            ':foto' => $datos['foto'] ?: null,
        ]);
    }

    public function actualizar(array $datos): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE jugadores
             SET jugador = :jugador,
                 posicion = :posicion,
                 dorsal = :dorsal,
                 edad = :edad,
                 club = :club,
                 equipo_id = :equipo_id,
                 foto = :foto
             WHERE idJugador = :idJugador"
        );

        return $stmt->execute([
            ':idJugador' => $datos['idJugador'],
            ':jugador' => $datos['jugador'],
            ':posicion' => $datos['posicion'],
            ':dorsal' => $datos['dorsal'] !== '' ? $datos['dorsal'] : null,
            ':edad' => $datos['edad'] !== '' ? $datos['edad'] : null,
            ':club' => $datos['club'],
            ':equipo_id' => $datos['equipo_id'],
            ':foto' => $datos['foto'] ?: null,
        ]);
    }

    public function eliminar(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM jugadores WHERE idJugador = ?");
        return $stmt->execute([$id]);
    }
}
