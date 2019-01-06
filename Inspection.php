<?php

$inspectionID = $_GET['InspectionID'];

if(! isset($inspectionID)){
	die("<h3 style=\"color: red\">Error: InspectionID was not given as a GET value</h3>");
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Quality Inspection Crawlers</title>
	<link rel="stylesheet" href="styles/CSSGridTemplate.css">
	<link rel="stylesheet" href="styles/WebsiteTemplate.css">
	<link rel="stylesheet" href="styles/InspectionStyle.css">

	<script src="http://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript"></script>
</head>
<body class="hg">
	<header class="hg__header">
		<div class="brandLogo" onclick="window.location='Home.php';">
			Lockheed Martin
		</div><!-- /brandLogo -->
		<div class="profileLink">
			<p class="greeting">Hello, Martin!</p>
			<image class="profileLogo" src="resources/placeholder-man.png" />
		</div><!-- /profileLink -->
	</header>

	<main class="hg__main">
		<?php
		require_once('DatabaseInterface.php');

		$conn = openDBConnection();

		/* Set up and execute the query. */
		$query = "SELECT * FROM dbo.Inspection WHERE InspectionID = " . $inspectionID;
		$response = sqlsrv_query( $conn, $query);
		if($response === false)
		{  
		     echo "Error in query preparation/execution.\n";  
		     die( print_r( sqlsrv_errors(), true));
		}

		$inspectionInfo = sqlsrv_fetch_array( $response, SQLSRV_FETCH_ASSOC);

		echo "<video class=\"streamVideo\" src=\"resources/Archived Videos/".$inspectionInfo['VideoPath']."\" controls>
			Your browser does not support video tags. Please try with the Google Chrome browser.
		</video>";

		?>
		<button id="annotateButton" type="submit">Annotate</button>
	</main>

	<aside class="hg__left">
		<?php

		require_once('DatabaseInterface.php');

		$conn = openDBConnection();

		/* Set up and execute the query. */
		$query = "SELECT * FROM dbo.Comment WHERE InspectionFK = ".$inspectionID;
		$response = sqlsrv_query( $conn, $query);
		if($response === false)
		{  
		     echo "Error in query preparation/execution.\n";  
		     die( print_r( sqlsrv_errors(), true));
		}

		while( $row = sqlsrv_fetch_array( $response, SQLSRV_FETCH_ASSOC))
		{
			if($row['UserFK'] != null) {
				#TODO: Check if UserFK is the user logged in and use messageMe instead of messageOther kind of div (also flips image and content order)

				echo "
				<div class=\"messageOther\">
					<image class=\"profilePic\" src=\"resources/placeholder-man.png\"/>
					".$row['Content']." 
				</div><!-- /messageOther -->";
			} else {
				echo "<div class=\"systemMessage\">".$row['Content']."</div>";
			}
		}

		closeDBConnection($conn);

		?>
	</aside>
	<aside class="hg__right">
		<div id="defects">
			<?php
			require_once('DatabaseInterface.php');

			$conn = openDBConnection();

			/* Set up and execute the query. */
			$query = "SELECT * FROM dbo.Defect WHERE InspectionFK = ".$inspectionID;
			$response = sqlsrv_query( $conn, $query);
			if($response === false)
			{  
			     echo "Error in query preparation/execution.\n";  
			     die( print_r( sqlsrv_errors(), true));
			}

			while( $row = sqlsrv_fetch_array( $response, SQLSRV_FETCH_ASSOC))
			{
				$defectPicturePath = trim($row['DefectPicturePath']);

				echo "
				<div class=\"defect\" id=".$row['DefectID']." onclick=\"defectPopup(".$row['DefectID'].")\">
					<image class=\"defectImage\" src=\"resources/Defect Pictures/".$defectPicturePath." \"/>
					<p class=\"classification\" id=Classification".$row['DefectID'].">".$row['Classification']."</p>
				</div><!-- /defect -->";
			}

			closeDBConnection($conn);
			?>
		</div><!-- /defects -->

		<div id="defectPopup" style="display: none"></div><!-- /defectPoppup -->

	</aside>

	<script>
		function defectPopup(defectID) {
			// AJAX code to execute query and get back to same page with table content without reloading the page.
			$.ajax({
				type: "POST",
				url: "defectPopup.php",
				data: {defectID: defectID},
				cache: false,
				success: function(html) {
					var elem = document.getElementById("defectPopup");
					elem.innerHTML = html;
					elem.style.display = "grid";
					var myClass = document.getElementById("classToSelect").value;
					$('#selectDefectClass').val(myClass);
				}
			});
			return false;
		}

		function updateDefectClass(defectID) {
			var selectDefectClass = $( "#selectDefectClass" ).val();
			// AJAX code to execute query and get back to same page with table content without reloading the page.
			$.ajax({
				type: "POST",
				url: "updateDefectClass.php",
				data: { defectID: defectID, class: selectDefectClass },
				cache: false,
				success: function(html) {
					var defectChanged = document.getElementById("Classification" + defectID);
					defectChanged.innerHTML = html;
					//var elem = document.getElementById("defectPopup");
					//elem.innerHTML = html;
					//elem.style.display = "grid";
				}
			});
			return false;
		}

		function closeDefectPopup() {
			var elem = document.getElementById("defectPopup");
			elem.style.display = "none";
		}
	</script>
</body>
</html>










