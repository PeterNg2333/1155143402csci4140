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
session_start(['cookie_httponly' => true, 'cookie_secure' => true,]);
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
function csci4140_login(){
    global $conn;
    $conn = db_connect();
    if ($conn instanceof PDOException) {
        return "Unable to connect to the database: " . $conn->getMessage();
    }
    $username = validate_input(string_sanitization($_POST['username']), '/[^$@\'&"=|]+/', "invalid-username");
    $password = validate_input(string_sanitization($_POST['password']), '/[^$@\'&"=|]+/', "invalid-password");

    $query = $conn->prepare("Select * FROM MYUSER WHERE name = ? LIMIT 1;");
    $query->bindParam(1, $username);
    if (!($query->execute())){
        return "Error in query";
    }
    if ($query->rowCount() == 0){
        return "User not found";
    }
    $db_user = $query->fetchAll()[0];
    $db_hash_password = $db_user["hash_password"];
    $db_flag = $db_user["flag"];
    $db_salt = $db_user["salt"];
    $new_hash_password = hash_hmac('sha256', $password, $db_salt);

    if ($new_hash_password == $db_hash_password){
        // When successfully authenticated,
        // 1. create authentication token
        $exp = time() + 3600*24;
        $hash = hash_hmac('sha256', $username . $exp, $db_salt);
        $token = array('name'=>$username, 'exp'=>$exp, 'k'=> ($hash));
        setcookie('auth', json_encode($token), $exp, "/", "", true, true);
        $_SESSION['auth'] = $token;
        session_regenerate_id();
    } else {
        return "Wrong user name or password";
    }

    // 2. redirect to page 
    if ($db_flag==1){
        header('Location: ../index.php?role=admin', true, 302);
        return "Successfully logged in as admin";
    } 
    else{
        header('Location: ../index.php?role=user', true, 302);
        return "Successfully logged in as user";
    } 
}


function csci4140_create_pd(){
    global $conn;
    $conn = db_connect();
    if ($conn instanceof PDOException) {
        return "Unable to connect to the database: " . $conn->getMessage();
    }
    $username = validate_input(string_sanitization($_REQUEST['username']), '/[^$@\'&"=|]+/', "invalid-username");
    $password = validate_input(string_sanitization($_REQUEST['password']), '/[^$@\'&"=|]+/', "invalid-password");
    $flag = 1;
    $salt = random_int(0, PHP_INT_MAX);
    $hash_password = hash_hmac('sha256', $password, $salt);

    $query = $conn->prepare('INSERT INTO myuser(name, hash_password, salt, flag) VALUES (?, ?, ?, ?);');
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