<?php
    require_once "functions.php";

    $host = "localhost";
    $username = "root";
    $password = "root";
    $dbName = "2170-w22";

    $database = new mysqli($host, $username, $password, $dbName);

    if($database->connect_error){
        die("Error code: " . $database->connect_errno . "<br>" . $database->connect_error);
    }

    $result;

    if(isset($_REQUEST["email"]) && isset($_REQUEST["password"])){
        $email = sanitizeData($_POST["email"]);
        $password = sanitizeData($_POST["password"]);
        $sql = "SELECT cb_user_firstname AS name, cb_login_id as ID
                    FROM cb_users
                    JOIN cb_login ON (cb_login_id = cb_user_id)
                    WHERE '$email' = cb_login_email && '$password' = cb_login_password;";
        $result = $database->query($sql);
    }
    if($result->num_rows > 0){
        session_start();
        $row = $result->fetch_assoc();

        $_SESSION['user-ID'] = $row["ID"];
        $_SESSION['user-name'] = $row["name"];

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