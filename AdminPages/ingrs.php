<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/STEMA/PhpUtils/checkLoginStatus.php");
    
    if(!$user["userOk"] || !$isAdmin)
    {
        logout();
        // PAGE NO LONGER AVAILABLE FOR LOGGED OUT USER
        header("Location: ../MainPHP/index.php");
        exit();
    }
    $pageIdx = "ING";
    if(isset($_GET["id"]) && !empty($_GET["id"]))//EDIT INGREDIENT
    {
        $ingId = mysqli_real_escape_string($dbConx, $_GET["id"]);
    }
    else//ADD INGREDIENT
    {
        $ingId = -1;
    }

    //PAGE VARIABLES
    $arrGeneral = $arrNutri = $arrAlrg = $arrAdtv = array();

    //ON SUMBIMT
    if(isset($_POST["save"]))
    {
        $ingrName = mysqli_real_escape_string($dbConx, $_POST["Name"]);
        $cal      = mysqli_real_escape_string($dbConx, $_POST["Calories"]);
        $fat      = mysqli_real_escape_string($dbConx, $_POST["Fat"]);
        $sFat     = mysqli_real_escape_string($dbConx, $_POST["SaturatedFat"]);
        $carbs    = mysqli_real_escape_string($dbConx, $_POST["Carbs"]);
        $sugar    = mysqli_real_escape_string($dbConx, $_POST["Sugar"]);
        $protein  = mysqli_real_escape_string($dbConx, $_POST["Protein"]);
        $sodium   = mysqli_real_escape_string($dbConx, $_POST["Sodium"]);
        $fiber    = mysqli_real_escape_string($dbConx, $_POST["Fiber"]);
        $alcohol  = mysqli_real_escape_string($dbConx, $_POST["Alcohol"]);
        $symptoms = mysqli_real_escape_string($dbConx, $_POST["Symptoms"]);
        $riskAlrg = mysqli_real_escape_string($dbConx, $_POST["AllergyRisk"]);
        $taste    = mysqli_real_escape_string($dbConx, $_POST["Taste"]);
        $color    = mysqli_real_escape_string($dbConx, $_POST["Color"]);
        $riskAdtv = mysqli_real_escape_string($dbConx, $_POST["AdditiveRisk"]);
        $formula  = mysqli_real_escape_string($dbConx, $_POST["Formula"]);

        if(!empty($ingrName) && (is_numeric($cal)    || empty($cal))    && (is_numeric($fat)   || empty($fat))   && (is_numeric($sFat)    || empty($sFat)) && 
                                     (is_numeric($carbs)  || empty($carbs))  && (is_numeric($sugar) || empty($sugar)) && (is_numeric($protein) || empty($protein)) && 
                                     (is_numeric($sodium) || empty($sodium)) && (is_numeric($fiber) || empty($fiber)) && (is_numeric($alcohol) || empty($alcohol))
        )
        {
            $sqlIngrCheck    = "SELECT * ,COUNT(*) AS rowNbr FROM Ingredients WHERE id = ".$ingId;
            
            $queryIngrCheck  = mysqli_query($dbConx, $sqlIngrCheck);
            
            $resIngrCheck    = mysqli_fetch_assoc($queryIngrCheck);
            
            mysqli_free_result($queryIngrCheck);

            $ingrImg = (empty($resIngrCheck["image"])) ? 'NULL' : '"'.$resIngrCheck["image"].'"';
            //UPLOAD THE IMAGE
            if(file_exists($_FILES['ingr-img']['tmp_name']) || is_uploaded_file($_FILES['ingr-img']['tmp_name']))
            {
                $target_dir    = "../IngredientsPics/";
                $target_file   = $target_dir . basename($_FILES["ingr-img"]["name"]);
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                $check         = getimagesize($_FILES["ingr-img"]["tmp_name"]);//CHECK IF FALSE IMAGE
                $uploadOk      = 1;

                if($check === false || $_FILES["ingr-img"]["size"] > 500000 ||
                  ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif")) 
                {
                    $uploadOk = 0;
                }

                //DELETE IMAGE IF EXISTS
                if(file_exists($target_file))
                { 
                    unlink($target_file);
                }

                if($uploadOk == 1)
                {
                    move_uploaded_file($_FILES["ingr-img"]["tmp_name"], $target_file);
                    $ingrImg = '"/Stema/IngredientsPics/'.basename($_FILES["ingr-img"]["name"]).'"';
                }
            }

            //SQL VARIABLES
            $sqlIngr = $sqlNutri = $sqlAlrg = $sqlAdtv = "";

            if($resIngrCheck["rowNbr"] == 1)
            { 
                $sqlIngr = 'UPDATE ingredients SET name = "'.$ingrName.'", image = '.$ingrImg.'
                                WHERE ingredients.id = '.$ingId;
                

                $queryIngr = mysqli_query($dbConx, $sqlIngr);
            }
            else
            {
                $sqlIngr = 'INSERT INTO ingredients (name, image)
                                VALUES ("'.$ingrName.'", '.$ingrImg.')';
                

                $queryIngr = mysqli_query($dbConx, $sqlIngr);

                if($queryIngr)
                {
                    //GET THE NEW CREATED ID
                    $ingId = mysqli_insert_id($dbConx);
                    $resIngrCheck["rowNbr"] = 1;
                } 
                else
                {
                    //FAILED TO INSERT INTO THE DATABASE
                    header("Location: manage.php?idx=".$pageIdx);
                    exit();
                }
            }

            $sqlNutriCheck   = "SELECT COUNT(*) AS rowNbr FROM nutritionalFacts WHERE ingredientId = ".$ingId;
            $sqlAlrgCheck    = "SELECT COUNT(*) AS rowNbr FROM alergiefacts     WHERE ingredientId = ".$ingId;
            $sqlAdtvCheck    = "SELECT COUNT(*) AS rowNbr FROM additivefacts    WHERE ingredientId = ".$ingId;

            $queryNutriCheck = mysqli_query($dbConx, $sqlNutriCheck);
            $queryAlrgCheck  = mysqli_query($dbConx, $sqlAlrgCheck);
            $queryAdtvCheck  = mysqli_query($dbConx, $sqlAdtvCheck);

            $resNutriCheck   = mysqli_fetch_assoc($queryNutriCheck);
            $resAlrgCheck    = mysqli_fetch_assoc($queryAlrgCheck);
            $resAdtvCheck    = mysqli_fetch_assoc($queryAdtvCheck);

            mysqli_free_result($queryNutriCheck);
            mysqli_free_result($queryAlrgCheck);
            mysqli_free_result($queryAdtvCheck);

            $cal     = (empty($cal)) ? "NULL": $cal;
            $fat     = (empty($fat)) ? "NULL": $fat;
            $sFat    = (empty($sFat)) ? "NULL": $sFat;
            $carbs   = (empty($carbs)) ? "NULL": $carbs;
            $sugar   = (empty($sugar)) ? "NULL": $sugar;
            $protein = (empty($protein)) ? "NULL": $protein;
            $sodium  = (empty($sodium)) ? "NULL": $sodium;
            $fiber   = (empty($fiber)) ? "NULL": $fiber;
            $alcohol = (empty($alcohol)) ? "NULL": $alcohol;

            if($resIngrCheck["rowNbr"] == 1 && $resNutriCheck["rowNbr"] == 1)
            {
                $sqlNutri = 'UPDATE nutritionalfacts SET calories = '.$cal.', fat = '.$fat.', saturatedFat = '.$sFat.',
                                    carbohydrate = '.$carbs.', sugar = '.$sugar.', protein = '.$protein.',
                                    sodium = '.$sodium.', fiber = '.$fiber.', alcohol = '.$alcohol.'
                            WHERE nutritionalfacts.ingredientId = '.$ingId.';';
            }
            else if($resIngrCheck["rowNbr"] == 1 && $resNutriCheck["rowNbr"] == 0)
            {
                $sqlNutri = 'INSERT INTO nutritionalfacts (ingredientId, calories, fat, saturatedFat, carbohydrate, sugar, protein, sodium, fiber, alcohol)
                                VALUES ('.$ingId.' ,'.$cal.', '.$fat.', '.$sFat.', '.$carbs.', '.$sugar.', '.$protein.', '.$sodium.', '.$fiber.', '.$alcohol.')';
            }

            if($resIngrCheck["rowNbr"] == 1 && $resAlrgCheck["rowNbr"] == 1)
            {
                $sqlAlrg = 'UPDATE alergiefacts SET symptoms = "'.$symptoms.'", healthRisk = "'.$riskAlrg.'"
                            WHERE alergiefacts.ingredientId = '.$ingId.';';
            }
            else if($resIngrCheck["rowNbr"] == 1 && $resAlrgCheck["rowNbr"] == 0 &&
                    (!empty($symptoms) || !empty($riskAlrg))
                   )
            {
                $sqlAlrg = 'INSERT INTO alergiefacts (ingredientId, symptoms, healthRisk) 
                                VALUES ('.$ingId.', "'.$symptoms.'", "'.$riskAlrg.'")';
            }
            
            if($resIngrCheck["rowNbr"] == 1 && $resAdtvCheck["rowNbr"] == 1)
            {
                $sqlAdtv = 'UPDATE additivefacts SET taste = "'.$taste.'", color = "'.$color.'", healthRisk = "'.$riskAdtv.'", formula = "'.$formula.'"  
                            WHERE additivefacts.ingredientId = '.$ingId.';';
            }
            else if($resIngrCheck["rowNbr"] == 1 && $resAdtvCheck["rowNbr"] == 0 &&
                    (!empty($taste) || !empty($color) || !empty($riskAdtv) || !empty($formula))
                   )
            {
                $sqlAdtv = 'INSERT INTO additivefacts (ingredientId, taste, color, healthRisk, formula)
                                VALUES ('.$ingId.', "'.$taste.'", "'.$color.'", "'.$riskAdtv.'", "'.$formula.'")';
            }
            
            $queryNutri = (!empty($sqlNutri)) ? mysqli_query($dbConx, $sqlNutri) : "";
            $queryAlrg  = (!empty($sqlAlrg))  ? mysqli_query($dbConx, $sqlAlrg)  : "";
            $queryAdtv  = (!empty($sqlAdtv))  ? mysqli_query($dbConx, $sqlAdtv)  : "";

        }
    }

    //REFRESH PAGE
    $sqlIngr = "SELECT ingr.name AS Name,
                        ingr.image
                FROM ingredients AS ingr
                WHERE ingr.id = ".$ingId.";";

    $queryIngr = mysqli_query($dbConx, $sqlIngr);
    
    if(mysqli_num_rows($queryIngr) == 1)
    {
        $resIngr = mysqli_fetch_assoc($queryIngr);

        foreach($resIngr as $key => $value)
        {
            $arrGeneral[$key] = $value;
        }
    }
    else
    {
        $resIngr = mysqli_fetch_fields($queryIngr);

        foreach($resIngr as $field)
        {   
            $arrGeneral[$field->name] = "";
        }
    }
    
    //PAGE TITLE
    $title = (!empty($arrGeneral["Name"])) ? "Edit ".$arrGeneral["Name"] : "Add Ingredient";

    mysqli_free_result($queryIngr);

    $sqlNutri = "SELECT
                    facts.calories AS Calories,
                    facts.fat AS Fat,
                    facts.saturatedFat AS `Saturated Fat`,
                    facts.carbohydrate AS Carbs,
                    facts.sugar AS Sugar,
                    facts.protein AS Protein,
                    facts.sodium AS Sodium,
                    facts.fiber AS Fiber,
                    facts.alcohol AS Alcohol
                    FROM 
                    nutritionalfacts AS facts
                    WHERE 
                    facts.ingredientId = ".$ingId.";";

    $queryNutri = mysqli_query($dbConx, $sqlNutri);

    if(mysqli_num_rows($queryNutri) == 1)
    {
        $resNutri = mysqli_fetch_assoc($queryNutri);

        foreach($resNutri as $key => $value)
        {
            $arrNutri[$key] = $value;
        }
    }
    else
    {
        $resNutri = mysqli_fetch_fields($queryNutri);

        foreach($resNutri as $field)
        {
            $arrNutri[$field->name] = "";
        }
    }
    mysqli_free_result($queryNutri);

    $sqlAlrg = "SELECT
                    alrg.symptoms AS Symptoms,
                    alrg.healthRisk AS `Allergy Risk`
                FROM 
                    alergieFacts AS alrg
                WHERE 
                    alrg.ingredientId = ".$ingId.";";
    
    $queryAlgr = mysqli_query($dbConx, $sqlAlrg);

    if(mysqli_num_rows($queryAlgr) == 1)
    {
        $resAlgr = mysqli_fetch_assoc($queryAlgr);

        foreach($resAlgr as $key => $value)
        {
            $arrAlrg[$key] = $value;
        }
    }
    else
    {
        $resAlgr = mysqli_fetch_fields($queryAlgr);

        foreach($resAlgr as $field)
        {
            $arrAlrg[$field->name] = "";
        }
    }
    mysqli_free_result($queryAlgr);

    $sqlAdtv = "SELECT
                    adtv.taste AS Taste,
                    adtv.color AS Color,
                    adtv.healthRisk AS `Additive Risk`,
                    adtv.formula AS Formula
                FROM 
                    additiveFacts AS adtv
                WHERE 
                    adtv.ingredientId = ".$ingId.";";

    $queryAdtv = mysqli_query($dbConx, $sqlAdtv);

    if(mysqli_num_rows($queryAdtv) == 1)
    {
        $resAdtv = mysqli_fetch_assoc($queryAdtv);

        foreach($resAdtv as $key => $value)
        {
            $arrAdtv[$key] = $value;
        }
    }
    else
    {
        $resAdtv = mysqli_fetch_fields($queryAdtv);

        foreach($resAdtv as $field)
        {
            $arrAdtv[$field->name] = "";
        }
    }
    mysqli_free_result($queryAdtv); 

    include("../MainElements/doctype.html");
?>
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
            
            <form class="form" action="<?php $action = $_SERVER["PHP_SELF"]."?id=".$ingId; echo $action;?>" method="POST" enctype="multipart/form-data" onsubmit=" return validateIngrs();">
                <div class="input-row">
                    <span class="input-label">Name:</span>
                    <input id="IngrName" class="form-control form-control-sm input" type="text" value="<?php echo $arrGeneral["Name"];?>" name="Name">
                </div>
                <div class="error-message-container">
                    <span id="errorName" class="error-message"></span>
                </div>
                <div class="input-row">
                    <div style="margin-top: 20px;">
                        <span class="input-label">Image:</span>
                        <div class="error-message-container">
                            <span id="errorImage" style = "margin: 0" class="error-message"></span>
                        </div>
                    </div>
                    <div class="image-upload">
                        <label for="file-input">
                        <div class="upload-icon">
                            <img class="icon" src="<?php $src = (empty($arrGeneral["image"])) ? "https://image.flaticon.com/icons/png/128/61/61112.png" : $arrGeneral["image"]; echo $src?>">
                        </div>
                        </label>
                    <input id="file-input" type="file" name="ingr-img"/>
                    </div>
                </div>
                <h4 class="section">Nutritional Info/100g</h4>
            <?php
                $nutriNbr = 0; //NUTRI FACT INPUT NUMBER

                foreach($arrNutri as $key=>$value)
                {
                    echo '<div class="input-row">
                            <span class="input-label">'.$key.':</span>
                            <input class="form-control form-control-sm input nutriFacts" type="text" value="'.$value.'" name="'.str_replace(' ', '', $key).'">
                        </div>
                        <div class="error-message-container">
                            <span id="errorNutriFact'.$nutriNbr.'" class="error-message"></span>
                        </div>
                        ';
                        $nutriNbr += 1;
                }
            ?>
                <h4 class="section">Alergy Info.</h4>
            <?php
                foreach($arrAlrg as $key=>$value)
                {
                    echo '<div class="input-row">
                            <span class="input-label">'.$key.':</span>
                            <input class="form-control form-control-sm input" type="text" value="'.$value.'" name="'.str_replace(' ', '', $key).'">
                        </div>
                        <div class="error-message-container">
                            
                        </div>
                        ';
                }
            ?>
            <h4 class="section">Additive Info.</h4>
            <?php
                foreach($arrAdtv as $key=>$value)
                {
                    echo '<div class="input-row">
                            <span class="input-label">'.$key.':</span>
                            <input class="form-control form-control-sm input" type="text" value="'.$value.'" name="'.str_replace(' ', '', $key).'">
                        </div>
                        <div class="error-message-container">
                            
                        </div>
                        ';
                }
            ?>
            <input type="submit" class="button-save" value="Save" name="save">
            </form>
        </div>
        <!-- JQUERY TO SHOW SELECTED IMAGE -->
        <script>
            $('#file-input').change( function(event) {
            $("img.icon").attr('src',URL.createObjectURL(event.target.files[0]));
            $("img.icon").parents('.upload-icon').addClass('has-img');
        });
        </script>
    </body>
</html>