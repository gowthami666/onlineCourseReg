<?php
$app->post('/simpleSearch', function() use ($app) {
    $r = json_decode($app->request->getBody());
	//echo($r->classId);
	//$p = array();
	//$p['classId'] = $r->classId;
    verifyRequiredParams(array('classId'),$r->search);
    $response = array();
    $db = new DbHandler();
	$classId =$r->search->classId;
    $query = "select distinct class.class_id, class.course_id, course.course_name, instructor.fname, instructor.lname,section.section_name, 
		term.term_name,class.status, capacity.total_capacity, capacity.used_capacity, waitlist.total_waitlist, waitlist.used_waitlist, 
		school.school_name from class 
		join course on class.course_id = course.course_id
		join instructor on class.instructor_id = instructor.instructor_id 
		join section on section.section_id = class.section_id
		join school on school.school_id = class.class_id
		join term on term.term_id = class.term_id 
		join capacity on capacity.class_id = class.class_id 
		join waitlist on waitlist.class_id = class.class_id where class.class_id='$classId'";
		$response = array();
		//$resparams= array('class_id','course_id','course_name','fname','lname','section_name','term_name','status','total_capacity','used_capacity','total_waitlist','used_waitlist','school_name');
    $classes = $db->getAllRecord($query);
	//echo($r->classId);
	//print_r($classes[0]);
    if ($classes != NULL) {
	     $response ="";
		   $query1 = "select major.major_prefix from major
		   join major_class on major.major_id = major_class.major_id
		   where major_class.class_id = '$classId'";
		   
		   $majors = $db->getAllRecord($query1);
		   $majorString ="";
		   foreach($majors as $key => $value)
		   {
			   //echo($key);
			   //echo($value['major_prefix']);
			   $majorString = $majorString." ".$value['major_prefix'];
		   }
		   $response['majors'] = $majorString;
	   $cls = $classes[0];
        $response['status'] = "success";
        $response['message'] = 'Logged in successfully.';
        $response['classId'] = $cls['class_id'];
		$response['course_id'] = $cls['course_id'];
        $response['course_name'] = $cls['course_name'];
        $response['instructor_name'] = $cls['fname']." ".$cls['lname'];
		$response['section_name'] = $cls['section_name'];
		$response['term_name'] = $cls['term_name'];
		$response['school_name'] = $cls['school_name'];
		$response['total_capacity'] = $cls['total_capacity'];
		$response['used_capacity'] = $cls['used_capacity'];
		$response['total_waitlist'] = $cls['total_waitlist'];
		$response['used_waitlist'] = $cls['used_waitlist'];
		$response['status'] = $cls['status'];
		
		
        
        } else {
            $response['status'] = "error";
            $response['message'] = 'No class with given class number';
        }
    
    echoResponse(200, $response);
});

$app->post('/closeClass', function() use ($app) {
    $r = json_decode($app->request->getBody());
	verifyRequiredParams(array('clasId'));
    $response = array();
    $db = new DbHandler();
	$classId = $r->clasId;
	$query = "UPDATE class SET status='c' WHERE class_id = '$classId'";
	$classe = $db->getAllRecord($query);
	if ($classe != NULL) {
		$response['status'] = "success";
        $response['message'] = 'Class closed sucessfully.';
		
	}
	echoResponse(200, $response);
});
 
?>
