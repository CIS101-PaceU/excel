<?php
require 'dbconfig/config.php';
    // Make sure an ID was passed
    if(true) {
    // Get the ID
        $assignmentID=$_GET['assignmentID'];
            // Fetch the file information
            $query = "SELECT * from excelassignments where assignmentID='$assignmentID'";
            $result = mysqli_query($con,$query);  
             if($result) {
                // Make sure the result is valid
                if($result->num_rows == 1) {
                // Get the row
                    $row = mysqli_fetch_assoc($result);
                    
                    $promptFileData=$row['promptFile'];
                    $promptFileType=$row['promptFileType'];
                    $promptFileSize=$row['promptFileSize'];
					          $filename ='Assignment1.xlsx';
					//echo $size;
                    $userVariableCell=$row['userVariableCell'];
					
                    
// Print headers

//header("Content-Type: Text");
   //header("Content-Type: image/jpeg");
   header("Content-length: $promptFileSize");
   header("Content-type: $promptFileType");
   header("Content-Disposition: attachment; filename=$filename");
   header('Content-Transfer-Encoding: binary');	
   header('Cache-Control: must-revalidate');
   header('Pragma: public');
	//Replace php://output with $filename and add code below after that(before exit;)
	
	       ob_clean();
         flush();
         echo $promptFileData;
         mysqli_close($con);
         exit;
	
	}
				}
			 }
  
 

    ?>