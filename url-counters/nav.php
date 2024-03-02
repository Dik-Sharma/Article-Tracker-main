

<div style="display:block">
    <ul>
        <li><a href="index.php">Home</a></li>
        <?php if ($_SESSION['usertype'] == 1) { ?>
            <li><a href="analytics.php">Analytics</a></li>
            <li><a href="empAnalytics.php">Individual Employee Analytics</a></li>
            <li><a href="compareAnalytics.php">Compare Employee Perfomance</a></li>
            <?php } ?>
            <?php if ($_SESSION['usertype'] == 2) { ?>
                <!-- <li><a href="empAnalytics.php">Individual Employee Analytics</a></li> -->
            <?php } ?>

<?php

$username = $_SESSION['username'];
$sql2 = "SELECT `emp_id` from `users` WHERE `username` = '$username'";
$res = mysqli_query($conn, $sql2);
$row = $res->fetch_row() ?? false;
$emp_id = $row[0]  ?? "NOOOOOOO";

    if ($_SESSION['usertype'] == 2) {
?>
        <li>
            <a href="empIndAnalytics.php?emp_id=<?php echo $emp_id?>" style="text-decoration: none;";>View Your Analytics</a>
        </li>
        <li>
            <a href="articles.php" style="text-decoration: none;";>View Articles</a>
        </li>
<?php
    }
?>
        <li style="float:right; padding-right: 20;">
            <a id="showhiddenmenu" 
            style="transform: rotate(90deg); cursor: pointer;"
            onselectstart="return false;"  ondragstart="return false;"
            onClick="showHiddenMenu()" disabled>|||</a>
        </li>
        <li style="float:right; /*padding-right: 20;*/ display: none;" id="logoutbutton">
            <a href="logout.php">Logout</a>
        </li>
        <li style="float:right; display: none;" id="settingsbutton">
            <a href="settings.php">Change Password</a>
        </li>
    </ul>
</div>

<script>
    function showHiddenMenu() {
        lb = document.getElementById("logoutbutton")
        sb = document.getElementById("settingsbutton")
        if (lb.style.display == "none") {
            lb.style.display = "block";
        } else {
            lb.style.display = "none";
        }
        if (sb.style.display == "none") {
            sb.style.display = "block";
        } else {
            sb.style.display = "none";
        }
    }
</script>


<span style="float:right; padding: 10px 30px;">

<?php

$query = "SELECT `name` from users WHERE username = '".$_SESSION['username']."'";
$result = mysqli_query($conn, $query);
$name = $result->fetch_row()[0] ?? false;

echo($name);

?>
</span>

<span style="float:left; padding: 10px 30px; font-weight: bold;">DASHBOARD</span>

<div class="text-center">
    <img src="logo.jpeg" height=60px alt="" style="margin: 30px auto;margin-left: auto; margin-top: 20px; display: block;">

</div>
