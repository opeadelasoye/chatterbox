<?php
    $currentPath = $_SERVER['REQUEST_URI'];
    if(str_contains($currentPath, 'includes')){
        header("Location: ../index.php");
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1, initial-scale=1.0">
    <title><?php echo $pageHeader; ?></title>

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