<?php
session_start();

if (isset($_SESSION['username']) && isset($_SESSION['usertype'])) {
    header("location: dashboard.php");
    exit;
}

header("location: login.php");
exit;
?>
<?php
