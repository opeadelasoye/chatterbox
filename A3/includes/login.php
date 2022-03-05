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
        $sql = "SELECT cb_login_id AS loginID
                    FROM cb_login
                    WHERE cb_login_email = '$email' && cb_login_password = '$password'";
        $result = $database->query($sql);
    }
    if($result->num_rows > 0){
        session_start();
        $row = $result->fetch_assoc();

        $_SESSION['login-id'] = $row["loginID"];
        
        header("Location: ../index.php");
    }
?>