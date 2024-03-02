<?php
session_start();

if (!isset($_SESSION['username']) || !isset($_SESSION['usertype'])) {
    header("location: logout.php");
    exit;
}

include 'db-connect.php';

?>



<html>
    <head>
        <title>Dashboard</title>
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


    </head>
<body>

<!-- include nav.php -->
<?php include 'nav.php'; ?>


<span style="float:left; padding: 10px 30px; font-weight: bold;">DASHBOARD</span>


<img src="logo.jpeg" height=60px alt="" style="margin: 30px auto; margin-top: 40px; display: block;">


<div style="height: 00px;"></div>



<?php if ($_SESSION['usertype'] == 1 || $_SESSION['usertype'] == 2) { ?>

    <div style="border: 1px solid #ddd; padding: 20px;
    border-radius: 15px;
    display: block; margin:10 auto; width: 50%;">
        
        
        <div style="display: block; margin:0 auto; width: fit-content;">
            <h1 style="display: block; margin:0 auto; padding: 20px;">Update Article</h1>
        </div>
        

        <?php

        $emp_id = $_GET["emp_id"];
        $title = $_GET["title"];
        $date= "";
        $url = "";
        $type = "";

        // echo $title;
        // echo "\n".$emp_id;
        // echo("SELECT `article_date` FROM `articles` WHERE `article_title`='$title' AND `emp_id`=$emp_id;");

        $query = "SELECT * FROM `articles` WHERE `article_title`='$title' AND `emp_id`=$emp_id;";
        echo $query;
        $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
        while ($row = mysqli_fetch_array($res)) {
            $date = $row['article_date'];
            $url = $row['article_url'];
            $type = $row['type'];
        }

        // echo $date;
        // echo "\n".$url;
        // echo "\n".$type;



        ?>
        

        <style>
            .formcontainer.formupdate *{
                width: fit-content;
            }
        </style>
         <?php
            // update data
                if (isset($_POST['update'])) {
                    // echo "hiiiii";
                    $date = $_POST['date'];
                    $title = $_POST['title'];
                    $url = $_POST['url'];
                    $type = $_POST['type'];
                    $query = "UPDATE `articles` SET `article_date`='$date',`article_title`='$title',`article_url`='$url',`type`='$type' WHERE `article_title`='$title' AND `emp_id`=$emp_id;";
                    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
                    
                    echo "<?php echo \"articles.php?empid=\".$emp_id?>&title=<?php echo $title?>&date=<?php echo $date?>&url=<?php echo $url?>";
                    
                    header("location: articles.php?empid=<?php echo $emp_id; ?>");


                }
            ?>

        
        <div id="formcontainer">
            <!-- form to edit the article details -->
            <form action="editArticle.php" method="post" >
                <div style="display: block; margin:0 auto; width: 80%;">
                    <label for="date" >Date</label>
                    <br>
                    <input style="width: 100%;" type="text" name="date" id="date" placeholder="yyyy-mm-dd" min="1997-01-01" max="<?php echo date("Y-m-d"); ?>"value="<?php echo $date?>" disabled>
                    
                    
                </div>
                <br>


                <div style="display: block; margin:0 auto; width: 80%;">
                    <label for="title"  >Title</label>
                    <br>
                    <input style="width: 100%;" type="text" name="title" id="title" value="<?php echo $title?>">
                </div>
                <br>
                <div style="display: block; margin:0 auto; width: 80%;">
                    <label for="url"  >URL</label>
                    <br>
                    <input style="width: 100%;" type="text" name="url" id="url" value="<?php echo $url?>">
                </div>
                <br>
                <!-- dropdown for web and social -->
                <div style="display: block; margin:0 auto; width: 80%;">
                    <label for="type"  >Type</label>
                    <br>
                    <select style="width: 100%;" name="type" id="type">
                        <option value="1">Web</option>
                        <option value="2">Social</option>
                    </select>
                </div>
                <br>

                <div style="display: block; margin:0 auto; width: 80%;text-align : center;">

                    <button type="submit" name="update" id="update" class="btn btn-success" >Update</button>
                </div>
            </form>

           
           

        </div>        
        

    </div>
   
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    
</body>
</html>
<?php }