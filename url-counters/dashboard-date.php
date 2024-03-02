<?php
session_start();

if (!isset($_SESSION['username']) || !isset($_SESSION['usertype'])) {
    header("location: logout.php");
    exit;
}

include 'db-connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION['usertype'] == 2) {
    

    
    if (!($_POST["date"] ?? false) || !($_POST["title"] ?? false) || !($_POST["url"] ?? false)) {
        header("location: dashboard.php");
        exit;
    }

    $usertype = 2; // 2 = Employee, 1 = Admin
    $username = $_SESSION['username'];


    $query = "SELECT `emp_id` from `users` WHERE `username` = '$username'";
    $result = mysqli_query($conn, $query);

    $row = $result->fetch_row() ?? false;
    $emp_id = $row[0]  ?? "NOOOOOOO";
    // Add a error handler for emp_id incase it doesn't exist in users table

    for ($i = 0; $i < count($_POST['date']); $i++) {
        $date = $_POST["date"][$i] ?? "";
        $title = $_POST["title"][$i] ?? "";
        $url = $_POST["url"][$i] ?? "";

        

        // FILTER_SANITIZE_URL

        if ($date != "" && $title != "" && $url  != "") {
            $query = "INSERT INTO `articles` (`emp_id`,`article_date`,`article_title`,`article_url`)
            VALUES ('$emp_id','$date','$title','$url')";
            $result = mysqli_query($conn, $query);
        }
        else {
            echo "";
        }
    }
    

}

?><html>
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
        <!--
            Author : Jwngfu Brahma
            For : G PLus
        -->
    </head>
<body>
<!-- include nav.php -->
<?php include 'nav.php'; ?>


<span style="float:left; padding: 10px 30px; font-weight: bold;">DASHBOARD</span>


<img src="logo.jpeg" height=60px alt="" style="margin: 30px auto; margin-top: 40px; display: block;">


<div style="height: 00px;"></div>



<?php
if ($_SESSION['usertype'] == 1) { ?>


<div style=" display: block; margin: 0 auto;
width:200; text-align: center;
padding: 10px; border: 1px solid #ccc; border-radius: 16px;">  
    <a href="add-employee.php">Add Employee</a> 
</div>
<?php

    // include 'db-connect.php';
    // $query = "SELECT u.name, a.emp_id, COUNT(a.emp_id) FROM users as u
    // RIGHT JOIN articles as a USING(emp_id)
    // GROUP BY u.name";

    $d = date("Y-m-d");



        $specificDate = $_GET['specificDate'] ?? false;

        $query = "SELECT u.name, a.emp_id, COUNT(a.emp_id) ,
                SUM(CASE WHEN a.article_date = '$d' THEN 1 ELSE 0 END) as today
        FROM users as u
        RIGHT JOIN articles as a USING(emp_id)
        WHERE a.article_date='$specificDate'
        GROUP BY u.name";
    
        $count = mysqli_num_rows(mysqli_query($conn, $query));
                
    if ($count == 0) {
        ?>
        <div style="border: 1px solid #ddd; padding: 20px;
            border-radius: 15px;
            display: block; margin:10 auto; width: fit-content; 
            text-align:center; border-spacing: 0px;">
        <h1>No articles on  <?php echo $specificDate ?></h1>
        </div>
        

        <?php
    }
    else{
        
        if ($result = mysqli_query($conn, $query)) {
            ?>



            <style>
                tr:nth-child(even) {
                    /*background-color: aliceblue;*/
                    background-color: rgba(0,0,0,.05);
                }
                th, td {
                    padding: 20px;
                    /*border: 1px solid black;*/
                    /*width: 00px;*/
                    /*word-wrap: break-word;*/
                }

            </style>



            <!--table style="border: 2px solid black; text-align:center;"-->
            <table style="border: 1px solid #ddd; padding: 20px;
                    border-radius: 15px;
                    display: block; margin:10 auto; width: fit-content; 
                    text-align:center; border-spacing: 0px;">
                <!--tr style="border: 1px solid black; font-weight: bold;"-->
                <tr>
                    <th style="width:150px">Name</th>
                    <!--td style="padding-left: 20px;padding-right: 20px;">Employee ID</td-->
                    <th style="width: 100px">Employee ID</th>
                    <!--td>NO OF ARTICLES</td-->
                    <th style="width: 100px">Today's Submission</th>
                    <th style="width: 100px">Total Submission</th>
                    <th style="width: 50px"></th>
                </tr>
                <!--tr>
                    <td>qqqqqqqqqqqqq qqqqqqqqqqqqqqq qqqqssssssssss ssssssqqqqqqqqqq</td>
                </tr-->    
            
            <?php
            // echo "EMPLOYEE ID - NO OF ARTICLES<br>";
            while ($row = $result->fetch_row()) {
                // printf("%s - %s - %s - %s <br>", $row[0], $row[1], $row[2], $row[3]);
                printf("<tr>
                            <td>%s</td>
                            <td>%s</td>
                            <td>%s</td>
                            <td>%s</td>
                    <td>
                        <a href="."\"articles.php?empid=%s\"".">View</a>
                    </td>
                </tr>", $row[0], $row[1], $row[2], $row[3],$row[1] );
            }
            ?>
            </table>

            
            <?php
        }?>
        
        <?php
        
    }  
    ?>
    <!-- filter options using date -->
    <!-- align on right side -->
    <style>
            .filter {
                font-size: large;
            }
            .filter input,.filter button{
                font-size: large;
            }
        </style>
        <div class="filter" style=" position: absolute; top:15%; right: 6%; ; z-index:5">

            <p><b>Filter by Date Range</b></p>
            
            <form action="dashboard.php" method="POST">
                <!-- datefrom label -->
                <label for="datefrom">Date From:</label>
                <input type="date" name="dateFrom" style="width: 250px; display: block; margin: 0 auto;">
                <!-- dateto label -->
                <br>
                <label for="dateto">Date To:</label>
                <input type="date" name="dateTo" style="width: 250px; display: block; margin: 0 auto;">
                <br><br>
                <input type="submit" name ="submit" value="Submit" style="width: 250px; display: block; margin: 0 auto;width:200; text-align: center;  padding: 10px; border-radius: 16px;"> 

                <!-- reset button  -->

                <button type="submit" onclick="Reset()" name ="reset" value="Reset" style="width: 250px; display: block; margin: 0 auto;width:200; text-align: center;  padding: 10px; border-radius: 16px;">
                    Reset
                </button>
                <br>
                <p><b>Filter By Specific Date</b></p>
                <!-- label and input for specific date -->
                <label for="specificdate">Specific Date:</label>
                <input type="date" name="specificDate" style="width: 250px; display: block; margin: 0 auto;">
                <br><br>
                <!-- submit button -->
                <button type="submit" name="submit-date" value="Reset" style="width: 250px; display: block; margin: 0 auto;width:200; text-align: center;  padding: 10px; border-radius: 16px;">
                    Submit Date
                </button>
                <button type="submit" onclick="Reset()" name ="reset" value="Reset" style="width: 250px; display: block; margin: 0 auto;width:200; text-align: center;  padding: 10px; border-radius: 16px;">
                    Reset
                </button>
            </form>


        </div>

    <?php
    // if form is submitted
    if (isset($_POST['submit'])) {
        $dateFrom = $_POST['dateFrom'];
        $dateTo = $_POST['dateTo'];

                
        ?>
        <script>
            window.location = "dashboard-filter.php?dateFrom=<?php echo $dateFrom;?>&dateTo=<?php echo $dateTo;?>";
        </script>


        <?php

    }

    // if form is submitted
    if (isset($_POST['submit-date'])) {
        $specificDate = $_POST['specificDate'];

                
        ?>
        <script>
            window.location = "dashboard-date.php?specificDate=<?php echo $specificDate;?>";
        </script>


        <?php

    }
?>





<script>
    function Reset(){
        window.location.href = "dashboard.php";
    }
</script>




<?php } elseif ($_SESSION['usertype'] == 2) {
    // echo "HI";
    ?>
    <!--h4><a href="articles.php" style="text-decoration: none;";>View Articles</a></h4-->
    
    <div style="display: block; margin:0 auto; width: fit-content;">
        <!--h3 style="display: block; margin:0 auto; padding: 20px;">Add Article</h3-->


        <div style="width:200; display:inline-block; text-align: center; padding: 10px; border: 1px solid #ccc; border-radius: 16px;">
        Today's Submission : 
<?php

// include 'db-connect.php';
// $d = date("d-m-Y");
$d = date("Y-m-d");
$username = $_SESSION['username'];
$query = "SELECT COUNT(a.article_title) from articles as a, users as u 
WHERE u.username = '$username' and u.emp_id = a.emp_id and a.article_date = '$d'
GROUP BY a.article_date;";
$result = mysqli_query($conn, $query);

$count = $result->fetch_row()[0] ?? false;

echo($count);



?>

        </div>
    </div>

    <div style="border: 1px solid #ddd; padding: 20px;
    border-radius: 15px;
    display: block; margin:10 auto; width: fit-content;">
        
        
        <div style="display: block; margin:0 auto; width: fit-content;">
            <h1 style="display: block; margin:0 auto; padding: 20px;">Add Article</h1>
        </div>
        
        
        <div id="formcontainer">
            <form action="" method="post" id="articlesubmission" name="articlesubmission">
            
                <div style="display: block; margin:0 auto; width: fit-content;">
                    <div style="width:200; display:inline-block; text-align: center; padding: 10px; border: 1px solid #ccc; border-radius: 16px;">Date</div>
                    <div style="width:200; display:inline-block; text-align: center; padding: 10px; border: 1px solid #ccc; border-radius: 16px;">Title</div>
                    <div style="width: 200px; display:inline-block; text-align: center; padding: 10px; border: 1px solid #ccc; border-radius: 16px;">URL</div>
                    
                </div>
                
                <!--div>
                    <input type="date" name="date" style="width: 222px;padding: 10px;border-left-width: 1px;border-right-width: 1px;border-bottom-width: 1px;margin-top: 1px;">
                    <input type="text" name="title" style="width: 222px;padding: 10px;border-left-width: 1px;border-right-width: 1px;border-bottom-width: 1px;margin-top: 1px;">
                    <input type="text" name="url" style="width: 222px;padding: 10px;border-left-width: 1px;border-right-width: 1px;border-bottom-width: 1px;margin-top: 1px;">
                </div-->

                
            </form>
        </div>        
        <button id="addfieldbutton" style="display: block; margin:0 auto;padding: 10px;background: cadetblue;border: 0px;color: aliceblue; cursor: pointer;">+ Add Article</button>        
        
        <!--div style="margin:0 auto;
            display: block; width: 680px;"-->
        <button id="formsubmitbutton" form="articlesubmission" style="
            padding: 20px 50px;
            background: black;
            color: aliceblue;
            border: 0;
            border-radius: 15px;        
            
            margin:0 auto;
            margin-top: 50px;
            display: none;
            cursor: pointer;
        ">Submit</button>
        <!--/div-->

    </div>
    <script>
        // submitButtonVisible = 0
        document.getElementById("addfieldbutton").onclick = function() {
            document.getElementById("formsubmitbutton").style.display = "block";
            addNewField()
        }
        function addNewField(){
            form = document.getElementById("articlesubmission")
            newDiv = document.createElement("div")
            newDiv.style = "padding-top: 2px; padding-bottom: 2px; display: block; margin:0 auto; width: fit-content;"

            newHr = document.createElement("hr")
            newHr.style = "border: 0; border-top: 1px solid #eee;"
            newDiv.appendChild(newHr)

            newCrossSpacer = document.createElement("div")
            // <div style="margin: 0 auto; width: 10px; display: inline-block">X</div>
            newCrossSpacer.style = "margin: 0 auto; width: 10px; display: inline-block"
            newDiv.append(newCrossSpacer)

            newInputDate = document.createElement("input")
            newInputDate.type = "Date"
            newInputDate.name = "date[]"
            newInputDate.required = true
            newInputDate.style = "width: 222px;padding: 10px;margin-top: 1px; margin-right: 5px;"
            newDiv.appendChild(newInputDate)

            newInputTitle = document.createElement("input")
            newInputTitle.type = "text"
            newInputTitle.name = "title[]"
            newInputTitle.required = true
            newInputTitle.style = "width: 222px;padding: 10px;margin-top: 1px; margin-right: 5px;"
            newDiv.appendChild(newInputTitle)

            newInputUrl = document.createElement("input")
            newInputUrl.type = "text"
            newInputUrl.name = "url[]"
            newInputUrl.required = true
            newInputUrl.style = "width: 222px;padding: 10px;margin-top: 1px; /*margin-right: 5px;*/"
            newDiv.appendChild(newInputUrl)

            
            newCross = document.createElement("div")
            // <div style="margin: 0 auto; width: 10px; display: inline-block">X</div>
            newCross.style = "margin: 0 auto; width: 10px; display: inline-block; padding: 3px; cursor: pointer; transform: rotate(-45deg);"
            newCross.innerText = "+"
            newCross.setAttribute('onClick','removeP(this)')
            newDiv.append(newCross)

            form.appendChild(newDiv)            
        }
        function removeP(e) {
            e.parentNode.parentNode.removeChild(e.parentNode)
        }

    </script>
    
</body>
</html>
<?php }