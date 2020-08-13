<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/STEMA/PhpUtils/checkLoginStatus.php");

    $msg = $status = "";

    if(!$user["userOk"] && !$isAdmin)
    {
        logout();
        // PAGE NO LONGER AVAILABLE FOR LOGGED OUT USER
        header("Location = index.php");
        exit();
    }

    $sqlToday = "SELECT COUNT(*) AS rowNbr
                 FROM users 
                 WHERE creationDate = CURDATE() ;";
                 
    $queryToday = mysqli_query($dbConx, $sqlToday);

    $sqlAll = "SELECT COUNT(*) AS rowNbr FROM users";

    $queryAll = mysqli_query($dbConx, $sqlAll);

    $sqlFav = "SELECT fav.variantId, vari.name, COUNT(fav.variantId) AS rowNbr
               FROM favorites AS fav
               JOIN variants AS vari ON vari.id = fav.variantId
               GROUP BY fav.variantId
               ORDER BY rowNbr DESC 
               LIMIT 1 ;";

    $queryFav = mysqli_query($dbConx, $sqlFav);

    $sqlView = "SELECT hist.variantId, vari.name, COUNT(hist.variantId) AS rowNbr
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
            <div class="info-container">
                <p class="info-nbr"><?php echo $resToday["rowNbr"]?></p>
                <p class="info">New Registered Users Today</p>
            </div>
            <div class="info-container">
                <p class="info-nbr"><?php echo $resAll["rowNbr"]?></p>
                <p class="info">Registered Users In Total</p>
            </div>
            <div class="info-container">
                <p>Most Favorite Product: <?php echo $resFav["name"]?></p>
            </div>
            <div class="info-container">
                <p>Most Viewed Product: <?php echo $resView["name"]?></p>
            </div>
        </div>
    </div>
</body>
</html>