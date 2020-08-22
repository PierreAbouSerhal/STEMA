<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/STEMA/PhpUtils/dbConx.php");
    
    $variId = mysqli_real_escape_string($dbConx, $_POST["variId"]);
    $userId = mysqli_real_escape_string($dbConx, $_POST["userId"]);

    
    $sqlAdd = 'INSERT INTO favorites (userId, variantId)
                    VALUES('.$userId.', '.$variId.')';

    $queryAdd = mysqli_query($dbConx, $sqlAdd);
    
    if($queryAdd)
    {
        echo 1;
        exit();   
    }
    
    echo 0;
    exit();
?>