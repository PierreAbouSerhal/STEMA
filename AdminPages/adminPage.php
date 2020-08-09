<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/STEMA/PhpUtils/checkLoginStatus.php");

    $msg = $status = "";

    if(!$user["userOk"] && !$isAdmin)
    {
        logout();
        // PAGE NO LONGER AVAILABLE FOR LOGGED OUT USER
        header("Location = index.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="../MainCss/header.css"/>
    <script src="../MainJs/header.js"></script>
    
    <title>Admin Page</title>
</head>
<body>
    <div class="container main-container">
        
        <?php 
            include_once($_SERVER["DOCUMENT_ROOT"]."/Stema/MainElements/header.php");
        ?>
        
       

    </div>
</body>
</html>