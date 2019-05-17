<?php
//THIS FILE NEEDS TO HAVE $identification, $reponse[array:(status,message)], $filenames[array: of paths on the $_FILES global], $pathname[string:filesystem path] AND $_FILES[array: php upload] SET

	for($i = 0; $i < count($filenames); $i++){
		
		if(isset($_FILES[$filenames[$i]])){
			
			$file = $_FILES[$filenames[$i]];
			
			//SET UPLOAD DIRECTORY
			if(!is_dir($pathname)){
				mkdir($pathname);
			}
						
			//GET $extension
			$ext = explode('/',$file['type']);
			if(count($ext) == 1){
				exit('{"status":false,"message":"THE FILE EXTENSION FOR ONE OF THE FILES YOU SENT IS NOT ACCEPTABLE. USE PNG OR JP[E]G IMAGES."}');
			}else if($ext[1] == 'png' || $ext[1] == 'jpeg' || $ext[1] == 'jpg'){
				//VALIDATE IMAGE TYPE $extension
				if($ext[1] == 'jpeg' || $ext[1] == 'jpg'){
					if(!$img = imagecreatefromjpeg($file['tmp_name'])){
						$response['status'] = false;
						$response['message'] = 'THE FILE EXTENSION FOR ONE OF THE FILES YOU SENT IS NOT ACCEPTABLE. USE PNG OR JP[E]G IMAGES.';
						exit(json_encode($response));	
					}
				}else if($ext[1] == 'png'){
					if(!$img = imagecreatefrompng($file['tmp_name'])){
						$response['status'] = false;
						$response['message'] = 'THE FILE EXTENSION FOR ONE OF THE FILES YOU SENT IS NOT ACCEPTABLE. USE PNG OR JP[E]G IMAGES.';	
						exit(json_encode($response));
					}
				}
				$extension = $ext[1];
			}
			
			//SET FILE NAME
			$filename = $pathname.'/'.$filenames[$i].'.'.$extension;
			
			//CHECK THAT NO ERROR OCCURRED
			switch ($file['error']) {
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
			
			if($response['status'] === true && is_uploaded_file($file['tmp_name'])){
				if(move_uploaded_file($file['tmp_name'],$filename)){
					
					$filename = ltrim($filename,'/.');
					
					$sql = "SELECT * FROM users WHERE `user_id` = '$identification' LIMIT 1";
					$query = mysqli_query($db,$sql);
					$num = mysqli_num_rows($query);
					if($num > 0){
						$row = mysqli_fetch_array($query,MYSQLI_ASSOC);
						$images = json_decode($row['images'],TRUE);
						if($filenames[$i] == 'header-image')
							$images['header'] = $filename;
						if($filenames[$i] == 'profile-image')
							$images['profile'] = $filename;
						$sql = "UPDATE users SET `images` = '".json_encode($images)."' WHERE `user_id` = '$identification' LIMIT 1";
						$query = mysqli_query($db,$sql);
					}
				}
			}
			else exit(json_encode($response));
			
		}
		
	}


?>