<?php
    session_set_cookie_params(0, "/", "localhost", true, true);
    session_start();
    
    if(isset($_SESSION["userToken"]) || isset($_COOKIE["userToken"]))
    {
        header("Location: index.php");
        exit();
    }
?>
<?php
    $msg = $phone = $pass = $status = "";

    // WHEN ACCOUNT IS VERIFIED
    if(isset($_GET["activated"]))
    {
        if($_GET["activated"] == 1)
        {
            $msg = "Your account has been verified, please login";
            $status = "success";
        }
    }
    else if(isset($_GET["cred"]))
    {
        if($_GET["cred"] == "sent")
        {
            $msg = "Your credentials have been sent to your email, please login";
            $status = "success";
        }
    }


    if(isset($_POST["userPhone"]) && isset($_POST["userPassword"]))
    {
        include_once($_SERVER["DOCUMENT_ROOT"]."/Stema/PhpUtils/dbConx.php");
       
        $phone = mysqli_real_escape_string($dbConx, $_POST["userPhone"]);
        $pass  = mysqli_real_escape_string($dbConx, $_POST["userPassword"]);
        
        //FORM DATA ERROR HANDLING
        if(!empty($phone) && !empty($pass) && is_numeric($phone))
        {
            
            $sql = "SELECT *, COUNT(*) AS rowNbr FROM users WHERE phone = ".$phone." AND password = '".$pass."';";
            $query = mysqli_query($dbConx, $sql);
            $res = mysqli_fetch_assoc($query);

            mysqli_free_result($query);

            if($res["rowNbr"] == 1)
            {
                //CREATE THE USER TOKEN
                $token = random_bytes(16);
                $token = bin2hex($token);

                //HASH THE USER TOKEN
                $tokenHash = hash("sha256",$token);

                //INSERT THE HASHED TOKEN INTO THE DATABASE
                $sqlInsert = "INSERT INTO userTokens (userId, hashedToken, creationDate, creationTime)
                                VALUES (".$res["id"].", '".$tokenHash."', CURDATE(), CURTIME()) ;";
                $insertQuery = mysqli_query($dbConx, $sqlInsert);

                //CREATE THE SESSIONS 
                $_SESSION["loggedin"]  = true;
                $_SESSION["userPhone"] = $phone;
                $_SESSION["userName"]  = $res["name"];
                $_SESSION["userEmail"] = $res["email"];
                $_SESSION["isAdmin"]   = ($res["role"] == "ADMIN") ? true : false ;
                $_SESSION["userToken"] = $token;

                //CREATE THE COOKIES
                setcookie("userToken", $token, time() + ( 100 * 30 * 24 * 60 * 60 ), "/", "localhost", true, true);

                header("Location: index.php");
                exit();
            }
            else
            {   
                $msg = "Wrong phone number or password, please try again.";
                $status = "danger";

                // NOTE: use password_verify in WEB3 proj
                
                /*CHECK PASSWORD
                if(password_verify($pass, $res["password"]))
                { CREATE SESSIONS AND COOKIE }
                 else
                 {
                    $msg = "Wrong password, please try again.";
                    $status = "danger";
                 }*/
                
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
    <title>Log in</title>
</head>
<body>
    <div class="container">
        <div class="login-header">
           <h1 class="login-message">Welcome to Stema</h1>
        </div>
        <form class="login-form" method="POST" action=<?php echo $_SERVER["PHP_SELF"];?> onsubmit="return valdateLogin()">
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
                <input type="tel" class="form-control" name="userPhone" placeholder="Phone Number" value=<?php echo $phone ?>>
                <div class="error-message-container">
                    <small id="errorPhone" class="form-text"></small>
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 0">
                <input type="password" class="form-control" name="userPassword" placeholder="Password" value=<?php echo $pass ?>>
                <div class="error-message-container">
                    <small id="errorPass" class="form-text"></small>
                </div>
            </div>
            
            <div class="forgot-password">
                <a href="forgotPass.php">Forgot password?</a>
            </div>
            <button type="submit" class="btn">Login</button>
            <div class="sign-up">
                <span>Don't have an account?<a href="signup.php">Sign Up</a></span>
            </div>            
        </form>
    </div>
</body>
</html>