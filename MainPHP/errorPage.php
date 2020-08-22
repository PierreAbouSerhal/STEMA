<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/STEMA/HtConfig/dbConfig.php");

    $dbSuccess = false;
    $dbConx = mysqli_connect($db['hostName'], $db['userName'], $db['password']);
    if ($dbConx) 
    {
        $dbSelected = mysqli_select_db($dbConx, $db['dataBase']);
    
        if ($dbSelected) 
        {
            $dbSuccess = true;
        }
    }

    if($dbSuccess)
    {
        header("Location: index.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../MainCss/login.css" />
    <title>Error</title>
</head>
<body>
    <div class="container">
        <div class="login-header">
           <h1 class="login-message">Error</h1>
        </div>
        <p class="error-message danger">
            An unexpected error occurred. we are now investigating the problem. please wait for a while 
        </p>
    </div>
</body>
</html>