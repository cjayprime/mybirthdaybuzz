<?php
	
	require_once('../../../secret/verification.php');
	require_once('../../../secret/connection.php');
	
	$db = mysqli_connect($host,$username,$password,$database);
	
	$response = array('status'=>false,'message'=>'');
	
	$user_id = $_SESSION['user_id'];
	
	if(
		(isset($_POST['day']) && !empty($_POST['day']) && is_numeric($_POST['day']))
		&&
		(isset($_POST['month']) && !empty($_POST['month']) && is_numeric($_POST['month']))
		&&
		(isset($_POST['year']) && !empty($_POST['year']) && is_numeric($_POST['year']))
	){
		
		$day = mysqli_real_escape_string($db,$_POST['day']);
		$month = mysqli_real_escape_string($db,$_POST['month']);
		$year = mysqli_real_escape_string($db,$_POST['year']);
		
		if(!checkdate($month,$day,$year)){
			$response['status'] = false;
			$response['message'] = 'THE DATE YOU ENTERED IS INCORRECT.';
			exit(json_encode($response));
		}else $dob = $year.'-'.$month.'-'.$day.' 00:00:00';
		
		$sql = "UPDATE users SET `dob` = '$dob',`dob_set` = 'true',`time` = NOW() WHERE user_id = '$user_id'";
		$query = mysqli_query($db,$sql);
		$num = mysqli_affected_rows($db);
		if($num > 0){
			$response['status'] = true;
			$response['message'] = 'SUCCESSFULLY CREATED YOUR BIRTHDAY.';
		}else{//IF BIRTH DOESN'T EXIST
			$response['status'] = false;
			$response['message'] = 'THE ATTEMPT TO CREATE YOUR BIRTHDAY WAS UNSUCCESSFUL.';
		}
			
	}else{
		
		$m = '';
		$response['status'] = false;
		
		if(!isset($_POST['day']) || empty($_POST['day']) || !is_numeric($_POST['day'])){
			$m = 'THE DAY YOU ENTERED IS INCORRECT IT MUST BE A NUMBER.';
		}else if(!isset($_POST['month']) || empty($_POST['month']) || !is_numeric($_POST['month'])){
			$m = 'THE MONTH YOU ENTERED IS INCORRECT IT MUST BE A NUMBER.';
		}else if(!isset($_POST['year']) || empty($_POST['year']) || !is_numeric($_POST['year'])){
			$m = 'THE YEAR YOU ENTERED IS INCORRECT IT MUST BE A NUMBER.';
		}else $m = 'AN UNKNOWN ERROR OCCURED TRY AGAIN.';
		
		$response['message'] = $m;
		
	}

	mysqli_close($db);
	echo json_encode($response);
?>