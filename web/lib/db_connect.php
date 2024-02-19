<?php
include_once('utilities/sanitization.php');
include_once('utilities/validation.php');
session_start(['cookie_httponly' => true, 'cookie_secure' => true,]);

$HOST = 'dpg-cn6tmn2cn0vc73dmghjg-a';
$DBNAME = 'db_1155143402csci4140';
$USERNAME = 'db_1155143402csci4140_user';
$PD = 'RQHfnfnO07Owiin69v9mf375Vrkd2yPi';

//////////////////////////////////////////////////////////////////////////////////
                            //////// DataBase ///////
//////////////////////////////////////////////////////////////////////////////////

function db_connect() {
    global $HOST, $DBNAME, $USERNAME, $PD;
    try {
        $conn = new PDO("pgsql:host=$HOST;port=5432;dbname=$DBNAME;user=$USERNAME;password=$PD");
        
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

function csci4140_delete_image(){
    if (isset($_GET['img_id'])){
        $img_id = validate_input(int_sanitization($_GET['img_id'] ), '/^\d+$/', "invalid-img_id");
    } else {
        return "No image id provided";
    }
    global $conn;
    $conn = db_connect();
    if ($conn instanceof PDOException) {
        return "Unable to connect to the database: " . $conn->getMessage();
    }
    $query = $conn->prepare("DELETE FROM myimage WHERE img_id = ?;");
    $query->bindParam(1, $img_id);
    if (!($query->execute())) {
        return "Error in query";
    }
    header('Location: ../index.php', true, 302);
    return "Successfully deleted the image";
}
function csci4140_init_all(){

    global $conn;
    $conn = db_connect();
    if ($conn instanceof PDOException) {
        return "Unable to connect to the database: " . $conn->getMessage();
    }
    $query = $conn->prepare("DROP TABLE IF EXISTS myimage;");
    if (!($query->execute())) {
        return "Error in query";
    }
    $query = $conn->prepare("CREATE TABLE myimage(
        img_id SERIAL PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        img BYTEA NOT NULL,
        filetype VARCHAR(100) NOT NULL,
        flag INT NOT NULL,
        creator INT NOT NULL,
        FOREIGN KEY (creator) REFERENCES myuser(id)
    );");
    if (!($query->execute())) {
        return "Error in query";
    }
    return "<p>Successfully initialized the website, you can go back to the <a href='../index.php'> index.php </a></p>";
}

//////////////////////////////////////////////////////////////////////////////////
                            //////// Image Management ///////
//////////////////////////////////////////////////////////////////////////////////
function csci4140_upload_image(){
    if (isset($_FILES['file'])) {
        $imageId = store_file($_FILES['file']);
        header('Location: ../photo_editor.php?img_id='.$imageId, true, 302);
        return "Successfully uploaded the image";
    }else {
        echo "No file uploaded";
        return json_encode($_FILES['file']);
        
    }
}

function store_file($file){
    global $conn;
    $allowedTypes = ['image/jpg', 'image/png', 'image/gif'];
    $fileType = $file['type'];
    if (!in_array($fileType, $allowedTypes) && !in_array(mime_content_type($file['tmp_name']), $allowedTypes)) {

        echo "Invalid file type for ". $file['type'];
        exit();
    }
    else {
        $conn = db_connect();
        if ($conn instanceof PDOException) {
            return "Unable to connect to the database: " . $conn->getMessage();
        }   
        $img = file_get_contents($file['tmp_name']);
        $name = validate_input(string_sanitization($file['name']), '/^[\w\- ]+$/', "invalid-filename");
        $creator = get_id_from_username(is_auth());
        if (isset($_POST["isPublic"])){
            $is_public = validate_input(string_sanitization($_POST["isPublic"]), '/^[\w\- ]+$/', "invalid-flag");
        }else{
            $is_public = "off";
        }
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
        return $imageId;
    }
}

function get_binimage_from_id($id){
    global $conn;
    $conn = db_connect();
    if ($conn instanceof PDOException) {
        return "Unable to connect to the database: " . $conn->getMessage();
    }
    $query = $conn->prepare("SELECT img_id, encode(img, 'base64') As img, filetype FROM myimage WHERE img_id = ? LIMIT 1;");
    $query->bindParam(1, $id);
    if (!($query->execute())) {
        return "Error in query";
    }
    $result = $query->fetchAll()[0];
    header('Content-type: ' . $result['filetype']);
    return $result['img'];
}

function retrieve_image(){
    if (isset($_GET['img_id'])){
        $img_id = validate_input(int_sanitization($_GET['img_id'] ), '/^\d+$/', "invalid-img_id");
        $img = get_binimage_from_id($img_id);
        echo base64_decode($img);
        exit();
    } else {
        return "No image id provided";
    }
}

function count_image($username){
    global $conn;
    $conn = db_connect();
    if ($conn instanceof PDOException) {
        return "Unable to connect to the database: " . $conn->getMessage();
    }
    $userid = get_id_from_username($username);
    if ($username == 'guest'){
        $query = $conn->prepare("SELECT COUNT(*) from myimage WHERE FLAG = 1;");
    } else {
        $query = $conn->prepare("SELECT COUNT(*) from myimage WHERE FLAG = 1 OR (Flag = 0 And creator = ?);");
        $query->bindParam(1, $userid);
    }
    if (!($query->execute())) {
        return "Error in query";
    }
    $result = $query->fetchColumn();
    return (int) $result;
}

function fetch_ten_public_image($start, $length){
    global $conn;
    $conn = db_connect();
    $limit = $start + $length;
    if ($conn instanceof PDOException) {
        return "Unable to connect to the database: " . $conn->getMessage();
    }
    $query = $conn->prepare("SELECT img_id, FLAG FROM myimage WHERE FLAG = 1 ORDER BY img_id DESC Limit ?;");
    $query->bindParam(1, $limit);
    if (!($query->execute())) {
        return "Error in query";
    }
    return (array)  $query->fetchAll();
}

function fetch_ten_image_auth($start, $length, $userid){
    global $conn;
    $conn = db_connect();
    $limit = $start + $length;
    if ($conn instanceof PDOException) {
        return "Unable to connect to the database: " . $conn->getMessage();
    }
    $query = $conn->prepare("SELECT img_id, FLAG FROM myimage WHERE FLAG = 1 OR (Flag = 0 OR creator = ?) ORDER BY img_id DESC Limit ?;");
    $query->bindParam(1, $userid);
    $query->bindParam(2, $limit);
    if (!($query->execute())) {
        return "Error in query";
    }
    return (array) $query->fetchAll();

}
                                                     
function csci4140_finish_edit(){
    // echo json_encode($_GET);
    // echo isset($_GET['img_id']);
    // echo isset($_GET['filter']);

    if (isset($_GET['img_id']) && isset($_GET['filter'])){
        $img_id = validate_input(int_sanitization($_GET['img_id'] ), '/^\d+$/', "invalid-img_id");
        $filter = validate_input(string_sanitization($_GET['filter'] ), '/^\w+$/', "invalid-filter");
    } else {
        return "No image id or filter provided";
    }
    $img = get_binimage_from_id($img_id);
    $imageDataDecoded = base64_decode($img);
    $image = new Imagick();
    $image->readImageBlob($imageDataDecoded);
    if ($filter == "border"){
        $image -> borderImage('black', 5, 5);
    } else if ($filter == "blackNwhite"){
        $image -> setImageType (2);
    }   
    $imgage = $image->getImageBlob();
    $conn = db_connect();
    if ($conn instanceof PDOException) {
        return "Unable to connect to the database: " . $conn->getMessage();
    }
    $query = $conn->prepare("UPDATE myimage SET img = ? WHERE img_id = ?;");
    $query->bindParam(1, $imgage, PDO::PARAM_LOB);
    $query->bindParam(2, $img_id);
    if (!($query->execute())) {
        return "Error in query";
    }
    header('Location: ../index.php', true, 302);
    return "Successfully edited the image";
    


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
        $conn = db_connect();
        $cookie = json_decode($_COOKIE['auth'], true);
        $cookie_name = $cookie["name"];
        $cookie_exp = $cookie["exp"];  
        $cookie_k = $cookie["k"];
        $query = $conn->prepare("Select * FROM MYUSER WHERE name = ? LIMIT 1;");
        $query->bindParam(1, $cookie_name);
        if (!($query->execute())){
            return "Error in query";
        }
        if ($query->rowCount() == 0){
            return "<p> User not found, go back to <a href='../index.php'></a></p>";
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

function get_userid_from_username($username){
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

function is_admin($username){
    global $conn;
    $conn = db_connect();
    if ($conn instanceof PDOException) {
        return "Unable to connect to the database: " . $conn->getMessage();
    }
    $query = $conn->prepare("Select * FROM MYUSER WHERE name = ? LIMIT 1;");
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