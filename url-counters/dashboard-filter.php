<?php
session_start();

if (!isset($_SESSION['username']) || !isset($_SESSION['usertype'])) {
    header("location: logout.php");
    exit;
}

include 'db-connect.php';


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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
		<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
<body>
<!-- include nav.php -->
<?php include 'nav.php'; ?>




<?php
if ($_SESSION['usertype'] == 1) { ?>


<?php

    // include 'db-connect.php';
    // $query = "SELECT u.name, a.emp_id, COUNT(a.emp_id) FROM users as u
    // RIGHT JOIN articles as a USING(emp_id)
    // GROUP BY u.name";

    $d = date("Y-m-d");



        $datefrom = $_GET['dateFrom'] ?? false;
        $dateto = $_GET['dateTo'] ?? false;

        $query = "SELECT u.name, a.emp_id, COUNT(a.emp_id) ,
        SUM(CASE WHEN a.article_date = '$d' THEN 1 ELSE 0 END) as today
        FROM users as u
        RIGHT JOIN articles as a USING(emp_id)
        WHERE a.article_date BETWEEN '$datefrom' AND '$dateto'
        GROUP BY u.name";

    $count = mysqli_num_rows(mysqli_query($conn, $query));
            
    if ($count == 0) {
        ?>
        <div style="border: 1px solid #ddd; padding: 20px;
            border-radius: 15px;
            display: block; margin:10 auto; width: fit-content; 
            text-align:center; border-spacing: 0px;">
        <h1>No articles between  <?php echo $datefrom ?> and <?php echo $dateto ?> </h1>
        </div>
        

        <?php
    }
    else{
        
        if ($result = mysqli_query($conn, $query)) {
            ?>
                <div class="row justify-content-evenly" style="padding: 0 40px;">

                    <div class="col-md-2">
                        <h2>Top Performers</h2>

                            <div class="row">
                                <div class="card mt-4">
                                    <div class="card-header">Pie Chart</div>
                                    <div class="card-body">
                                        <div class="chart-container pie-chart">
                                            <canvas id="pie_chart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="row">
                                <div class="card mt-4">
                                    <div class="card-header">Doughnut Chart</div>
                                    <div class="card-body">
                                        <div class="chart-container pie-chart">
                                            <canvas id="doughnut_chart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            <!-- <div class="row">
                                <div class="card mt-4 mb-4">
                                    <div class="card-header">Bar Chart</div>
                                    <div class="card-body">
                                        <div class="chart-container pie-chart">
                                            <canvas id="bar_chart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div> -->

                    </div>
                    
                    <script>
                        
                        $(document).ready(function(){
                        
                            makechart();
                        
                            function makechart()
                            {
                                $.ajax({
                                    url:"dataAll.php",
                                    method:"POST",
                                    data:{action:'fetch'},
                                    dataType:"JSON",
                                    success:function(data)
                                    {
                                        var name = [];
                                        var total = [];
                                        var color = [];
                        
                                        for(var count = 0; count < 3; count++)
                                        {
                                            name.push(data[count].name);
                                            total.push(data[count].total);
                                            color.push(data[count].color);
                                        }
                        
                                        var chart_data = {
                                            labels:name,
                                            datasets:[
                                                {
                                                    label:'Article',
                                                    backgroundColor:color,
                                                    color:'#fff',
                                                    data:total
                                                }
                                            ]
                                        };
                        
                                        var options = {
                                            responsive:true,
                                            scales:{
                                                yAxes:[{
                                                    ticks:{
                                                        min:0
                                                    }
                                                }]
                                            }
                                        };
                        
                                        var group_chart1 = $('#pie_chart');
                        
                                        var graph1 = new Chart(group_chart1, {
                                            type:"pie",
                                            data:chart_data
                                        });
                        
                                        // var group_chart2 = $('#doughnut_chart');
                        
                                        // var graph2 = new Chart(group_chart2, {
                                        //     type:"doughnut",
                                        //     data:chart_data
                                        // });
                        
                                        var group_chart3 = $('#bar_chart');
                        
                                        var graph3 = new Chart(group_chart3, {
                                            type:'bar',
                                            data:chart_data,
                                            options:options
                                        });
                                    }
                                })
                            }
                        
                        });
                        
                    </script>



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
                        word-break: break-all;

                        }

                    </style>

                    <div class="col-md-8" style="border: 1px solid #ddd; padding: 20px;border-radius: 15px;display: block; margin:10 auto;width: fit-content;  height:fit-content; overflow-y:auto; ">
                            <table style="
                                    
                                     width: fit-content; 
                                    text-align:center; border-spacing: 0px;">
                                <!--tr style="border: 1px solid black; font-weight: bold;"-->
                                <tr>
                                    <th style="width:250px">Name</th>
                                    <!--td style="padding-left: 20px;padding-right: 20px;">Employee ID</td-->
                                    <!-- <th style="width: 100px">Employee ID</th> -->
                                    <!--td>NO OF ARTICLES</td-->
                                    <th style="width: 200px">Today's Submission</th>
                                    <th style="width: 200px">Total Submission</th>
                                    <th style="width: 150px"></th>
                                </tr>
                                <!--tr>
                                    <td>qqqqqqqqqqqqq qqqqqqqqqqqqqqq qqqqssssssssss ssssssqqqqqqqqqq</td>
                                </tr-->    
                            
                            <?php
                            // echo "EMPLOYEE ID - NO OF ARTICLES<br>";
                            // $datefrom = $_GET['dateFrom'] ?? "";
                            // $dateto = $_GET['dateTo'] ?? "";
                            while ($row = $result->fetch_row()) {
                                // printf("%s - %s - %s - %s <br>", $row[0], $row[1], $row[2], $row[3]);
                                printf("<tr>
                                            <td>%s</td>

                                            <td>%s</td>
                                            <td>%s</td>
                                    <td>
                                        <a href="."\"articles.php?empid=%s&dateFrom=%s&dateTo=%s\"".">View</a>

                                
                                    </td>
                                </tr>", $row[0], $row[3],$row[2], $row[1], $_GET['dateFrom'] ?? "", $_GET['dateTo'] ?? "");
                            }
                            ?>
                            </table>
                    </div>


                    <div class="col-md-2">

                        
                        <div class="filter row">

                            <p><b>Filter by Date Range</b></p>
                            
                            <form action="dashboard.php" method="POST">
                                <!-- datefrom label -->
                                <label for="datefrom">Date From:</label>
                                <input type="date" name="dateFrom" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>" style="width: 250px; display: block; margin: 0 auto;">
                                <!-- dateto label -->
                                <br>
                                <label for="dateto">Date To:</label>
                                <input type="date" name="dateTo" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>" style="width: 250px; display: block; margin: 0 auto;">
                                <br><br>
                                <div class="row">
                                    <input type="submit" name ="submit" value="Submit" class="btn btn-primary col-5" style=" display: block; margin: 0 auto; text-align: center;  border-radius: 10px;"> 

                                    <!-- reset button  -->
                                    <br>

                                    <input type="submit" onclick="Reset()" name ="reset" value="Reset" class="btn btn-warning col-5" style=" display: block; margin: 0 auto; text-align: center;  border-radius: 10px;">
                                
                                
                                    <div>
                                        <?php
                                        
                                        if ($datefrom != "" && $dateto != "") {
                                            echo "From: ".$datefrom."<br>";
                                            echo "To: ".$dateto."<br>";
                                        }
                                        ?>
                                    </div>
                                </div>
                                <br>
                                
                            </form>


                        </div>
                        <div class="filter-type row">
                            <p><b>Filter by Type</b></p>

                            <form action="dashboard.php">
                                <!-- select for type web and social -->
                                <select name="type" style="width: 100%; display: block; margin: 0 auto;">
                                    <option value="">Select Type</option>
                                    <option value="web">Web</option>
                                    <option value="social">Social</option>
                                </select>
                                <br>
                                <button type="submit-type" name ="submit-type" class="btn btn-primary" style="width: 100%px; display: block; margin: 0 auto; text-align: center;  border-radius: 10px;"> 
                                    Submit
                                </button>
                                
                            </form>
                            <?php
                                if (isset($_POST['submit-type'])) {
                                    $type = $_POST['type'];
                                    ?>
                                    <script>
                                        window.location = "dashboard.php?type=<?php echo $type; ?>";
                                    </script>


                                    <?php

                                }
                            ?>

                        </div>

                    </div>

                    
            <?php
        }?>
        
        <?php
        
    }  
    ?>
    <!-- filter options using date -->
        <!-- align on right side -->
    




<?php }?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

</body>
</html>
