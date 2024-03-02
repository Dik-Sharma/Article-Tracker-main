<?php
session_start();

if (!isset($_SESSION['username']) || !isset($_SESSION['usertype'])) {
    header("location: logout.php");
    exit;
}

if ($_SESSION['usertype'] != 1) {
    header("location: dashboard.php");
    exit;
}

include 'db-connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // include 'db-connect.php';
    // $username = $_POST["username"];
    $username = preg_replace("/[^a-zA-Z0-9]+/", "", $_POST["username"]);
    
    $password = $_POST["password"];

    // $name = $_POST["name"];
    $name = preg_replace("/[^a-zA-Z0-9]\s+/", "", $_POST["name"]);

    // $emp_id = $_POST["empid"];
    $emp_id = preg_replace("/[^a-zA-Z0-9]+/", "", $_POST["empid"]);

    $usertype = 2; // 2 = Employee, 1 = Admin

    if ($password != "" && $username != "" && $usertype > 0 && $usertype < 3
        && $name != "" && $emp_id != "") {
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $query = "INSERT INTO `users` (`usertype`, `name`, `emp_id`, `username`, `password_hash`) 
        VALUES ('$usertype', '$name', '$emp_id', '$username', '$password_hash')";
        $result = mysqli_query($conn, $query);

        echo "Employee Added Succesfully! Username: ".$username." Password : ".$password;
        ?>
        <script>
            window.location = "dashboard.php";
        </script>
        <?php
    }
    else {
        echo "Couldn't signup!<br>Make sure that you have entered all the fields correctly.";
    }

}


?><html>
    <head>
        <title>Add Employee</title>
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
    </head>
<body>
<!-- include nav.php -->
<?php include 'nav.php'; ?>

<style>
    button.addemployeebutton {
                background-color: #333;
                color: #fff;
                border: none;
                border-radius: 6px;
                font-size: 20px;
                line-height: 48px;
                padding: 0 16px;
                width: 332px;
                cursor: pointer;
                display: block;
                margin: 20 auto;
            }
    button.addemployeebutton:hover {
        background-color: #111;
    }
</style>

<span style="float:left; padding: 10px 30px; font-weight: bold;">
    <a href="dashboard.php">Go back to Dashboard</a>
</span>

        <div>
      

            <div><h1 style="margin:10px auto; margin-top: 100px;display: block; width: fit-content;">Add New Employee<h1></div>
            <div>
                <form action="" method="post" action="" method="post" style="border: 1px solid black; margin:0 auto; width: fit-content; padding: 20px;">
                    <div style="padding: 10px;">
                        <div style="display: inline-block; width: 100px;">Name</div>
                        <input type="text" name="name" style="width: 300px; height: 10px; padding: 15px;">
                    </div>
                    <div style="padding: 10px;">
                        <div style="display: inline-block; width: 100px;">Employee ID</div>
                        <input type="text" name="empid" style="width: 300px; height: 10px; padding: 15px;">
                    </div>
                    <div style="padding: 10px;">
                        <div style="display: inline-block; width: 100px;">Username</div>
                        <input type="text" name="username" style="width: 300px; height: 10px; padding: 15px;">
                    </div>
                    <div style="padding: 10px;">
                        <div style="display: inline-block; width: 100px;">Password</div>
                        <input type="text" name="password" style="width: 300px; height: 10px; padding: 15px;">
                    </div>
                
                        <button class="addemployeebutton" type="submit">Add Employee</button>
                    
                </form>
            </div>
        </div>
    </body>
</html>
