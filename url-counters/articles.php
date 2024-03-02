<?php
session_start();

if (!isset($_SESSION['username']) || !isset($_SESSION['usertype'])) {
    header("location: logout.php");
    exit;
}

include 'db-connect.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {

    if (!($_GET["empid"] ?? false) && ($_SESSION['usertype'] != 2)) {
        header("location: dashboard.php");
    }    

    ?>
<html>
    <head>
        <title>Articles</title>
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
        <style>
                tr:nth-child(even) {
                    /*background-color: aliceblue;*/
                    background-color: rgba(0,0,0,.05);
                }
                th, td {
                    padding: 20px;
                    /* word-wrap: break-word; */
                    /* width: 110px; */
                    word-break: break-all;
                    /*border: 1px solid black;*/
                }
            </style>
    </head>
<body>
    <!-- include nav.php -->
<?php include 'nav.php'; ?>


    
    <?php

    if ($_SESSION['usertype'] == 2) {
        ?>
        <h1 style="display: block; margin: 5px auto; width: fit-content;">
            Submitted Articles
        </h1>        
        
        <?php
        // echo "<h2 style=\"display: inline-block\"> / Your Submitted Articles</h2>";
        // echo "    <h4><a href=\"dashboard.php\">Go back to Dashboard</a></h4>";
        $query = "SELECT `a`.`article_date`, `a`.`article_title`, `a`.`article_url`, `a`.`type` FROM `articles` as `a`, `users` as `u` 
        WHERE `a`.`emp_id` = `u`.`emp_id` AND `u`.`username` = '".$_SESSION['username']."'
        ORDER BY `a`.`article_date` DESC";

        $type = $_GET['type'] ?? false;
        // if not empty
        if ($type) {
            $query = "SELECT `a`.`article_date`, `a`.`article_title`, `a`.`article_url`, `a`.`type` FROM `articles` as `a`, `users` as `u` 
            WHERE `a`.`emp_id` = `u`.`emp_id` AND `u`.`username` = '".$_SESSION['username']."' AND `a`.`type` = '$type'
            ORDER BY `a`.`article_date` DESC";
        }

        $result = mysqli_query($conn, $query);
        ?>

        <style>
            tr:nth-child(even) {
                /*background-color: aliceblue;*/
                background-color: rgba(0,0,0,.05);
            }
            th, td {
                padding: 20px;
                /*border: 1px solid black;*/
            }
        </style>




        <div style="display: block; margin:0 auto; width: fit-content;">
                <!--h3 style="display: block; margin:0 auto; padding: 20px;">Add Article</h3-->

            <div style="width:200; display:inline-block; text-align: center; padding: 10px; border: 1px solid #ccc; border-radius: 16px;">
            Total Submission : 

                <?php

                    // include 'db-connect.php';
                    $d = date("Y-m-d");
                    $username = $_SESSION['username'];
                    $query = "SELECT COUNT(a.article_title) from articles as a, users as u 
                    WHERE u.username = '$username' and u.emp_id = a.emp_id
                    GROUP BY a.emp_id;";
                    $result2 = mysqli_query($conn, $query);

                    $count = $result2->fetch_row()[0] ?? false;

                    echo($count);
                ?>
            </div>


            <div style="width:200; display:inline-block; text-align: center; padding: 10px; border: 1px solid #ccc; border-radius: 16px;">
                Today's Submission : 
                <?php

                    // include 'db-connect.php';
                    $d = date("Y-m-d");
                    $username = $_SESSION['username'];
                    $query = "SELECT COUNT(a.article_title) from articles as a, users as u 
                    WHERE u.username = '$username' and u.emp_id = a.emp_id and a.article_date = '$d'
                    GROUP BY a.article_date;";
                    $result2 = mysqli_query($conn, $query);

                    $count = $result2->fetch_row()[0] ?? false;

                    echo($count);

                ?>
            </div>
        </div>

        <div class="row justify-content-evenly" style="padding: 0 20px">
            <div class="col-md-2"></div>

            <div class="col-md-8">
                <table  style="border: 1px solid #ddd; padding: 20px; border-radius: 15px; display: block; margin:10 auto; width: fit-content;  text-align:center; border-spacing: 0px;">
                    <!--tr>
                        <th>Date</th><th style="/*padding-left: 20px;padding-right: 20px;*/">Title</th><th>URL</th>
                    </tr-->   
                    <tr>
                        <th style="width: 150px">Date</th>
                        <th style="width: 150px">Title</th>
                        <th style="width: 100px">Type</th>
                        <th style="width: 300px">URL</th>
                        <!-- <th style="width: 100px">Edit</th>
                        <th style="width: 100px">Delete</th> -->
                    </tr>     

                    <?php
                    while($row = $result->fetch_row()) {
                    printf("<tr><td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td><a href=\"%s\" target=\"_blank\">%s</a></td>
                    
                    </tr>",
                    $row[0], $row[1],$row[3], $row[2], $row[2]);
                    }?>

                </table>
            </div>
        <?php
       
    }
    elseif ($_SESSION['usertype'] == 1) {
        // Sanitizer for emp_id
        $emp_id = preg_replace("/[^a-zA-Z0-9]+/", "", $_GET["empid"]);
        if ($emp_id) {
            $query = "SELECT `name` FROM `users` WHERE `emp_id` = '$emp_id'";
            $result = mysqli_query($conn, $query);
            $name = $result->fetch_row()[0] ?? "N/A";

            $query = "SELECT `a`.`article_date`, `a`.`article_title`, `a`.`article_url` FROM `articles` as `a` 
            WHERE `a`.`emp_id` = '$emp_id'";
            $result = mysqli_query($conn, $query);
            ?>

            <div style="
            margin: 10 auto;
            width:200; display:block; 
            text-align: center; padding: 10px; border: 1px solid #ccc; 
            border-radius: 16px;">
                Employee Name : <b>
                <?php
                    $query = "SELECT `name` from users
                    WHERE emp_id = '$emp_id'";
                    $result2 = mysqli_query($conn, $query);

                    $count = $result2->fetch_row()[0] ?? false;

                    if ($count) { echo($count); }
                    else { echo "0";}

                ?>
                </b>
            </div>


            <div style="display: block; margin:0 auto; width: fit-content;">
                <!--h3 style="display: block; margin:0 auto; padding: 20px;">Add Article</h3-->

            <div style="width:200; display:inline-block; text-align: center; padding: 10px; border: 1px solid #ccc; border-radius: 16px;">
                Total Submission : 

                <?php

                    // include 'db-connect.php';
                    $d = date("Y-m-d");
                    $query = "SELECT COUNT(a.article_title) from articles as a
                    WHERE a.emp_id = '$emp_id'
                    GROUP BY a.emp_id;";
                    
                    $result2 = mysqli_query($conn, $query);
                    $count = $result2->fetch_row()[0] ?? false;

                    if ($count) {

                        echo($count);
                    }
                    else {
                        echo "0";
                    }
                ?>
            </div>


            <div style="width:200; display:inline-block; text-align: center; padding: 10px; border: 1px solid #ccc; border-radius: 16px;">
                Today's Submission : 
                <?php

                    // include 'db-connect.php';
                    $d = date("Y-m-d");
                    $query = "SELECT COUNT(a.article_title) from articles as a
                    WHERE a.emp_id = '$emp_id' and a.article_date = '$d'
                    GROUP BY a.article_date;";
                    $result2 = mysqli_query($conn, $query);

                    $count = $result2->fetch_row()[0] ?? false;

                    if ($count) { echo($count); }
                    else { echo "0";}

                ?>
            </div>
        </div>



            
            
            <div class="row justify-content-evenly" style="padding: 0 20px">
                <div class="col-md-2"></div>

                <!-- <div class="col-md-8" style="border: 1px solid #ddd; padding: 20px;
                    border-radius: 15px;
                    display: block; margin:10 auto;width: 100%;  height:50%; overflow-y:auto;"> -->
                <div class="col-md-8" style="overflow-y:auto;height:50%;">
                    <table  style="border: 1px solid #ddd; padding: 20px; border-radius: 15px; display: block; margin:10 auto; width:fit-content;  text-align:center; border-spacing: 0px;">
                    
                    <!-- <table style="width:100%;text-align:center; border-spacing: 0px;overflow-y:auto;"> -->
                        <tr>
                            <th style="width: 150px">Date</th>
                            <th style="width: 200px">Title</th>
                            <th style="width: 300px">URL</th>
                            <th style="width: 100px">Type</th>
                            <th style="width: 150px">Edit</th>
                            <th style="width: 150px">Delete</th>
                        </tr>        

                        <?php
                        
                        $dateFrom = $_GET["dateFrom"] ?? "";

                        // echo $dateFrom;
                        if($dateFrom = "" || $dateFrom == null || $dateFrom == " ") {
                            
                            $query = "SELECT `a`.`article_date`, `a`.`article_title`, `a`.`article_url`, `a`.`type` from articles as a
                            WHERE a.emp_id = '$emp_id'
                            ORDER BY a.article_date DESC;";

                        // echo $query;
                        }
                        else{
                            $dateFrom = $_GET["dateFrom"] ?? "";
                            $dateTo = $_GET["dateTo"];
                            // echo $dateFrom;
            
                            $query = "SELECT `a`.`article_date`, `a`.`article_title`, `a`.`article_url`, `a`.`type` FROM `articles` as `a` 
                            WHERE `a`.`emp_id` = '$emp_id' AND `a`.`article_date` BETWEEN '" . $dateFrom . "' AND '" .$dateTo. "'
                            ORDER BY a.article_date DESC;";
                        }
                        // echo $query;
                        $result = mysqli_query($conn, $query);

                        while($row = $result->fetch_row()) {
                        printf("<tr>
                            <td>%s</td>
                            <td>%s</td>
                            <td><a href=\"%s\" target=\"_blank\">%s</a></td>
                            <td>%s</td>
                            <td> <button type=\"button\" class=\"btn btn-warning\"> 
                            <a style=\"color:black;text-decoration:none;\" href=\"editArticle.php?emp_id=$_GET[empid]&title=%s\"> Edit </a>
                                </button> </td>
                            <td> <button type=\"button\" class=\"btn btn-danger\"> 
                                    <a style=\"color:black;text-decoration:none;\" href=\"deleteArticle.php?emp_id=$_GET[empid]&title=%s\"> Delete </a>
                            </button> </td>
                            


                        </tr>",
                        $row[0][8].$row[0][9]
                        .$row[0][7].$row[0][5].$row[0][6]
                        .$row[0][4].$row[0][0].$row[0][1].$row[0][2].$row[0][3], 
                        
                        $row[1], $row[2], $row[2], $row[3], $row[1], $row[1]);
                        }?>

                    </table>
                </div>


<?php

        }
    }
}
?>


            <div class="col-md-2" >

                        <p><b>Filter by Type</b></p>

                        <form action="articles.php">

                            <select name="type" style="width: 100%; display: block; margin: 0 auto;">
                                <option value="">Select Type</option>
                                <option value="web">Web</option>
                                <option value="social">Social</option>
                            </select>
                            <br>
                            <div class="row">
                                <input type="submit" name ="submit-type" id="submit-type" value="Submit" class="btn btn-primary col-5" style=" display: block; margin: 0 auto; text-align: center;  border-radius: 10px;"> 

                                <!-- reset button  -->
                                <br>

                                <input type="submit" onclick="Reset()" name ="reset" value="Reset" class="btn btn-warning col-5" style=" display: block; margin: 0 auto; text-align: center;  border-radius: 10px;">
                            </div>
                            
                        </form>
                        <script>
                            function Reset(){
                                window.location = "articles.php";

                            }
                        </script>
                        <?php
                            if (isset($_POST['submit-type'])) {
                                $type = $_POST['type'];
                                ?>
                                <script>
                                    window.location = "articles.php?type=<?php echo $type; ?>";
                                </script>


                                <?php

                            }
                        ?>



                </div>

            </div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

</body>
</html>