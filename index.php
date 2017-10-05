<?php
	session_start();
	require 'dbconfig/config.php';

	if(isset($_POST['login']))
		{
			$userName = $_POST['username'];
			$password = $_POST['password'];
			
			$query = "select * from userinfo where userName='$userName' and password='$password'";
			$query_run = mysqli_query($con,$query);
			if(mysqli_num_rows($query_run) > 0)
			{
				$_SESSION['username'] = $userName;
				header('location: landingPage.php');
			}
			else
			{
				$message = "User name and password do not match";
					echo "<script type='text/javascript'>alert('$message');</script>";	
			}	
		}
?>


<!DOCTYPE html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="HandheldFriendly" content="true">
	<!--<meta http-equiv="refresh" content="3" >-->
	<title>My Login Page</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="CSS/style.css">
</head>


<body style="background-color: #2c3e50">
	
	
	<div class="box">
		<div class="jumbotron">
			<center>
			<h2> Login Form </h2>
		
			<img src = "imgs/paceofficiallogo.jpg" class="image-responsive">
			</center>
			<form action="index.php" method="post" class="myClass">
			<fieldset>
			<label id="lbl1"><b>UserName</b></label></br>
			<input name="username" type="text" class="txtfield" placeholder="Pace ID"/></br>
			<label id="lbl2"><b>Password</b></label></br>
			<input name = "password" type="password" class="txtfield" placeholder="Password"/></br>
			

			<input name = "login" type="submit" id="btn" value="Login"/>
			
			<a href="registration.php">
			<input type="button" id="btn" value = "Register Now"/>
			</a>
			</fieldset>
			</form>

			
		</div>
	</div>
</body>
</html>