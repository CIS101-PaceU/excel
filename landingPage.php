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
    background-color: black;
}
</style>
</head>
<body style="background-color: #2c3e50">
<div class="navbar">
<ul>
  <li><a class="active" href="landingPage.php">Home</a></li>
  <li><a href="registerCourses.php">Register Courses</a></li>
  <li><a href="homepage.php">Create Assignment</a></li>
  <li><a href="studenthomepage.php">Submit Assignment</a></li>
  <li><a href="#about">View Grade</a></li>
  <li style="float:right"><a class="active" href="index.php">Logout</a></li>
</ul>
</div>

<div class="box">
<center>
<img src = "imgs/paceofficiallogo.jpg" class="image-responsive"></br>


<h2> Welcome <?php echo $_SESSION['username'] ?> !</h2>
</center>

</div>

</body>
</html>