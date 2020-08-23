<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/STEMA/PhpUtils/checkLoginStatus.php");

    if(!$user["userOk"])
    {//AUTOMATIC LOGOUT
      logout();
    }
    
    if(isset($_GET["userInput"]) && !empty($_GET["userInput"]))
    {
        $userInput = mysqli_real_escape_string($dbConx, $_GET["userInput"]);
        $emptyMsg = "Nothing Found. Sorry, but no results were found in our database.";

        $sql = "SELECT
                    vari.id AS variId,
                    vari.name AS variName,
                    vari.volume AS vol,
                    vari.image1 AS img1,
                    prod.nutriscore AS score,
                    prod.name AS prodName,
                    brands.name AS brdName
                FROM
                    (
                        variants AS vari JOIN products AS prod ON vari.productID = prod.id
                    )
                    JOIN productbrands AS brands ON brands.id = prod.brandId
                WHERE
                    prod.name LIKE '".$userInput."%' OR vari.name LIKE '".$userInput."%' OR vari.barcode = '".$userInput."'  
                ";

        $query = mysqli_query($dbConx, $sql);
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
<link rel="stylesheet" type="text/css" href="../MainCss/variants.css"/>

<script src="../MainJs/header.js"></script>
</head>
<body>
<div class="container main-container">
  
    <?php 
      include_once($_SERVER["DOCUMENT_ROOT"]."/Stema/MainElements/header.php");
    ?>
  
  <form method="GET" action=<?php echo $_SERVER["PHP_SELF"] ?>>
    <div class="search-container">
      <input class="search-icon" type="submit" value="">
      <input class="search-bar" type="text" placeholder="Start Typing..." name="userInput" value="<?php $inpt = (isset($_GET["userInput"])) ? $userInput : "" ; echo $inpt?>">
      <img class="scan-icon" src="../StemaPics/scan-image.png" alt="Scan" onclick="window.location.replace('https:/\/localhost/STEMA/MainPhp/barcodeScanner.php')">
    </div>
  </form>
  <div class="variants-container">
    <?php
        if(mysqli_num_rows($query) == 0)
        {
            echo "<p>".$emptyMsg."</p>";
        }
        while($row = mysqli_fetch_assoc($query))
        {
            $variId   = $row["variId"];
            $variName = $row["variName"];
            $vol      = $row["vol"];
            $prodName = $row["prodName"];
            $score    = $row["score"];
            $brdName  = $row["brdName"];

            $image    = (!empty($row["img1"])) ? '<img style="height:50px;width:50px" src="'.$row["img1"].'">' : '';
            $color    = strtolower($score);
            
            echo '  
                <div class="variant border-'.$color.'" onclick="location.href = \'productDetails.php?variId='.$variId.'\';">
                    <span class="letter '.$color.'">'.$score.'</span>
                    <div class="names">
                        <span class="prod-vari-name">'.$prodName.' '.$variName.'</span>
                        <span class="brand-name">'.$brdName.' '.$vol.'</span>
                    </div>
                    '.$image.'
                </div>';
        }

        mysqli_free_result($query);
    ?>
  </div>

</div>


</body>
</html> 
