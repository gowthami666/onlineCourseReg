<?php

$app->get('/terms', function() {
	
	$response = array();
    $db = new DbHandler();
	$terms = $db->getAllRecord("select term_id,term_name from term");
	//echo($terms);
    $response['terms'] = $terms;
    echoResponse(200, $response);
});

$app->get('/majors', function() {
	
	$response = array();
    $db = new DbHandler();
	$majors = $db->getAllRecord("select major_id,major_name,major_prefix from major");
    $response['majors'] = $majors;
    echoResponse(200, $response);
});

$app->get('/courses', function() {
	
	$response = array();
    $db = new DbHandler();
	$courses = $db->getAllRecord("select course_id,course_name from course");
    $response['courses'] = $courses;
    echoResponse(200, $response);
});

$app->get('/classLevels', function() {
	
	$response = array();
    $db = new DbHandler();
	$classLevels = $db->getAllRecord("select cls_lvl_id,level from class_level");
    $response['classLevels'] = $classLevels;
    echoResponse(200, $response);
});

$app->get('/instructors', function() {
	
	$response = array();
    $db = new DbHandler();
	$instructors = $db->getAllRecord("select instructor_id,fname,lname,email from instructor");
    $response['instructors'] = $instructors;
    echoResponse(200, $response);
});

$app->get('/schools', function() {
	
	$response = array();
    $db = new DbHandler();
	$schools = $db->getAllRecord("select school_id,school_name from school");
    $response['schools'] = $schools;
    echoResponse(200, $response);
});

$app->get('/sections', function() {
	
	$response = array();
    $db = new DbHandler();
	$sections = $db->getAllRecord("select section_id,section_name from section");
    $response['sections'] = $sections;
    echoResponse(200, $response);
});

?>