<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "TrackerNew";

$conn = mysqli_connect($server, $username, $password, $database, 3325);
if (!$conn) {
    die("Error" . mysqli_connect_error());
}
// else {
//     die("Error". mysqli_connect_error());
// }