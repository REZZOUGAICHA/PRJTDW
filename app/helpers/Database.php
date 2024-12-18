<?php
class Database {
    private $dbname = "PRJTDW";
    private $host = "127.0.0.1";
    private $user = "root";
    private $password = "";
    private $connection;

    // Fonction de connexion
    public function connexion() {
        $dsn = "mysql:dbname=$this->dbname;host=$this->host;";
        try {
            $this->connection = new PDO($dsn, $this->user, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $ex) {
            echo "Erreur de connexion à la base de données : " . $ex->getMessage();
            exit();
        }
        return $this->connection;
    }

    public function request($conn, $sql, $params = []) {
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // Fonction de déconnexion
    public function deconnexion() {
        $this->connection = null; 
    }
}
?>
