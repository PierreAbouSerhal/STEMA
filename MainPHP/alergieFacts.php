<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/STEMA/PhpUtils/checkLoginStatus.php");

    if(!$user["userOk"])
    {//AUTOMATIC LOGOUT
      logout();
    }
    
    $sql = "SELECT
                alrg.symptoms AS symp,
                alrg.healthRisk AS risk,
                ingr.name,
                ingr.image
            FROM 
                alergieFacts AS alrg,
                ingredients AS ingr
            WHERE 
                alrg.ingredientId = ingr.id
            ORDER BY ingr.name ;";

    $query = mysqli_query($dbConx, $sql);   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alergie Facts</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

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
                $symp  = $row["symp"];
                $risk  = $row["risk"];
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
                        <div class="alrg-facts">
                            <div class="symptoms-container">
                                <div class="sml-title">
                                    Symptoms
                                </div>
                                <div class="details">
                                    '.$symp.'
                                </div>
                            </div>
                            <div class="risks-container">
                                <div class="sml-title">
                                    Health Risks
                                </div>
                                <div class="details">
                                    '.$risk.'
                                </div>
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