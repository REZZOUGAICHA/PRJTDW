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

    public function request($conn, $sql, $params = []) {
    try {
        $stmt = $conn->prepare($sql);
        error_log('Executing SQL: ' . $sql);
        error_log('With parameters: ' . print_r($params, true));
        $result = $stmt->execute($params);
        error_log('SQL execution result: ' . ($result ? 'success' : 'failure'));
        return $stmt;
    } catch (PDOException $e) {
        error_log('Database error: ' . $e->getMessage());
        throw $e;
    }
}

    public function deconnexion() {
        $this->connection = null; 
    }
}
?>