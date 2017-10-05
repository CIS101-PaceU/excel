
<?php

session_start();
if($_SESSION['username']==''){
header( 'Location: index.php' );

}
?>
<?php
	require 'dbconfig/config.php';
?>


<!DOCTYPE html>
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="HandheldFriendly" content="true">
<title>Landing Page</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
<link rel="stylesheet" href="CSS/style.css">
<style>
ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #333;
}

li {
    float: left;
}

li a {
    display: block;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}

li a:hover:not(.active) {
    background-color: green;
}

.active {
    background-color: green;
}
</style>
</head>
<body style="background-color: #2c3e50">
<div>

<ul>
  <li><a class="active" href="landingPage.php">Home</a></li>
  <li><a href="#news">Register Courses</a></li>
  <li><a href="homepage.php">Upload Assignment</a></li>
  <li><a href="#about">Download Assignment</a></li>
  <li style="float:right"><a class="active" href="index.php">Logout</a></li>
</ul>
</div>
<div class="box">
	<form action="registerCourses.php" method="post" name="form">

		<img src = "imgs/paceofficiallogo.jpg" class="logo"></br>

		<center>	
		<h2> Welcome <?php echo $_SESSION['username'] ?>!</h2>
		<h3>Select course to register </h3>

		

		<div style="overflow-x:auto;">
					   <select name="program" size="8" >
			           <option value="intro">Intro to Parallel and Distributed Computing
			           <option value="CIC">Concepts and Structures Internet Computing
			           <option value="Algo">Algorithms and Computational Theory
			           <option value="Comp1">Computer Science Project 1
			           <option value="Comp2">Computer Science Project 2
			           <option value="mobileApp">Mobile Application Development
			           <option value="mobileWeb">Mobile Web Content and Development
					   <option value="dataMining">Data Mining
					   <option value="bigData">Big Data
					   <option value="dbms">Database Systems
			           </select>
		</div>
				

		    <input type="submit" name="submit_btn" id="btn" value="Register"/>
			<input type="hidden" name="submitted" id="btn" value="TRUE"/>
		</center>
	
	</form> 


	 <?php
		
		if(isset($_POST['submit_btn']))
		{           
	               
					$courses= $_POST['program'];
	                $userName =$_SESSION['username'];
					$query = "insert into courses(userName,courseName) values('$userName','$courses')";
					$query_run = mysqli_query($con,$query);
					
					if($query_run){
					$message = "Course has been resgistered successfully..";
					echo "<script type='text/javascript'>alert('$message');</script>";
					}
					else{
					$message = "Some error has occured!!...";
					echo "<script type='text/javascript'>alert('$message');</script>";
					}
		}
		
		?>

</div>

</body>
</html>
