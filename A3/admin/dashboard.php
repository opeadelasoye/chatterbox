<?php
	ini_set('display_errors', 1);
    require_once "../includes/functions.php";
	session_start();

	$host = "localhost";
    $username = "root";
    $password = "root";
    $dbName = "2170-w22";

    $database = new mysqli($host, $username, $password, $dbName);

	$suspendPressed = false;
	$clearPressed = false;
	$deletePressed = false;
	$id = 0;

    if($database->connect_error){
        die("Error code: " . $database->connect_errno . "<br>" . $database->connect_error);
    }

	if($_SESSION['user-role'] == 1){
		header("Location: ../index.php");
	}

	$sqlNumOfUsers = "SELECT COUNT(cb_user_id) FROM cb_users;";
	$numOfUsers = $database->query($sqlNumOfUsers);
	$numOfUsers = $numOfUsers->fetch_assoc()["COUNT(cb_user_id)"];

	$sqlNumOfReportedPosts = "SELECT MAX(cb_reported_post_id) FROM cb_reported_posts;";
	$numOfReportedPosts = $database->query($sqlNumOfReportedPosts);
	$numOfReportedPosts = $numOfReportedPosts->fetch_assoc()["MAX(cb_reported_post_id)"];

	$sqlReportedPosts = "SELECT CONCAT(cb_user_firstname, \" \", cb_user_lastname) AS reporter, cb_post_content, cb_reported_post_id
							FROM cb_reported_posts
							JOIN cb_posts ON (cb_post_id = cb_reported_post_id)
							JOIN cb_users ON (cb_user_id = cb_reported_by_user_id)
							WHERE cb_reported_post_status = 'reported';";
	$reportedPosts = $database->query($sqlReportedPosts);

	$sqlUsers = "SELECT CONCAT(cb_user_firstname, \" \", cb_user_lastname) AS name, cb_user_id, cb_user_role 
					FROM cb_users;";
	$users = $database->query($sqlUsers);

	while((!$suspendPressed && !$clearPressed && !$deletePressed) && (($id < $numOfUsers) || ($id < $numOfReportedPosts))){
		$id++;
		if(isset($_POST["suspend-user-$id"])){
			$suspendPressed = true;
		}else if(isset($_POST["clear-post-$id"])){
			$clearPressed = true;
		}else if(isset($_POST["delete-post-$id"])){
			$deletePressed = true;
		}
	}

	if($suspendPressed){
		$suspend = "UPDATE cb_users SET cb_user_suspended = 1 WHERE cb_user_id = $id;";
		$database->query($suspend);
		header("Location: dashboard.php");
	}else if($clearPressed){
		$clear = "UPDATE cb_reported_posts SET cb_reported_post_status = 'cleared' WHERE cb_reported_post_id = $id;";
		$database->query($clear);
		$clear = "UPDATE cb_posts SET cb_post_report = 0 WHERE cb_post_id = $id;";
		$database->query($clear);
		header("Location: dashboard.php");
	}else if($deletePressed){
		$delete = "DELETE FROM cb_reported_posts WHERE cb_reported_post_id = $id;";
		$database->query($delete);
		$delete = "DELETE FROM cb_posts WHERE cb_post_id = $id;";
		$database->query($delete);
		header("Location: dashboard.php");
	}

	$feed = "../index.php";
	$profile = "../profile.php";
	$logout = "../includes/logout.php";
	$dashboard = "dashboard.php";
	$pageHeader = "Admin Dashboard";
	$img = "../img/logo.jpg";
	$css = "../css/main.css";

	$sessionStarted = true;
	$userIsAdmin = true;
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
        <?php echo "<h5 class=\"text-center\">Welcome, " . $_SESSION['user-first-name'] . "</h5>";?>
		
        <div class="row">
			<div class="col-6">
				<?php
					while($row = $users->fetch_assoc()){
						echo "<p>Name: " . $row["name"] . " | ID:  " . $row["cb_user_id"] . " | Role: ";
						if($row["cb_user_role"] == 0){
							echo "Admin</p>";
						}else{
							echo "User</p>";
						}
						echo "<form method=\"post\" action=\"dashboard.php\">
						<button class=\"btn btn-dark my-2\" type=\"submit\" id=\"suspend-button\" name=\"suspend-user-" . $row["cb_user_id"] . "\">Suspend</button><br><br>";
					}
				?>
			</div>
			<div class="col-6">
				<?php
					while($row = $reportedPosts->fetch_assoc()){
						echo "<p>Post: " . $row["cb_post_content"] . " Reported by: " . $row["reporter"] . "</p>";
						echo "<form method=\"post\" action=\"dashboard.php\">
						<button class=\"btn btn-primary my-2\" type=\"submit\" id=\"clear-button\" name=\"clear-post-" . $row["cb_reported_post_id"] . "\">Clear</button>
						<button class=\"btn btn-danger my-2\" type=\"submit\" id=\"delete-button\" name=\"delete-post-" . $row["cb_reported_post_id"] . "\">Delete</button><br><br>";
					}
				?>
			</div>
		</div>

	</main>    

    <footer class="footer fixed-bottom">
		<div class="container">
			<p class="mx-auto">&copy; 2022 | CSCI2170 | Assignment 3 | Ope Adelasoye | <a href="https://www.linkedin.com/in/ope-remi-adelasoye/" id="linkedin">Linkedin</a></p>
		</div>
	</footer>
</body>
</html>