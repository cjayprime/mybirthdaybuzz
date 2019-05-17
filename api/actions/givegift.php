<?php
	
	require_once('../../../secret/verification.php');
	require_once('../../../secret/connection.php');
	
	$db = mysqli_connect($host,$username,$password,$database);
	
	$response = array('status'=>false,'message'=>'');
	
	$user_id = $_SESSION['user_id'];
	
	if(
		(isset($_POST['username']) && !empty($_POST['username']))
		&&
		(isset($_POST['type']) && !empty($_POST['type']))
	){
		
		$username = mysqli_real_escape_string($db,$_POST['username']);
		$type = mysqli_real_escape_string($db,$_POST['type']);
		
		//VERIFY THAT username EXISTS <- THIS IS THE RECEIVER
		$sql = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
		$query = mysqli_query($db,$sql);
		$num = mysqli_affected_rows($db);
		if($num > 0){
			$row = mysqli_fetch_array($query,MYSQLI_ASSOC);
			$id = $row['user_id'];
			$received_gifts = json_decode($row['gifts'],TRUE);
			array_push($received_gifts['received'],array('type'=>$type,'from'=>$user_id,'date'=>time()));
			$received_gifts = json_encode($received_gifts);
				
			$sql = "UPDATE users SET `gifts` = '$received_gifts' WHERE username = '$username' LIMIT 1";
			$query = mysqli_query($db,$sql);
			$num = mysqli_affected_rows($db);
			if($num > 0)$response['message'] .= 'SUCCESSFULLY DELIVERED YOUR GIFT.';
			
			//SELECT SESSION OWNERS column <- THIS IS THE SENDER
			$sql = "SELECT * FROM users WHERE user_id = '$user_id' LIMIT 1";
			$query = mysqli_query($db,$sql);
			$num = mysqli_affected_rows($db);
			if($num > 0){
				$row = mysqli_fetch_array($query,MYSQLI_ASSOC);
				$id2 = $row['user_id'];
				$sent_gifts = json_decode($row['gifts'],TRUE);
				array_push($sent_gifts['sent'],array('type'=>$type,'to'=>$id,'date'=>time()));
				$sent_gifts = json_encode($sent_gifts);
				
				$sql = "UPDATE users SET `gifts` = '$sent_gifts' WHERE user_id = '$user_id' LIMIT 1";
				$query = mysqli_query($db,$sql);
				$num = mysqli_affected_rows($db);
				if($num > 0)$response['message'] = 'YOUR GIFT HAS BEEN SENT.<br>'.$response['message'];
				
				$response['status'] = true;
				
			}else{//IF SESSION OWNERS PROFILE WASN'T FOUND
				$response['status'] = false;
				$response['message'] = 'AN ERROR OCCURED WITH YOUR PROFILE. SIGN IN.';
			}
		}else{//IF BIRTH DOESN'T EXIST
			$response['status'] = false;
			$response['message'] = 'THE USER WITH THAT USERNAME WAS NOT FOUND';
		}
			
	}else{
		
		$m = '';
		$response['status'] = false;
		
		if(!isset($_POST['username']) || empty($_POST['username'])){
			$m = 'YOU NEED TO ENTER A VALID USERNAME.';
		}else if(!isset($_POST['type']) || empty($_POST['type']) ){
			$m = 'YOU NEED TO ENTER A VALID TYPE.';
		}else $m = 'AN UNKNOWN ERROR OCCURED TRY AGAIN.';
		
		$response['message'] = $m;
		
	}

	mysqli_close($db);
	echo json_encode($response);
?>