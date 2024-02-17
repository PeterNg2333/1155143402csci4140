

<?php
$host = 'dpg-cn6tmn2cn0vc73dmghjg-a';
$dbname = 'db_1155143402csci4140';
$username = 'db_1155143402csci4140_user';
$password = 'RQHfnfnO07Owiin69v9mf375Vrkd2yPi';

try {
    $conn = new PDO("pgsql:host=$host;port=5432;dbname=$dbname;user=$username;password=$password");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->query('SELECT version()');
    $version = $stmt->fetchColumn();
    echo "<p>Output result: " . $version . "</p>";

} catch(PDOException $e) {
    echo "<p>Unable to connect to the database: " . $e->getMessage() . "</p>";
}
?>

<!-- 


    Create Table MyUser(id INT Primary Key, Name Varchar Not Null, hash_password Varchar NOT NULL, SALT Varchar NOT NULL,FLAG INTEGER NOT NULL) 



-->
