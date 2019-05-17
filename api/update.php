<?php
	
	require_once('../../secret/verification.php');
	require_once('../../secret/connection.php');
	
	$db = mysqli_connect($host,$username,$password,$database);
	
	$response = array('status'=>false,'message'=>'');
	
	$user_id = $_SESSION['user_id'];
	
	if(
		(isset($_POST['update']) && !empty($_POST['update']) && ($_POST['update'] == 'comments'))
		&&
		(isset($_POST['identification']) && !empty($_POST['identification']))
		&&
		(isset($_POST['start']) && !empty($_POST['start']))
	){
		
		$update = mysqli_real_escape_string($db,$_POST['update']);
		$username = $identification = mysqli_real_escape_string($db,$_POST['identification']);
		$comment_id = mysqli_real_escape_string($db,$_POST['start']);
		
		if($update == 'comments'){
			$sql = "SELECT *,users.images AS userimage,comments.images AS commentimages FROM users INNER JOIN comments WHERE `username` = '$username' AND (users.user_id = comments.receiver_id) AND (`comment_id` > '$comment_id')";
			$query = mysqli_query($db,$sql);
			$num = mysqli_num_rows($query);
			if($num > 0){
				$response['message'] = array();
				while($row = mysqli_fetch_array($query,MYSQLI_ASSOC)){
					$firstname = $row['firstname'];
					$lastname = $row['lastname'];
					$comment_id = $row['comment_id'];
					$comment = $row['comment'];
					$commentimages = json_decode($row['commentimages'],TRUE);
					$userimage = json_decode($row['userimage'],TRUE);
					$gifts = json_decode($row['gifts'],TRUE);
					$buzz = json_decode($row['buzz'],TRUE);
					$profileimage = $userimage['profile'];
					array_push($response['message'],array('firstname'=>$firstname,'lastname'=>$lastname,'comment_id'=>$comment_id,'comment'=>$comment,'commentimages'=>$commentimages,'profileimage'=>$profileimage,'gifts'=>$gifts,'buzz'=>$buzz));
					
				}
				$response['status'] = true;
				
			}else{//IF BOARD DOESN'T EXIST
				$response['status'] = false;
				$response['message'] = 'THERE ARE NO NEW COMMENTS.';
			}
		}
			
	}else{
		
		$m = '';
		$response['status'] = false;
		
		if(!isset($_POST['update']) || empty($_POST['update']) || ($_POST['update'] != 'comments' && $_POST['update'] != 'messages')){
			$m = 'AN UNKNOWN ERROR OCCURED WITH THE UPDATE FIELD TRY AGAIN.';
		}else if(!isset($_POST['identification']) || empty($_POST['identification'])){
			$m = 'AN UNKNOWN ERROR OCCURED WITH THE IDENTIFICATION FIELD TRY AGAIN.';
		}else if(!isset($_POST['start']) || empty($_POST['start'])){
			$m = 'AN UNKNOWN ERROR OCCURED WITH THE START FIELD TRY AGAIN.';
		}else $m = 'AN UNKNOWN ERROR OCCURED TRY AGAIN.';
		
		$response['message'] = $m;
		
	}

	mysqli_close($db);
	echo json_encode($response);
?>