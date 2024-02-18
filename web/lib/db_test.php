<?php
$host = 'dpg-cn6tmn2cn0vc73dmghjg-a';
$dbname = 'db_1155143402csci4140';
$username = 'db_1155143402csci4140_user';
$password = 'RQHfnfnO07Owiin69v9mf375Vrkd2yPi';

try {
    $conn = new PDO("pgsql:host=$host;port=5432;dbname=$dbname;user=$username;password=$password");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->query('SELECT COUNT(*) from myuser;');
    $version = $stmt->fetchColumn();
    echo "<p>1. Output result is " . gettype($version)."</p>";

    $stmt = $conn->query('SELECT 100;');
    $version = $stmt->fetchColumn();
    echo "<p>2. Output result is " . (int) $version."</p>";

    $stmt = $conn->query('SELECT 100;');
    $version = $stmt->fetchAll();
    echo "<p>3. Output result is " . json_encode($version) ."</p>";

    echo "<p>4. Output result is " . true."</p>";
    echo "<p>4. Output result is " . 1 ."</p>";
    echo "<p>4. Output result is " . "1" ."</p>";


} catch(PDOException $e) {
    echo "<p>Unable to connect to the database: " . $e->getMessage() . "</p>";
}
?>

<!-- 


    CREATE TABLE MyUser(
    id INT AUTO_INCREMENT PRIMARY KEY, 
    Name VARCHAR(80) NOT NULL, 
    hash_password VARCHAR(200), 
    SALT VARCHAR(80) NOT NULL,
    FLAG INTEGER NOT NULL
);
    Create Table MyImage(img_id INT Primary Key, Name Varchar Not Null, img bytea, filetype Text, FLAG INTEGER NOT NULL, creator INT NOT NULL references MyUser(id));


-->
