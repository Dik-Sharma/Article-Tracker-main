<?php
session_start();

if (!isset($_SESSION['username']) || !isset($_SESSION['usertype'])) {
    header("location: logout.php");
    exit;
}

include 'db-connect.php';
//dataAll.php

// $_SESSION['filterData'] = "today";

if(isset($_POST["action"]))
{
	if($_POST["action"] == 'fetch')
	{

		$sql = "
		SELECT u.name, COUNT(a.emp_id) AS Total 
		FROM `articles` a ,`users` u
		WHERE A.emp_id=u.emp_id
		GROUP BY u.name
		ORDER BY COUNT(a.emp_id) DESC
		";

		if( $_GET["code"] == "today"){
			$sql = "
			SELECT u.name, COUNT(a.emp_id) AS Total
			FROM `articles` a ,`users` u
			WHERE A.emp_id=u.emp_id AND a.article_date = CURDATE()
			GROUP BY u.name
			ORDER BY COUNT(a.emp_id) DESC;
			";
		}
		else if ( $_GET["code"] == "thisMonth"){
			$sql = "
			SELECT u.name, COUNT(a.emp_id) AS Total
			FROM `articles` a ,`users` u
			WHERE A.emp_id=u.emp_id AND MONTH(a.article_date) = MONTH(CURDATE())
			GROUP BY u.name
			ORDER BY COUNT(a.emp_id) DESC;
			";
		}
		else if ( $_GET["code"] == "thisYear"){
			$sql = "
			SELECT u.name, COUNT(a.emp_id) AS Total
			FROM `articles` a ,`users` u
			WHERE A.emp_id=u.emp_id AND YEAR(a.article_date) = YEAR(CURDATE())
			GROUP BY u.name
			ORDER BY COUNT(a.emp_id) DESC;
			";
		}
		else if ( $_GET["code"] == "customMonth"){
			$sql = "
			SELECT u.name, COUNT(a.emp_id) AS Total
			FROM `articles` a ,`users` u
			WHERE A.emp_id=u.emp_id AND MONTH(a.article_date) = '$_GET[month]'
			GROUP BY u.name
			ORDER BY COUNT(a.emp_id) DESC;
			";
		}
		else if ( $_GET["code"] == "customYear"){
			$sql = "
			SELECT u.name, COUNT(a.emp_id) AS Total
			FROM `articles` a ,`users` u
			WHERE A.emp_id=u.emp_id AND YEAR(a.article_date) = '$_GET[year]'
			GROUP BY u.name
			ORDER BY COUNT(a.emp_id) DESC;
			";
		}
		else if ( $_GET["code"] == "dateRange")
		{
			$sql = "
			SELECT u.name, COUNT(a.emp_id) AS Total
			FROM `articles` a ,`users` u
			WHERE A.emp_id=u.emp_id AND a.article_date BETWEEN '$_GET[dateFrom]' AND '$_GET[dateTo]'
			GROUP BY u.name
			ORDER BY COUNT(a.emp_id) DESC;
			";
		}
			

		
		$result = $conn->query($sql);

		$data = array();

		foreach($result as $row)
		{
			$data[] = array(
				'name'		=>	$row["name"],
				'total'			=>	$row["Total"],
				'color'			=>	'#' . rand(100000, 999999) . ''
			);
		}

		echo json_encode($data);
	}
}


?>