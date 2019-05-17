<?php
//UPLOAD AND CONNECTION scripts USE THE secret FOLDER
	
	require_once('../../secret/connection.php');
	
	$db = mysqli_connect($host,$username,$password,$database);
	
	$response = array('status'=>false,'message'=>'');
	
	if(
		isset($_POST['email']) && !empty($_POST['email'])
		&&
		isset($_POST['password']) && !empty($_POST['password'])
	){
		
		$email = mysqli_real_escape_string($db,$_POST['email']);
		$password = mysqli_real_escape_string($db,$_POST['password']);
		
		//EMAIL AND MOBILE MUST BE UNIQUE
		$sql = "SELECT * FROM users WHERE (`username` = '$email' OR `email` = '$email' OR `mobile` = '$email') AND password = md5('$password') LIMIT 1";
		$query = mysqli_query($db,$sql);
		$num = mysqli_num_rows($query);
		if($num > 0){//IF THE USER EXISTS
			
			$row = mysqli_fetch_array($query,MYSQLI_ASSOC);
			
			session_start();
			$_SESSION['user_id'] = $row['user_id'];
			$_SESSION['username'] = $row['username'];
		
			$response['status'] = true;
			$response['message'] = 'SUCCESSFULLY SIGNED IN.';
			
		}else if($num == 0){//IF THE USER EXISTS
			$response['status'] = false;
			$response['message'] = 'THE EMAIL AND PASSWORD COMBINATION ARE INCORRECT.';
		}else{//IF AN ERROR OCCURRED IN THE DATABASE
			$response['status'] = false;
			$response['message'] = 'AN ERROR OCCURED WHILE ATTEMPTING TO SIGN YOU IN. TRY AGAIN TO CONFIRM.';
		}
		
	}else{
		
		$m = '';
		$response['status'] = false;
		
		if(!isset($_POST['email']) || empty($_POST['email'])){
			
			$m = 'ENTER A VALID EMAIL ADDRESS';
			
		}else if(!isset($_POST['password']) || empty($_POST['password'])){
		
			$m = 'CHECK YOUR PASSWORD AND TRY AGAIN.';
		
		}else $m = 'AN UNKNOWN ERROR OCCURED TRY AGAIN.';
		
		$response['message'] = $m;
		
	}

	mysqli_close($db);
	echo json_encode($response);
?>