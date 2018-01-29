<?php

class DbHandler {

    private $conn;

    function __construct() {
        require_once 'dbConnect.php';
        // opening db connection
        $db = new dbConnect();
        $this->conn = $db->connect();
    }
	
    /**
     * Fetching single record
     */
    public function getOneRecord($query) {
        $r = $this->conn->query($query.' LIMIT 1') or die($this->conn->error.__LINE__);
        return $result = $r->fetch_assoc();    
    }
	/**
	* Fetching all records
	*/
	 public function getAllRecord($query) {
        $r = $this->conn->query($query) or die($this->conn->error.__LINE__);
		$rows =array();
		while ($row = $r->fetch_assoc()) {
			$rows[] = $row;
		}
		
		
        return $rows;   
    }
	
	
	/**
	*Fetch records for query
	*/
	public function getRecordsForQuery($query,$params, $types)
	{
		
		$r = $this->conn->prepare($query) or die($this->conn->error.__LINE__);
		for ($i =0; $i< count($params) ;$i++ ) {
				$r->bind_param($types[$i],$params[$i]);
		  }
		$r->execute();
		$rows =array();
		$result = $r->get_result();
		
		while ($row =  $result->fetch_assoc()) {
			$rows[] = $row;
			
			
		}
		return $rows;
	}
	
	
	
	
	
	
    /**
     * Creating new record
     */
    public function insertIntoTable($obj, $column_names, $table_name) {
        
        $c = (array) $obj;
        $keys = array_keys($c);
        $columns = '';
        $values = '';
		$query='';
        foreach($column_names as $desired_key){ // Check the obj received. If blank insert blank into the array.
           if(!in_array($desired_key, $keys)) {
                $$desired_key = '';
            }else{
                $$desired_key = $c[$desired_key];
            }
            $columns = $columns.$desired_key.',';
            $values = $values."'".$$desired_key."',";
        }
        $query = "INSERT INTO ".$table_name."(".trim($columns,',').") VALUES(".trim($values,',').")";
		//echo($query);
        $r = $this->conn->query($query) or die($this->conn->error.__LINE__);

        if ($r) {
            $new_row_id = $this->conn->insert_id;
            return $new_row_id;
            } else {
            return NULL;
        }
    }
public function getSession(){
    if (!isset($_SESSION)) {
        session_start();
    }
    $sess = array();
    if(isset($_SESSION['uid']))
    {
        $sess["uid"] = $_SESSION['uid'];
       //$sess["fname"] = $_SESSION['fname'];
       //$sess["email"] = $_SESSION['email'];
	   //$sess['uid'] = $response["uid"];
            $sess['fname'] = $_SESSION['fname'];
			$sess['lname'] = $_SESSION['lname'];
            $sess['email'] = $_SESSION['email'];
			$sess['cls_lvl_id'] =$_SESSION['cls_lvl_id'];
			$sess['major_id'] =$_SESSION['major_id'];
			$sess['school_id'] =$_SESSION['school_id'];
			$sess['term_id'] =$_SESSION['term_id'];
			$sess['role_id'] =$_SESSION['role_id']; //student
			
    }
    else
    {
        $sess["uid"] = '';
        $sess["fname"] = 'Guest';
		$sess["lname"] = '';
        $sess["email"] = '';
		$sess['cls_lvl_id'] ='';
			$sess['major_id'] ='';
			$sess['school_id'] ='';
			$sess['term_id'] ='';
			$sess['role_id'] ='';
			//$sess['lname'] = '';
    }
    return $sess;
}
public function destroySession(){
    if (!isset($_SESSION)) {
    session_start();
    }
    if(isSet($_SESSION['uid']))
    {
        unset($_SESSION['uid']);
        unset($_SESSION['fname']);
        unset($_SESSION['lname']);
		unset($_SESSION['email']);
		unset($_SESSION['cls_lvl_id']);
		unset($_SESSION['major_id']);
		unset($_SESSION['school_id']);
		unset($_SESSION['term_id']);
		unset($_SESSION['role_id']);
        $info='info';
        if(isSet($_COOKIE[$info]))
        {
            setcookie ($info, '', time() - $cookie_time);
        }
        $msg="Logged Out Successfully...";
    }
    else
    {
        $msg = "Not logged in...";
    }
    return $msg;
}
 
}

?>
