<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/STEMA/PhpUtils/checkLoginStatus.php");

    if(!$user["userOk"])
    {//AUTOMATIC LOGOUT
      logout();
    }
    
    $sql = "SELECT
                facts.calories AS Calories,
                facts.fat AS Fat,
                facts.saturatedFat AS `Saturated Fat`,
                facts.carbohydrate AS Carbs,
                facts.sugar AS Sugar,
                facts.protein AS Protein,
                facts.sodium AS Sodium,
                facts.fiber AS Fiber,
                facts.alcohol AS Alcohol,
                ingr.name,
                ingr.image
            FROM 
                nutritionalfacts AS facts,
                ingredients AS ingr
            WHERE 
                facts.ingredientId = ingr.id
            ORDER BY ingr.name ;";

    $query = mysqli_query($dbConx, $sql);
    
    include("../MainElements/doctype.html");
?>
    <title>Nutrition Facts</title>

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

        <div class="info">
            <span>Nutrition Facts</span>
            <span>Per 100 g</span>
        </div>

        <?php
            while($row = mysqli_fetch_assoc($query))
            {
                $name  = $row["name"];
                $image = $row["image"];
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

                    <div class="nutrition-facts">'
                    ;
        
                    foreach($row as $key => $value)
                    {
                        if($key != "name" && $key != "image" && !empty($value))
                        {
                            if($key == "Calories" || $key == "Fat" || $key == "Carbs")
                            {
                                $style = 'style = "font-weight: bold"';
                            }
                            else
                            {
                                $style = '';
                            }
                            echo '
                                <div class="fact">
                                    <span '.$style.'>'.$key.'</span>
                                    <span '.$style.'>'.$value.'</span>
                                </div>
                            ';
                        }
                    }
            echo '
                    </div>
                </div>
            ';
            }

            mysqli_free_result($query);
        ?>
        </div>
  </body>
</html>