<?php
    $activated = 0;
    $location = "";

    if(isset($_GET["activCode"]))
    {
        include_once($_SERVER["DOCUMENT_ROOT"]."/STEMA/PhpUtils/dbConx.php");

        $activCode = mysqli_real_escape_string($dbConx, $_GET["activCode"]);
        $sql = "SELECT COUNT(*) AS rowNbr FROM users WHERE activated = 0 AND activationCode = '".$activCode."' ;";
        $query = mysqli_query($dbConx, $sql);
        $res = mysqli_fetch_assoc($query);
        
        mysqli_free_result($query);

        if($res["rowNbr"] == 1)
        {
            $sqlUpdate = "UPDATE users SET activated = 1 WHERE activationCode = '".$activCode."' LIMIT 1;";
            $queryUpdate = mysqli_query($dbConx, $sqlUpdate);
            if($queryUpdate)
            {
                $activated = 1;
            }
        }
    }
    
    $location = ($activated == 0) ? "signup.php?activated=0" : "login.php?activated=1";
    
    header("Location: ".$location);
    exit();

?>