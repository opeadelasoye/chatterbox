<?php
    $currentPath = $_SERVER['REQUEST_URI'];
    if(str_contains($currentPath, 'includes')){
        header("Location: ../index.php");
    }

    /** Sanitize user inputted data */
    function sanitizeData($data) {
        $sanitizedForm = stripslashes($data);
        $sanitizedForm = trim($sanitizedForm);
        $sanitizedForm = htmlspecialchars($sanitizedForm);
    
        return $sanitizedForm;
    }

    /** Retrieve footer content */
    function getFooter($userIsInDashboard){
        if($userIsInDashboard){
            return require "../includes/footer.php";
        }else{
            return require "includes/footer.php";
        }
    }

    /** Retrieve header content */
    function getHeader($userIsInDashboard, $css, $img, $pageHeader, $sessionStarted, $feed, $profile, $logout, $dashboard, $userIsAdmin){
        $css; $img; $pageHeader; $sessionStarted; $feed; $profile; $logout; $dashboard; $userIsAdmin;
        
        if($userIsInDashboard){
            return require "../includes/header.php";
        }else{
            return require "includes/header.php";
        }
    }

    /** Retrieve database content */
    function getDatabase($userIsInDashboard){
        if($userIsInDashboard){
            return require_once "../includes/db.php";
        }else{
            return require_once "includes/db.php";
        }       
    }
?>