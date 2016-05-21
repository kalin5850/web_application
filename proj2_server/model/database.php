<?php
// Set up the database connection
if (gethostname() === 'topcat') {
    $username = 'nichapu';  // mysql username on topcat is UNIX username
    $password = $username;
    $location = '/cs637/' . $username;  // where on server: student dir

    $dsn = 'mysql:host=localhost;dbname=' . $username . 'db';
} else {  // dev machine,
    $dsn = 'mysql:host=localhost;dbname=pizzadb';
    $username = 'root';
    $password = '1234';
}
$options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);

try {
    $db = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    $error_message = $e->getMessage();
    include('errors/db_error_connect.php');
    exit();
}
?>