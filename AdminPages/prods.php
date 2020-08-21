<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/STEMA/PhpUtils/checkLoginStatus.php");
    
    if(!$user["userOk"] || !$isAdmin)
    {
        logout();
        // PAGE NO LONGER AVAILABLE FOR LOGGED OUT USER
        header("Location: ../MainPHP/index.php");
        exit();
    }
    $pageIdx = "PRD";
    if(isset($_GET["id"]) && !empty($_GET["id"]))//EDIT PRODUCT
    {
        $prodId = mysqli_real_escape_string($dbConx, $_GET["id"]);
    }
    else//ADD PRODUCT
    {
        $prodId = -1;
    }

    //PAGE VARIABLES
    $arrGeneral = $arrIngr = array();
    $nutriScore = array("A", "B", "C", "D", "E");

    //ON SUMBIMT
    if(isset($_POST["save"]))
    {
        $prodName = mysqli_real_escape_string($dbConx, $_POST["Name"]);
        $brandId  = mysqli_real_escape_string($dbConx, $_POST["brand"]);
        $score    = strtoupper(mysqli_real_escape_string($dbConx, $_POST["NutriScore"]));
        $info     = mysqli_real_escape_string($dbConx, $_POST["Info"]);
        $save     = true;

        $info     = (empty($info)) ? 'NULL' : '"'.$info.'"';
        $ingrQty  = array();

        if(!empty($_POST["ingr_list"]))
        {
            foreach($_POST["ingr_list"] as $ingrId)
            {
                if(empty($_POST["qty_".$ingrId]))
                {
                    $save = false;
                    break;
                }
                $ingrId = mysqli_real_escape_string($dbConx, $ingrId);
                $ingrQty[$ingrId] = mysqli_escape_string($dbConx, $_POST["qty_".$ingrId]);
            }
        }
        
        if(!empty($prodName) && !empty($brandId) && in_array($score, $nutriScore) && !empty($ingrQty) && $save)
        {
            $sqlProdCheck    = "SELECT COUNT(*) AS rowNbr FROM Products WHERE id = ".$prodId;
            
            $queryProdCheck  = mysqli_query($dbConx, $sqlProdCheck);
            
            $resProdCheck    = mysqli_fetch_assoc($queryProdCheck);
            
            mysqli_free_result($queryProdCheck);

            //SQL VARIABLES
            $sqlProd = $sqlIngr = "";

            if($resProdCheck["rowNbr"] == 1)
            {
                $sqlProd = 'UPDATE products SET name = "'.$prodName.'", brandId = "'.$brandId.'", nutriscore = "'.$score.'", info = '.$info.'
                                WHERE products.id = '.$prodId;

                $queryProd = mysqli_query($dbConx, $sqlProd);
            }
            else
            {
                $sqlProd = 'INSERT INTO products (name, brandId, nutriScore, info)
                                VALUES ("'.$prodName.'", "'.$brandId.'", "'.$score.'", '.$info.')';

                $queryProd = mysqli_query($dbConx, $sqlProd);

                if($queryProd)
                {
                    //GET THE NEW CREATED ID
                    $prodId = mysqli_insert_id($dbConx);
                    $resProdCheck["rowNbr"] = 1;
                } 
                else
                {
                    //FAILED TO INSERT INTO THE DATABASE
                    header("Location: manage.php?idx=".$pageIdx);
                    exit();
                }
            }

            if($resProdCheck["rowNbr"] = 1)
            {
                //DELETE IF ALREADY EXISTS
                $sqlDelete   = 'DELETE FROM productingredients WHERE productId = '.$prodId;
                $queryDelete = mysqli_query($dbConx, $sqlDelete);

                if($queryDelete)
                {
                    foreach($ingrQty as $ingr=>$qty)
                    {
                        $sqlProdIngr = 'INSERT INTO productingredients (productId, ingredientId, quantity)
                                            VALUES('.$prodId.', '.$ingr.', '.$qty.')';
                        
                        $queryProdIngr = mysqli_query($dbConx, $sqlProdIngr);
                    }
                }
            }
        }
    }

    //REFRESH PAGE
    $sqlProd = "SELECT products.name AS Name,
                       products.nutriscore AS `Nutri Score`,
                       products.info AS Info,
                       products.brandId
                FROM products 
                WHERE products.id = ".$prodId.";";

    $queryProd = mysqli_query($dbConx, $sqlProd);

    $sqlAllBrands = "SELECT pb.id AS brandId, pb.name AS brand FROM productBrands AS pb ORDER BY NAME";

    $queryAllBrands = mysqli_query($dbConx, $sqlAllBrands);

    $resBrand = "";

    if(mysqli_num_rows($queryProd) == 1)
    {
        $resProd    = mysqli_fetch_assoc($queryProd);

        $sqlBrand   = "SELECT name AS Brand, id FROM productbrands AS pb WHERE pb.id = ".$resProd["brandId"];

        $queryBrand = mysqli_query($dbConx, $sqlBrand);
    
        $resBrand   = mysqli_fetch_assoc($queryBrand);

        mysqli_free_result($queryBrand);

        foreach($resProd as $key => $value)
        {
            if($key == "brandId")
            {
                continue;
            }
            $arrGeneral[$key] = $value;
        }
    }
    else
    {
        $resProd = mysqli_fetch_fields($queryProd);

        foreach($resProd as $field)
        {   
            if($field->name == "brandId")
            {
                continue;
            }
            $arrGeneral[$field->name] = "";
        }
    }
    
    //PAGE TITLE
    $title = (!empty($arrGeneral["Name"])) ? "Edit ".$arrGeneral["Name"] : "Add Product";

    mysqli_free_result($queryProd);

    $sqlIngr = "SELECT ingr.name, prodIngr.productId, prodIngr.quantity, ingr.id AS ingrId
                FROM ingredients AS ingr
                LEFT JOIN (
                        SELECT * FROM productIngredients AS PI 
                        WHERE PI.productId = ".$prodId."
                    ) AS prodIngr ON prodIngr.ingredientId = ingr.id
                ORDER BY ingr.name";

    $queryIngr = mysqli_query($dbConx, $sqlIngr);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="../MainCss/header.css"/>
    <link rel="stylesheet" type="text/css" href="../MainCss/manage.css"/>

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
            
            <form class="form" action="<?php $action = $_SERVER["PHP_SELF"]."?id=".$prodId; echo $action;?>" method="POST" enctype="multipart/form-data" onsubmit="return validateProds();">
                
            <?php
                $prodNbr = 0; //PRODUCT INFO INPUT NUMBER

                foreach($arrGeneral as $key=>$value)
                {
                    echo '<div class="input-row">
                            <span class="input-label">'.$key.':</span>
                            <input class="form-control form-control-sm input product" type="text" value="'.$value.'" name="'.str_replace(' ', '', $key).'">
                        </div>
                        <div class="error-message-container">
                            <span id="errorProduct'.$prodNbr.'" class="error-message"></span>
                        </div>
                        ';
                        $prodNbr += 1;
                }
            ?>
               <div class="input-row">
                   <span class="input-label">Brand:</span>
                   <select id = "brand" name="brand" class="custom-select custom-select-sm input">
                        <?php
                            $brandId = -1;

                            if(!empty($resBrand))
                            {
                                $brandId = $resBrand["id"];
                            }
                            
                            if($brandId == -1)
                            {
                                echo '<option value="" hidden disabled selected>Select Brands</option>';
                            }

                            if($queryAllBrands !== false)
                            {
                                while($resAllBrands = mysqli_fetch_assoc($queryAllBrands))
                                {
                                    $selected = "";
                                    if($brandId == $resAllBrands["brandId"])
                                    {
                                        $selected = 'selected="selected"';
                                    }
                                    echo '<option value="'.$resAllBrands["brandId"].'" '.$selected.'>'.$resAllBrands["brand"].'</option>';
                                }
                                mysqli_free_result($queryAllBrands);
                            }
                        ?>
                   </select>
               </div>
               <div class="error-message-container">
                    <span id="errorBrand" class="error-message"></span>
                </div>

                <h4 class="section">Product Ingredients</h4>
                
                <div id="errorQty" class="empty-message"></div>
                <div class="scroll-container">                            
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                <th scope="col" style="text-align: center">
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th scope="col">Ingredient</th>
                                <th scope="col" style="text-align: center;">Quantity/100g</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    while($resIngr = mysqli_fetch_assoc($queryIngr))
                                    {
                                        $checked  = (!empty($resIngr["quantity"])) ? "checked" : "";
                                        $disabled = (!empty($resIngr["quantity"])) ? "" : "disabled";
                                        
                                        echo '<tr>
                                                <td style="text-align: center">
                                                    <input type="checkbox" class="ingr_list" name="ingr_list[]"  value="'.$resIngr["ingrId"].'" '.$checked.'>
                                                </td>
                                                <td>'.$resIngr["name"].'</td>
                                                <td>
                                                <input type="number" name="qty_'.$resIngr["ingrId"].'"  class="form-control form-control-sm quantity-input" value = "'.$resIngr["quantity"].'" '.$disabled.'>
                                                </td>
                                            </tr>';
                                    }
                                    mysqli_free_result($queryIngr);
                                ?>
                            </tbody>
                        </table>
                    </div>
            <input type="submit" class="button-save" value="Save" name="save">
            </form>
        </div>
        <script>
            //MAKE INPUT FIELD EDITABLE ON CHECK BOX CLICK
            $(document).ready(function()
            {
                $(".ingr_list").click(function() 
                {
                    if ($(this).is(':checked')) 
                    {
                        $(this).parents('tr').children('td').children('input[type="number"]').removeAttr('disabled');
                    } 
                    else 
                    {
                        $(this).parents('tr').children('td').children('input[type="number"]').attr('disabled', 'disabled');
                    }
                })

                //SELECT/UNSELECT INGREDIENT CHECKBOXES     
                $("#selectAll").click(function()
                {
                    $('input:checkbox').prop('checked', this.checked);
                
                    if(this.checked)
                    {
                        $(".quantity-input").removeAttr('disabled');
                    }    
                    else
                    {
                        $(".quantity-input").attr('disabled', 'disabled');
                    }
                    
                })
            })
    </script>
    </body>
</html>