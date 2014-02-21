<?php
//includes
include "includes/config.php";
include "includes/sql_cred.php";



  $json_string = file_get_contents("http://api.wunderground.com/api/${wunderground_key}/geolookup/conditions/q/${wunderground_location}.json");
  $parsed_json = json_decode($json_string);
  $observation_time = $parsed_json->{'current_observation'}->{'observation_epoch'};
  $temp_c = $parsed_json->{'current_observation'}->{'temp_c'};
  $temp_c = $temp_c * 10; //want to store int
  echo "Current temperature @ ${observation_time}  is: ${temp_c}\n";

//Connect to the database. (host,username,password,database)
$mysqli = mysqli_connect($myhost, $myuser, $mypasswd, $mydatabase);
// Check for errors connecting to database.
if (mysqli_connect_errno()) {
	die('Unable to connect to database. '.$mysqli -> connect_error);
}
// temperature data
try {
	// All queries and commands go here.
	$query = "INSERT into measured_temperature VALUES ('$observation_time','$temp_c');"; 
	if(!$mysqli -> query($query)) {
		throw new Exception($mysqli -> error);
	}
}
catch (Exception $e) {
	echo $e -> getMessage();
}


?>
