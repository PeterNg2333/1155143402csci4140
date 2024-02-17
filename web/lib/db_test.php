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
    echo "<p>1. Output result is " . $version."</p>";
    $stmt = $conn->query('SELECT 100 from myuser;');
    $version = $stmt->fetchColumn();
    echo "<p>2. Output result is " . strval($version)."</p>";
    $stmt = $conn->query('SELECT 100 from myuser;');
    $version = $stmt->fetchAll();
    echo "<p>3. Output result is " . strval($version[0])."</p>";


} catch(PDOException $e) {
    echo "<p>Unable to connect to the database: " . $e->getMessage() . "</p>";
}
?>

<!-- 


    Create Table MyUser(id INT Primary Key, Name Varchar Not Null, hash_password Varchar NOT NULL, SALT Varchar NOT NULL,FLAG INTEGER NOT NULL) 
    Create Table MyImage(img_id INT Primary Key, Name Varchar Not Null, img bytea, filetype Text, FLAG INTEGER NOT NULL)


-->
