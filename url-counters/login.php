<?php
session_start();
if (isset($_SESSION['username']) && isset($_SESSION['usertype'])) {
    header("location: dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db-connect.php';

    $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
    // $username = $_POST["username"];
    $password = $_POST["password"];

    $query = "SELECT `password_hash`, `usertype` from `users` WHERE `username` = '$username';";
    $result = mysqli_query($conn, $query);

    $row = $result->fetch_row() ?? false;
    $password_hash = $row[0] ?? false;
    $usertype = $row[1] ?? false;    
    
    if (password_verify($password, $password_hash)) {
        echo "Password Matched!";
        // session_start();
        $_SESSION['username'] = $username;
        $_SESSION['usertype'] = $usertype;
        // header("location: dashboard.php");
        ?>
        <script>
            window.location = "dashboard.php";
        </script>
        <?php
    }
    else {
        echo "<div 
            style=\"
                background-color: DarkRed;
                color: #ccc;
                padding: 10px;
                text-align: center;
            \">
        Username or Password did NOT match! Please try again. 
        </div>";
    }
}

?><html>
    <head>
        <title>Sign in</title>
        <style>
            body {
                font-family: Arial, Helvetica, sans-serif;
                background-color: #064269;
            }
        </style>
    </head>
    <body>

        <div style="border: 1px solid #ddd; padding: 20px;
                background-color: white;
                border-radius: 15px;
                display: block; margin:10 auto; margin-top: 100px; width: fit-content; 
                text-align:center; ">
                <img src="logo.jpeg" height=100px alt="">
        
        <div style="
            margin: 0 auto;
            margin-top: 15px;
            /*margin-top: 100px;*/
            display: block;
            width: fit-content;
            font-size: 30px;"
        >
            Sign in
        </div>
        <form action="" method="post" class="loginform">
            <div style="">
                <!--label for="username" style="">Username</label-->
                <input type="text" name="username" id="username" placeholder="Username" title="Username" required>
            </div>
            <div style="padding: 10px;">
                <!--label for="password" style="">Password</label-->
                <input type="password" name="password" id="password" placeholder="Password" title="Password" required>
            </div>
            <!--button type="submit" style="margin: 0 auto; display: block; padding: 10px; padding-left: 20px; padding-right: 20px;">Login</button-->
            <button type="submit" class="loginbutton">Sign in</button>
        </form>

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
    </body>
</html>