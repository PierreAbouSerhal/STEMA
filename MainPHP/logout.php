<?php
    session_set_cookie_params(0, "/", "localhost", true, true);
    session_start();
    
    $_SESSION = array();

    if(isset($_COOKIE["userToken"]))
    {
        setcookie("userToken", "", 0, "/");
    }

    session_destroy();
    header("Location: index.php");
    exit();
?>