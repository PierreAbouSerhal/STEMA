<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/STEMA/PhpUtils/checkLoginStatus.php");

    if(!$user["userOk"])
    {//AUTOMATIC LOGOUT
      logout();
    }
    
    $sql = "SELECT
            adtv.taste,
            adtv.color,
            adtv.healthRisk AS risk,
            adtv.formula,
            ingr.name,
            ingr.image
        FROM 
            additiveFacts AS adtv,
            ingredients AS ingr
        WHERE 
            adtv.ingredientId = ingr.id
        ORDER BY ingr.name ;";

    $query = mysqli_query($dbConx, $sql);   

    include("../MainElements/doctype.html");
?>

    <title>Additive Facts</title>

    <link rel="stylesheet" type="text/css" href="../MainCss/header.css"/>
    <link rel="stylesheet" type="text/css" href="../MainCss/facts.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <script src="../MainJs/header.js"></script>
    <script src="../MainJs/letterNavig.js"></script>

</head>

    <body onload="createNavigationList();">
        <div class="container main-container">
            <?php 
                include_once($_SERVER["DOCUMENT_ROOT"]."/Stema/MainElements/header.php");
            ?>
        
        <div id="nav" class="CharacterContainer"></div>

        <?php
            while($row = mysqli_fetch_assoc($query))
            {
                $name  = $row["name"];
                $image = $row["image"];
                $taste = $row["taste"];
                $color = $row["color"];
                $risk  = $row["risk"];
                $frmla = $row["formula"];
                $id    = strtoupper($name[0]);

                echo '
                    <div class="ingredient" id='.$id.'>
                        <div class="ingredient-header">
                        ';

                        if(empty($image))
                        {
                            echo '<span class="ingredient-img"></span>';
                        }
                        else
                        {
                            echo '<img class="ingredient-img" src="'.$image.'">';
                        }
                    echo '<span class="ingredient-name">'.$name.'</span><span></span>
                        </div>
                        <div class="adtv-facts">
                            <div class="adtv-prop">
                                <span class="prop">Taste:</span>
                                <span class="prop-info">'.$taste.'</span>
                            </div>
                            <div class="adtv-prop">
                                <span class="prop">Color:</span>
                                <span class="prop-info">'.$color.'</span>
                            </div>
                            <div class="adtv-prop">
                                <span class="prop">Health Risk:</span>
                                <span class="prop-info">'.$risk.'</span>
                            </div>
                            <div class="adtv-prop">
                                <span class="prop">Formula:</span>
                                <span class="prop-info">'.$frmla.'</span>
                            </div>
                        </div>
                    </div>
                ';
            }
            mysqli_free_result($query);
        ?>
        </div>
  </body>
</html>