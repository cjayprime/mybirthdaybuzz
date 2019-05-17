<?php
//UPLOAD AND CONNECTION scripts USE THE secret FOLDER

	require_once('../../secret/connection.php');
	
	$db = mysqli_connect($host,$username,$password,$database);
	
	$response = array('status'=>false,'message'=>'');
	
	$regex_username = '/^[A-Za-z0-9]+$/';
	$regex_name = '/^[A-Za-z]+$/';
	$regex_mobile = '/^[0-9]{1,11}$/';
	$regex_email = '/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';
	
	if(
		isset($_POST['username']) && !empty($_POST['username']) && preg_match($regex_username,$_POST['username'])
		&&
		isset($_POST['firstname']) && !empty($_POST['firstname']) && preg_match($regex_name,$_POST['firstname'])
		&&
		isset($_POST['lastname']) && !empty($_POST['lastname']) && preg_match($regex_name,$_POST['lastname'])
		&&
		isset($_POST['mobile']) && !empty($_POST['mobile']) && preg_match($regex_mobile,$_POST['mobile'])
		&&
		isset($_POST['email']) && !empty($_POST['email']) && preg_match($regex_email,$_POST['email'])
		&&
		isset($_POST['password']) && !empty($_POST['password']) && (strlen($_POST['password']) >= 5)
	){
		
		$username = trim(mysqli_real_escape_string($db,$_POST['username']));
		$firstname = trim(mysqli_real_escape_string($db,$_POST['firstname']));
		$lastname = trim(mysqli_real_escape_string($db,$_POST['lastname']));
		$mobile = trim(mysqli_real_escape_string($db,$_POST['mobile']));
		$email = trim(mysqli_real_escape_string($db,$_POST['email']));
		$password = trim(mysqli_real_escape_string($db,$_POST['password']));
		
		//EMAIL AND MOBILE MUST BE UNIQUE
		$sql = "SELECT * FROM users WHERE (`email` = '$email' OR `mobile` = '$mobile') LIMIT 1";
		$query = mysqli_query($db,$sql);
		$num = mysqli_num_rows($query);
		if($num == 0){//IF THE USER DOES NOT EXIST
			$sql = "INSERT INTO users(`username`,`firstname`,`lastname`,`images`,`mobile`,`email`,`password`,`dob`,`dob_set`,`gifts`,`buzz`,`online`,`status`,`time`,`date`) VALUES('$username','$firstname','$lastname','{\"profile\":\"\",\"header\":\"\"}','$mobile','$email',md5('$password'),NOW(),'false','".'{"sent":[],"received":[]}'."','".'{"sent":[],"received":[]}'."','0','1',NOW(),NOW())";
			$query = mysqli_query($db,$sql);
			$num = mysqli_affected_rows($db);
			if($num > 0){//IF USER CREATION SUCCEEDS
			
				$identification = mysqli_insert_id($db);
				$pathname = '../users/accounts/'.$identification;
				$filenames = array('header-image','profile-image');
				
				require_once('../../secret/upload-profile.php');
				
				$response['status'] = true;
				$response['message'] = 'YOU HAVE SUCCESSFULLY SIGNED UP. PROCEED TO THE LOG IN PAGE.';
			}else{//IF USER CREATION FAILS
				$response['status'] = false;
				$response['message'] = 'AN ERROR OCCURED WHILE ATTEMPTING TO SIGN YOU UP. TRY AGAIN';
			}
		}else if($num > 0){//IF THE USER EXISTS
			$response['status'] = false;
			$row = mysqli_fetch_array($query,MYSQLI_ASSOC);
			if($row['mobile'] == $mobile)$response['message'] = 'THE MOBILE NUMBER ALREADY EXISTS.';
			if($row['email'] == $email)$response['message'] = 'THE EMAIL ALREADY EXISTS.';
		}else{//IF AN ERROR OCCURRED IN THE DATABASE
			$response['status'] = false;
			$response['message'] = 'AN ERROR OCCURED WHILE ATTEMPTING TO SIGN YOU UP. THIS USER MAY ALREADY EXIST. TRY AGAIN TO CONFIRM.';
		}
		
	}else{
		
		$m = '';
		$response['status'] = false;
		
		if(!isset($_POST['username']) || empty($_POST['username']) || !preg_match($regex_username,$_POST['username'])){
			
			$m = 'THIS ISN\'T A VALID USERNAME. TRY AGAIN.';
			
		}if(!isset($_POST['firstname']) || empty($_POST['firstname']) || !preg_match($regex_name,$_POST['firstname'])){
			
			$m = 'THIS DOESN\'T APPEAR TO BE YOUR FIRST NAME. TRY AGAIN.';
			
		}else if(!isset($_POST['lastname']) || empty($_POST['lastname']) || !preg_match($regex_name,$_POST['lastname'])){
			
			$m = 'THIS DOESN\'T APPEAR TO BE YOUR LAST NAME. TRY AGAIN.';
			
		}else if(!isset($_POST['mobile']) || empty($_POST['mobile']) || !preg_match($regex_mobile,$_POST['mobile'])){
		
			$m = 'THIS DOESN\'T APPEAR TO BE YOUR MOBILE NUMBER. TRY AGAIN.';
		
		}else if(!isset($_POST['email']) || empty($_POST['email']) || !preg_match($regex_email,$_POST['email'])){
			
			$m = 'THIS DOESN\'T APPEAR TO BE A VALID EMAIL ADDRESS. TRY AGAIN.';
			
		}else if(!isset($_POST['password']) || empty($_POST['password']) && (strlen($_POST['password']) < 5)){
		
			$m = 'CHECK YOUR PASSWORD AND TRY AGAIN.';
		
		}else $m = 'AN UNKNOWN ERROR OCCURED TRY AGAIN.';
		
		$response['message'] = $m;
		
	}

	mysqli_close($db);
	echo json_encode($response);
?>