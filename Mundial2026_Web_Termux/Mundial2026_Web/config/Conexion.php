<?php 

class Conexion{
	
	private $host = 'localhost';
	private $pass = '';
	private $db = '';
	private $user = 'root';
	private $charset = 'utf8mb4';
	private $pdo = null;

	public function conectar(){
		try {
			$dsn = "mysql:host=$this->host; dbname=$this->db; charset=$this->charset";
			
			$opcions = [
				PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        		PDO::ATTR_EMULATE_PREPARES   => false,
			];
			$this->pdo = new PDO($dsn, $this->user, $this->pass, $opcions);
		} catch (Exception $e) {
			echo "Error de conexión: " . $e->getMessage();
		}
		return $this->pdo;
	}

}

?>
