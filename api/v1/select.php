<?php
if(isset($_GET['school']))
{
	getSchools();
}
elseif(isset($_GET['term']))
{
}
elseif(isset($_GET['clslevel']))
{
}
elseif(isset($_GET['course']))
{
}
elseif(isset($_GET['major']))
{
}
elseif(isset($_GET['section']))
{
}

function getSchools() {
	$response = array();
	$db = new DbHandler();
    $query = "select school_name from school;"
	$response = $db->getAllRecord($query);
	echo $response;
	return json_encode($response);
}


?>