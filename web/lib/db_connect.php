<?php
include_once('utilities/sanitization.php');
include_once('utilities/validation.php');
session_start(['cookie_httponly' => true, 'cookie_secure' => true,]);

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
                            //////// Image Management ///////
//////////////////////////////////////////////////////////////////////////////////
function csci4140_upload_image(){
    if (isset($_FILES['file'])) {
        $result = store_file($_FILES['file']);
        return $result;
    }else {
        echo "No file uploaded";
        return json_encode($_FILES['file']);
        
    }
}

function store_file($file){
    global $conn;
    $allowedTypes = ['image/jpg', 'image/png', 'image/gif'];
    $fileType = $file['type'];
    if (!in_array($fileType, $allowedTypes) || !in_array(mime_content_type($file['tmp_name']), $allowedTypes)) {
        return "Invalid file type";
    }
    else {
        $conn = db_connect();
        if ($conn instanceof PDOException) {
            return "Unable to connect to the database: " . $conn->getMessage();
        }   
        $img = file_get_contents($file['tmp_name']);
        $name = validate_input(string_sanitization($file['name']), '/^[\w\- ]+$/', "invalid-filename");
        $creator = get_id_from_username(is_auth());
        $is_public = validate_input(string_sanitization($_POST["isPublic"]), '/^[\w\- ]+$/', "invalid-flag");
        $flag = 0;
        if ($is_public == "on"){
            $flag = 1;
        }
        $query = $conn->prepare('INSERT INTO myimage(name, img, filetype, flag, creator) VALUES (?, ?, ?, ?, ?);');
        $query -> bindParam(1, $name);
        $query -> bindParam(2, $img, PDO::PARAM_LOB);
        $query -> bindParam(3, $fileType);
        $query -> bindParam(4, $flag);
        $query -> bindParam(5, $creator);
        $query->execute();
        $imageId = $conn->lastInsertId();
        return "Successfully uploaded the image with ID: " . $imageId;
    }
}

function retrieve_image(){
    global $conn;
    $conn = db_connect();
    if ($conn instanceof PDOException) {
        return "Unable to connect to the database: " . $conn->getMessage();
    }
    $query = $conn->prepare("SELECT img_id, encode(img, 'base64') As img, filetype FROM myimage WHERE img_id = 1 LIMIT 10;");
    if (!($query->execute())) {
        return "Error in query";
    }
    $result = $query->fetchAll()[0];
    return base64_decode($result['img']);
}

function csci4140_fetch_ten_image(){
    global $conn;
    $conn = db_connect();
    if ($conn instanceof PDOException) {
        return "Unable to connect to the database: " . $conn->getMessage();
    }
    $query = $conn->prepare("SELECT img_id, encode(img, 'base64') As img, filetype FROM myimage WHERE img_id = 1 LIMIT 10;");
    if (!($query->execute())) {
        return "Error in query";
    }
    $result = $query->fetchAll()[0];
    header('Content-type: ' . $result['filetype']);
    echo base64_decode($result['img']);
    exit();
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
        echo "<p> User not found, go back to <a href='../index.php'></a></p>";
        exit();
    }
    $db_user = $query->fetchAll()[0];
    $db_hash_password = $db_user["hash_password"];
    $db_flag = $db_user["flag"];
    $db_salt = $db_user["salt"];
    $new_hash_password = hash_hmac('sha256', $password, $db_salt);

    if ($new_hash_password == $db_hash_password){
        // When successfully authenticated,
        // 1. create authentication token
        $exp = time() + 3600;
        $hash = hash_hmac('sha256', $db_hash_password . $exp, $db_salt);
        $token = array('name'=>$username, 'exp'=>$exp, 'k'=> ($hash));
        setcookie('auth', json_encode($token), $exp, "/", "", true, true);
        $_SESSION['auth'] = $token;
        session_regenerate_id();
    } else {
        echo "<p>Wrong user name or password, go back to <a href='../index.php'> index.php </a></p>";
        exit();
    }

    // 2. redirect to page 
    if ($db_flag==1){
        header('Location: ../index.php', true, 302);
        return "Successfully logged in as admin";
    } 
    else{
        header('Location: ../index.php', true, 302);
        return "Successfully logged in as user";
    } 
}

function csci4140_logout(){
    unset($_COOKIE['auth']);
    setcookie('auth', '', time()-3600, "/", "", true, true);
    unset($_SESSION['auth']);
    header('Location: ../login.php', true, 302);
    return "Successfully logged out";
}

function csci4140_check_auth(){
    if (is_auth()){
        return "Authenticated";
    } else {
        return "Not Authenticated";
    }
}

function is_auth(){
    if (isset($_SESSION['auth'])){
        return $_SESSION['auth']["name"];
    }
    if (isset($_COOKIE['auth'])){
        global $conn;
        $cookie = json_decode($_COOKIE['auth'], true);
        $cookie_name = $cookie["name"];
        $cookie_exp = $cookie["exp"];  
        $cookie_k = $cookie["k"];
        $conn = db_connect();
        $query = $conn->prepare("Select * FROM MYUSER WHERE name = ? LIMIT 1;");
        $query->bindParam(1, $cookie_name);
        if (!($query->execute())){
            return "Error in query";
        }
        if ($query->rowCount() == 0){
            return "User not found";
        }
        $db_user = $query->fetchAll()[0];
        $db_salt = $db_user["salt"];
        $db_hash_password = $db_user["hash_password"];
        $new_hash_password = hash_hmac('sha256', $db_hash_password . $cookie_exp, $db_salt);
        if ($new_hash_password == $cookie_k){
            return $cookie_name;
        }else{
            return false;
        }
    }
    return false;
}

function is_admin($username){
    global $conn;
    $conn = db_connect();
    if ($conn instanceof PDOException) {
        return "Unable to connect to the database: " . $conn->getMessage();
    }
    $query = $conn->prepare("Select flag FROM MYUSER WHERE name = ? LIMIT 1;");
    $query->bindParam(1, $username);
    if (!($query->execute())){
        return "Error in query";
    }
    if ($query->rowCount() == 0){
        return "User not found";
    }
    $db_user = $query->fetchAll()[0];
    $db_flag = $db_user["flag"];
    if ($db_flag==1){
        return true;
    } 
    else{
        return false;
    }
}

function get_id_from_username($username){
    global $conn;
    $conn = db_connect();
    if ($conn instanceof PDOException) {
        return "Unable to connect to the database: " . $conn->getMessage();
    }
    $query = $conn->prepare("Select id FROM MYUSER WHERE name = ? LIMIT 1;");
    $query->bindParam(1, $username);
    if (!($query->execute())){
        return "Error in query";
    }
    if ($query->rowCount() == 0){
        return "User not found";
    }
    $db_user = $query->fetchAll()[0];
    return $db_user["id"];
}

// function csci4140_create_pd(){
//     global $conn;
//     $conn = db_connect();
//     if ($conn instanceof PDOException) {
//         return "Unable to connect to the database: " . $conn->getMessage();
//     }
//     $username = validate_input(string_sanitization($_REQUEST['username']), '/[^$@\'&"=|]+/', "invalid-username");
//     $password = validate_input(string_sanitization($_REQUEST['password']), '/[^$@\'&"=|]+/', "invalid-password");
//     $flag = 1;
//     $salt = random_int(0, PHP_INT_MAX);
//     $hash_password = hash_hmac('sha256', $password, $salt);

//     $query = $conn->prepare('INSERT INTO myuser(name, hash_password, salt, flag) VALUES (?, ?, ?, ?);');
//     $query -> bindParam(1, $username);
//     $query -> bindParam(2, $hash_password);
//     $query -> bindParam(3, $salt);
//     $query -> bindParam(4, $flag);

//     if ($query->execute()) {
//         return "Successfully created the accound for " . $username;
//     } else {
//         return "Error in query";
//     }
// }
?>