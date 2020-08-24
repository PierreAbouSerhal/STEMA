<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/STEMA/PhpUtils/checkLoginStatus.php");

    if(!$user["userOk"] || !$isAdmin)
    {
        logout();
        // PAGE NO LONGER AVAILABLE FOR LOGGED OUT USER
        header("Location: ../MainPHP/index.php");
        exit();
    }
    //PAGE INDEX
    $pageIdx = "";

    if(isset($_GET["idx"]))
    {
        $pageIdx = mysqli_real_escape_string($dbConx, $_GET["idx"]);
    }

    if($pageIdx != "ING" && $pageIdx != "PRD" && $pageIdx != "VAR" && $pageIdx != "BRD")
    {
        //REDIRECT TO INFO PAGE
        header("Location: generalInfo.php");
        exit();
    }

    //MANAGE ARRAYS
    $arrPHolder = array("ING"=>"Ingredient",  "PRD"=>"Product",   "VAR"=>"Variant",   "BRD"=>"Brand");
    $arrTable   = array("ING"=>"ingredients", "PRD"=>"products",  "VAR"=>"variants",  "BRD"=>"productbrands");
    $arrPage    = array("ING"=>"ingrs.php",   "PRD"=>"prods.php", "VAR"=>"varis.php", "BRD"=>"brnds.php");

    $phpSelf = $_SERVER["PHP_SELF"]."?idx=".$pageIdx;
    $placeHolder = "Search ".$arrPHolder[$pageIdx];

    $fop = "true";
    $userInput = "";

    if(isset($_POST["userInput"]) && !empty($_POST["userInput"]))
    {
        $userInput = mysqli_escape_string($dbConx, $_POST["userInput"]);
        $fop = $arrTable[$pageIdx].".name LIKE '%".$userInput."%'";
    }

    $sqlSrch = 'SELECT * 
                FROM '.$arrTable[$pageIdx].'
                WHERE '.$fop.'
                 ORDER BY Name';

    $querySrch = mysqli_query($dbConx, $sqlSrch);

    include("../MainElements/doctype.html");

?>
    <link rel="stylesheet" type="text/css" href="../MainCss/header.css"/>
    <link rel="stylesheet" type="text/css" href="../MainCss/searchBar.css"/>
    <link rel="stylesheet" type="text/css" href="../MainCss/manage.css"/>
    <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css" rel="stylesheet" />

    <script src="https://kit.fontawesome.com/3571b2f364.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://code.iconify.design/1/1.0.7/iconify.min.js"></script>
    <script src="../MainJs/header.js"></script>
    
    <title>Manage <?php echo $arrPHolder[$pageIdx];?></title>
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
                    <th scope="col"><?php echo $arrPHolder[$pageIdx];?> Name</th>
                    <th scope="col" style="text-align: center;">Options</th>
                    </tr>
                </thead>
                <tbody>
        <?php
            $lineId = 0;
            while($resSrch = mysqli_fetch_assoc($querySrch))
            {
                $mngId     = $resSrch["id"];
                $mngName   = $resSrch["name"];
                $lineId += 1;

                echo '<tr>
                        <th scope="row">'.$lineId.'</th>
                        <td>'.$mngName.'</td>
                        <td class="option-col">
                            <a href="'.$arrPage[$pageIdx].'?isNew=0&id='.$mngId.'">
                                <i class="far fa-edit" title="Edit '.$arrPHolder[$pageIdx].'"></i>
                            </a>
                            <span class= "detete-icon">
                                <i id="mng_'.$mngName.'_'.$mngId.'" class="far fa-trash-alt delete" title="Delete '.$arrPHolder[$pageIdx].'"></i>
                            </span>
                        </td>
                      </tr>';
            }
        ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        //MESSAGEBOX + REMOVE VARIANT FROM DB AND UPDATE HTML
        $(document).ready(function()
        {
            //DELETE 
            $('.delete').click(function()
            {
                let idx     = "<?php echo $pageIdx?>";
                let el      = this;
                let id      = el.id;
                let splitid = id.split("_");
                let backClr = 'linear-gradient(90deg, rgb(3,129,66), rgb(133,187,47), rgb(255,204,3), rgb(238,129,0), rgb(230,62,17))';

                
                //ID + NAME
                let mngName = splitid[1];
                let mngId   = splitid[2];

                //MESSAGE BOX
                $('<div></div>')
                .appendTo('body')
                .html('<div><h6>' + 'Are you sure you want to delete ' + mngName + '?</h6></div>')
                .dialog({
                    modal: true,
                    title: 'Delete message',
                    zIndex: 10000,
                    autoOpen: true,
                    width: 'auto',
                    dialogClass: "no-close",
                    resizable: false,
                    draggable: false,
                    buttons: 
                    {
                        Yes: function() 
                        {
                            //AJAX REQUEST
                            $.ajax(
                            {
                                url: 'remove.php',
                                type: 'POST',
                                data: { idx: idx ,id: mngId },
                                success: function(response)
                                {
                                    if(response == 1)
                                    {
                                        //REMOVE VARIANT TR FROM HTML
                                        $(el).closest('tr').css('background','tomato');
                                        $(el).closest('tr').fadeOut(800,function()
                                        {
                                            $(el).remove();
                                        });
                                    }
                                    else
                                    {
                                        alert("Unable to remove " + mngName + "!");
                                    }
                                }
                            });
                            $(this).dialog("close");
                        },
                        No: function() 
                        {
                            $(this).dialog("close");
                        }
                    },
                    close: function(event, ui) 
                    {
                        $(this).remove();
                    }
                }).prev(".ui-dialog-titlebar").css("background",backClr).prev(".ui-dialog-titlebar").css("border", "none");
            });
        });
    </script>
</body>
</html>