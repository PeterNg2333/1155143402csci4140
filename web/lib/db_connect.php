<?php
include_once('utilities/sanitization.php');
include_once('utilities/validation.php');

$host = 'dpg-cn6tmn2cn0vc73dmghjg-a';
$dbname = 'db_1155143402csci4140';
$username = 'db_1155143402csci4140_user';
$password = 'RQHfnfnO07Owiin69v9mf375Vrkd2yPi';

//////////////////////////////////////////////////////////////////////////////////
                            //////// DataBase ///////
//////////////////////////////////////////////////////////////////////////////////
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

function csci4140_db_test(){
    try {
        global $conn;
        $conn = db_connect();
        if ($conn instanceof PDOException) {
            return "Unable to connect to the database: " . $conn->getMessage();
        }
        $stmt = $conn->query('SELECT version()');
        $version = $stmt->fetchColumn();
        return "Successfully connected to the Database. Version: " . $version;
    
    } catch(PDOException $e) {
        return "Error in query: " . $e->getMessage();
    }
}

//////////////////////////////////////////////////////////////////////////////////
                            //////// Request ///////
//////////////////////////////////////////////////////////////////////////////////

function csci4140_show_request(){
    return json_encode($_REQUEST);
}

//////////////////////////////////////////////////////////////////////////////////
                      //////// Password Management ///////
//////////////////////////////////////////////////////////////////////////////////
function csci4140_create_pd(){
    global $conn;
    $conn = db_connect();
    if ($conn instanceof PDOException) {
        return "Unable to connect to the database: " . $conn->getMessage();
    }
    $username = validate_input(string_sanitization($_REQUEST['username']), '/[^$@\'&"=|]+/', "invalid-username");
    $password = validate_input(string_sanitization($_REQUEST['password']), '/[^$@\'&"=|]+/', "invalid-password");
    $flag = 0;
    if (strpos($username, "user"))
        $flag = 0;
    else if (strpos($username, "admin"))
        $flag = 1;
    $salt = random_int(0, PHP_INT_MAX);
    $hash_password = hash_hmac('sha256', $password, $salt);

    $query = $conn->prepare('INSERT INTO myuser(id, name, hash_password, salt, flag) VALUES (NULL, ?, ?, ?, ?);');
    $query -> bindParam(1, $username);
    $query -> bindParam(2, $hash_password);
    $query -> bindParam(3, $salt);
    $query -> bindParam(4, $flag);

    if ($query->execute()) {
        return "Successfully created the accound for " . $username;
    } else {
        return "Error in query";
    }
}
?>