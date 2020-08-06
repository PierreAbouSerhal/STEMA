<?php
    session_set_cookie_params(0, "/", "localhost", true, true);
    session_start();
    
    if(isset($_SESSION["userToken"]) || isset($_COOKIE["userToken"]))
    {
        header("Location: index.php");
        exit();
    }

    require_once($_SERVER["DOCUMENT_ROOT"]."/STEMA/HtConfig/mailConfig.php");
    require_once($_SERVER["DOCUMENT_ROOT"]."/STEMA/PhpUtils/mailSetup.php");

    $msg = $status = $usrEmail = "";

    if(isset($_POST["userEmail"]))
    {
        include_once($_SERVER["DOCUMENT_ROOT"]."/Stema/PhpUtils/dbConx.php");

        $usrEmail = mysqli_real_escape_string($dbConx, $_POST["userEmail"]);

        if(!empty($usrEmail) && strpos($usrEmail, '@') !== false)
        {
            $sql = "SELECT *, COUNT(*) AS rowNbr FROM users WHERE email = '".$usrEmail."' ;";

            $query = mysqli_query($dbConx, $sql);
            $res = mysqli_fetch_assoc($query);
            
            mysqli_free_result($query);

            if($res["rowNbr"] == 1)
            {
                $to = array(
                    array(
                        "name" => $res["name"],
                        "email" => $usrEmail
                    )
                );

                $subject = "Credentials";
                $html = '<h1>Hi '.$res["name"].'</h1>
                            <p>Your phone number is: <strong>'.$res["phone"].'</strong><p/>
                            <p>Your password is: <strong>'.$res["password"].'</strong><p/>';
                $from = array("name" => "Stema", "email" => $smtp["username"]);

                //SEND THE MAIL
                $jmomailer = new JMOMailer(true, $smtp);
                                
                if($jmomailer->mail($to, $subject, $html, $from))
                {
                    header("Location: login.php?cred=sent");
                    exit();
                }
                else
                {
                    $msg = "An error has occured while sending the mail, please try again";
                    $status ="danger";
                }
            }
            else
            {
                $msg = "We could not found that email address, please try again";
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
    <link rel="stylesheet" type="text/css" href="../MainCss/login.css" />
    <script src="../MainJs/formValidation.js"></script>

    <title>Forgot Password</title>
</head>
<body>
    <div class="container">
        <div class="login-header">
                <h1 class="login-message">Welcome to Stema</h1>
        </div>
        <form class="login-form" method="POST" action=<?php echo $_SERVER["PHP_SELF"];?> onsubmit="return validateForgPass()">
            <div class="invisible-separator">
                <?php
                    if(!empty($msg))
                    {   
                        echo '<div class="alert '.$status.' msg-container" role="alert">'
                                .$msg.
                            '</div>';                
                    }
                ?>
            </div>
            <div class="form-group">
                <input type="email" class="form-control" id="userEmail" name="userEmail" placeholder="Enter your email" value=<?php echo $usrEmail ?>>
                <div class="error-message-container">
                    <small id="errorEmail" class="form-text"></small>
                </div>
            </div>
            <button type="submit" class="btn">Send Credentials</button>
    </form>
    </div>
    
</body>
</html>