<!DOCTYPE html>
<html>
<head>
	<title>Quality Inspection Crawlers</title>
	<link rel="stylesheet" href="styles/CSSGridTemplate.css">
	<link rel="stylesheet" href="styles/WebsiteTemplate.css">
	<link rel="stylesheet" href="styles/HomeStyle.css">
	<?php require_once('DatabaseInterface.php'); ?>
</head>
<body class="hg">
	<header class="hg__header">
		<div class="brandLogo">
			Lockheed Martin
		</div><!-- /brandLogo -->
		<div class="profileLink">
			<p class="greeting">Hello, Martin!</p>
			<image class="profileLogo" src="resources/placeholder-man.png" />
		</div><!-- /profileLink -->
	</header>
	<main class="hg__main">
		<div id="startInspection">
			<button type="submit">Start new inspection</button>
		</div><!-- /startInspection -->
		<div id="inspectionSearch">
			<form>
				<input type="text" placeholder="Search">
				<button type="submit">Go</button>
			</form>
		</div><!-- /inspectionSearch -->
		<div id="inspectionList">

			<?php
				$conn = openDBConnection();
				$query = "SELECT * FROM dbo.Inspection ORDER BY InspectionID DESC";
				$response = sqlsrv_query($conn, $query);
				if($response === false) {
					echo "Error querying database";
					die(print_r(sqlsrv_errors(), true));
				}

				echo "
				<table id=\"inspectionTable\">
					<tr class=\"table-header\">
						<th>Inspection #</th>
						<th>Date</th>
						<th>Bay</th>
						<th>Defects Found</th>
						<th>Crawler Name</th>
					</tr>";
				while($row = sqlsrv_fetch_array($response, SQLSRV_FETCH_ASSOC)) {
					/* Send new query for defect count */
					$queryDefectCount = "SELECT COUNT(*) FROM dbo.Defect WHERE InspectionFK = ".$row['InspectionID'];
					$defectCountResp = sqlsrv_query( $conn, $queryDefectCount);
					if($defectCountResp === false)
					{  
						echo "Error in query preparation/execution.\n";  
						die( print_r( sqlsrv_errors(), true));
					}
					$defectCount = implode(sqlsrv_fetch_array( $defectCountResp, SQLSRV_FETCH_ASSOC));

					/* Send new query for crawler name */
					$queryCrawlerNames = "
						SELECT dbo.Crawler.Name
						FROM dbo.Inspection
						JOIN dbo.InspectionCrawler ON(dbo.Inspection.InspectionID = dbo.InspectionCrawler.InspectionFK)
						JOIN dbo.Crawler ON(dbo.InspectionCrawler.CrawlerFK = dbo.Crawler.CrawlerID)
						WHERE dbo.Inspection.InspectionID = ".$row['InspectionID'];
					$crawlerNamesResp = sqlsrv_query( $conn, $queryCrawlerNames);
					if($crawlerNamesResp === false)
					{  
						echo "Error in query preparation/execution.\n";  
						die( print_r( sqlsrv_errors(), true));
					}

					/* If date is today, set the inspection as active */
					if(date('Ymd') == $row['Date']->format('Ymd')) {
						echo "<tr class=\"table-active\" onclick=\"window.location='inspection.php?InspectionID=".$row['InspectionID']."';\">";
					} else {
						echo "<tr onclick=\"window.location='inspection.php?InspectionID=".$row['InspectionID']."';\">";
					}
					echo "<td>".$row['InspectionID']."</td>";
					echo "<td>".$row['Date']->format('m/d/Y')."</td>";
					echo "<td>".$row['Bay']."</td>";
					echo "<td>".$defectCount."</td>";
					echo "<td>";
					while($crawlerNames = sqlsrv_fetch_array($crawlerNamesResp, SQLSRV_FETCH_ASSOC)) {
						echo implode($crawlerNames);
					}
					echo "</td>";
					echo "</tr>";
				}
				echo "</table>";
				closeDBConnection($conn);
			?>
		</div><!-- /inspectionList -->
	</main>
	<aside class="hg__left"></aside>
	<aside class="hg__right"></aside>
</body>
</html>