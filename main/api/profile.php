<?php

	require_once('../../secret/verification.php');
	require_once('../../secret/connection.php');
	
	$db = mysqli_connect($host,$username,$password,$database);
	
	$response = array('status'=>false,'message'=>'');
	
	$user_id = $_SESSION['user_id'];
	
	if(
		(isset($_FILES['profile-image']) && !empty($_FILES['profile-image'])
		||
		isset($_FILES['header-image']) && !empty($_FILES['header-image']))
		&&
		(isset($_POST['board_id']) && !empty($_POST['board_id']))
	){
		
		$username = $board_id = mysqli_real_escape_string($db,$_POST['board_id']);
		
		//THE ORIGINAL OWNER OF THE BOARD IS THE ONLY ONE THAT CAN MODIFY IT
		$sql = "SELECT * FROM users WHERE (`username` = '$username' AND `user_id` = '$user_id') LIMIT 1";
		$query = mysqli_query($db,$sql);
		$num = mysqli_num_rows($query);
		if($num > 0){
			$row = mysqli_fetch_array($query,MYSQLI_ASSOC);
			$identification = $row['user_id'];
			$images = json_decode($row['images'],TRUE);
			$filenames = array('header-image','profile-image');
			$pathname = '../users/accounts/'.$identification;
			
			require_once('../../secret/upload-profile.php');
			
			$response['status'] = true;
			foreach($_FILES as $key => $value){
				$parts = explode('-',$key);
				$response['message'] = 'YOU HAVE SUCCESSFULLY UPDATED YOUR '.strtoupper($parts[0]).' IMAGE. ';
			}
			
		}else{//IF BOARD DOESN'T EXIST
			$response['status'] = false;
			$response['message'] = 'THIS BOARD DOESN\'T APPEAR TO EXIST.';
		}
		
	}else{
		
		$m = '';
		$response['status'] = false;
		
		if(!isset($_FILES['profile-image']) || empty($_POST['profile-image'])
			||
		   !isset($_FILES['header-image']) || empty($_POST['header-image'])){
			
			$m = 'YOU FAILED TO UPLOAD IMAGES TO MODIFY YOUR PROFILE.';
			
		}else $m = 'AN UNKNOWN ERROR OCCURED TRY AGAIN.';
		
		$response['message'] = $m;
		
	}

	mysqli_close($db);
	echo json_encode($response);
?>