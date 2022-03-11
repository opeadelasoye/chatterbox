<?php
	ini_set('display_errors', 1);
    require_once "includes/functions.php";

    // Starter file for A3 in CSCI 2170
    require_once "includes/db.php";

    $userID = $_SESSION['user-ID'];
    $userIsAdmin = false;
    $userIsSuspended = false;

    if($_SESSION['user-role'] == 0){
        $userIsAdmin = true;
    }
    if($_SESSION['user-suspended'] == 1){
        $userIsSuspended = true;
    }

    $passwordLength = strlen($_SESSION['user-password']);
    $passwordHidden = "*";

    for($i = 1; $i < $passwordLength; $i++){
        $passwordHidden = $passwordHidden . "*";  
    }

    if(isset($_REQUEST["first-name"]) && $_POST["first-name"] != ""){
        $firstName = sanitizeData($_POST["first-name"]);
        $sql = "UPDATE cb_users
                    SET cb_user_firstname = '$firstName'
                    WHERE cb_user_id = '$userID';";

        $database->query($sql);
    }
    if(isset($_REQUEST["last-name"]) && $_POST["last-name"] != ""){
        $lastName = sanitizeData($_POST["last-name"]);
        $sql = "UPDATE cb_users
                    SET cb_user_lastname = '$lastName'
                    WHERE cb_user_id = '$userID';";
                    
        $database->query($sql);
    }
    if(isset($_REQUEST["email"]) && $_POST["email"] != ""){
        $email = sanitizeData($_POST["email"]);
        $sql = "UPDATE cb_login
                    SET cb_login_email = '$email'
                    WHERE cb_login_id = '$userID';";
                    
        $database->query($sql);
    }
    if(isset($_REQUEST["password"]) && $_POST["password"] != ""){
        $password = sanitizeData($_POST["password"]);
        $sql = "UPDATE cb_login
                    SET cb_login_password
                    WHERE cb_login_id = '$userID';";
                    
        $database->query($sql);
    }

    $feed = "index.php";
	$profile = "profile.php";
	$logout = "includes/logout.php";
	$dashboard = "admin/dashboard.php";
    $pageHeader = "Profile";
	$img = "img/logo.jpg";
	$css = "css/main.css";

    $sessionStarted = true;
?>

<?php
	getHeader(false, $css, $img, $pageHeader, $sessionStarted, $feed, $profile, $logout, $dashboard, $userIsAdmin);
?>

    <main class="w-50 mx-auto">
        <?php echo "<h5 class=\"text-center\">Welcome, " . $_SESSION['user-first-name'] . "</h5>"; ?>
		
        <?php if($userIsSuspended){ echo "<br><h6>This account is suspended.</h6><br>";}?>

        <img src="img/user-profile.jpg" alt="Profile picture template" class="mx-auto d-block">

        <form class="mx-auto" style="width: 300px" method="post" action="profile.php" id="user-input">
            <div class="form-group my-3">
				<label for="first-name-input" id="input-label">First name: <?php echo $_SESSION['user-first-name'];?></label>
        		<input type="text" class="form-control" name="first-name" id="first-name-input" placeholder="Update first name">
			</div>
            <div class="form-group my-3">
				<label for="last-name-input" id="input-label">Last name: <?php echo $_SESSION['user-last-name'];?></label>
        		<input type="text" class="form-control" name="last-name" id="last-name-input" placeholder="Update last name">
			</div>
            <div class="form-group my-3">
				<label for="email-input" id="input-label">Email address: <?php echo $_SESSION['user-email'];?></label>
        		<input type="text" class="form-control" name="email" id="email-input" placeholder="Update email">
			</div>
        	<div class="form-group my-3">
				<label for="password-input" id="input-label">Password:<p id="password"> <?php echo "$passwordHidden";?></p></label>
        		<input type="hidden" class="form-control" name="password" id="password-input" placeholder="Update password" disabled>
			</div>
        	<button class="btn btn-primary my-2 mx-auto d-block" type="submit" id="submit-button" >Submit Changes</button>
		</form>
        <p class="text-center">(Logout to see updated changes.)</p>

	</main>    

<?php
	getFooter(false);
?>