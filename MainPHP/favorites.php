<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/STEMA/PhpUtils/checkLoginStatus.php");

    if(!$user["userOk"])
    {//AUTOMATIC LOGOUT
        logout();
        // PAGE NO LONGER AVAILABLE FOR LOGGED OUT USER
        header("Location: index.php");
        exit();
    }

    $sql = "SELECT
                prod.id AS prodId,
                prod.name AS prodName,
                prod.nutriscore AS score,
                brands.name AS brdName
            FROM
                (
                    products AS prod JOIN favorites AS fav ON prod.id = fav.productId
                ) 
                JOIN productbrands AS brands ON brands.id = prod.brandId 
            WHERE 
                fav.userId = ".$user["userId"].";";

    $query = mysqli_query($dbConx, $sql);

    $rowNbr = mysqli_num_rows($query);

    if($rowNbr == 0)
    {
        $msg = '<div class="empty-list">
                    Your list of favorite products is currently empty, search for things you love and click the heart button to see items populate your list every day.
                </div>
                ';
    }

    include("../MainElements/doctype.html");
?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <link rel="stylesheet" type="text/css" href="../MainCss/header.css"/>
    <link rel="stylesheet" type="text/css" href="../MainCss/variants.css"/>

    <script src="../MainJs/header.js"></script>
    
    <title>Favorites</title>
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
                        $prodId   = $row["prodId"];
                        $prodName = $row["prodName"];
                        $score    = $row["score"];
                        $brdName  = $row["brdName"];
                        
                        $color    = strtolower($score);

                        echo '
                        <div class="variant-wrapper-fav border-'.$color.'">
                            <div class="variant-fav" onclick="location.href = \'productDetails.php?prodId='.$prodId.'\';">
                                <span class="letter '.$color.'">'.$score.'</span>
                                <div class="names">
                                    <span class="prod-vari-name">'.$prodName.'</span>
                                    <span class="brand-name">'.$brdName.'</span>
                                </div>
                            </div>        
                            <img class="delete heart-icon" id="fav_'.$prodId.'" src="../StemaPics/heart-full.png" alt="remove" title="remove">
                        </div>
                        ';
                    }
                    mysqli_free_result($query);
            }
        ?>
        </div> 
           
    </div>
    
    <script>
        //REMOVE FAVORITE FROM DB AND UPDATE HTML 
        $(document).ready(function()
        {
            //DELETE 
            $('.delete').click(function()
            {
                let el = this;
                let id = this.id;
                let splitid = id.split("_");

                //IDS
                let prodId = splitid[1];
                let userId = <?php echo $user["userId"]?>;

                //AJAX REQUEST
                $.ajax(
                {
                    url: 'removeFavorite.php',
                    type: 'POST',
                    data: { prodId: prodId, userId: userId },
                    success: function(response)
                    {
                        if(response == 1)
                        {
                            //REMOVE VARIANT DIV FROM HTML
                            $(el).closest('div').css('background','tomato');
                            $(el).closest('div').fadeOut(800,function()
                            {
                                $(this).remove();
                            });
                        }
                        else
                        {
                            alert("Unable to remove variant from favorite list");
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>