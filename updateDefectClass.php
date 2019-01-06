<?php

$defectID = htmlspecialchars($_POST['defectID']);
$class = htmlspecialchars($_POST['class']);

require_once('DatabaseInterface.php');

$conn = openDBConnection();

/* Set up and execute the query. */
$query = "
UPDATE dbo.Defect
SET Classification = '$class'
where DefectID=$defectID";

$response = sqlsrv_query( $conn, $query);
if($response === false)
{  
     echo "Error in query preparation/execution.\n";  
     die( print_r( sqlsrv_errors(), true));
}

// Echo class to update the defect form
echo $class;

?>