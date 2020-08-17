<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/STEMA/PhpUtils/checkLoginStatus.php");

    if(!$user["userOk"] || !$isAdmin)
    {
        logout();
        // PAGE NO LONGER AVAILABLE FOR LOGGED OUT USER
        header("Location: ../MainPHP/index.php");
        exit();
    }

    $sqlToday = "SELECT COUNT(*) AS rowNbr
                 FROM users 
                 WHERE creationDate = CURDATE() ;";
                 
    $queryToday = mysqli_query($dbConx, $sqlToday);

    $sqlAll = "SELECT COUNT(*) AS rowNbr FROM users";

    $queryAll = mysqli_query($dbConx, $sqlAll);

    $sqlFav = "SELECT fav.variantId, 
                      vari.name,
                      vari.image1, 
                      COUNT(fav.variantId) AS rowNbr
               FROM favorites AS fav
               JOIN variants AS vari ON vari.id = fav.variantId
               GROUP BY fav.variantId
               ORDER BY rowNbr DESC 
               LIMIT 1 ;";

    $queryFav = mysqli_query($dbConx, $sqlFav);

    $sqlView = "SELECT hist.variantId,
                       vari.name, 
                       COUNT(hist.variantId) AS rowNbr
                FROM history AS hist
                JOIN variants AS vari ON vari.id = hist.variantId
                GROUP BY hist.variantId
                ORDER BY rowNbr DESC 
                LIMIT 1 ;";

    $queryView = mysqli_query($dbConx, $sqlView);

    $resToday = mysqli_fetch_assoc($queryToday);
    $resAll   = mysqli_fetch_assoc($queryAll);
    $resFav   = mysqli_fetch_assoc($queryFav);
    $resView  = mysqli_fetch_assoc($queryView);

    mysqli_free_result($queryToday);
    mysqli_free_result($queryAll);
    mysqli_free_result($queryFav);
    mysqli_free_result($queryView);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="../MainCss/header.css"/>
    <link rel="stylesheet" type="text/css" href="../MainCss/generalInfo.css"/>
    <script src="../MainJs/header.js"></script>
    
    <title>Admin Page</title>
</head>
<body>
    <div class="container main-container">
        
        <?php 
            include_once($_SERVER["DOCUMENT_ROOT"]."/Stema/MainElements/adminHeader.php");
        ?>
        <h3 class="welcome-msg">Welcome <?php echo $_SESSION["userName"];?></h3>
        <div class="info-main-container">
            <div class="info-container" style="background-color: rgb(133,187,47);">
                <p class="info-nbr"><?php echo $resToday["rowNbr"]?></p>
                <p class="info">New Registered Users Today</p>
                <img class="info-img" src="../StemaPics/newUser.png">
            </div>
            <div class="info-container" style="background-color: rgb(255,204,3);">
                <p class="info-nbr"><?php echo $resAll["rowNbr"]?></p>
                <p class="info">Registered Users In Total</p>
                <img class="info-img" src="../StemaPics/multy-user.png">
            </div>
            <div class="info-container" style="background-color: rgb(238,129,0);">
                <p class="info-txt">Most Favorite Product:</p>
                <p class="info"><?php echo $resFav["name"]?></p>
                <?php
                    $picPath = (empty($resFav["image1"])) ? "../StemaPics/stars.png" : $resFav["image1"];
                ?>
                <img class="info-img" src="../StemaPics/stars.png">
            </div>
            <div class="info-container" style="background-color: rgb(230,62,17);">
                <p class="info-txt">Most Viewed Product:</p>
                <p class="info"><?php echo $resView["name"]?></p>
                <img class="info-img" src="../StemaPics/preview.png">
            </div>
        </div>
    </div>
</body>
</html>