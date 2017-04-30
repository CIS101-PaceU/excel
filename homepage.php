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
<link rel="stylesheet" href="CSS/style.css">
</head>


<body style="background-color: #1abc9c">
<div id="main-wrapper">

	<center><h2> WELCOME 
	<?php
	
	echo $_SESSION['username']
	
	?>
	</h2>
	<img src = "imgs/paceofficiallogo.jpg" class="logo"></br>
	
	</center>
	<form action="" method="post" class="myClass" enctype="multipart/form-data">
	<input type="file" 	 name="excelFile" />
	<input type="file" 	 name="submittedFile" />
	<input type="submit" name="btnImport" id="" value="Import Data"/></br>
	<input type="submit" name="logout"    id="logout_btn" value="Logout"/></br>
	
	<?php
	
	if(isset($_POST['btnImport']))
	{
		
		
			$fileName = $_FILES["excelFile"]["name"];
			echo $fileName;
			$filePath = $_FILES["excelFile"]["tmp_name"];
			echo $filePath;
			$subfileName = $_FILES["submittedFile"]["name"];
			echo $fileName;
			$subfilePath = $_FILES["submittedFile"]["tmp_name"];
			echo $filePath;
			
		//$filePath = ;



			/* $objPHPExcel = PHPExcel_IOFactory::load($filePath);
		foreach($objPHPExcel->getWorksheetIterator() as $worksheet)
		{
			
			$highestRow = $worksheet->getHighestRow();
			//echo "<script type='text/javascript'>alert('$highestRow');</script>";
			
			//$dataFfile = "C:/tmp/test_data.xls";
			//	$objPHPExcel = PHPExcel_IOFactory::load($dataFfile);
			$sheet = $objPHPExcel->getActiveSheet();
			$data = $sheet->rangeToArray('A1:A23');
			$keyCells =array();
			//echo "Rows available: " . count($data) . "\n";
			echo "<br/>";
			echo "<br/>";
			foreach ($data as $row) {
				foreach($row as $key=>$value) {
					$keyCells[] = $value ;
					echo 'index is '.$key.' and value is '.$value  ; 
					echo "<br/>";
				}
			//print_r($row);
			
			} */
			
			
			/*for($row=2; $row<=$highestRow; $row++)
			{
			$id = mysqli_real_escape_string($con,$worksheet->getCellByColumnAndRow(0,$row)->getValue());
			$name = mysqli_real_escape_string($con,$worksheet->getCellByColumnAndRow(1,$row)->getValue());
			$email = mysqli_real_escape_string($con,$worksheet->getCellByColumnAndRow(2,$row)->getValue());
			
			
			$sql = "INSERT INTO tbl_excel(excel_name,excel_email) VALUES ('$name','$email')";
			$query_run = mysqli_query($con,$sql);
			if($query_run)
			{
				$message = "Excel data uploaded successfully";
				echo "<script type='text/javascript'>alert('$message');</script>";
			}
			else
			{
				$message = "No data uploaded.. Some error occured";
				echo "<script type='text/javascript'>alert($message);</script>";
			}
			}*/
			
		// }
		
		
		$objPHPExcel = PHPExcel_IOFactory::load($filePath);
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$keydata =array();  $x=0;$y=0;
		foreach($objPHPExcel->getWorksheetIterator() as $worksheet)
		{
		
					echo '<table border ="1px" width ="50%" float:left overflow:auto>' . "\n";
			foreach ($objWorksheet->getRowIterator() as $row) {
			  echo '<tr>' . "\n";
					
			  $cellIterator = $row->getCellIterator();
			  $cellIterator->setIterateOnlyExistingCells(false); // This loops all cells,
																 // even if it is not set.
																 // By default, only cells
																 // that are set will be
																 // iterated.
			  foreach ($cellIterator as $cell) {
				  
				$keydata[$x][$y] = $cell->getValue();
				
				echo '<td>' . $keydata[$x][$y] . '</td>' .'<td>' . $cell->getCalculatedValue() . '</td>' . "\n";
				$y++;
			  }
			  $x++;
			  echo '</tr>' . "\n";
			}
			echo '</table>' . "\n";
		}	

		
		
		
		array_shift($keyCells);
		$objNPHPExcel = PHPExcel_IOFactory::load($subfilePath);
		foreach($objNPHPExcel->getWorksheetIterator() as $worksheet)
		{
			
			$highestRow = $worksheet->getHighestRow();
			//echo "<script type='text/javascript'>alert('$highestRow');</script>";
			
			//$dataFfile = "C:/tmp/test_data.xls";
			//	$objPHPExcel = PHPExcel_IOFactory::load($dataFfile);
			$sheet = $objNPHPExcel->getActiveSheet();
			$data = $sheet->rangeToArray('A1:A23');
			$subFormula =array();
			$subValue =array();
			//echo "Rows available: " . count($data) . "\n";
			//foreach($keyCells as $data)
			echo "<br/>";
			for ( $x =0 ; $x< count($keyCells);$x++)
			{
				echo 'data is ' .$keyCells[$x]. "  ";
				$subValue[$x] = $sheet->getCell($keyCells[$x])->getValue();
				$subFormula[$x]= $sheet->getCell($keyCells[$x])->getCalculatedValue();
				echo 'Submitted Formula ' .$subValue[$x] . "  ";
				echo 'Submitted Value ' .$subFormula[$x];
				echo "<br/>";
			}
			
			
			
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