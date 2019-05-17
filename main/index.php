<?php

	require_once('../secret/connection.php');
	
	$db = mysqli_connect($host,$username,$password,$database);

	$user = function($firstname,$lastname,$profilepic,$day){
		return '<div class="user">'.
                	'<div class="profilepicture" style="background-image:url(\''.$profilepic.'\')"></div>'.
                	'<div class="day">'.$day.'</div>'.//23<sup>rd</sup>
                	'<div class="name">'.$firstname.' '.$lastname.'</div>'.
                '</div>';
	};
	
	
	//SELECT 25 USERS
	$sql = "SELECT * FROM users WHERE (`dob_set` = 'true') LIMIT 25";
	$query = mysqli_query($db,$sql);
	$num = mysqli_num_rows($query);
	$users = '';
	if($num > 0){
		while($row = mysqli_fetch_array($query,MYSQLI_ASSOC)){
			
			$firstname = $row['firstname'];
			$lastname = $row['lastname'];
			$images = json_decode($row['images'],TRUE);
			$dob = $row['dob'];
			$timestamp = strtotime($dob);
			$date = date('dS',$timestamp);
			$day = preg_replace('([a-zA-Z]+)','<sup>$0</sup>',$date);
			$users .= $user($firstname,$lastname,$images['profile'],$day);
			
		}
	}
	
	//SELECT 3 TOP BLOG POSTS
	$blog = function($blog_id,$image,$title,$text){
                return '<div class="blog-post">'.
							'<div class="blog-image" style="background-image:url(\''.$image.'\')"></div>'.
							'<div class="blog-title"><a href="blog.php?id='.$blog_id.'">'.$title.'</a></div>'.
							'<div class="blog-text">'.$text.'</div>'.
						'</div>';
	};
	$sql = "SELECT * FROM blog ORDER BY visitors DESC LIMIT 3";
	$query = mysqli_query($db,$sql);
	$num = mysqli_num_rows($query);
	$blogs = '';
	if($num > 0){
		while($row = mysqli_fetch_array($query,MYSQLI_ASSOC)){
			$blog_id = $row['blog_id'];
			$image = $row['image'];
			$title = $row['title'];
			$post = substr($row['post'],0,500);
			$blogs .= $blog($blog_id,$image,$title,$post);
		}
	}
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1 shrink-to-fit=yes">
        <link rel="icon" href="images/logo/small.png" type="image/x-icon" />
        <title>Birthday Buzz /// Home</title>
        
        <link rel="stylesheet" type="text/css" href="styles/index.css">
        
		<script src="scripts/jquery-1.11.1.js" type="application/javascript"></script>
        <script src="scripts/index.js" type="application/javascript"></script>
        <script src="scripts/menu.js" type="application/javascript"></script>
        <script src="scripts/signin.js" type="application/javascript"></script>
        <script src="scripts/signup.js" type="application/javascript"></script>
        <script src="scripts/birthdaybuzz.js" type="application/javascript"></script>
    </head>
    <body>
    	
        <!--HEADER-->
        <div id="header">
        	<div><img src="images/logo/small.png" /></div>
            <div class="menuitem"><a href="/">HOME</a></div>
            <div class="menuitem"><a href="about.php">AMOUT MBB</a></div>
            <div class="menuitem"><a href="blog.php">BEAUTIFUL BIRTHDAY STORIES</a></div>
            <div class="menuitem"><a href="feedback.php">FEEDBACK</a></div>            
        </div>
        
        <div id="menu" data-state="close"><img src="images/actions/menu.png" /></div>
        
        <!--SECTIONS-->
        <div id="section-one" class="section">
        	<div id="overlay"></div>
        	<div class="image active" style="background-image:url('images/background/background-1.jpeg')"></div>
        	<div class="image" style="background-image:url('images/background/background-2.jpeg')"></div>
        	<div class="image" style="background-image:url('images/background/background-3.jpeg')"></div>
            
        	<div class="carousel view"></div>
        	<div class="carousel"></div>
        	<div class="carousel"></div>
            
        	<div id="banner-text">
            	<span>Celebrate</span>
                <div>That special moment with someone today</div>
                <div>Share the love and laugh together</div>
                <input id="signup" type="button" value="Sign Up" />
                <input id="signin" type="button" value="Sign In" />
            </div>
        </div>
        <div id="section-two" class="section">
            <span>Create Birthday</span> <div><img src="images/actions/createbirthday.svg" id="create-birthday"/></div>
        	<span>Give Gift</span> <div><img src="images/actions/givegift.svg" id="give-gift"/></div>
        	<span>Create Birthday Buzz</span> <div><img src="images/actions/createbirthdaybuzz.svg" id="create-birthday-buzz"/></div>
        	<span>Get Gift</span> <div><img src="images/actions/getgift.svg" id="get-gift"/></div>
        	<span>Calendar</span> <div><img src="images/actions/calendar.svg" id="calendar"/></div>
            <div id="base-arrow"></div>
        </div>
        <div id="section-three" class="section">
        	<div id="label-birthday-month">Birthday of Top Users</div>
            <div class="button-scroll" id="left-scroll"><div></div></div>
            <div class="button-scroll" id="right-scroll"><div></div></div>
            <div id="scrollable">
            	<?php echo $users; ?>
                <!--
                <div class="user">
                	<div class="profilepicture" style="background-image:url('images/sample/B.jpg')"></div>
                	<div class="day">20<sup>th</sup></div>
                	<div class="name">Sarah Kazaki</div>
                	<div class="social">
                    	<img src="images/social/account/facebook.png" />
                    	<img src="images/social/account/instagram.png" />
                    	<img src="images/social/account/twitter.png" />
                    	<img src="images/social/account/googleplus.png" />
                    </div>
                </div>
            	<div class="user">
                	<div class="profilepicture" style="background-image:url('images/sample/C.jpg')"></div>
                	<div class="day">30<sup>th</sup></div>
                	<div class="name">Rowan Lilan</div>
                	<div class="social">
                    	<img src="images/social/account/facebook.png" />
                    	<img src="images/social/account/instagram.png" />
                    	<img src="images/social/account/twitter.png" />
                    	<img src="images/social/account/googleplus.png" />
                    </div>
                </div>
            	<div class="user">
                	<div class="profilepicture" style="background-image:url('images/sample/D.jpg')"></div>
                	<div class="day">1<sup>st</sup></div>
                	<div class="name">Abigail Abayo</div>
                	<div class="social">
                    	<img src="images/social/account/facebook.png" />
                    	<img src="images/social/account/instagram.png" />
                    	<img src="images/social/account/twitter.png" />
                    	<img src="images/social/account/googleplus.png" />
                    </div>
                </div>
            	<div class="user">
                	<div class="profilepicture" style="background-image:url('images/sample/E.jpg')"></div>
                	<div class="day">23<sup>rd</sup></div>
                	<div class="name">Gandoki Muni</div>
                	<div class="social">
                    	<img src="images/social/account/facebook.png" />
                    	<img src="images/social/account/instagram.png" />
                    	<img src="images/social/account/twitter.png" />
                    	<img src="images/social/account/googleplus.png" />
                    </div>
                </div>
            	<div class="user">
                	<div class="profilepicture" style="background-image:url('images/sample/F.jpg')"></div>
                	<div class="day">20<sup>th</sup></div>
                	<div class="name">Sarah Kazaki</div>
                	<div class="social">
                    	<img src="images/social/account/facebook.png" />
                    	<img src="images/social/account/instagram.png" />
                    	<img src="images/social/account/twitter.png" />
                    	<img src="images/social/account/googleplus.png" />
                    </div>
                </div>
            	<div class="user">
                	<div class="profilepicture" style="background-image:url('images/sample/A.jpg')"></div>
                	<div class="day">23<sup>rd</sup></div>
                	<div class="name">Gandoki Muni</div>
                	<div class="social">
                    	<img src="images/social/account/facebook.png" />
                    	<img src="images/social/account/instagram.png" />
                    	<img src="images/social/account/twitter.png" />
                    	<img src="images/social/account/googleplus.png" />
                    </div>
                </div>
            	<div class="user">
                	<div class="profilepicture" style="background-image:url('images/sample/G.jpg')"></div>
                	<div class="day">20<sup>th</sup></div>
                	<div class="name">Sarah Kazaki</div>
                	<div class="social">
                    	<img src="images/social/account/facebook.png" />
                    	<img src="images/social/account/instagram.png" />
                    	<img src="images/social/account/twitter.png" />
                    	<img src="images/social/account/googleplus.png" />
                    </div>
                </div>
                -->
            </div>
        </div>
        <div id="section-four" class="section">
        	<div id="overlap">	
                <span>The Blog<div id="blog-main-subtext">Beautiful Birth Stories</div></span>
                <?php echo $blogs; ?>
                <!--
                <div class="blog-post">
                	<div class="blog-image" style="background-image:url('images/blog/A.jpg')"></div>
                	<div class="blog-title">The Ferry Twin</div>
                	<div class="blog-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla sed dui felis. Vivamus vitae pharetra nisl, eget fringilla elit. Ut nec est sapien. Aliquam dignissim velit sed nunc imperdiet cursus. Proin arcu diam, tempus ac vehicula a, dictum quis nibh. Maecenas vitae quam ac mi venenatis vulputate. Suspendisse fermentum suscipit eros, ac ultricies leo sagittis quis. Nunc sollicitudin lorem eget eros eleifend facilisis. Quisque bibendum sem at bibendum suscipit. Nam id tellus mi.</div>
                </div>
                <div class="blog-post">
                	<div class="blog-image" style="background-image:url('images/blog/B.jpg')"></div>
                	<div class="blog-title">The Male Gift</div>
                	<div class="blog-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla sed dui felis. Vivamus vitae pharetra nisl, eget fringilla elit. Ut nec est sapien. Aliquam dignissim velit sed nunc imperdiet cursus. Proin arcu diam, tempus ac vehicula a, dictum quis nibh. Maecenas vitae quam ac mi venenatis vulputate. Suspendisse fermentum suscipit eros, ac ultricies leo sagittis quis. Nunc sollicitudin lorem eget eros eleifend facilisis. Quisque bibendum sem at bibendum suscipit. Nam id tellus mi.</div>
                </div>
                <div class="blog-post">
                	<div class="blog-image" style="background-image:url('images/blog/C.jpg')"></div>
                	<div class="blog-title">The Male Gift</div>
                	<div class="blog-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla sed dui felis. Vivamus vitae pharetra nisl, eget fringilla elit. Ut nec est sapien. Aliquam dignissim velit sed nunc imperdiet cursus. Proin arcu diam, tempus ac vehicula a, dictum quis nibh. Maecenas vitae quam ac mi venenatis vulputate. Suspendisse fermentum suscipit eros, ac ultricies leo sagittis quis. Nunc sollicitudin lorem eget eros eleifend facilisis. Quisque bibendum sem at bibendum suscipit. Nam id tellus mi.</div>
                </div>
            	-->
            </div>
        </div>
        
        <!--FOOTER-->
        <div id="footer">
        	<div>Lets get Social</div>
            <div>
            	<a href="https://www.facebook.com/mybirthdaybuzz">
                	<div class="social-connect" style="background-image:url('images/social/share/facebook.svg')"></div>
                </a>
                <a href="https://www.googleplus.com/mybirthdaybuzz">
                	<div class="social-connect" style="background-image:url('images/social/share/googleplus.svg')"></div>
                </a>
                <a href="https://www.twitter.com/mybirthdaybuzz">
                	<div class="social-connect" style="background-image:url('images/social/share/twitter.svg')"></div>
                </a>
                <a href="https://www.instagram.com/mybirthdaybuzz">
                	<div class="social-connect" style="background-image:url('images/social/share/instagram.svg')"></div>
                </a>
            </div>
        	<div>
                <div>
                    <span class="footer-details">NEWSLETTER</span>
                    <div class="footer-details">For up to date information, subscribe to our newsletter</div>
                    <input type="text" class="footer-details" id="email" placeholder="Enter Your Email..."/>
                    <input type="submit" value="SUBSCRIBE" id="subscribe" class="footer-details"/>
                </div>
            </div>
            <div>Copyright&copy; 2018. My BirthdayBuzz. Designed By TroggeUrban</div>
        </div>
        
        <div id="signup-modal">
        	<div class="close"><img src="images/actions/close.png" /></div>
            <div class="response"></div>
            <div class="header-image"><img src="images/actions/edit.png" /></div>
            <input id="header-upload" type="file" style="display:none;"/>
            <div class="profile-image"><img src="images/actions/edit.png" /></div>
            <input id="profile-upload" type="file" style="display:none;"/>
            <div class="form-inputs-signup">
                <input type="text" class="text username" placeholder="Username" />
                <input type="text" class="text firstname" placeholder="First name" />
                <input type="text" class="text lastname" placeholder="Last name" />
                <input type="text" class="text mobile" placeholder="Mobile number" />
                <input type="text" class="text email" placeholder="Email"/>
                <input type="password" class="text password" placeholder="Password"/>
                <input type="password" class="text repeat-password"  placeholder="Repeat Password"/>
                <input type="submit" value="Sign Up" class="text" id="submit-signup"/>
            <div class="linker"></div>
            </div>
            
        </div>
        
        <div class="cover"></div>
        
        <div id="signin-modal">
        	<div class="close"><img src="images/actions/close.png" /></div>
            <div class="response">AN ERROR OCCURED</div>
        	<div class="form-inputs-signin">
                <input type="text" class="text email" placeholder="Username, Email or Mobile" />
                <input type="password" class="text password" placeholder="Password" />
                <input type="submit" value="Sign In" class="text" id="submit-signin"/>
            </div>
        </div>
        
    </body>
</html>