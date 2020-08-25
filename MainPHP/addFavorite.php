<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/STEMA/PhpUtils/dbConx.php");
    
    $prodId = mysqli_real_escape_string($dbConx, $_POST["prodId"]);
    $userId = mysqli_real_escape_string($dbConx, $_POST["userId"]);

    
    $sqlAdd = 'INSERT INTO favorites (userId, productId)
                    VALUES('.$userId.', '.$prodId.')';

    $queryAdd = mysqli_query($dbConx, $sqlAdd);
    
    if($queryAdd)
    {
        echo 1;
        exit();   
    }
    
    echo 0;
    exit();
?>