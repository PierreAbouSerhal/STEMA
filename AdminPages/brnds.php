<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/STEMA/PhpUtils/checkLoginStatus.php");
    
    if(!$user["userOk"] || !$isAdmin)
    {
        logout();
        // PAGE NO LONGER AVAILABLE FOR LOGGED OUT USER
        header("Location: ../MainPHP/index.php");
        exit();
    }
    $pageIdx = "BRD";
    if(isset($_GET["id"]) && !empty($_GET["id"]))//EDIT INGREDIENT
    {
        $brandId = mysqli_real_escape_string($dbConx, $_GET["id"]);
    }
    else//ADD INGREDIENT
    {
        $brandId = -1;
    }

    //PAGE VARIABLES
    $arrGeneral = array();

    //ON SUMBIMT
    if(isset($_POST["save"]))
    {
        $brandName    = mysqli_real_escape_string($dbConx, $_POST["Name"]);

        if(!empty($brandName))
        {
            $sqlBrandCheck    = "SELECT *, COUNT(*) AS rowNbr FROM productbrands WHERE id = ".$brandId;
            
            $queryBrandCheck  = mysqli_query($dbConx, $sqlBrandCheck);
            
            $resBrandCheck    = mysqli_fetch_assoc($queryBrandCheck);
            
            mysqli_free_result($queryBrandCheck);
           
            //SQL VARIABLES
            $sqlBrnd = "";

            if($resBrandCheck["rowNbr"] == 1)
            {
                $sqlBrnd = 'UPDATE productBrands SET name = "'.$brandName.'"
                            WHERE productBrands.id = '.$brandId;
                            
                $queryBrnd = mysqli_query($dbConx, $sqlBrnd);
            }
            else
            {
                $sqlBrnd = 'INSERT INTO productBrands (name)
                                VALUES ("'.$brandName.'")';

                $queryBrnd = mysqli_query($dbConx, $sqlBrnd);
                
                if($queryBrnd)
                {
                    //GET THE NEW CREATED ID
                    $brandId = mysqli_insert_id($dbConx);
                }
                else
                {
                    //FAILED TO INSERT INTO THE DATABASE
                    header("Location: manage.php?idx=".$pageIdx);
                    exit();
                }
            }
        }
    }

    //REFRESH PAGE
    $sqlBrnd = "SELECT pb.name AS Name
                FROM productBrands AS pb
                WHERE pb.id = ".$brandId.";";

    $queryBrnd = mysqli_query($dbConx, $sqlBrnd);
    
    if(mysqli_num_rows($queryBrnd) == 1)
    {
        $resBrnd = mysqli_fetch_assoc($queryBrnd);

        foreach($resBrnd as $key => $value)
        {
            $arrGeneral[$key] = $value;
        }
    }
    else
    {
        $resBrnd = mysqli_fetch_fields($queryBrnd);

        foreach($resBrnd as $field)
        {   
            $arrGeneral[$field->name] = "";
        }
    }
    
    //PAGE TITLE
    $title = (!empty($arrGeneral["Name"])) ? "Edit ".$arrGeneral["Name"] : "Add Brand";

    mysqli_free_result($queryBrnd);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="../MainCss/header.css"/>
    <link rel="stylesheet" type="text/css" href="../MainCss/manage.css"/>

    <script src="https://code.iconify.design/1/1.0.7/iconify.min.js"></script>
    <script src="../MainJs/header.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="../MainJs/formValidation.js"></script>

    <title><?php echo $title;?></title>
</head>
    <body>
        <div class="container main-container">
            
            <?php 
                include_once($_SERVER["DOCUMENT_ROOT"]."/Stema/MainElements/adminHeader.php");
            ?>
            <h4 class="section">General Info.</h4>
            
            <form class="form" action="<?php $action = $_SERVER["PHP_SELF"]."?id=".$brandId; echo $action;?>" method="POST" enctype="multipart/form-data" onsubmit=" return validateBrnds();">
                <?php
                    foreach($arrGeneral as $key=>$value)
                    {
                        echo '<div class="input-row">
                                <span class="input-label">'.$key.':</span>
                                <input id="'.str_replace(' ', '', $key).'" class="form-control form-control-sm input" type="text" value="'.$value.'" name="'.str_replace(' ', '', $key).'">
                            </div>
                            <div class="error-message-container">
                                <span id="errorName" class="error-message"></span>
                            </div>
                            ';
                    }
                ?>
            <input type="submit" class="button-save" value="Save" name="save">
            </form>
        </div>
    </body>
</html>