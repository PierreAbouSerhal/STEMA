<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/STEMA/PhpUtils/checkLoginStatus.php");

    $msg = $status = "";

    if(!$user["userOk"])
    {
        logout();
        // PAGE NO LONGER AVAILABLE FOR LOGGED OUT USER
        header("Location = index.php");
        exit();
    }

    if(isset($_POST["userOldPass"]) && isset($_POST["userNewPass"]) && isset($_POST["userName"]))
    {
        $name    = mysqli_real_escape_string($dbConx, $_POST["userName"]);
        $oldPass = mysqli_real_escape_string($dbConx, $_POST["userOldPass"]);
        $newPass = mysqli_real_escape_string($dbConx, $_POST["userNewPass"]);

        if(!empty($oldPass) && !empty($newPass) && !empty($name) && strlen($newPass) > 4)
        {
            $sql = "SELECT COUNT(*) AS rowNbr FROM users WHERE phone = ".$userPhone." AND password = '".$oldPass."' ;";
            $query = mysqli_query($dbConx, $sql);
            $res = mysqli_fetch_assoc($query);

            mysqli_free_result($query);

            if($res["rowNbr"] == 1) //OLD PASS MATCH
            {
                $sqlUpdate = "UPDATE users SET password = '".$newPass."', name = '".$name."' WHERE phone = ".$userPhone." LIMIT 1";
                $queryUpdate = mysqli_query($dbConx, $sqlUpdate);

                if($queryUpdate)
                {
                    $msg = "Name and password have been changed!";
                    $status = "success";
                }
                else
                {
                    $msg = "Failed to change name and password";
                    $status = "danger";
                }
            }
            else
            {
                $msg = "Wrong password, please try again";
                $status = "danger";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

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
                if(!empty($status))
                {
                    echo '<div class="alert '.$status.' msg-container" role="alert">'
                            .$msg.
                        '</div>';                
                }
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
                <input type="password" class="form-control editable" id="userNewPass" name="userNewPass" placeholder="New Password">
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
            <button type="submit" class="btn">Update Profile</button>    
        </form>

    </div>
</body>
</html>