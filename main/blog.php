<?php
	
	require_once('../secret/connection.php');
	
	$db = mysqli_connect($host,$username,$password,$database);

	if(!isset($_GET['id']) || empty($_GET['id'])){
		header("Location: blog.php?id=all");
	}else $blog_id = $_GET['id'];

	
	if(strtolower($blog_id) == 'all')$condition = "";
	else $condition = "WHERE (`blog_id` = '$blog_id') LIMIT 1";
	
	
	$sql = "SELECT * FROM blog $condition";
	$query = mysqli_query($db,$sql);
	$num = mysqli_num_rows($query);
	$id = array();
	$post = array();
	$title = array();
	$image = array();
	if($num > 0){
		while($row = mysqli_fetch_array($query,MYSQLI_ASSOC)){
			array_push($id,$row['blog_id']);
			array_push($post,$row['post']);
			array_push($title,$row['title']);
			array_push($image,$row['image']);
		}
		$page_title = (count($title) > 0) ? $title[0] : 'All';
	}else if(strtolower($blog_id) == 'all'){		
		$post[0] = 'All';
		$page_title = $title[0] = 'All';
		$image[0] = 'All';
	}else header("Location: blog.php?id=all");
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1 shrink-to-fit=yes">
        <link rel="icon" href="images/logo/small.png" type="image/x-icon" />
        <title>Birthday Buzz /// Blog /// <?php echo $page_title; ?></title>
        
        <link rel="stylesheet" type="text/css" href="styles/blog.css">
        
		<script src="scripts/jquery-1.11.1.js" type="application/javascript"></script>
		<script src="scripts/menu.js" type="application/javascript"></script>
		<script src="scripts/blog.js" type="application/javascript"></script>
        
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
        
        <?php
			
			if(is_numeric($blog_id)){
				echo <<<EOT
					<div id="blog-head">
						<div id="blog-image" style="background-image:url('$image[0]')"></div>
						<div id="blog-title">$title[0]</div>
					</div>
					<div id="blog-post">
						<div>
							$post[0]
						</div>
					</div>
EOT;
			}
    	?>
    	
        <div id="blogs">
        <?php
			for($i = 0; $i < count($id) && !is_numeric($blog_id); $i++){
				$p = substr($post[$i],0,500);
				echo <<<EOT
					<a href="blog.php?id=$id[$i]">
						<div class="blogposts">
							<div class="image" style="background-image:url('$image[$i]')"></div>
							<div class="title">$title[$i]</div>
							<div class="post">$p</div>
						</div>
					</a>
EOT;
			}
		?>
        </div>
            
    </body>
</html>