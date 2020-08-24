<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/STEMA/PhpUtils/checkLoginStatus.php");
    
    if(!$user["userOk"] || !$isAdmin)
    {
        logout();
        // PAGE NO LONGER AVAILABLE FOR LOGGED OUT USER
        header("Location: ../MainPHP/index.php");
        exit();
    }
    $pageIdx = "VAR";
    if(isset($_GET["id"]) && !empty($_GET["id"]))//EDIT INGREDIENT
    {
        $variId = mysqli_real_escape_string($dbConx, $_GET["id"]);
    }
    else//ADD INGREDIENT
    {
        $variId = -1;
    }

    //PAGE VARIABLES
    $arrGeneral = $arrProd = array();

    //ON SUMBIMT
    if(isset($_POST["save"]))
    {
        $variName    = mysqli_real_escape_string($dbConx, $_POST["Name"]);
        $variVol     = mysqli_real_escape_string($dbConx, $_POST["Volume"]);
        $variBarCode = mysqli_real_escape_string($dbConx, $_POST["BarCode"]);
        $variProdId  = mysqli_real_escape_string($dbConx, $_POST["productId"]);

        if(!empty($variName) && !empty($variVol) && !empty($variBarCode) && !empty($variProdId))
        {
            $sqlVariCheck    = "SELECT *, COUNT(*) AS rowNbr FROM variants WHERE id = ".$variId;
            
            $queryVariCheck  = mysqli_query($dbConx, $sqlVariCheck);
            
            $resVariCheck    = mysqli_fetch_assoc($queryVariCheck);
            
            mysqli_free_result($queryVariCheck);

            $variImg1 = (empty($resVariCheck["image1"])) ? 'NULL' : '"'.$resVariCheck["image1"].'"'; 
            $variImg2 = (empty($resVariCheck["image2"])) ? 'NULL' : '"'.$resVariCheck["image2"].'"'; 
            $variImg3 = (empty($resVariCheck["image3"])) ? 'NULL' : '"'.$resVariCheck["image3"].'"'; 

            //UPLOAD THE 3 IMAGE
            if(file_exists($_FILES['Image1']['tmp_name']) || is_uploaded_file($_FILES['Image1']['tmp_name']))
            {
                $target_dir    = "../VariantsPics/";
                $target_file   = $target_dir . basename($_FILES["Image1"]["name"]);
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                $check         = getimagesize($_FILES["Image1"]["tmp_name"]);//CHECK IF FALSE IMAGE
                $uploadOk      = 1;

                if($check === false || $_FILES["Image1"]["size"] > 500000 ||
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
                    move_uploaded_file($_FILES["Image1"]["tmp_name"], $target_file);
                    $variImg1 = '"/Stema/VariantsPics/'.basename($_FILES["Image1"]["name"]).'"';
                }
            }

            if(file_exists($_FILES['Image2']['tmp_name']) || is_uploaded_file($_FILES['Image2']['tmp_name']))
            {
                $target_dir    = "../VariantsPics/";
                $target_file   = $target_dir . basename($_FILES["Image2"]["name"]);
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                $check         = getimagesize($_FILES["Image2"]["tmp_name"]);//CHECK IF FALSE IMAGE
                $uploadOk      = 1;

                if($check === false || $_FILES["Image2"]["size"] > 500000 ||
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
                    move_uploaded_file($_FILES["Image2"]["tmp_name"], $target_file);
                    $variImg2 = '"/Stema/VariantsPics/'.basename($_FILES["Image2"]["name"]).'"';
                }
            }

            if(file_exists($_FILES['Image3']['tmp_name']) || is_uploaded_file($_FILES['Image3']['tmp_name']))
            {
                $target_dir    = "../VariantsPics/";
                $target_file   = $target_dir . basename($_FILES["Image3"]["name"]);
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                $check         = getimagesize($_FILES["Image3"]["tmp_name"]);//CHECK IF FALSE IMAGE
                $uploadOk      = 1;

                if($check === false || $_FILES["Image3"]["size"] > 500000 ||
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
                    move_uploaded_file($_FILES["Image3"]["tmp_name"], $target_file);
                    $variImg3 = '"/Stema/VariantsPics/'.basename($_FILES["Image3"]["name"]).'"';
                }
            }
           
            //SQL VARIABLES
            $sqlVari = "";

            if($resVariCheck["rowNbr"] == 1)
            {
                $sqlVari = 'UPDATE variants SET productId = '.$variProdId.', name = "'.$variName.'", volume = "'.$variVol.'", 
                                barcode = "'.$variBarCode.'", image1 = '.$variImg1.', image2 = '.$variImg2.', image3 = '.$variImg3.'
                            WHERE variants.id = '.$variId;
                            
                $queryVari = mysqli_query($dbConx, $sqlVari);
            }
            else
            {
                $sqlVari = 'INSERT INTO variants (productId, name, volume, barcode, image1, image2, image3)
                                VALUES ('.$variProdId.', "'.$variName.'", "'.$variVol.'", "'.$variBarCode.'", '.$variImg1.', '.$variImg2.', '.$variImg3.')';

                $queryVari = mysqli_query($dbConx, $sqlVari);
                
                if($queryVari)
                {
                    //GET THE NEW CREATED ID
                    $variId = mysqli_insert_id($dbConx);
                }
                else
                {
                    //FAILED TO INSERT/UPDATE INTO THE DATABASE
                    header("Location: manage.php?idx=".$pageIdx);
                    exit();
                }
            }
        }
    }

    //REFRESH PAGE
    $sqlVari = "SELECT vari.name AS Name,
                        prod.name AS Product,
                        vari.volume AS Volume,
                        vari.barcode AS `Bar Code`,
                        vari.image1 AS `Image 1`,
                        vari.image2 AS `Image 2`,
                        vari.image3 AS `Image 3`
                FROM variants AS vari JOIN products AS prod ON vari.productId = prod.id
                WHERE vari.id = ".$variId.";";

    $sqlAllProds = "SELECT * FROM products ORDER BY name";

    $queryAllProd = mysqli_query($dbConx, $sqlAllProds);

    $queryVari = mysqli_query($dbConx, $sqlVari);
    
    if(mysqli_num_rows($queryVari) == 1)
    {
        $resVari = mysqli_fetch_assoc($queryVari);

        foreach($resVari as $key => $value)
        {
            $arrGeneral[$key] = $value;
        }
    }
    else
    {
        $resVari = mysqli_fetch_fields($queryVari);

        foreach($resVari as $field)
        {   
            $arrGeneral[$field->name] = "";
        }
    }
    
    //PAGE TITLE
    $title = (!empty($arrGeneral["Name"])) ? "Edit ".$arrGeneral["Name"] : "Add Variants";

    mysqli_free_result($queryVari);

    include("../MainElements/doctype.html");
?>
<!DOCTYPE html>

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
            
            <form class="form" action="<?php $action = $_SERVER["PHP_SELF"]."?id=".$variId; echo $action;?>" method="POST" enctype="multipart/form-data" onsubmit=" return validateVaris();">
                <?php
                    foreach($arrGeneral as $key=>$value)
                    {
                        echo '<div class="input-row">';
                        if($key == "Product")
                        {
                            echo '<span class="input-label">'.$key.':</span>
                                    <select id = "productId" name="productId" class="custom-select custom-select-sm input">';
                            
                            if(empty($value))
                            {
                                echo '<option value="" hidden disabled selected value>Select Product</option>';
                            }

                            while($resAllProd = mysqli_fetch_assoc($queryAllProd))
                            {
                                $selected = "";
                                if($value == $resAllProd["name"])
                                {
                                    $selected = 'selected="selected"';
                                }
                                echo '<option value="'.$resAllProd["id"].'" '.$selected.'>'.$resAllProd["name"].'</option>';
                            }
                            
                            echo '</select>';
                        }
                        else if(strpos($key, "Image") !== false)
                        {
                            $src = (empty($arrGeneral[$key])) ? "https://image.flaticon.com/icons/png/128/61/61112.png" : $arrGeneral[$key];

                            echo '
                                    <div style="margin-top: 20px;">
                                        <span class="input-label">'.$key.':</span>
                                        <div class="error-message-container">
                                            <span id="error'.str_replace(' ', '', $key).'" style = "margin: 0" class="error-message"></span>
                                        </div>
                                    </div>
                                    <div class="image-upload">
                                        <label for="file-input-'.strtolower(str_replace(' ', '', $key)).'">
                                        <div class="upload-icon">
                                            <img class="'.strtolower(str_replace(' ', '', $key)).'" src="'.$src.'">
                                        </div>
                                        </label>
                                    <input id="file-input-'.strtolower(str_replace(' ', '', $key)).'" type="file" name="'.str_replace(' ', '', $key).'"/>
                                    </div>
                                ';
                        }
                        else
                        {
                            echo '<span class="input-label">'.$key.':</span>
                                  <input id="'.str_replace(' ', '', $key).'" class="form-control form-control-sm input" type="text" value="'.$value.'" name="'.str_replace(' ', '', $key).'">';
                        }
                        
                        echo '</div>'; //CLOSING INPUT ROW

                        if(strpos($key, "Image") === false)
                        {
                            echo '<div class="error-message-container">
                                    <span id="error'.str_replace(' ', '', $key).'" class="error-message"></span>
                                </div>
                            ';
                        }
                    }
                ?>
            <input type="submit" class="button-save" value="Save" name="save">
            </form>
        </div>
        <!-- JQUERY TO SHOW SELECTED IMAGE -->
        <script>
            $('#file-input-image1').change( function(event) {
            $("img.image1").attr('src',URL.createObjectURL(event.target.files[0]));
            $("img.image1").parents('.upload-icon').addClass('has-img');
        });

        $('#file-input-image2').change( function(event) {
            $("img.image2").attr('src',URL.createObjectURL(event.target.files[0]));
            $("img.image2").parents('.upload-icon').addClass('has-img');
        });

        $('#file-input-image3').change( function(event) {
            $("img.image3").attr('src',URL.createObjectURL(event.target.files[0]));
            $("img.image3").parents('.upload-icon').addClass('has-img');
        });
        </script>
    </body>
</html>