<?php 
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "db_cesc_safety_management_live";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

if (isset($_POST['save'])) { 
    $Picc = $_FILES['file_name']['name'];  
//    print_r($Picc);die; 
    if (!empty($Picc)) {
        $temp = $_FILES['file_name']['tmp_name'];
        $uniq = uniqid("");
        $Picc = $uniq . "_" . $Picc; 
        $upload = "../../../../assets/uploads/excel/" . $Picc;   
        copy($temp, $upload);
        require('../../lib/php-excel-reader/excel_reader2.php');
        require('../../lib/SpreadsheetReader.php');
        $Reader = new SpreadsheetReader($upload);  
        $Sheets = $Reader->Sheets(); 
        $flag = 0;
       
       foreach ($Sheets as $Index => $Name) {
            $Reader->ChangeSheet($Index);
            foreach ($Reader as $Row){  
                if (trim(@$Row[0]) != "financial_year_id") {
                    if ($Row != "sheets") { 
                                $financial_year_id  = $Row[0]; 
                                $cset_contractor_id = $Row[2]; 
                                $month_id           = $Row[3]; 
                                $target             = $Row[4]; 

                      $insert_data = "insert into annual_target_gen_site_audit(financial_year_id,cset_contractor_id,month_id,target) values('".$financial_year_id."','" .$cset_contractor_id."','".$month_id."','".$target."')";  
                        if ($conn->query($insert_data) === TRUE) {
                                 $flag = 1;
                        } 
                    }
                }
            }   
         
        }
       if($flag == 1) {
           redirect("SAGen_TargetEntry.php");
           echo "New record inserted successfully";
       } else {
           echo "Error: " . $insert_data . "<br>" . $conn->error;
       }
      
    }
}
?>