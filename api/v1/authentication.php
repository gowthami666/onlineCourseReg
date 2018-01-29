<?php 
$app->get('/session', function() {
    $db = new DbHandler();
    $session = $db->getSession();
    $response["uid"] = $session['uid'];
    $response["email"] = $session['email'];
    $response["fname"] = $session['fname'];
	$response["lname"] = $session['lname'];
	$response["role_id"] = $session['role_id'];
	$response["email"] = $session['email'];
	$response["cls_lvl_id"] = $session['cls_lvl_id'];
	$response["major_id"] = $session['major_id'];
	$response["school_id"] = $session['school_id'];
	$response["term_id"] = $session['term_id'];
    echoResponse(200, $session);
});

$app->post('/login', function() use ($app) {
    require_once 'passwordHash.php';
    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('email', 'password'),$r->customer);
    $response = array();
    $db = new DbHandler();
    $password = $r->customer->password;
    $email = $r->customer->email;
    $user = $db->getOneRecord("select user_id,fname,lname,password,emailId,role_id from user where emailId='$email'");
    if ($user != NULL) {
		$user_id = $user['user_id'];
        if(passwordHash::check_password($user['password'],$password)){
			if($user['role_id'] == 2)
			{
				$user_details = $db->getOneRecord("select cls_lvl_id,major_id,term_id,school_id from student_details where student_id='$user_id'");
				$cls_lvl_id = $user_details['cls_lvl_id'];
				$major_id = $user_details['major_id'];
				$term_id = $user_details['term_id'];
				$school_id = $user_details['school_id'];
			}
			else
			{
				$cls_lvl_id = '';
				$major_id = '';
				$term_id = '';
				$school_id = '';
			}
			
        $response['status'] = "success";
        $response['message'] = 'Logged in successfully.';
        $response['uid'] = $user_id;
		
       // $response['createdAt'] = $user['created'];
        if (!isset($_SESSION)) {
            session_start();
        }
         $_SESSION['uid'] = $response["uid"];
            $_SESSION['fname'] = $user['fname'];
			$_SESSION['lname'] = $user['lname'];
            $_SESSION['email'] = $user['emailId'];
			$_SESSION['cls_lvl_id'] =$user_details['cls_lvl_id'];
			$_SESSION['major_id'] =$user_details['major_id'];
			$_SESSION['school_id'] =$user_details['school_id'];
			$_SESSION['term_id'] =$user_details['term_id'];
			$_SESSION['role_id'] =$user['role_id'];
        } else {
            $response['status'] = "error";
            $response['message'] = 'Login failed. Incorrect credentials';
        }
    }else {
            $response['status'] = "error";
            $response['message'] = 'No such user is registered';
        }
    echoResponse(200, $response);
});
$app->post('/signUp', function() use ($app) {
    $response = array();
    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('email', 'fname','lname','password','classLevel','major','term','school'),$r->customer);
    require_once 'passwordHash.php';
    $db = new DbHandler();
    $fname = $r->customer->fname;
    $lname = $r->customer->lname;
    $email = $r->customer->email;
    $classLevel = $r->customer->classLevel;
	$major = $r->customer->major;
	$term = $r->customer->term;
    $school = $r->customer->school;
	$password = $r->customer->password;
    $isUserExists = $db->getOneRecord("select 1 from user where emailId='$email'");
    if(!$isUserExists){
        $r->customer->password = passwordHash::hash($password);
		$user = array();
		$user["fname"] = $fname;
		$user["lname"] = $lname;
		$user["emailId"] = $email;
		$user["password"] = $r->customer->password;
		$user["role_id"] = 2; // student role id
		//$db->beginTransaction();
        $table_name = "user";
        $column_names = array('fname', 'lname', 'emailId', 'password', 'role_id');
        $result = $db->insertIntoTable($user, $column_names, $table_name);
        if ($result != NULL) {
			$user_details =array();
			$user_details["student_id"] = $result;
			$user_details["cls_lvl_id"] = $classLevel;
			$user_details["major_id"] = $major;
			$user_details["school_id"] = $school;
			$user_details["term_id"]= $term;
			
			$column_names_details = array('student_id', 'cls_lvl_id', 'major_id', 'school_id', 'term_id');
			$details_result = $db->insertIntoTable($user_details, $column_names_details, 'student_details');
			//echo($details_result == NULL);
			
            $response["status"] = "success";
            $response["message"] = "User account created successfully";
            $response["uid"] = $result;
			
            if (!isset($_SESSION)) {
                session_start();
            }
            $_SESSION['uid'] = $response["uid"];
            $_SESSION['fname'] = $fname;
			$_SESSION['lname'] = $lname;
            $_SESSION['email'] = $email;
			$_SESSION['cls_lvl_id'] =$classLevel;
			$_SESSION['major_id'] =$major;
			$_SESSION['school_id'] =$school;
			$_SESSION['term_id'] =$term;
			$_SESSION['role_id'] =2; //student
			echoResponse(200, $response);

            
        } else {
			//$db->rollBackTransaction();
            $response["status"] = "error";
            $response["message"] = "Failed to create the account. Please try again";
            echoResponse(201, $response);
        }            
    }else{
        $response["status"] = "error";
        $response["message"] = "An user with the provided email exists!";
        echoResponse(201, $response);
    }
	//echoResponse(200,)
});
 $app->post('/Add', function() use ($app) {
    $response = array();
    $r = json_decode($app->request->getBody());
	
    verifyRequiredParams(array('school','term','classLevel','course','major','section','capacity','instructor','waitlist'),$r->customer);
	$db = new DbHandler();
	$school_id = $r->customer->school;
	$term_id = $r->customer->term;
	$classLevel_id = $r->customer->classLevel;
	$course_id = $r->customer->course;
	$major_id = $r->customer->major;
	$section_id = $r->customer->section;
	$capacity = $r->customer->capacity;
	
	$instructor_id = $r->customer->instructor;
	$waitlist = $r->customer->waitlist;
	$class = array();
	$class["term_id"] = $term_id;
	$class["course_id"] = $course_id;
	$class["section_id"] = $section_id;
	$class["instructor_id"] = $instructor_id;
	$class["status"] = 'A';
	$class["school_id"] = $school_id;
	$column_names = array('term_id', 'course_id', 'section_id', 'instructor_id', 'status','school_id');
	$result = $db->insertIntoTable($class, $column_names, 'class');
	if ($result != NULL) {
		
			$response["status"] = "success";
            $response["message"] = "Records updated successfully";
            $response["cid"] = $result;
			$cap = array();
			$cap["class_id"] = $result;
			$cap["total_capacity"] = $capacity;
			$cap["used_capacity"] = '0';
			$column_names = array('class_id', 'total_capacity', 'used_capacity');
			$result = $db->insertIntoTable($cap, $column_names, 'capacity');
			$wait = array();
			$wait["capacity_id"] = $result;
			$wait["class_id"] = $cap["class_id"];
			$wait["total_waitlist"] = $waitlist;
			$wait["used_waitlist"] = '0';
			$column_names = array('capacity_id', 'class_id','total_waitlist', 'used_waitlist');
			$result = $db->insertIntoTable($wait, $column_names, 'waitlist');
			$majors = array();
			$majors["major_id"] = $major_id;
			$majors["class_id"] = $cap["class_id"];
			$column_names = array('major_id','class_id');
			$result = $db->insertIntoTable($majors, $column_names, 'major_class');
			echoResponse(200, $response);
			
	}
	else{
        $response["status"] = "error";
        $response["message"] = "Failed to update";
        echoResponse(201, $response);
	}
});
  

$app->get('/logout', function() {
    $db = new DbHandler();
    $session = $db->destroySession();
    $response["status"] = "info";
    $response["message"] = "Logged out successfully";
    echoResponse(200, $response);
});
?>