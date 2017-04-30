<?php

	require 'dbconfig/config.php';

?>


<!DOCTYPE html>
<head>
<title>
Registration Page
</title>
<link rel="stylesheet" href="CSS/style.css">
</head>


<body style="background-color: #2c3e50">
<div id="main-wrapper">

	<center><h2> REGISTER HERE !! </h2>
	<img src = "imgs/paceofficiallogo.jpg" class="logo"></br>
	</center>
	
	<form action="registration.php" method="post" class="myClass">
	
	<label id="lbl0"><b>First Name</b></label></br>
	<input name="firstname" type="text" class="inputValues" placeholder="Enter your first name" required/></br>
	
	<label id="lbl01"><b>Last Name</b></label></br>
	<input name="lastname" type="text" class="inputValues" placeholder="Enter your last name" required/></br>
	
	<label id="lbl02"><b>Address</b></label></br>
	<input name="address" type="text" class="inputValues" placeholder="Enter your address" required/></br>
	
	
	
	<label id="lbl1"><b>UserName</b></label></br>
	<input name="username" type="text" class="inputValues" placeholder="Pace ID" required/></br>
	<label id="lbl2"><b>Password</b></label></br>
	<input name="password" type="password" class="inputValues" placeholder="Password" required /></br>
	<label id="lbl3"><b>Confirm Password</b></label></br>
	<input name="cpassword" type="password" class="inputValues" placeholder="retype password" required /></br>
	<label id="lbl4"><b>Branch</b></label></br>
	<input name="sbranch" type="text" class="inputValues" placeholder="Enter your branch" required /></br>
	<input name="submit_btn" type="submit" id="signup_btn" value="Sign Up" /></br>
	<a href="index.php"><input type="button" id="back_btn" value="<< Go Back"/></a>
	</form>
	<?php
	
	if(isset($_POST['submit_btn']))
	{
		$studFirstName = $_POST['firstname'];
		$studLastName = $_POST['lastname'];
		$studAddress = $_POST['address'];
		$username = $_POST['username'];
		$password = $_POST['password'];
		$cpassword = $_POST['cpassword'];
		$studBranch = $_POST['sbranch'];
		
		
		if($password == $cpassword)
		{
			
			$query = "select * from userinfo where userName = '$username'" ;
			$query_run = mysqli_query($con,$query);
			
			if(mysqli_num_rows($query_run) > 0)
			{
			
				$message = "User Name already exists!!....try another one";
				echo "<script type='text/javascript'>alert('$message');</script>";
			}
			else
			{
				$query = "insert into userinfo(firstName,lastName,Address,userName,password,studBranch) values('$studFirstName','$studLastName','$studAddress','$username','$password','$studBranch')";
				$query_run = mysqli_query($con,$query);
				
				if($query_run){
				$message = "Your account is created successfully..";
				echo "<script type='text/javascript'>alert('$message');</script>";
				}
				else{
					
					$message = "Some error has occured!!...";
				echo "<script type='text/javascript'>alert('$message');</script>";
				}
			}
			
		}
		
		else{
			
				$message = "Password and confirm password do not match";
				echo "<script type='text/javascript'>alert('$message');</script>";
		}
		
	}
	
	?>
	
</div>

</body>
</html>