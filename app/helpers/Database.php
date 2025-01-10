<?php
class Database {
    private $dbname = "PRJTDW";
    private $host = "127.0.0.1";
    private $user = "root";
    private $password = "";
    private $connection;
    private $debug = true; // Toggle debugging mode

    public function connexion() {
        $dsn = "mysql:dbname=$this->dbname;host=$this->host;";
        try {
            $this->connection = new PDO($dsn, $this->user, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->connection;
        } catch (PDOException $ex) {
            $this->handleError('Connection Error', $ex);
            exit();
        }
    }

    public function request($conn, $sql, $params = [], $types = []) {
        try {
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
        } catch (PDOException $ex) {
            $errorCode = $ex->getCode();
            
            // Handle specific MySQL error codes
            switch ($errorCode) {
                case '42S02': // Table not found
                    $this->handleError('Table Not Found', $ex, [
                        'SQL' => $sql,
                        'Parameters' => $params
                    ]);
                    break;
                    
                case '23000': // Duplicate entry
                    $this->handleError('Duplicate Entry', $ex, [
                        'SQL' => $sql,
                        'Parameters' => $params
                    ]);
                    break;
                    
                case '42000': // Syntax error
                    $this->handleError('SQL Syntax Error', $ex, [
                        'SQL' => $sql,
                        'Parameters' => $params
                    ]);
                    break;
                    
                default:
                    $this->handleError('Database Error', $ex, [
                        'SQL' => $sql,
                        'Parameters' => $params
                    ]);
            }
            
            throw $ex; // Re-throw the exception after logging
        }
    }

    private function handleError($type, PDOException $ex, array $context = []) {
        $errorMessage = "[" . date('Y-m-d H:i:s') . "] ";
        $errorMessage .= "TYPE: {$type}\n";
        $errorMessage .= "CODE: " . $ex->getCode() . "\n";
        $errorMessage .= "MESSAGE: " . $ex->getMessage() . "\n";
        
        if (!empty($context)) {
            $errorMessage .= "CONTEXT:\n";
            foreach ($context as $key => $value) {
                $errorMessage .= "\t{$key}: " . print_r($value, true) . "\n";
            }
        }
        
        $errorMessage .= "TRACE:\n" . $ex->getTraceAsString() . "\n";
        $errorMessage .= "------------------------------------------------\n";

        // Log to file
        error_log($errorMessage, 3, __DIR__ . '/../../logs/database_errors.log');

        // Display error if debugging is enabled
        if ($this->debug) {
            echo "<div style='background-color: #ffebee; border: 1px solid #ef5350; padding: 15px; margin: 10px; border-radius: 4px;'>";
            echo "<h3 style='color: #c62828; margin-top: 0;'>Database Error: {$type}</h3>";
            echo "<pre style='background-color: #fff; padding: 10px; border-radius: 4px;'>";
            echo htmlspecialchars($errorMessage);
            echo "</pre>";
            echo "</div>";
        } else {
            // Generic error message for production
            echo "<div style='background-color: #ffebee; border: 1px solid #ef5350; padding: 15px; margin: 10px; border-radius: 4px;'>";
            echo "<p style='color: #c62828; margin: 0;'>Une erreur est survenue. Veuillez réessayer plus tard.</p>";
            echo "</div>";
        }
    }

    public function deconnexion() {
        $this->connection = null;
    }

    // Setter for debug mode
    public function setDebug($mode) {
        $this->debug = (bool) $mode;
    }
}
?>