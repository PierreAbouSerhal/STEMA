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

    $sqlFav = "SELECT fav.productId, 
                      prod.name,
                      COUNT(fav.productId) AS rowNbr
               FROM favorites AS fav
               JOIN products AS prod ON prod.id = fav.productId
               GROUP BY fav.productId
               ORDER BY rowNbr DESC 
               LIMIT 1 ;";

    $queryFav = mysqli_query($dbConx, $sqlFav);

    $sqlView = "SELECT hist.productId,
                       prod.name, 
                       COUNT(hist.productId) AS rowNbr
                FROM history AS hist
                JOIN products AS prod ON prod.id = hist.productId
                GROUP BY hist.productId
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

    include("../MainElements/doctype.html");
?>

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