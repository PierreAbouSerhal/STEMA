<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/STEMA/PhpUtils/dbConx.php");
    
    $prodId = mysqli_real_escape_string($dbConx, $_POST["prodId"]);
    $userId = mysqli_real_escape_string($dbConx, $_POST["userId"]);

    $sql   = "SELECT COUNT(*) AS rowNbr FROM favorites WHERE userId = ".$userId." AND productId = ".$prodId." ;";
    $query = mysqli_query($dbConx, $sql);
    $res   = mysqli_fetch_assoc($query);

    mysqli_free_result($query);

    if($res["rowNbr"] == 1)
    {
        $sqlDelete = "DELETE FROM favorites WHERE userId = ".$userId." AND productId = ".$prodId." ;";
        $queryDelete = mysqli_query($dbConx, $sqlDelete);
        
        if($queryDelete)
        {
            echo 1;
            exit();
        }
    }

    echo 0;
    exit();
?>