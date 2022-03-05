<?php
    // Starter file for A3 in CSCI 2170
    session_start();
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
		<h2 class="text-center">Login Page</h2>
	</header>
	<br>

    <main class="w-50 mx-auto">
		<!-- Content here -->
		<?php 	
			if(isset($_SESSION['login-id'])){
				header("Location: profile.php");
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
        	<button class="btn btn-primary my-2" type="submit" id="login-button" >Login</button>
			</form>

		<?php
			}
		?>
	</main>    

    <footer class="footer fixed-bottom">
		<div class="container">
			<p class="mx-auto">&copy; 2022 | CSCI2170 | Assignment 3 | Ope Adelasoye | <a href="https://www.linkedin.com/in/ope-remi-adelasoye/" id="linkedin">Linkedin</a></p>
		</div>
	</footer>
</body>
</html>