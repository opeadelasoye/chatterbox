<?php
    function sanitizeData($data) {
        $sanitizedForm = stripslashes($data);
        $sanitizedForm = trim($sanitizedForm);
        $sanitizedForm = htmlspecialchars($sanitizedForm);
    
        return $sanitizedForm;
    }
?>