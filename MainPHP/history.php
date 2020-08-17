<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/STEMA/PhpUtils/checkLoginStatus.php");

    if(!$user["userOk"])
    {//AUTOMATIC LOGOUT
        logout();
        // PAGE NO LONGER AVAILABLE FOR LOGGED OUT USER
        header("Location: index.php");
        exit();
    }

    $sql = "    SELECT 
                    vari.id AS variId,
                    vari.name AS variName,
                    vari.volume AS vol,
                    vari.image1 AS image1,
                    prod.name AS prodName,
                    prod.nutriscore AS score,
                    brands.name AS brdName,
                    history.viewDate AS date,
                    history.viewTime AS time 
                FROM (
                        (
                             variants AS vari JOIN history ON vari.id = history.variantId 
                        ) 
                        JOIN products AS prod ON vari.productId = prod.id 
                     ) 
                    JOIN productbrands AS brands ON brands.id = prod.brandId
                WHERE 
                    history.userId = ".$user["userId"]." 
                ORDER BY 
                    date DESC, 
                    time DESC 
                LIMIT 50 ;
                ";

    $query = mysqli_query($dbConx, $sql);

    $rowNbr = mysqli_num_rows($query);

    if($rowNbr == 0)
    {
        $msg = '<div class="empty-list">
                    Your history is currently empty, search for things you love and click on products to see them populate your list every day.
                </div>
                ';
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="../MainCss/header.css"/>
    <link rel="stylesheet" type="text/css" href="../MainCss/variants.css"/>

    <script src="../MainJs/header.js"></script>
    
    <title>History</title>
</head>
<body>

    <div class="container main-container">
        
            <?php 
            include_once($_SERVER["DOCUMENT_ROOT"]."/Stema/MainElements/header.php");
            ?>
        

        <div class="variants-container">
        <?php
            if(isset($msg))
            {
                echo $msg;
            }
            else
            {
                while($row = mysqli_fetch_assoc($query))
                    {
                        $variId = $row["variId"];
                        $variName = $row["variName"];
                        $vol = $row["vol"];
                        $image1 = $row["image1"];
                        $prodName = $row["prodName"];
                        $score = $row["score"];
                        $brdName = $row["brdName"];
                        $date = $row["date"];
                        $time = $row["time"];
                        $color = strtolower($score);

                        echo '
                            <div class="variant border-'.$color.'" style="height: 110px">
                                <span class="letter '.$color.'">'.$score.'</span>
                                <div class="names" style="height: 60px">
                                    <a href="productDetails.php?variId='.$variId.'" style="color: black; text-decoration: none">
                                        <span class="product-name">'.$prodName.'</span>
                                        <span class="variant-name">'.$variName.' '.$vol.'</span>
                                    </a>
                                </div>
                                <div class="date-time">
                                    <span>Seen in: '.$date.'</span>
                                    <span>&nbsp</span>
                                    <span>At: '.$time.'</span>
                                </div>
                            </div>
                        ';
                    }

                    mysqli_free_result($query);
            }
        ?>
        </div> 
           
    </div>
</body>
</html>