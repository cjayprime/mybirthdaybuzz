<?php
	/*
		***
		***CALENDAR shows people who have previously sent a Birthday Buzz
	*/
	require_once('../secret/connection.php');
	
	$db = mysqli_connect($host,$username,$password,$database);
	
	session_start();
	
	if(!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])){
		header("Location: index.php");
		exit;
	}
	
	if(!isset($_GET['username']) || empty($_GET['username']))
	header("Location: board.php?username=".$_SESSION['username']);
	
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1 shrink-to-fit=yes">
        <link rel="icon" href="images/logo/small.png" type="image/x-icon" />
        <title>Birthday Buzz /// Board</title>
        
        <link rel="stylesheet" type="text/css" href="styles/board.css">
        
		<script src="scripts/jquery-1.11.1.js" type="application/javascript"></script>
        <script src="scripts/board.js" type="application/javascript"></script>
        <script src="scripts/actions.js" type="application/javascript"></script>
    </head>
    <body>
    	<?php
		
			$user_id = $_SESSION['user_id'];
			$username = $_SESSION['username'];
			
			//BOARD DETAILS
			$board_id = trim($_GET['username']);
			$owner = ($username == $board_id);
			
			//USER BOARD ACCOUNT DETAILS
			$sql = "SELECT * FROM users WHERE (`username` = '$board_id') LIMIT 1";
			$query = mysqli_query($db,$sql);
			$num = mysqli_num_rows($query);
			if($num > 0){
				$row = mysqli_fetch_array($query,MYSQLI_ASSOC);
				$id = $row['user_id'];
				$firstname = $row['firstname'];
				$lastname = $row['lastname'];
				$images = json_decode($row['images'],TRUE);
				$profile_image = $images['profile'];
				$header_image = $images['header'];
				$birthday = '';//35<sup>+</sup> <div>This Month</div>
			}else header("Location: board.php?username=".$_SESSION['username']);
			
			//ALL BOARD COMMENTS
			$html = function($firstname,$lastname,$profilepicture,$comment,$comment_id,array $image){
				$comment_images = '';
				if(!empty($image)){
					for($i = 0; $i < count($image); $i++){
						$url = $image[$i]['url'];
						$caption = $image[$i]['caption'];
						$comment_images .= 
											'<div class="comment-image" style="background-image:url(\''.$url.'\')">'.
												'<div class="comment-caption">'.$caption.'</div>'.
											'</div>';
					}
				}
				return '<div class="comments" data-commentid="'.$comment_id.'">'.
							'<div class="comment-profile" style="background-image:url(\''.$profilepicture.'\')"></div>'.
							'<div class="comment-fullname"><b>'.$firstname.'</b> '.$lastname.'</div>'.
							'<div class="comment-text">'.
								'<div>'.
									$comment.
								'</div>'.
							'</div>'.
							'<div class="comment-images">'.
								$comment_images.
							'</div>'.
						'</div>';
			};
			
			$sql = "SELECT *,users.images AS userimages, comments.images AS commentimages FROM `comments` INNER JOIN `users` WHERE (users.`user_id` = comments.`receiver_id`) AND (comments.`receiver_id` = '$id')";
			$query = mysqli_query($db,$sql);
			$num = mysqli_num_rows($query);
			$comments_html = '';
			if($num > 0){
				while($row = mysqli_fetch_array($query,MYSQLI_ASSOC)){
					$users_images = json_decode($row['userimages'],true);
					$images = json_decode($row['commentimages'],true);
					$comments_html .= $html($row['firstname'],$row['lastname'],$users_images['profile'],$row['comment'],$row['comment_id'],$images);
				}
			}
			
			
			
			//SESSION OWNER ACCOUNT DETAILS
			$calendar = function($name,$username,$birthday){
				return '<div class="calendar-item">'.
							'<div class="calendar-name">'.$name.'</div>'.
							'<div class="calendar-username">'.$username.'</div>'.
							'<div class="calendar-date">'.$birthday.'</div>'.
						'</div>';
			};
			$sql = "SELECT * FROM users WHERE (`user_id` = '$user_id') LIMIT 1";
			$query = mysqli_query($db,$sql);
			$num = mysqli_num_rows($query);
			if($num > 0){
				$row = mysqli_fetch_array($query,MYSQLI_ASSOC);
				$gifts = json_decode($row['gifts'],TRUE);
				$buzz = json_decode($row['buzz'],TRUE);
				$calendar_details = '';
				$c = array($gifts,$buzz);
				for($j = 0; $j < count($c); $j++)
				foreach($c[$j] as $key => $value){
					//$key may be sent or received
					if($key == 'sent')$which = 'to';
					if($key == 'received')$which = 'from';
					for($i = 0; $i < count($gifts[$key]); $i++){
						$id = $gifts[$key][$i][$which];
						$sql = "SELECT * FROM users WHERE (`user_id` = '$id') LIMIT 1";
						$query = mysqli_query($db,$sql);
						$num = mysqli_num_rows($query);
						if($num > 0){
							$row = mysqli_fetch_array($query,MYSQLI_ASSOC);
							if($row['dob'] != '0000-00-00 00:00:00'){
								$calendar_details .= $calendar($row['firstname'].' '.$row['lastname'],$row['username'],$row['dob']);
							}
						}
					}
				}
				
			}else header("Location: board.php?username=".$_SESSION['username']);
			
			$calendar_modal = '<div id="calendar-modal">'.
									'<div class="modal-notice"></div>'.
									'<div class="label">CALENDAR</div>'.
									'<div class="close-modal"><img src="images/actions/close.png" /></div>'.
									'<div class="form-functions-calendar">'.
										$calendar_details.
									'</div>'.
								'</div>';
			
		?>
        
        
        <div id="top-container" class="container">
        	<div id="header-image" data-identification="<?php echo $board_id;?>" style="background-image:url('<?php echo $header_image;?>')">
            	<input type="file" id="header-image-file" style="display:none;"/>
            	<div id="header-upload-progress">0%</div>
				<?php if($owner)echo '<img src="images/actions/edit.png" />';?>
            </div>
        	<div id="profile-image" style="background-image:url('<?php echo $profile_image;?>')">
            	<input type="file" id="profile-image-file" style="display:none;"/>
            	<div id="profile-upload-progress">0%</div>
				<?php if($owner)echo '<img src="images/actions/edit.png" />';?>
            </div>
        	<div id="fullname"><b><?php echo $firstname;?></b> <?php echo $lastname;?></div>
        	<!--
            <div id="view-birthday">View Birthdays</div>
        	<div id="view-gifts">View Gifts</div>
            -->
        	<div id="profile-birthday"><?php echo $birthday;?></div>
            <div id="profile-actions">
            <?php
				$t = <<<EOT
					
						<div id="create-birthday" class="actionbutton"><img src="images/actions/createbirthday.svg" /></div>
						<div id="give-gift" class="actionbutton"><img src="images/actions/givegift.svg" /></div>
						<div id="create-birthday-buzz" class="actionbutton"><img src="images/actions/createbirthdaybuzz.svg" /></div>
						<div id="get-gift" class="actionbutton"><img src="images/actions/getgift.svg" /></div>
						<div id="calendar" class="actionbutton"><img src="images/actions/calendar.svg" /></div>
EOT;
				if($owner)echo $t;
            ?>
            </div>
        </div>
    	<div id="bottom-container" class="container">
        	
            <div id="comment-pointer"></div>
            
            <?php 
				echo $comments_html; 
			?>
            
            <!--
                <div class="comments" data-commentid="">
                    <div class="comment-profile" style="background-image:url('images/messages.png')"></div>
                    <div class="comment-fullname"><b>Nna</b> Chijioke</div>
                    <div class="comment-text">
                        <div>
                            A comment
                        </div>
                    </div>
                    <div class="comment-images">
                    	<div class="comment-image" style="background-image:url('images/messages.png')">
                        	<div class="comment-caption">This is the imageThis is the imageThis is the imageThis is the imageThis is the imageThis is the imageThis is the imageThis is the image</div>
                        </div>
                    	<div class="comment-image" style="background-image:url('images/messages.png')">
                        	<div class="comment-caption">This is the imageThis is the imageThis is the imageThis is the imageThis is the imageThis is the imageThis is the imageThis is the image</div>
                        </div>
                    </div>
                </div>
              -->
                
            <div id="comment-box">
            	<div>
                	<div id="entertosend"><input type="checkbox" id="entertosendcontrol" />Enter to send</div>
                    <div id="comment-file-container">
                    	<img src="images/actions/file.png" id="comment-file" />
                    	<input type="file" id="comment-file-upload" style="display:none;" multiple="multiple"/>
                    </div>
                    <div id="send-button-container"><input type="button" id="send-button" value="Send" /></div>
                </div>
                <textarea id="addcomment" placeholder="Press enter to send a comment"></textarea>
                <div id="selected-images">
                	<div id="upload-progress">30%</div>
                	<div id="upload-error"></div>
                	<img src="images/actions/exit.png" id="comment-file" />
                    <!--
                    <div class="selected-image">
                    	<img src="images/actions/close.png" id="comment-file" />
                        <input type="text" class="caption" placeholder="Enter a caption of less than 50 characters" maxlength="49"/>
                    </div>
                    -->
               </div>
            </div>
            
        </div>
        
        <div id="overlay"></div>
        
        <div id="create-birthday-modal">
        	<div class="modal-notice"></div>
            <div class="label">CREATE BIRTHDAY</div>
            <div class="close-modal"><img src="images/actions/close.png" /></div>
            <div class="form-functions">
            	<select id="create-birthday-day">
                	<?php echo '<option>DAY</option>'; for($i = 1; $i <= 31; $i++)echo '<option>'.$i.'</option>'; ?>
                </select>
            	<select id="create-birthday-month">
                	<?php echo '<option>MONTH</option>'; $months = array('JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'); for($i = 0; $i < count($months); $i++)echo '<option>'.$months[$i].'</option>'; ?>
                </select>
            	<select id="create-birthday-year">
                	<?php echo '<option>YEAR</option>'; for($i = 1960; $i <= 2018; $i++)echo '<option>'.$i.'</option>'; ?>
                </select>
                <input id="create-birthday-now" type="button" value="CREATE"/>
            </div>
        </div>
        
        <div id="give-gift-modal">
        	<div class="modal-notice"></div>
            <div class="label">GIVE GIFTS</div>
            <div class="close-modal"><img src="images/actions/close.png" /></div>
            <input id="send-gift-input" type="text" placeholder="Enter a username to send gifts to"/>
            <div class="form-functions-givegift">
            	<div class="gift-lists" data-type="love">
                	<img src="images/gifts/love.png" />
                    <div class="send-gift-list">SEND</div>
                </div>
            	<div class="gift-lists" data-type="butterfly">
                	<img src="images/gifts/butterfly.png" />
                    <div class="send-gift-list">SEND</div>
                </div>
            	<div class="gift-lists" data-type="cat">
                	<img src="images/gifts/cat.png" />
                    <div class="send-gift-list">SEND</div>
                </div>
            	<div class="gift-lists" data-type="dog">
                	<img src="images/gifts/dog.png" />
                    <div class="send-gift-list">SEND</div>
                </div>
            	<div class="gift-lists" data-type="idea">
                	<img src="images/gifts/idea.png" />
                    <div class="send-gift-list">SEND</div>
                </div>
            	<div class="gift-lists" data-type="unicorn">
                	<img src="images/gifts/unicorn.png" />
                    <div class="send-gift-list">SEND</div>
                </div>
            	<div class="gift-lists" data-type="celebration">
                	<img src="images/gifts/celebration.png" />
                    <div class="send-gift-list">SEND</div>
                </div>
            </div>
        </div>
        
        <div id="create-birthday-buzz-modal">
        	<div class="modal-notice"></div>
            <div class="label">CREATE BIRTHDAY BUZZ</div>
            <div class="close-modal"><img src="images/actions/close.png" /></div>
            <div class="form-functions">
            	<input type="text" id="create-birthday-buzz-input" placeholder="Enter the username to send buzz to"/>
                <input id="create-birthday-buzz-now" type="button" value="BUZZ"/>
            </div>
        </div>
        
        <div id="get-gift-modal">
        	<div class="modal-notice"></div>
            <div class="label">GET GIFTS</div>
            <div class="close-modal"><img src="images/actions/close.png" /></div>
            <div class="form-functions-getgift">
            	<div class="gift-lists" data-type="love">
                	<div class="sent-by-gift" title="From: Username">From: Username</div>
                	<img src="images/gifts/love.png" />
                    <a href="images/gifts/love.png" download="Love"><div class="download-gift-list"><img src="images/actions/download.png" /></div></a>
                </div>
            	<div class="gift-lists" data-type="butterfly">
                	<div class="sent-by-gift" title="From: Username">From: Username</div>
                	<img src="images/gifts/butterfly.png" />
                    <a href="images/gifts/butterfly.png" download="Butterfly"><div class="download-gift-list"><img src="images/actions/download.png" /></div></a>
                </div>
            	<div class="gift-lists" data-type="cat">
                	<div class="sent-by-gift" title="From: Username">From: Username</div>
                	<img src="images/gifts/cat.png" />
                    <a href="images/gifts/cat.png" download="Cat"><div class="download-gift-list"><img src="images/actions/download.png" /></div></a>
                </div>
            	<div class="gift-lists" data-type="dog">
                	<img src="images/gifts/dog.png" />
                    <a href="images/gifts/dog.png" download="Dog"><div class="download-gift-list"><img src="images/actions/download.png" /></div></a>
                </div>
            	<div class="gift-lists" data-type="idea">
                	<img src="images/gifts/idea.png" />
                    <a href="images/gifts/idea.png" download="Idea"><div class="download-gift-list"><img src="images/actions/download.png" /></div></a>
                </div>
            	<div class="gift-lists" data-type="unicorn">
                	<img src="images/gifts/unicorn.png" />
                    <a href="images/gifts/unicorn.png" download="Unicorn"><div class="download-gift-list"><img src="images/actions/download.png" /></div></a>
                </div>
            	<div class="gift-lists" data-type="celebration">
                	<img src="images/gifts/celebration.png" />
                    <a href="images/gifts/celebration.png" download="Celebration"><div class="download-gift-list"><img src="images/actions/download.png" /></div></a>
                </div>
            </div>
        </div>
        
        <?php 
			if($owner)echo $calendar_modal;
		?>
        <!--
        <div id="calendar-modal">
        	<div class="modal-notice"></div>
            <div class="label">CALENDAR</div>
            <div class="close-modal"><img src="images/actions/close.png" /></div>
            <div class="form-functions-calendar">
            	<div class="calendar-item">
                	<div class="calendar-name">NAME</div>
                	<div class="calendar-username">USERNAME</div>
                	<div class="calendar-date">BIRTHDAY</div>
                </div>
            </div>
        </div>
        -->
        
        
    </body>
</html>