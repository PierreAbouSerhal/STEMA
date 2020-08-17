<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/STEMA/PhpUtils/checkLoginStatus.php");

    if(!$user["userOk"] || !$isAdmin)
    {
        logout();
        // PAGE NO LONGER AVAILABLE FOR LOGGED OUT USER
        header("Location: ../MainPHP/index.php");
        exit();
    }

    $phpSelf = $_SERVER["PHP_SELF"];
    $placeHolder = "Search Ingredients";

    $fop = "true";
    $userInput = "";

    if(isset($_POST["userInput"]) && !empty($_POST["userInput"]))
    {
        $userInput = mysqli_escape_string($dbConx, $_POST["userInput"]);
        $fop = "ingredients.name LIKE '".$userInput."%'";
    }

    $sqlSrch = "SELECT * 
                FROM ingredients
                WHERE ".$fop.
                " ORDER BY Name";

    $querySrch = mysqli_query($dbConx, $sqlSrch);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <script src="https://kit.fontawesome.com/3571b2f364.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" type="text/css" href="../MainCss/header.css"/>
    <link rel="stylesheet" type="text/css" href="../MainCss/searchBar.css"/>
    <link rel="stylesheet" type="text/css" href="../MainCss/manage.css"/>
    <script src="https://code.iconify.design/1/1.0.7/iconify.min.js"></script>
    <script src="../MainJs/header.js"></script>
    
    <title>Manage Ingredients</title>
</head>
<body>
    <div class="container main-container">
        
        <?php 
            include_once($_SERVER["DOCUMENT_ROOT"]."/Stema/MainElements/adminHeader.php");
            include_once($_SERVER["DOCUMENT_ROOT"]."/Stema/MainElements/searchBar.php");
        ?>
        <div class="ingr-container">
            <table class="table table-striped">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Ingredient Name</th>
                    <th scope="col" style="text-align: center;">Options</th>
                    </tr>
                </thead>
                <tbody>
        <?php
            $lineId = 0;
            while($resSrch = mysqli_fetch_assoc($querySrch))
            {
                $ingrId    = $resSrch["id"];
                $ingrName  = $resSrch["name"];
                $lineId += 1;

                echo '<tr>
                        <th scope="row">'.$lineId.'</th>
                        <td>'.$ingrName.'</td>
                        <td class="option-col">
                            <a href="ingrs.php?isNew=0&id='.$ingrId.'">
                                <i class="far fa-edit" title="Edit Ingredient"></i>
                            </a>
                            <a href="">
                                <i class="far fa-trash-alt" title="Delete Ingredient"></i>
                            </a>
                        </td>
                      </tr>';
            }
        ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>