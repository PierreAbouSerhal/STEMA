<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/STEMA/PhpUtils/checkLoginStatus.php");

    if(!$user["userOk"])
    {//AUTOMATIC LOGOUT
      logout();
    }
    
    if(isset($_GET["prodId"]) && !empty($_GET["prodId"]))
    {
        $prodId = mysqli_real_escape_string($dbConx, $_GET["prodId"]);
        $variId = (isset($_GET["variId"])) ? mysqli_real_escape_string($dbConx, $_GET["variId"]) : "";


        //FETCH PRODUCT GENERAL INFO
        $sqlProd = 'SELECT prod.name AS prodName,
                           prod.nutriscore AS score,
                           prod.info
                    FROM products AS prod
                    WHERE prod.id = '.$prodId.';';

        $fop = (!empty($variId)) ? 'WHERE vari.id = '.$variId.';' : 'WHERE vari.productId = '.$prodId.';';

        
        $sqlVari = 'SELECT vari.image1,
                        vari.image2,
                        vari.image3
                    FROM variants AS vari '.$fop;
        
        $queryProd = mysqli_query($dbConx, $sqlProd);
        $queryVari = mysqli_query($dbConx, $sqlVari);
        
        if(mysqli_num_rows($queryProd) == 0)
        {
            header("Location: index.php");
            exit;
        }
        else
        {
            //ADD TO USER HISTORY
            $sqlHist = "SELECT COUNT(*) AS rowNbr
                        FROM history
                        WHERE userId = ".$user["userId"]." AND productId = ".$prodId;

            $queryHist = mysqli_query($dbConx, $sqlHist);

            $resHist = mysqli_fetch_assoc($queryHist);

            mysqli_free_result($queryHist);

            if($resHist["rowNbr"] == 0)
            {
                $sqlAddHist = "INSERT INTO history (userId, productId, viewDate, viewTime)
                                    VALUES (".$user["userId"].", ".$prodId.", CURDATE(), CURTIME())";
                    
                $queryAddHist = mysqli_query($dbConx, $sqlAddHist);
            }
        }
        
        $rowProd = mysqli_fetch_assoc($queryProd);
        
        mysqli_free_result($queryProd);

        //GENERAL INFO VARIABLES
        $prodName = $rowProd["prodName"];
        $score    = $rowProd["score"];
        $color    = strtolower($score);
        $info     = $rowProd["info"];

        $qty = "(prodIngr.quantity / 100)";

        //FETCH NUTRITION FACTS
        $sqlNf = "SELECT  SUM(".$qty." * nutri.fat) AS `Fat/ lipids`, 
                            SUM(".$qty." * nutri.saturatedFat)  AS `Saturated fatty acids`,
                            SUM(".$qty." * nutri.carbohydrate) AS Carbohydrate,
                            SUM(".$qty." * nutri.sugar) AS Sugar, 
                            SUM(".$qty." * nutri.protein) AS Protein,
                            SUM(".$qty." * nutri.sodium) AS Sodium,
                            SUM(".$qty." * nutri.fiber) AS Fiber,
                            SUM(".$qty." * nutri.alcohol) AS Alcohol
                    FROM nutritionalfacts AS nutri
                    JOIN productingredients AS prodIngr 
                        ON prodIngr.ingredientId = nutri.ingredientId 
                    WHERE prodIngr.productId = ".$prodId.";";

        $queryNf = mysqli_query($dbConx, $sqlNf);

        //FETCH ALLERGIC INGREDIENTS
        $sqlAlrg = "SELECT ingr.name AS alrgName,
                           prodIngr.ingredientID AS ingrId
                    FROM (
                            alergiefacts AS alrg LEFT JOIN ingredients AS ingr ON ingr.id = alrg.ingredientID
                         )
                        LEFT JOIN (
                                    SELECT * FROM productIngredients WHERE productId = ".$prodId."
                                  ) AS prodIngr ON prodIngr.ingredientID = alrg.ingredientId";

        $queryAlrg = mysqli_query($dbConx, $sqlAlrg);
        
        //FETCH ADDITIVE INGREDIENTS
        $sqlAdtv = "SELECT ingr.name AS adtvName
                    FROM (
                            ingredients AS ingr JOIN additiveFacts AS adtv ON ingr.id = adtv.ingredientId 
                        )
                        JOIN productIngredients AS prodIngr ON prodIngr.ingredientID = adtv.ingredientId
                    WHERE prodIngr.productId = ".$prodId.";";

        $queryAdtv = mysqli_query($dbConx, $sqlAdtv);
        
        //CHECK IF IN FAVORITE LIST
        $sqlCheckFav = 'SELECT COUNT(*) AS rowNbr 
                        FROM favorites 
                        WHERE userId = '.$user["userId"].' AND productId = '.$prodId;

        $queryCheckFav = mysqli_query($dbConx, $sqlCheckFav);

        $resCheckFav = mysqli_fetch_assoc($queryCheckFav);

        mysqli_free_result($queryCheckFav);
    }
    else
    {
        header("Location: index.php");
        exit();
    }

    include("../MainElements/doctype.html");
?>
<title>Stema</title>

<link rel="stylesheet" type="text/css" href="../MainCss/header.css"/>
<link rel="stylesheet" type="text/css" href="../MainCss/index.css"/>
<link rel="stylesheet" type="text/css" href="../MainCss/productDetails.css"/>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="../MainJs/header.js"></script>
</head>
<body>
<div class="container main-container">
  
    <?php 
      include_once($_SERVER["DOCUMENT_ROOT"]."/Stema/MainElements/header.php");
    ?>
  
  <form method="GET" action="searchRes.php">
    <div class="search-container">
      <input class="search-icon" type="submit" value="">
      <input class="search-bar" type="text" placeholder="Start Typing..." name="userInput">
      <img class="scan-icon" src="../StemaPics/scan-image.png" alt="Scan" onclick="window.location.replace('https:/\/localhost/STEMA/MainPhp/barcodeScanner.php')">
    </div>
  </form>
    
    <div class="prod-name-container border-<?php echo $color;?> background-<?php echo $color;?>">
        <?php 
            echo '<span class="prod-name">'.$prodName.'</span>';
            if($resCheckFav["rowNbr"] == 0)
            {
                echo '<img class="add heart-icon" id="fav_'.$prodId.'" src="../StemaPics/heart-empty.png" alt="add favorite" title="add favorite">';
            }

        ?>
    </div>

    <marquee behavior="scroll" direction="left" scrollamount="10" style="margin-top:10px">
        <?php
            
            while($rowVari = mysqli_fetch_assoc($queryVari))
            {
                $image1 = $rowVari["image1"];
                $image2 = $rowVari["image2"];
                $image3 = $rowVari["image3"];

                if(!empty($image1))
                {
                    echo '<img src="'.$image1.'" width="125" height="82" alt="'.$prodName.'">';
                }
                if(!empty($image2))
                {
                    echo '<img src="'.$image2.'" width="125" height="82" alt="'.$prodName.'">';
                }
                if(!empty($image3))
                {
                    echo '<img src="'.$image3.'" width="125" height="82" alt="'.$prodName.'">';
                }
            }
                
        ?>
    </marquee>

    <div class="facts-container">

        <p class="title">Nutriscore Classification</p>
        <p class="info">
            <span class="letter"><?php echo $score."-" ?></span>
            <span>&nbsp;</span>
            <?php
                $descr = "";
                switch($score)
                {
                    case "A": $descr.="Absence of sugars, salt, lipids";
                        break;
                    case "B": $descr.="No or little of sugars, salt, lipids";
                        break;
                    case "C": $descr.="Litlle quantity of sugars, salt, lipids";
                        break;
                    case "D": $descr.="Considerable quantity of sugars, salt, lipids";
                        break;
                    case "E": $descr.="Extensive amount of sugars, salt, lipids";
                        break;
                }
                echo $descr;
            ?>
        </p>

        <p class="title">Nutritional Scale</p>
        <ul class="nutri-ul">
        <?php
            while($rowNf = mysqli_fetch_assoc($queryNf))
            {
                foreach($rowNf as $key=>$value)
                {
                    if($value != 0)
                    {
                        
                        echo '<span><li>'.$key.': '.number_format($value, 1).'g</li></span>';
                    }
                }
            }
            mysqli_free_result($queryNf);
        ?>
        </ul>

        <p class="title">Allergic Contents</p>
        
        <ul class="alrg-ul">
            <?php
                while($rowAlrg = mysqli_fetch_assoc($queryAlrg))
                {
                    $style = ($rowAlrg["ingrId"] != NULL) ? 'style="text-decoration: underline;"' : "" ;
                    
                    echo '<li '.$style.'>'.$rowAlrg["alrgName"].'</li>';
                }
                mysqli_free_result($queryAlrg);

            ?>
        </ul>

        <?php
            if(mysqli_num_rows($queryAdtv) != 0)
            {
                echo '<p class="title">Additives</p>';
                echo '<div class="adtv-container">';
                
                while($rowAdtv = mysqli_fetch_assoc($queryAdtv))
                {
                    echo '<span class="adtv">'.$rowAdtv["adtvName"].'</span>';
                }

                echo '</div>';
                mysqli_free_result($queryAdtv);
            }
        ?>

        <p class="title">Original Informations</p>
        
        <?php
            echo '<p class="info">'.$info.'</p>';
        ?>

    </div>
</div>

<script>
        //ADD FAVORITE TO DB AND UPDATE HTML 
        $(document).ready(function()
        {
            //ADD 
            $('.add').click(function()
            {
                $(this).attr("src", "../StemaPics/heart-full.png");

                let el = this;
                let id = this.id;
                let splitid  = id.split("_");
                
                //IDS
                let prodId = splitid[1];
                let userId = <?php echo $user["userId"]?>;
                console.log(prodId);
                console.log(userId);
                //AJAX REQUEST
                $.ajax(
                {
                    url: 'addFavorite.php',
                    type: 'POST',
                    data: { prodId: prodId, userId: userId },
                    success: function(response)
                    {
                        if(response == 1)
                        {
                            $('.heart-icon').fadeOut(300, function()
                            {
                                $('.heart-icon').remove();
                            });
                        }
                    }
                });
            });
        });
    </script>

</body>
</html> 
