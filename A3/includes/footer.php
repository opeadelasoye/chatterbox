<?php
    $currentPath = $_SERVER['REQUEST_URI'];
    if(str_contains($currentPath, 'includes')){
        header("Location: ../index.php");
    }
?>

<footer class="footer fixed-bottom">
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