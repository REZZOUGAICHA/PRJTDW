<?php
class Database {
    private $dbname = "PRJTDW";
    private $host = "127.0.0.1";
    private $user = "root";
    private $password = "";
    private $connection;

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

    public function request($conn, $sql, $params = [], $types = []) {
        $stmt = $conn->prepare($sql);
        
        // Bind each parameter with its specific type if provided
        foreach ($params as $key => $value) {
            $paramType = PDO::PARAM_STR; // default to string
            if (isset($types[$key])) {
                $paramType = $types[$key];
            }
            $stmt->bindValue($key, $value, $paramType);
        }
        
        $stmt->execute();
        return $stmt;
    }

    public function deconnexion() {
        $this->connection = null; 
    }
}
?>