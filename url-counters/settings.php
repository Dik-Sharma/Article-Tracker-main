<?php
session_start();

if (!isset($_SESSION['username']) || !isset($_SESSION['usertype'])) {
    header("location: logout.php");
    exit;
}

include 'db-connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $current_password = $_POST["currentpassword"] ?? false;
    $new_password = $_POST["newpassword"] ?? false;
    $confirm_new_password = $_POST["confirmnewpassword"] ?? false;

    if (!$current_password || !$new_password || !$confirm_new_password) {
        echo "Couldn't Change Password";
    }
    elseif ($new_password != $confirm_new_password) {
        ?>
        <div 
            style="
                background-color: DarkRed;
                color: #ccc;
                padding: 10px;
                text-align: center;
            ">
        Please enter the same password in the the <b>Confirm New Password</b> field. 
        </div>
        <?php
    }
    else {
        $username = $_SESSION['username'];
        $query = "SELECT `password_hash` from `users` 
        WHERE `username` = '$username';";
        $result = mysqli_query($conn, $query);
        $password_hash = $result->fetch_row()[0] ?? false;

        if (password_verify($current_password, $password_hash)) {
            // echo "Password Matched!";
            $new_password_hash = password_hash($new_password, PASSWORD_BCRYPT);
            $query = "UPDATE `users` SET `password_hash` = '$new_password_hash'
            WHERE  `username` = '$username';
            ";
            if ($result = mysqli_query($conn, $query)) {
                echo "Password Changed Succesfully!";
            }
            else {
                echo "An Error Occured while trying to change the password";
            }
        }
        else {
            ?>
            <div 
            style="
                background-color: DarkRed;
                color: #ccc;
                padding: 10px;
                text-align: center;
            ">
        Wrong Password : Please enter the correct <b>Password</b>. 
        </div>
            <?php
        }
    }

    // $usertype = 2; // 2 = Employee, 1 = Admin

    // if ($password != "" && $username != "" && $usertype > 0 && $usertype < 3
    //     && $name != "" && $emp_id != "") {
    //     $password_hash = password_hash($password, PASSWORD_BCRYPT);
    //     $query = "INSERT INTO `users` (`usertype`, `name`, `emp_id`, `username`, `password_hash`) 
    //     VALUES ('$usertype', '$name', '$emp_id', '$username', '$password_hash')";
    //     $result = mysqli_query($conn, $query);

    //     echo "Employee Added Succesfully! Username: ".$username." Password : ".$password;
    // }
    // else {
    //     echo "Couldn't signup!<br>Make sure that you have entered all the fields correctly.";
    // }

}


?><html>
    <head>
        <title>Settings</title>
        <style>
            body {
                font-family: Arial, Helvetica, sans-serif;
                border: 40px solid #064269;
                border-top: 0;
                padding: 0px;
                padding-top: 0;
                margin: 0 0;
            }
            a {
                    text-decoration: none;
                }
        </style>
    </head>
    <body>

    <style>
    ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
        overflow: hidden;
        background-color: #064269;
    }
    li {
        float: left;
        padding-left: 20;
    }
    li a {
        display: block;
        color: white;
        text-align: center;
        padding: 14px 16px;
    }
    li a:hover {
        background-color: rgba(255, 255, 255, 0.5);
        /*color: #064269;*/
    }
</style>

<div style="display:block">
    <ul>
        <li><a href="index.php">Home</a></li>

<?php
    if ($_SESSION['usertype'] == 2) {
?>
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
        <!--li style="float:right; display: none;" id="settingsbutton">
            <a href="settings.php">Change Password</a>
        </li-->
    </ul>
</div>

<script>
    function showHiddenMenu() {
        lb = document.getElementById("logoutbutton")
        // sb = document.getElementById("settingsbutton")
        if (lb.style.display == "none") {
            lb.style.display = "block";
        } else {
            lb.style.display = "none";
        }
        // if (sb.style.display == "none") {
        //     sb.style.display = "block";
        // } else {
        //     sb.style.display = "none";
        // }
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
<span style="float:left; padding: 10px 30px; font-weight: bold;">
    <a href="dashboard.php">Go back to Dashboard</a>
</span>


        <div>
            <div>
                <h1 style="margin:10px auto; margin-top: 100px;display: block; width: fit-content;">
                    Change Password
                </h1>

            </div>
            <div>
                <div style="border: 1px solid #ddd; padding: 20px;
                background-color: white;
                border-radius: 15px;
                display: block; margin: 0 auto; width: fit-content; 
                text-align:center; ">
                <form action="" method="post" class="loginform">
                    <div style="padding: 10px;">
                        <input type="password" name="currentpassword" id="currentpassword" placeholder="Current Password" title="Current Password" required>
                    </div>
                    <div style="padding: 10px;">
                        <input type="password" name="newpassword" id="newpassword" placeholder="New Password" title="New Password" required>
                    </div>
                    <div style="padding: 10px;">
                        <input type="password" name="confirmnewpassword" id="confirmnewpassword" placeholder="Confirm New Password" title="Confirm New Password" required>
                    </div>
                    <button type="submit" class="loginbutton">Change Password</button>
                </form>
                </div>

                <style>
            form.loginform {
                /*border: 1px solid black; */
                margin:0 auto;
                width: fit-content;
                padding: 20px;
                padding-top: 0;
            }

            form.loginform div {
                padding: 10px;
            }

            form.loginform div label {
                padding: 14px 16px;
                font-size: 17px;
            }

            form.loginform div input {
                width: 330px;
                /*height: 10px;*/
                padding: 14px 16px;
                font-size: 17px;
                border-radius: 6px;
                border: 1px solid #ccc;
            }

            button.loginbutton {
                background-color: #333;
                color: #fff;
                border: none;
                border-radius: 6px;
                font-size: 20px;
                line-height: 48px;
                padding: 0 16px;
                width: 332px;
                cursor: pointer;
            }
            button.loginbutton:hover {
                background-color: #111;
            }
        </style>
            </div>
        </div>
    </body>
</html>
