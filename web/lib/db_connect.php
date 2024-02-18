<?php
$host = 'dpg-cn6tmn2cn0vc73dmghjg-a';
$dbname = 'db_1155143402csci4140';
$username = 'db_1155143402csci4140_user';
$password = 'RQHfnfnO07Owiin69v9mf375Vrkd2yPi';


function db_connect() {
    global $host, $dbname, $username, $password;
    try {
        $conn = new PDO("pgsql:host=$host;port=5432;dbname=$dbname;user=$username;password=$password");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        return $e;
    }
}

function db_test(){
    try {
        global $conn;
        $conn = db_connect();
        if ($conn instanceof PDOException) {
            return "<p>Unable to connect to the database: " . $conn->getMessage() . "</p>";
        }
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->query('SELECT version()');
        $version = $stmt->fetchColumn();
        return "<p>Successfully connected to the Database. Version: " . $version . "</p>";
    
    } catch(PDOException $e) {
        return "<p>Error in query: " . $e->getMessage() . "</p>";
    }
}
?>
