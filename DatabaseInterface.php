<?php

function openDBConnection() {
	/* Specify the server and connection string attributes. */
	$serverName = "CRAWLERSERVER\SQLEXPRESS";
	$connectionInfo = array( "Database"=>"master");

	/* Connect using Windows Authentication. */  
	$conn = sqlsrv_connect( $serverName, $connectionInfo);
	if( $conn === false )
	{  
	     echo "Unable to connect.</br>";  
	     die( print_r( sqlsrv_errors(), true));
	}
	return $conn;
}

function closeDBConnection($conn) {
	/* Free statement and connection resources. */
	sqlsrv_close( $conn);
}

?>