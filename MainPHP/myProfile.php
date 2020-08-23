<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/STEMA/PhpUtils/checkLoginStatus.php");

    $msg = "To change your name without changing your password leave passwords empty.";
    $status = "secondary";

    if(!$user["userOk"])
    {
        logout();
        // PAGE NO LONGER AVAILABLE FOR LOGGED OUT USER
        header("Location: index.php");
        exit();
    }

    if(isset($_POST["update"]))
    {
        $name     = mysqli_real_escape_string($dbConx, $_POST["userName"]);
        $oldPass  = mysqli_real_escape_string($dbConx, $_POST["userOldPass"]);
        $newPass1 = mysqli_real_escape_string($dbConx, $_POST["userNewPass1"]);
        $newPass2 = mysqli_real_escape_string($dbConx, $_POST["userNewPass2"]);

        if(!empty($name) && empty($oldPass) && empty($newPass1) && empty($newPass2))
        {   
            $sqlUpdate = 'UPDATE users SET name = "'.$name.'" WHERE id = '.$user["userId"];

            $queryUpdate = mysqli_query($dbConx, $sqlUpdate);
            
        }
        else if(!empty($oldPass) && !empty($newPass1) && !empty($newPass2) && !empty($name) && strlen($newPass1) > 4 && strlen($newPass2) > 4)
        {
            $sql   = "SELECT COUNT(*) AS rowNbr FROM users WHERE phone = ".$userPhone." AND password = '".$oldPass."' ;";
            $query = mysqli_query($dbConx, $sql);
            $res   = mysqli_fetch_assoc($query);

            mysqli_free_result($query);

            if($res["rowNbr"] == 1 && $newPass1 == $newPass2) //OLD AND NEW PASS MATCH 
            {
                $sqlUpdate = "UPDATE users SET password = '".$newPass1."', name = '".$name."' WHERE phone = ".$userPhone." LIMIT 1";

                $queryUpdate = mysqli_query($dbConx, $sqlUpdate);
            }
            else
            {
                $msg = "Wrong password, please try again";
                $status = "danger";
            }
        }
        if(isset($queryUpdate) && $queryUpdate)
        {
            $msg    = "Profile have been updated!";
            $status = "success";

            $userName = refreshName($dbConx, $user);
            $_SESSION["userName"] = $userName;
        }
        else if(isset($queryUpdate) && !$queryUpdate)
        {
            $msg = "Failed to update profile!";
            $status = "danger";
        }
    }

    include("../MainElements/doctype.html");
?>
    <link rel="stylesheet" type="text/css" href="../MainCss/header.css"/>
    <link rel="stylesheet" type="text/css" href="../MainCss/login.css"/>
    <script src="../MainJs/header.js"></script>
    <script src="../MainJs/formValidation.js"></script>

    <title>My Profile</title>
</head>
<body>
    <div class="container main-container">
        
        <?php 
            include_once($_SERVER["DOCUMENT_ROOT"]."/Stema/MainElements/header.php");
        ?>
        
        <form class="login-form" method="POST" action=<?php echo $_SERVER["PHP_SELF"];?> onsubmit="return validateMyProfile();">

            <div class="invisible-separator">

            <?php
                echo '<div class="alert alert-'.$status.' msg-container" role="alert">'
                        .$msg.
                    '</div>';                
            ?>
            </div>
            
            <div class="form-group">
                <input type="text" class="form-control editable" id="userName" name="userName" placeholder="Change Your Name" value=<?php echo $userName ?>>
                <div class="error-message-container">
                    <small id="errorName" class="form-text"></small>
                </div>
            </div>
            <div class="form-group">
                <input type="tel" class="form-control" id="userPhone" readonly value=<?php echo $userPhone ?>>
                <div class="error-message-container">
                    
                </div>
            </div>
            <div class="form-group">
                <input type="email" class="form-control" id="userEmail" readonly value=<?php echo $userEmail ?>>
                <div class="error-message-container">
                    
                </div>
            </div>
            <div class="form-group">
                <input type="password" class="form-control editable" id="userOldPass" name="userOldPass" placeholder="Old Password">
                <div class="error-message-container">
                    <small id="errorOldPass" class="form-text"></small>
                </div>
            </div>
            <div class="form-group">
                <input type="password" class="form-control editable" id="userNewPass1" name="userNewPass1" placeholder="New Password">
                <div class="error-message-container">
                    <small id="errorNewPass1" class="form-text"></small>
                </div>
            </div>
            <div class="form-group">
                <input type="password" class="form-control editable" id="userNewPass2" name="userNewPass2" placeholder="Confirm Password">
                <div class="error-message-container">
                    <small id="errorNewPass2" class="form-text"></small>
                </div>
            </div>
            <button type="submit" name="update" class="btn">Update Profile</button>    
        </form>

    </div>
</body>
</html>