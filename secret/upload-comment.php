<?php
//THIS FILE NEEDS TO HAVE $identification, $reponse[array:(status,message)], $filenames[array: of paths on the $_FILES global], $pathname[string:filesystem path] AND $_FILES[array: php upload] SET

	for($i = 0; $i < count($filenames); $i++){
		
		if(isset($_FILES['files']['name'][0])){
			
			$file = $_FILES['files'];//[$filenames[$i]];
			
			//SET UPLOAD DIRECTORY
			if(!is_dir($pathname)){
				mkdir($pathname);
			}
			
			//GET $extension
			$ext = explode('/',$file['type'][$i]);
			if(count($ext) == 1){
				exit('{"status":false,"message":"THE FILE EXTENSION FOR ONE OF THE FILES YOU SENT IS NOT ACCEPTABLE. USE GIF, PNG OR JP[E]G IMAGES."}');
			}else if($ext[1] == 'png' || $ext[1] == 'jpeg' || $ext[1] == 'jpg' || $ext[1] == 'gif'){
				//VALIDATE IMAGE TYPE $extension
				$response['status'] = $err = false;
				if($ext[1] == 'jpeg' || $ext[1] == 'jpg')
					if(!$img = imagecreatefromjpeg($file['tmp_name'][$i]))$err = true;
				if($ext[1] == 'png')
					if(!$img = imagecreatefrompng($file['tmp_name'][$i]))$err = true;
				if($ext[1] == 'gif')
					if(!$img = imagecreatefromgif($file['tmp_name'][$i]))$err = true;
				
				$response['status'] = true;
				$response['message'] = 'THE FILE EXTENSION FOR ONE OF THE FILES YOU SENT IS NOT ACCEPTABLE. USE GIF, PNG OR JP[E]G IMAGES.';
				
				if($err)exit(json_encode($response));
				$extension = $ext[1];
			}
			
			//CHECK THAT NO ERROR OCCURRED
			switch ($file['error'][$i]) {
				case UPLOAD_ERR_OK:
					$response['status'] = true;
					$response['message'] = "THE UPLOAD WAS SUCCESSFUL";
					break;
				case UPLOAD_ERR_INI_SIZE://The uploaded file exceeds the upload_max_filesize directive in php.ini
					$response['status'] = false;
					$response['message'] = "THE UPLOADED FILE EXCEEDED THE MAXIMUM PERMITTED SIZE.";
					break;
				case UPLOAD_ERR_FORM_SIZE://The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form
					$response['status'] = false;
					$response['message'] = "THE UPLOADED FILE EXCEEDED THE MAXIMUM PERMITTED SIZE.";
					break;
				case UPLOAD_ERR_PARTIAL://The uploaded file was only partially uploaded
					$response['status'] = false;
					$response['message'] = "THE UPLOADED FILE PARTIALLY UPLOADED. TRY AGAIN. IF ACCOUNT EXISTS SIGN IN FIRST.";
					break;
				case UPLOAD_ERR_NO_FILE://No file was uploaded
					$response['status'] = false;
					$response['message'] = "YOU DIDN'T UPLOAD ANY FILES";
					break;
				case UPLOAD_ERR_NO_TMP_DIR://Missing a temporary folder
					$response['status'] = false;
					$response['message'] = "A FATAL ERROR OCCURRED. TRY AGAIN";
					break;
				case UPLOAD_ERR_CANT_WRITE://Failed to write file to disk
					$response['status'] = false;
					$response['message'] = "A FATAL ERROR OCCURRED. TRY AGAIN";
					break;
				case UPLOAD_ERR_EXTENSION://File upload stopped by extension
					$response['status'] = false;
					$response['message'] = "A FATAL ERROR OCCURRED. TRY AGAIN";
					break;
				default:
					$response['status'] = false;
					$response['message'] = "AN UNKNOWN ERROR OCCURRED. TRY AGAIN";
					break; 
			}
			
			//SET FILE NAME
			$filename = $pathname.'/'.md5($filenames[$i].'--%--'.$i).'.'.$extension;
			
			if($response['status'] === true && is_uploaded_file($file['tmp_name'][$i])){
				if(move_uploaded_file($file['tmp_name'][$i],$filename)){
					
					$filename = ltrim($filename,'/.');
					
					$sql = "SELECT * FROM comments WHERE `comment_id` = '$identification' LIMIT 1";
					$query = mysqli_query($db,$sql);
					$num = mysqli_num_rows($query);
					if($num > 0){
						$row = mysqli_fetch_array($query,MYSQLI_ASSOC);
						$images = json_decode($row['images'],TRUE);
						$name = mysqli_real_escape_string($db,$filenames[$i]);
						array_push($images,array('url'=>$filename,'caption'=>$name));
						$sql = "UPDATE comments SET `images` = '".json_encode($images)."' WHERE `comment_id` = '$identification' LIMIT 1";
						$query = mysqli_query($db,$sql);
					}
				}
			}
			else exit(json_encode($response));
			
		}
		
	}


?>