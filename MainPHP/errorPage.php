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

    include("../MainElements/doctype.html");
?>
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