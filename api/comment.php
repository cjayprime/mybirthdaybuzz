<?php

	require_once('../../secret/verification.php');
	require_once('../../secret/connection.php');
	
	$db = mysqli_connect($host,$username,$password,$database);
	
	$response = array('status'=>false,'message'=>'');
	
	$user_id = $_SESSION['user_id'];
	
	if(
		isset($_POST['comment']) && !empty($_POST['comment'])
		&&
		isset($_POST['board_id']) && !empty($_POST['board_id'])
	){
		
		$comment = mysqli_real_escape_string($db,$_POST['comment']);
		$board_id = mysqli_real_escape_string($db,$_POST['board_id']);
		//THE SESSION OWNER IS THE $sender_id
		$sender_id = $user_id;
		
		//THE ORIGINAL OWNER OF THE BOARD IDENTIFIED BY board_id IS THE $receiver_id
		$sql = "SELECT user_id FROM users WHERE (`username` = '$board_id') LIMIT 1";
		$query = mysqli_query($db,$sql);
		$num = mysqli_num_rows($query);
		if($num > 0){
			$row = mysqli_fetch_array($query,MYSQLI_ASSOC);
			$receiver_id = $row['user_id'];
			
			//EMAIL AND MOBILE MUST BE UNIQUE
			$sql = "INSERT INTO comments(`receiver_id`,`sender_id`,`comment`,`images`,`date`) VALUES('$receiver_id','$sender_id','$comment','[]',NOW())";
			$query = mysqli_query($db,$sql);
			$num = mysqli_affected_rows($db);
			if($num > 0){//IF THE USER DOES NOT EXIST
				$identification = mysqli_insert_id($db);
				$pathname = '../users/comments/'.$identification;
				if(isset($_POST['captions'])){
					//IF captions IS SET THEN THERE IS A FILE
					$captions = $_POST['captions'];
					$filenames = array();
					for($i = 0; $i < count($captions); $i++){
						array_push($filenames,$captions[$i]);
					}
					require_once('../../secret/upload-comment.php');
				}
				
				$response['status'] = true;
				$response['message'] = 'YOUR COMMENT HAS BEEN SUCCESSFULLY SENT.';
				
			}else if($num == 0){//IF THE USER EXISTS
				$response['status'] = false;
				$response['message'] = 'AN ERROR OCCURED WHILE ATTEMPTING TO SUBMIT YOUR COMMENT. TRY AGAIN TO CONFIRM.';
			}else{//IF AN ERROR OCCURRED IN THE DATABASE
				$response['status'] = false;
				$response['message'] = 'AN ERROR OCCURED WHILE ATTEMPTING TO SUBMIT YOUR COMMENT. TRY AGAIN TO CONFIRM.';
			}
		}else{//IF BOARD DOESN'T EXIST
			$response['status'] = false;
			$response['message'] = 'THIS BOARD DOESN\'T APPEAR TO EXIST.';
		}
		
	}else{
		
		$m = '';
		$response['status'] = false;
		
		if(!isset($_POST['comment']) || empty($_POST['comment'])){
			
			$m = 'THIS DOESN\'T APPEAR TO BE YOUR FIRST NAME. TRY AGAIN.';
			
		}else $m = 'AN UNKNOWN ERROR OCCURED TRY AGAIN.';
		
		$response['message'] = $m;
		
	}

	mysqli_close($db);
	echo json_encode($response);
?>