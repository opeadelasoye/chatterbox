<?php
	ini_set('display_errors', 1);
    session_start();

	$host = "localhost";
    $username = "root";
    $password = "root";
    $dbName = "2170-w22";

    $database = new mysqli($host, $username, $password, $dbName);

    if($database->connect_error){
        die("Error code: " . $database->connect_errno . "<br>" . $database->connect_error);
    }

	$sessionStarted = false;
	$pageHeader = "Login Page";
	$id = 0;
    $likePressed = false;
    $reportPressed = false;
	$postID = null;
	$sqlLikeCount = "";
	$likeCount = null;
	$postReportStatus = null;
	$userID = null;
	$userIsAdmin = false;
	$userIsSuspended = false;

	if(isset($_SESSION['user-ID'])){
		$sessionStarted = true;
		$pageHeader = "Feed";
		$userID = $_SESSION['user-ID'];
		$numOfPosts = $_SESSION['user-num-of-posts'];

		if($_SESSION['user-role'] == 0){
			$userIsAdmin = true;
		}
		if($_SESSION['user-suspended'] == 1){
			$userIsSuspended = true;
		}

		while((!$likePressed && !$reportPressed) && $id < $numOfPosts){
			$id++;
			if(isset($_POST["like-$id"])){
				$likePressed = true;
			}else if(isset($_POST["report-$id"])){
				$reportPressed = true;
			}
		}
	
		$postID = $_SESSION['user-feed-post-' . $id . '-id'];

		if($likePressed){
			$database->query("UPDATE cb_posts SET cb_post_likes = cb_post_likes + 1 WHERE cb_post_id = $postID;");
			
			$sqlLikeCount = "SELECT cb_post_likes FROM cb_posts WHERE cb_post_id = '$postID';";
			$result = $database->query($sqlLikeCount);
			$likeCount = $result->fetch_assoc()["cb_post_likes"];
		}else if($reportPressed){
			$database->query("UPDATE cb_posts SET cb_post_report = 1 WHERE cb_post_id = $postID;");
			$database->query("INSERT INTO cb_reported_posts VALUES ($postID, $userID, 'reported');");
			$database->query("UPDATE cb_reported_posts SET cb_reported_post_status = 'reported' WHERE cb_reported_post_id = $postID");
			$_SESSION['user-feed-post-' . $id . '-report-status'] = 1;
		}
	}

	$feed = "index.php";
	$profile = "profile.php";
	$logout = "includes/logout.php";
	$dashboard = "admin/dashboard.php";
	$img = "img/logo.jpg";
	$css = "css/main.css";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1, initial-scale=1.0">
    <title>Index</title>

	<!-- Link to main css file -->
	<link href=<?php echo $css;?> rel="stylesheet">
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
	<nav class="navbar navbar-expand navbar-light" id="navigation">
        	<a class="navbar-brand">
            	<img src=<?php echo $img;?> id="logo" alt="Drawing of twitter bird by Oliver Tacke" width="100" height="50">
        	</a>
		<?php
			if($sessionStarted){
		?>
        <div class="navbar-nav" id="nav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href=<?php echo $feed;?>>Feed</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href=<?php echo $profile;?>>Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href=<?php echo $logout;?>>Logout</a>
                </li>
		<?php
				if($userIsAdmin){
		?>
				<li class="nav-item">
                    <a class="nav-link" href=<?php echo $dashboard;?>>Dashboard</a>
                </li>
            </ul>
        </div>
		<?php
				}
			}
		?>
    </nav>
	<br>
    <header>
		<h2 class="text-center"><?php echo $pageHeader; ?></h2>
	</header>
	<br>

<main class="w-50 mx-auto">
	<!-- Content here -->
	<?php 	
		if($sessionStarted && !$userIsSuspended){
			echo "<h5 class=\"text-center\">Welcome, " . $_SESSION['user-first-name'];
			echo "</h5>";
			
			for($i = 0; $i < $numOfPosts; $i++){
				$id = $i+1;
				echo "<br><p>" . $_SESSION['user-feed-post-' .$id . '-name'] . ": " . $_SESSION['user-feed-post-' . $id] . "</p>";
				echo "<form method=\"post\" action=\"index.php\">
						<button class=\"btn btn-danger my-2\" type=\"submit\" id=\"like-button\" name=\"like-$id\">Like</button>";
				
				if($_SESSION['user-feed-post-' . $id . '-report-status'] == 0){
					echo "<button class=\"btn btn-dark my-2\" type=\"submit\" id=\"report-button\" name = \"report-$id\">Report</button>";
				}else{
					echo "	&#128681 This post has been reported for community guideline violations.";
				}		
					
				if($likePressed && $_SESSION['user-feed-post-' . $id . '-id'] == $postID){
					echo "<p>Likes: " . $likeCount . "</p>";
				}
				echo "</form><br>";
			}
		}else if($userIsSuspended){
			echo "<h5 class=\"text-center\">Welcome, " . $_SESSION['user-first-name'] . ". " . "<a href=\"includes/logout.php\">Logout</a> <a href=\"profile.php\">Profile</a>";
			echo "<br><h6>This account is suspended.</h6><br>";
		}else{
	?>

	<form class="mx-auto" style="width: 300px" method="post" action="includes/login.php" id="user-input">
    	<div class="form-group my-3">
			<label for="email-input" id="input-label">Email address</label>
       		<input type="text" class="form-control" name="email" id="email-input" placeholder="Email">
		</div>
       	<div class="form-group my-3">	
			<label for="password-input" id="input-label">Password</label>
        	<input type="text" class="form-control" name="password" id="password-input" placeholder="Password">
		</div>
        	<button class="btn btn-primary my-2 mx-auto d-block" type="submit" id="login-button" >Login</button>
	</form>

	<?php
		}
	?>
</main>    

    <footer class="footer">
		<div class="row">
			<div class="col-6">
				<p>&copy; 2022</p>
				<p>ChatterBox is a simpler way for users to stay up to date with content. We are still in Beta and at the moment, we have only provided content sharing permissions to some users. You will get access to this feature in the coming days. Thanks for being a part of our journey!</p>
			</div>
			<div class="col-6 d-flex justify-content-end">
				<p><a id="empty-links" href=#>Privacy Policy | </a> <a id="empty-links" href=#>Terms Of Use | </a> <a id="empty-links" href=#>Contact Us</a></p>
			</div>
		</div>
	</footer>
</body>
</html>