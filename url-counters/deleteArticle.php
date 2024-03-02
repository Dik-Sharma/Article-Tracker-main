<?php
session_start();

include 'db-connect.php';

if (!isset($_SESSION['username']) || !isset($_SESSION['usertype'])) {
    header("location: logout.php");
    exit;
}

include 'db-connect.php';

$title = $_GET["title"];
$emp_id = $_GET["emp_id"];

   
mysqli_query($conn, "DELETE FROM articles WHERE article_title='$title' AND emp_id=$emp_id;") or die(mysqli_error($conn));

?>
<script type="text/javascript">
    alert("Article Deleted");
    window.location = "articles.php?empid=<?php echo $emp_id; ?>";
</script>