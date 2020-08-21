<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/STEMA/PhpUtils/dbConx.php");
    
    $id    = mysqli_real_escape_string($dbConx, $_POST["id"]);
    $idx   = mysqli_real_escape_string($dbConx, $_POST["idx"]);
    $table = "";

    //IDXS
    switch($idx)
    {
        case "ING":
            $table = "ingredients";
            break;
        case "PRD":
            $table = "products";
            break;
        case "VAR":
            $table = "variants";
            break;
        case "BRD":
            $table = "productbrands";
            break;
    }

    $sql       = "SELECT COUNT(*) AS rowNbr FROM ".$table." WHERE id = ".$id.";";
    $sqlDelete = "DELETE FROM ".$table." WHERE id = ".$id.";";

    $query = mysqli_query($dbConx, $sql);
    $res   = mysqli_fetch_assoc($query);

    mysqli_free_result($query);

    if($res["rowNbr"] == 1)
    {
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