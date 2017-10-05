<?php

session_start();
if($_SESSION['username']==''){
header( 'Location: index.php' );

}
require 'dbconfig/config.php';
include 'PHPExcel.php';            // External plugin..
?>
<!DOCTYPE html>
<head>
<title>
Welcome Page
</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
<link rel="stylesheet" href="CSS/style.css" >
</head>
<style>
table {
    border: 1px solid black;
    border-collapse: collapse;
	
	overflow : auto;
	width: 50%;
}
th, td {
	border: 1px solid black;
    border-collapse: collapse;
    padding: 5px;
    text-align: left;    
}
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

	<center><h2> WELCOME 
	<?php
	
	echo $_SESSION['username']
	
	?>
	</h2>
	
	<img src = "imgs/paceofficiallogo.jpg" class="image-responsive"></br>
	
	</center>
	<form action="homepage.php" method="post" class="myClass" enctype="multipart/form-data">
	<fieldset>
	<legend>Create Assignment</legend>
	<label>Assignment Title:</label> 
	<input type="text" name = "assignName" class="txtfield" placeholder="Assignment Title"><br/>
	<label>Key Cell:</label> 
	<input type="text" name = "keyCell" class="txtfield" placeholder="Cell Index to verify"><br/>
	<label>Select Key File:</label> 
	<input type="file" 	 name="excelFile" /><br/>
	<label>Select Prompt File:</label> 
	<input type="file" 	 name="submittedFile" /><br/>
	<input type="submit" name="btnImport" id="btn" value="Import Data"/></br>


	<?php
	if(isset($_POST['btnImport']))
	{
		
			$assignTitle =$_POST["assignName"];
			$fileName = $_FILES["excelFile"]["name"];
			$filePath = $_FILES["excelFile"]["tmp_name"];
			$subfileName = $_FILES["submittedFile"]["name"];
			$subfilePath = $_FILES["submittedFile"]["tmp_name"];
			
			$keyCell =$_POST['keyCell'];
			$keyFileExt = pathinfo($fileName, PATHINFO_EXTENSION);
			$promptFileExt = pathinfo($subfileName, PATHINFO_EXTENSION);
			if($assignTitle =='')
			{
				$message = "Please select assignment Title" ;
		echo "<script type='text/javascript'>alert('$message');</script>";
		
			}
			else if ($fileName =='')
			{
				$message = "Please select key file to upload" ;
		echo "<script type='text/javascript'>alert('$message');</script>";
			}
			else if ($subfileName =='')
			{
				$message = "Please select prompt file to upload" ;
		echo "<script type='text/javascript'>alert('$message');</script>";
			}
			else if($keyFileExt !="xlsx" || $promptFileExt !="xlsx"  ){
				$message = "Please select xlsx format file to upload" ;
		echo "<script type='text/javascript'>alert('$message');</script>";	
			}
			
			else 
			{
			
			// QUERY TO BE EDITED
			$date = date('Y-m-d');
			$datetime = date('Y-m-d H:i:s');	
			$query = "insert into assignment(CRN,courseID,assignmentTitle,assignmentDescriptor,assignmentInstructor,possibleGrade,dueDate,availableDate,endAvailableDate,submissionNum,gradeAttempt) values('63391842','CS101','$assignTitle','$assignTitle','$assignTitle','A', '$date','$date','$date','0','NA')";			
			$query_run = mysqli_query($con,$query) or die(mysqli_error($con));
			$result = mysqli_query($con, "SELECT assignmentID FROM assignment where assignmentTitle = '$assignTitle'");
							while ($row = $result->fetch_assoc()) {
    							$assignmentID=  $row['assignmentID'];
								}
								
	$keyfileData = mysqli_real_escape_string($con,file_get_contents($_FILES["excelFile"]["tmp_name"]));
	
	$promptfileData = mysqli_real_escape_string($con,file_get_contents($_FILES["submittedFile"]["tmp_name"]));
	$prmptfileType = mysqli_real_escape_string($con,$_FILES["submittedFile"]["type"]);
	$promptfileSize = mysqli_real_escape_string($con,$_FILES['submittedFile']['size']);
	
		$query ="insert into excelassignments(assignmentID,promptFile,keyFile,userVariableCell,acceptableExt,promptFileType,promptFileSize) values ('$assignmentID','$promptfileData','$keyfileData','$keyCell', '.xlsx','$prmptfileType','$promptfileSize')";
					$query_run = mysqli_query($con,$query);			
		
		$objPHPExcel = PHPExcel_IOFactory::load($filePath);
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$keydata =array();  $x=0;$y=0;
		foreach($objPHPExcel->getWorksheetIterator() as $worksheet)
		{
		
			foreach ($objWorksheet->getRowIterator() as $row) {
					
			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false); 
			$y=0;
			foreach ($cellIterator as $cell) {
			$keydata[$x][$y] = [
					'Value' => $cell->getValue(),
					'Index' => $cell->getCoordinate(),
					'CalculateValue' => $cell->getCalculatedValue()
					];
				$y++;
			  }
			  $x++;
			}
		}	
		$objPHPExcel->disconnectWorksheets();
		unset($objPHPExcel);

		echo "<br/>";
		echo "<br/>";
		$objPHPExcel = PHPExcel_IOFactory::load($subfilePath);
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$promptData =array();
		$x=0;$y=0;
		foreach($objPHPExcel->getWorksheetIterator() as $worksheet)
		{
			foreach ($objWorksheet->getRowIterator() as $row) {
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				$y =0;
				foreach ($cellIterator as $cell) {
					$promptData[$x][$y] = [
					'Value' => $cell->getValue(),
					'Index' => $cell->getCoordinate(),
					'CalculateValue' => $cell->getCalculatedValue()
					];
					$y++;
				}
				$x++;
			}
			
		}
		$r1 =count($keydata);
		$d= 0;$c = 0;$diffCount =0;
		$diffData =array();	
		echo '<fieldset>';
		echo '<legend>Difference Found:</legend>';
		echo '<table >' . "\n";
		echo '<tr>' ;	
		echo '<td>' . "Cell" . '</td>' .'<td>' . "Value" . '</td>' .'<td>' . "Formula" . '</td>'  . "\n";
		for ( $i = 0; $i < $r1; $i++)
		{	
			$c1 =count($keydata[$i]);
			
			$d =0;
			for ( $j= 0; $j< $c1; $j++)
			{
				
				if(isset($keydata[$i][$j]))
				{
					if($keydata[$i][$j] != '')
					{
						if( $keydata[$i][$j] == $promptData[$i][$j])
						{
							
								
						}
						else
						{
							echo '<tr>' ;	
							$diffData[$c][$d] = [
							'Value' => $keydata[$i][$j]['Value'],
							'Index' => $keydata[$i][$j]['Index'],
							'CalculateValue' => $keydata[$i][$j]['CalculateValue']
							];
							$query = "insert into differences(assignmentID,Cell,KeyFormula,Status,PotentialPoints,Type,Value) values($assignmentID,'{$diffData[$c][$d]['Index']}','{$diffData[$c][$d]['Value']}','A',0.2,'.xlsx','{$diffData[$c][$d]['CalculateValue']}')";
							$query_run = mysqli_query($con,$query);
							echo '<td>' . $diffData[$c][$d]['Index'] . '</td>' .'<td>' . $diffData[$c][$d]['CalculateValue'] . '</td>'.'<td>' . $diffData[$c][$d]['Value'] . '</td>'  . "\n";
							$d++;$diffCount++;
						}
					}
						
				}
				
			}
			echo '</tr>' ;
			$c++;
		}
		echo '</table>' . "\n";
		echo '</fieldset>';
		echo "<br/>";
		$message = "Total of " . $diffCount . " differences saved in database" ;
		echo "<script type='text/javascript'>alert('$message');</script>";
			}		
		
	}
	
	if(isset($_POST['logout']))
	{
		
		
		$message = "LOG OUT";
				echo "<script type='text/javascript'>alert('$message');</script>";
		SESSION_destroy();
		header('location: index.php');
		
	}
	
	?>
	</fieldset>
	

	</form>
</div>

</body>
</html>
	
	