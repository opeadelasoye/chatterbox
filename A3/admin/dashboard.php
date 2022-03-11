<?php
	ini_set('display_errors', 1);
    require_once "../includes/functions.php";
	require_once "../includes/db.php";

	$suspendPressed = false;
	$clearPressed = false;
	$deletePressed = false;
	$id = 0;

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

<?php
	getHeader(true, $css, $img, $pageHeader, $sessionStarted, $feed, $profile, $logout, $dashboard, $userIsAdmin);
?>

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

<?php
	getFooter(true);
?>