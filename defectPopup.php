<?php
$defectID = $_POST['defectID'];

require_once('DatabaseInterface.php');

$conn = openDBConnection();

/* Set up and execute the query. */
$query = "SELECT * from dbo.Defect where DefectID=$defectID";
$response = sqlsrv_query( $conn, $query);
if($response === false)
{  
     echo "Error in query preparation/execution.\n";  
     die( print_r( sqlsrv_errors(), true));
}

$defectInfo = sqlsrv_fetch_array( $response, SQLSRV_FETCH_ASSOC);
?>
<?php echo "<image class=\"defectImage\" src=\"resources/Defect Pictures/".$defectInfo['ContextPicturePath']."\" />"?>

<div class="defectInfo">
	<div class="defectID">
		<h3>ID</h3>
		<p><?php $defectID ?></p>
	</div><!-- /defectID -->
	<div class="defectClass">
		<h3>Classification</h3>
		<?php echo "<input id=\"classToSelect\" type=\"hidden\" value=\"".$defectInfo['Classification']."\">"?>
		
		<select id="selectDefectClass">
			<option value="Tape">Tape</option>
			<option value="Washer">Washer</option>
			<option value="Unknown">Unknown</option>
		</select>
	</div><!-- /defectClass -->
</div><!-- /defectInfo -->
<div class="defectButtons">
	<?php echo "<button type=\"submit\" name=\"submit\" onclick=\"updateDefectClass($defectID)\">Save</button>" ?>
	
	<button id="closeButton" onclick="closeDefectPopup()">Close</button>
</div><!-- /defectButtons -->
