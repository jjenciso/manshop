<?php
$server = "localhost";
$username = "root";
$password = "";
$db_name = "manshop";
$conn = "";

try{
    $conn = mysqli_connect(
        $server,
        $username,
        $password,
        $db_name
    );
} catch(mysqli_sql_exception) {
    echo "Could not connect";
}
?>