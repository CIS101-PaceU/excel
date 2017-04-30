<?php
session_start();
require 'dbconfig/config.php';
?>


<!DOCTYPE html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>
My Login Page
</title>
<link rel="stylesheet" href="CSS/style.css">
</head>


<body style="background-color: #2c3e50">
<div id="main-wrapper">

	<center><h2> LOGIN FORM </h2>
	<img src = "imgs/paceofficiallogo.jpg" class="logo"></br>
	</center>
	
	<form action="index.php" method="post" class="myClass">
	<label id="lbl1"><b>UserName</b></label></br>
	<input name="username" type="text" class="inputValues" placeholder="Pace ID"/></br>
	<label id="lbl2"><b>Password</b></label></br>
	<input name = "password" type="password" class="inputValues" placeholder="Password"/></br>
	<input name = "login" type="submit" id="login_btn" value="Login"/></br>
	<a href="registration.php"><input type="button" id="reg_btn" value = "Register Now"/></a>
	</form>
	
	<?php
	
	if(isset($_POST['login']))
	{
		
		
		
		$userName = $_POST['username'];
		$password = $_POST['password'];
		
		$query = "select * from userinfo where userName='$userName' and password='$password'";
		$query_run = mysqli_query($con,$query);
		if(mysqli_num_rows($query_run) > 0)
		{
			$_SESSION['username'] = $userName;
			header('location: homepage.php');
		}
		else
		{
			
			$message = "User name and password do not match";
				echo "<script type='text/javascript'>alert('$message');</script>";
			
		}
		
	}
	
	
	
	?>
	
	
</div>

</body>
</html>