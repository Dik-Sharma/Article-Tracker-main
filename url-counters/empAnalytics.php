<?php
session_start();

$_SESSION['filterData'] = "today";

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

    
    $d = date("Y-m-d");

    $query = "SELECT
        u.name,
        a.emp_id,
        COUNT(a.emp_id),
        SUM(CASE WHEN a.article_date = '$d' THEN 1 ELSE 0 END) as today
    FROM users as u
        RIGHT JOIN articles as a USING(emp_id)
    GROUP BY a.emp_id;";

    if ($result = mysqli_query($conn, $query)) {
        ?>
        
        <div class="row justify-content-evenly">

				<div class="col-md-2">
                    <div class="row justify-content-evenly">

                        <div class="card mt-4">
                            <div class="card-header">Pie Chart</div>
                            <div class="card-body">
                                <div class="chart-container pie-chart">
                                    <canvas id="pie_chart"></canvas>
                                </div>
                            </div>
                        </div>
				    </div>
                    <div class="row justify-content-evenly">
                        <div class="card mt-4">
                            <div class="card-header">Doughnut Chart</div>
                            <div class="card-body">
                                <div class="chart-container pie-chart">
                                    <canvas id="doughnut_chart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

				<div class="col-md-7">
					<div class="card mt-4 mb-4">
						<div class="card-header">Bar Chart</div>
						<div class="card-body">
							<div class="chart-container pie-chart">
								<canvas id="bar_chart"></canvas>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-2">
                    <p><b>Filter Options</b></p>
                    <form method="POST" >
                        <select name="emp_id" id="emp_id" style="width: 100%; display: block; margin: 0 auto;">
                            <!-- php for loop to keep all user name in option -->

                            <option value="">Select Employee...</option>

                            <?php
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='".$row['emp_id']."'>".$row['emp_id']." ".$row['name']."</option>";
                                }
                            ?>
                        </select>
                        <br>
                        <br>
                        <select id="filterData" name="filterData" style="width: 100%; display: block; margin: 0 auto;">

                            <option value="">Select one...</option>
                            <option value="today">Today</option>
                            <option value="thisMonth">This Month</option>
                            <option value="thisYear">This Year</option>
                            <option value="customMonth">Custom Month</option>
                            <option value="customYear">Custom Year</option>
                            <option value="dateRange">Date Range</option>
                        </select>
                        <!-- submit button -->
                        <br>
                        <br>
                        <div id="Description">

                            <br>
                            <div id="customMonth"  style="display: none;">

                                <!-- select for all months -->
                                <p>Select Month</p>

                                <select id="month" name="month" style="width: 100%; display: block; margin: 0 auto;">
                                    <option value="">Select one...</option>
                                    <option value="1">January</option>
                                    <option value="2">February</option>
                                    <option value="3">March</option>
                                    <option value="4">April</option>
                                    <option value="5">May</option>
                                    <option value="6">June</option>
                                    <option value="7">July</option>
                                    <option value="8">August</option>
                                    <option value="9">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                            </div>
                            <div id="customYear"  style="display: none;">
                                <!-- input for year -->
                                <p>Enter Year</p>
                                <input type="text" id="year" name="year" style="width: 100%; display: block; margin: 0 auto;" minlength="4" maxlength="4" min="2000" max="9999">
                                
                            </div>
                            <div id="dateRange"  style="display: none;">

                                <label for="datefrom">Date From:</label>
                                <input type="date" name="dateFrom" id="dateFrom" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>" style="width: 100%; display: block; margin: 0 auto;">
                                <!-- dateto label -->
                                <br>
                                <label for="dateto">Date To:</label>
                                <input type="date" name="dateTo" id="dateTo" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>" style="width: 100%; display: block; margin: 0 auto;">
                                
                            </div>
                            
                            <br>
                        </div>
                        
                        <?php 
                            if (isset($_POST['filter'])) {

                                // echo "dataEmp.php?emp_id=";
                                // echo $_POST['emp_id']."&code=";
                                echo $_POST['filterData'];
                                // ."&month=";echo $_POST['month']."&year=";echo $_POST['year']."&dateFrom=";echo $_POST['dateFrom']."&dateTo=";echo $_POST['dateTo']."";
                                

                            }

                        ?>
                        <button type="submit" name ="filter" id="filter" class="btn btn-primary" style="width: 150px; display: block; margin: 0 auto; text-align: center;  border-radius: 10px;"> 
                            Submit
                        </button>
                        
                    </form>

                </div>

            </div>

		</div>
        
        <script>
            
            $(document).ready(function(){

                var selectElement06 = document.getElementById("filterData");


                if (selectElement06) {
                    selectElement06.addEventListener('change', filtercode);
                }
                filtercode()
                function filtercode() {

                    var select = "Please choose a filter";
                

                        var option = document.getElementById("filterData").value;
                        //alert(option);
                        if(option == "today") // Better to use a Switch function. but this will work for now.
                        {
                            document.getElementById("customMonth").style.display = "none";
                            document.getElementById("customYear").style.display = "none";
                            document.getElementById("dateRange").style.display = "none";



                        }
                        else if(option == "thisMonth")
                        {

                            document.getElementById("customMonth").style.display = "none";
                            document.getElementById("customYear").style.display = "none";
                            document.getElementById("dateRange").style.display = "none";


                            
                        }
                        else if(option == "thisYear")
                        {

                            document.getElementById("customMonth").style.display = "none";
                            document.getElementById("customYear").style.display = "none";
                            document.getElementById("dateRange").style.display = "none";

                        }
                        else if(option == "customMonth")
                        {

                            document.getElementById("customMonth").style.display = "block";
                            document.getElementById("customYear").style.display = "none";
                            document.getElementById("dateRange").style.display = "none";


                        }
                        else if(option == "customYear")
                        {

                            document.getElementById("customYear").style.display = "block";
                            document.getElementById("customMonth").style.display = "none";
                            document.getElementById("dateRange").style.display = "none";


                        }
                        else if(option == "dateRange")
                        {

                            document.getElementById("dateRange").style.display = "block";
                            document.getElementById("customYear").style.display = "none";
                            document.getElementById("customMonth").style.display = "none";


                        }
                        else{
                            <?php $_SESSION['filterData'] = ""; ?>


                        }






                }
            
                makechart();
            
                function makechart()
                {
                    $.ajax({
                        url:"dataEmp.php?emp_id=<?php echo $_POST['emp_id']; ?>&code=<?php echo $_POST['filterData']; ?>&month=<?php echo $_POST['month']; ?>&year=<?php echo $_POST['year']; ?>&dateFrom=<?php echo $_POST['dateFrom']; ?>&dateTo=<?php echo $_POST['dateTo']; ?>",
                        method:"POST",
                        data:{action:'fetch'},
                        dataType:"JSON",
                        success:function(data)
                        {
                            var article_date = [];
                            var total = [];
                            var color = [];
            
                            for(var count = 0; count < data.length; count++)
                            {
                                article_date.push(data[count].article_date);
                                total.push(data[count].total);
                                color.push(data[count].color);
                            }
            
                            var chart_data = {
                                labels:article_date,
                                datasets:[
                                    {
                                        label:'Articles',
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
            
                            var group_chart2 = $('#doughnut_chart');
            
                            var graph2 = new Chart(group_chart2, {
                                type:"doughnut",
                                data:chart_data
                            });
            
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


        <?php
    }

?>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    
</body>
</html>
<?php }