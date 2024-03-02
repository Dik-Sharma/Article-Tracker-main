<?php
session_start();

if (!isset($_SESSION['username']) || !isset($_SESSION['usertype'])) {
    header("location: logout.php");
    exit;
}

include 'db-connect.php';



if(isset($_POST["action"]))
{
	if($_POST["action"] == 'fetch')
	{

		$sql = "
		SELECT a.article_date, COUNT(a.article_date) AS Total 
		FROM articles a
		WHERE a.emp_id= '$_GET[emp_id]'
		GROUP BY a.article_date
		ORDER BY a.article_date
		";
        if( $_GET["code"] == "today"){
            $sql = "
            SELECT a.article_date, COUNT(a.article_date) AS Total
            FROM articles a
            WHERE a.emp_id= '$_GET[emp_id]' AND a.article_date = CURDATE()
            GROUP BY a.article_date
            ORDER BY a.article_date
            ";
        }
        
        else if( $_GET["code"] == "thisMonth")
        {
            $sql = "
            SELECT a.article_date, COUNT(a.article_date) AS Total
            FROM articles a
            WHERE a.emp_id= '$_GET[emp_id]' AND MONTH(a.article_date) = MONTH(CURDATE())
            GROUP BY a.article_date
            ORDER BY a.article_date;
            ";

        }
        else if ( $_GET["code"] == "thisYear")
        {
            $sql = "
            SELECT a.article_date, COUNT(a.article_date) AS Total
            FROM articles a
            WHERE a.emp_id= '$_GET[emp_id]' AND YEAR(a.article_date) = YEAR(CURDATE())
            GROUP BY a.article_date
            ORDER BY a.article_date;
            ";
        }
        else if ( $_GET["code"] == "customMonth")
        {
            $sql = "
            SELECT a.article_date, COUNT(a.article_date) AS Total
            FROM articles a
            WHERE a.emp_id= '$_GET[emp_id]' AND MONTH(a.article_date) = '$_GET[month]' AND YEAR(a.article_date) = YEAR(CURDATE())
            GROUP BY a.article_date
            ORDER BY a.article_date;
            ";
        }
        else if ( $_GET["code"] == "customYear")
        {
            $sql = "
            SELECT a.article_date, COUNT(a.article_date) AS Total
            FROM articles a
            WHERE a.emp_id= '$_GET[emp_id]' AND YEAR(a.article_date) = '$_GET[year]'
            GROUP BY a.article_date
            ORDER BY a.article_date;
            ";
        }
        else if ( $_GET["code"] == "dateRange")
        {
            $sql = "
            SELECT a.article_date, COUNT(a.article_date) AS Total
            FROM articles a
            WHERE a.emp_id= '$_GET[emp_id]' AND a.article_date BETWEEN '$_GET[dateFrom]' AND '$_GET[dateTo]'
            GROUP BY a.article_date
            ORDER BY a.article_date;
            ";
        }
		
			

		
		$result = $conn->query($sql);

		$data = array();

		foreach($result as $row)
		{
			$data[] = array(
				'article_date'	=>	$row["article_date"],
				'total'			=>	$row["Total"],
				'color'			=>	'#' . rand(100000, 999999) . ''
			);
		}

		echo json_encode($data);
	}
}


?>