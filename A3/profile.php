<?php
    require_once "includes/functions.php";

	ini_set('display_errors', 1);
    // Starter file for A3 in CSCI 2170
    session_start();

	$host = "localhost";
    $username = "root";
    $password = "root";
    $dbName = "2170-w22";

    $database = new mysqli($host, $username, $password, $dbName);

    if($database->connect_error){
        die("Error code: " . $database->connect_errno . "<br>" . $database->connect_error);
    }

    $userID = $_SESSION['user-ID'];

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
        header("Location: includes/login.php");
    }
    if(isset($_REQUEST["last-name"]) && $_POST["last-name"] != ""){
        $lastName = sanitizeData($_POST["last-name"]);
        $sql = "UPDATE cb_users
                    SET cb_user_lastname = '$lastName'
                    WHERE cb_user_id = '$userID';";
                    
        $database->query($sql);
        header("Location: includes/login.php");
    }
    if(isset($_REQUEST["email"]) && $_POST["email"] != ""){
        $email = sanitizeData($_POST["email"]);
        $sql = "UPDATE cb_login
                    SET cb_login_email = '$email'
                    WHERE cb_login_id = '$userID';";
                    
        $database->query($sql);
        header("Location: includes/login.php");
    }
    if(isset($_REQUEST["password"]) && $_POST["password"] != ""){
        $password = sanitizeData($_POST["password"]);
        $sql = "UPDATE cb_login
                    SET cb_login_password
                    WHERE cb_login_id = '$userID';";
                    
        $database->query($sql);
        header("Location: includes/login.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1, initial-scale=1.0">
    <title>Index</title>

	<!-- Link to main css file -->
	<link href="css/main.css" rel="stylesheet">
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
	<br>
    <header>
		<h2 class="text-center">Profile Page</h2>
	</header>
	<br>

    <main class="w-50 mx-auto">
        <h5 class="text-center">Welcome, <?php echo $_SESSION['user-first-name'];?>. <a href="includes/logout.php">Logout</a> <a href="index.php">News Feed</a></h5>
		
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

    <footer class="footer fixed-bottom">
		<div class="container">
			<p class="mx-auto">&copy; 2022 | CSCI2170 | Assignment 3 | Ope Adelasoye | <a href="https://www.linkedin.com/in/ope-remi-adelasoye/" id="linkedin">Linkedin</a></p>
		</div>
	</footer>
</body>
</html>