
<?php

session_start();
if($_SESSION['username']==''){
header( 'Location: index.php' );

}
require 'dbconfig/config.php';
include 'PHPExcel.php';            
?>


<!DOCTYPE html>
<head>
<title>
Welcome Page
</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
<link rel="stylesheet" href="CSS/style.css">
 <script src='http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>
<script type="text/javascript">
	
	$(document).ready(function() {
	$("#assignName").on('change', function () {
    var assignmentID = $(this).val();
    var link = "download.php?assignmentID=" + assignmentID;
	document.getElementById("downloadLink").setAttribute("href",link);
});
});
	
</script>
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

a:link, a:visited {
    background-color: #333;
    color: white;
    padding: 14px 25px;
    text-align: center; 
    text-decoration: none;
    display: inline-block;
}

a:hover, a:active {
    background-color: green;
}

</style>

<body style="background-color: #1abc9c">
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
	
	<form action="" method="post" class="myClass" enctype="multipart/form-data">
	<fieldset>
	<legend>Submit Assignment</legend>
	<label>Assignment Title:</label> 
	
	<select id="assignName"   name="assignName" class ="txtfield">
	<option value="" >Select assignment title</option>
	<?php
		$query = "select assignmentID,assignmentTitle from  assignment";
		$query_run = mysqli_query($con,$query);
		foreach($query_run as $assigninfo) {
	?>
	<option id="<?php echo $assigninfo['assignmentID']; ?>" value="<?php echo $assigninfo['assignmentID']; ?>"><?php echo $assigninfo['assignmentTitle'];?> </option>
	<?php
	  }
	?>
	</select></br>

	<a id="downloadLink" class="active">Click here to download prompt file</a>
	<br/>
	
	<label>Select Assignment file File:</label> 
	<input type="file" 	 name="excelFile" /><br/>
	<input type="submit" name="btnImport" id="btn" value="Submit"/></br>
	 </fieldset>
	<?php
	
	if(isset($_POST['btnImport']))
	{
			
			$assignmentID =$_POST["assignName"];
			$fileName = $_FILES["excelFile"]["name"];
			$filePath = $_FILES["excelFile"]["tmp_name"];
		$result = mysqli_query($con, "SELECT * FROM differences where assignmentID = '$assignmentID'");
		$keyData = array(); $p =0;
		foreach($result as $row)
		{	
			$keyData[$p] = [
					'Value' => $row["KeyFormula"],
					'Index' => $row["Cell"],
					'CalculateValue' => $row["Value"]
					];
			$p++;		
		}
		$subFileExt = pathinfo($fileName, PATHINFO_EXTENSION);
		if($subFileExt !='xlsx')
			{
				$message = "Please select xlsx format file" ;
		echo "<script type='text/javascript'>alert('$message');</script>";
		
			}
		else{	
			
			$date = date('Y-m-d');
			$datetime = date('Y-m-d H:i:s');	


		$fileData = mysqli_real_escape_string($con,file_get_contents($_FILES["excelFile"]["tmp_name"]));
	
		$query = "insert into submissionsdata(assignmentID,submittedDate,submittedFile,userID,submissionOrder,gradesEarned,updateTime,submittedUserVariable,isCheated) values(1,'$date','$fileData','X01358581','0','NA','$datetime','NA','NO')";
							$query_run = mysqli_query($con,$query);
							$result = mysqli_query($con, "SELECT submissionID FROM submissionsData where assignmentID = '1'");
							while ($row = $result->fetch_assoc()) {
    							$submissionID=  $row['submissionID'];
								}
							
		$objPHPExcel = PHPExcel_IOFactory::load($filePath);
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$submittedData =array();  $x=0;$y=0;
		//echo '<table >' . "\n";
		//echo '<tr>' ;	
		//echo '<td>' . "Value" . '</td>' .'<td>' . "Calculated value" . '</td>' . "\n";
		foreach($objPHPExcel->getWorksheetIterator() as $worksheet)
		{
		
			//echo '<table border ="1px" width ="50%" float:left overflow:auto>' . "\n";
			foreach ($objWorksheet->getRowIterator() as $row) {
			//echo '<tr>' . "\n";
					
			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false); // This loops all cells, even if it is not set. By default, only cells that are set will be iterated.
			$y=0;
			//$objPHPExcel->getActiveSheet()->getCell('B8')->getValue();
			foreach ($cellIterator as $cell) {
			$submittedData[$x][$y] = [
					'Value' => $cell->getValue(),
					'Index' => $cell->getCoordinate(),
					'CalculateValue' => $cell->getCalculatedValue()
					];
			//echo '<td>' . $submittedData[$x][$y]['Value'] . '</td>' .'<td>' . $submittedData[$x][$y]['CalculateValue'] . '</td>' . "\n";
				$y++;
			  }
			  $x++;
			  //echo '</tr>' . "\n";
			}
			//echo '</table>' . "\n";
		}	
		
		
		
		$r1 =count($submittedData);
		$d= 0;$c = 0;$diffCount =0; $earnPoints =0.0;$totalPoints =0;$sno=0;
		$diffFound =array();
		echo '<table >' . "\n";
		echo '<thead>';
		echo '<tr>' ;	
		echo '<th >' . "Sno" .'</th>'.'<th >' . "Cell" . '</th>' .'<th >' . "Key Value" . '</th>' .'<th >' . "Key CalculateValue" . '</th>'  .'<th >' . "Submitted Value" . '</th>'  .'<th >' . "Submitted CalculateValue" . '</th>'  .'<th >' . "Earned Points" . '</th>'  . "\n";
		echo '</tr>' ;echo '</thead>';
		for ( $i = 0; $i < $r1; $i++)
		{	
			$c1 =count($submittedData[$i]);
			
			//$d =0;
			for ( $j= 0; $j< $c1; $j++)
			{
				for($k =0; $k < count($keyData) ;$k ++)
				{

					if($submittedData[$i][$j]['Index'] == $keyData[$k]['Index'])
					{
						$d++;
						if($submittedData[$i][$j]['Value'] != null && $keyData[$k]['Value']!= null)
						{

						if($submittedData[$i][$j]['Value'] == $keyData[$k]['Value'] && round($submittedData[$i][$j]['CalculateValue'],2) ==round($keyData[$k]['CalculateValue'],2) )
						{
							$earnPoints =0.2;
							$totalPoints =$totalPoints + $earnPoints;
							$query = "insert into submissiondifference(submissionID,Cell,submittedFormula,submittedValue,pointsEarned) values('$submissionID','{$submittedData[$i][$j]['Index']}','{$submittedData[$i][$j]['Value']}','{$submittedData[$i][$j]['CalculateValue']}',0.2)";
							$query_run = mysqli_query($con,$query);
						}
						else
						{
							$earnPoints =0.0;
							$query = "insert into submissiondifference(submissionID,Cell,submittedFormula,submittedValue,pointsEarned) values('$submissionID','{$submittedData[$i][$j]['Index']}','{$submittedData[$i][$j]['Value']}','{$submittedData[$i][$j]['CalculateValue']}',0.0)";
							$query_run = mysqli_query($con,$query);
						}
						}

						echo '<tbody>';
					echo '<tr>' ;
					$sno++;	
					$diffFound[$i] = [
					'Key Value' => $keyData[$k]['Value'],
					'Index' => $submittedData[$i][$j]['Index'],
					'Key CalculateValue' => $keyData[$k]['CalculateValue'],
					'Submitted Value' => $submittedData[$i][$j]['Value'],
					'Submitted CalculateValue' => $submittedData[$i][$j]['CalculateValue']
					];
					echo '<td >' . $sno. '</td>' .'<td >' . $diffFound[$i]['Index'] . '</td>' .'<td >' . $diffFound[$i]['Key Value'] . '</td>' .'<td >' . $diffFound[$i]['Key CalculateValue'] . '</td>'  .'<td >' . $diffFound[$i]['Submitted Value'] . '</td>' .'<td >'. $diffFound[$i]['Submitted CalculateValue'] . '</td>' .'<td >'. $earnPoints . '</td>' ."\n";
					
					}
				}
				
				
			}
			echo '</tr>' ;echo '</tbody>';
			//$c++;
		}
		echo '<tr>' ;	
		echo '<td>' . "" . '</td>' .'<td>' . "" . '</td>' .'<td>' . "" . '</td>' .'<td>' . "" . '</td>'  .'<td>' . "" . '</td>'  .'<td>' . "Total" . '</td>'  .'<td>' . $totalPoints . '</td>'  . "\n";
		echo '</tr>' ;
		echo '</table>' . "\n";
		//echo '</div>';
		echo '</fieldset>';
		echo "<br/>";
		$message = "Total of " . $d . " differences verified and score is displayed" ;
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
	
	</form> 
	 
	
	 
</div>

</body>
</html>