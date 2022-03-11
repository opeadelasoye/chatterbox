<?php
	ini_set('display_errors', 1);

    $currentPath = $_SERVER['REQUEST_URI'];
    if(str_contains($currentPath, 'includes')){
        header("Location: ../index.php");
    }

    require_once "functions.php";

    getDatabase(false);

    $result;

    if(isset($_REQUEST["email"]) && isset($_REQUEST["password"])){
        $email = sanitizeData($_POST["email"]);
        $password = sanitizeData($_POST["password"]);
        $sql = "SELECT *, cb_user_firstname, cb_user_lastname, cb_login_id as ID
                    FROM cb_users
                    JOIN cb_login ON (cb_login_id = cb_user_id)
                    WHERE '$email' = cb_login_email && '$password' = cb_login_password;";
        $result = $database->query($sql);
    }
    if($result->num_rows > 0){
        session_start();
        $row = $result->fetch_assoc();

        $_SESSION['user-ID'] = $row["ID"];
        $_SESSION['user-first-name'] = $row["cb_user_firstname"];
        $_SESSION['user-last-name'] = $row["cb_user_lastname"];
        $_SESSION['user-email'] = $row["cb_login_email"];
        $_SESSION['user-password'] = $row["cb_login_password"];
        $_SESSION['user-role'] = $row["cb_user_role"];
        $_SESSION['user-suspended'] = $row["cb_user_suspended"];

        $userID = $row["ID"];

        $sql = "SELECT *, CONCAT(cb_user_firstname, \" \" , cb_user_lastname) AS name 
                    FROM cb_posts 
                    JOIN cb_users ON (cb_user_id = cb_post_author_id)
                    WHERE cb_post_author_id != '$userID'
                    ORDER BY cb_post_id;";
        $result = $database->query($sql);
        $numOfPosts = 0;

        while($row = $result->fetch_assoc()){
            $numOfPosts++;
            $_SESSION['user-feed-post-' . $numOfPosts] = $row["cb_post_content"];
            $_SESSION['user-feed-post-' . $numOfPosts . '-name'] = $row["name"];
            $_SESSION['user-feed-post-' . $numOfPosts . '-id'] = $row["cb_post_id"];
            $_SESSION['user-feed-post-' . $numOfPosts . '-report-status'] = $row["cb_post_report"];
        }

        $_SESSION['user-num-of-posts'] = $numOfPosts;

        header("Location: ../index.php");
    }else{
        header("Location: ../index.php");
    }
?>