<?php
    require_once($_SERVER["DOCUMENT_ROOT"]."/STEMA/HtConfig/mailConfig.php");
    require_once($_SERVER["DOCUMENT_ROOT"]."/STEMA/PhpUtils/mailSetup.php");

    $usrName = $usrPhone = $usrEmail = $usrPass1 = $usrPass2 = $msg = $status = "";

    function activationCode ()
    {
        $rand = random_bytes(16);
        $rand = bin2hex($rand);
        $activCode = password_hash($rand, PASSWORD_DEFAULT);
        return $activCode;
    }

    //INPUT VALIDATION
    if(isset($_POST["userName"]) && isset($_POST["userPhone"]) && isset($_POST["userEmail"]) && isset($_POST["userPass1"]) && isset($_POST["userPass2"]))
    {
        include_once($_SERVER["DOCUMENT_ROOT"]."/Stema/PhpUtils/dbConx.php");

        $usrName  = mysqli_real_escape_string($dbConx, $_POST["userName"]);
        $usrPhone = mysqli_real_escape_string($dbConx, $_POST["userPhone"]);
        $usrEmail = mysqli_real_escape_string($dbConx, $_POST["userEmail"]);
        $usrPass1 = mysqli_real_escape_string($dbConx, $_POST["userPass1"]);
        $usrPass2 = mysqli_real_escape_string($dbConx, $_POST["userPass2"]);

        if(!empty($usrName) && !empty($usrPhone) && !empty($usrEmail) && !empty($usrPass1) && !empty($usrPass2))
        {
            if(is_numeric($usrPhone) && strpos($usrEmail, '@') !== false && strlen($usrPass1) > 4 && $usrPass1 == $usrPass2)
            {
                //UNIQUE ACCOUNT VERIFICATION
                $sqlVerif = "SELECT COUNT(*) AS rowNbr, activated, phone FROM users WHERE phone = ".$usrPhone." OR email = '".$usrEmail."' ;";

                $queryVerif = mysqli_query($dbConx, $sqlVerif);
                $res = mysqli_fetch_assoc($queryVerif);
                
                mysqli_free_result($queryVerif);

                $to = array(
                    array(
                        "name" => $usrName,
                        "email" => $usrEmail
                    )
                );
                
                //CREATE VERIFICATION CODE
                $activCode = activationCode();

                $subject = "Verification Email";
                $html = '<h1>Hi '.$usrName.'</h1><p>Thanks for getting started with Stema! Please click on the link below to confirm your email address: <a href ="https://localhost/STEMA/MainPhp/activate.php?activCode='.$activCode.'">https://localhost/STEMA/MainPhp/activate.php?activCode='.$activCode.'"</a></p>
                <p>If you have problems, please paste the above link into your web browser</p>
                <p>Thanks,<p/>
                <p>Stema Support</p>';
                $from = array("name" => "Stema", "email" => $smtp["username"]);

                if($res["rowNbr"] == 0) //ACCOUNT NOT FOUND
                {
                    //HASH THE PASSWORD 
                    //$usrPass = password_hash($usrPass1, PASSWORD_DEFAULT); NOT USEFUL FOR FORGOT PASS LOGIC
                    $usrPass = $usrPass1;
                    
                    //INSERT UNACTIVATED USER
                    $sqlInsert = "INSERT INTO users (phone, name, email, password, activationCode) 
                                        VALUES (".$usrPhone.", '".$usrName."', '".$usrEmail."', '".$usrPass."', '".$activCode."')";

                    $queryInsert = mysqli_query($dbConx, $sqlInsert);

                    if($queryInsert)
                    {
                        //SEND THE MAIL
                        $jmomailer = new JMOMailer(true, $smtp);
                        
                        if($jmomailer->mail($to, $subject, $html, $from))
                        {
                            $msg = "Your account has been Created please verify it by clicking the activation link that has been send to your email.";
                            $status = "success";
                        }
                        else
                        {
                            $msg = "An error has occured while sending the mail, please try again";
                            $status ="danger";
                        }
                    }
                }
                else if($res["rowNbr"] == 1 && $res["activated"] == 0)//ACCOUNT FOUND BUT NOT ACTIVATED
                {
                    $sqlUpdate = "UPDATE users 
                                  SET activationCode = '".$activCode."' 
                                  WHERE phone = ".$res["phone"].";";
                    
                    $queryUpdate = mysqli_query($dbConx, $sqlUpdate);

                    if($queryUpdate)
                    {
                        //SEND THE MAIL
                        $jmomailer = new JMOMailer(true, $smtp);
                            
                        if($jmomailer->mail($to, $subject, $html, $from))
                        {
                            $msg = "This account is already created but not activated, please verify it by clicking the activation link that has been sent to your email.";
                            $status = "success";
                        }
                        else
                        {
                            $msg = "An error has occured while sending the mail, please try again";
                            $status ="danger";
                        }
                    }
                    else
                    {
                        $msq = "Error in sql Update";
                        $status = "danger";
                    }
                }
                else // ACCOUNTS WITH SAME PHONE NUMBER OR EMAIL ARE FOUND
                {
                    $msg = "An account with the same phone number or email address already exists, please try again";
                    $status = "danger";
                }
            }
        }
    }
    include("../MainElements/doctype.html");
?>
    <link rel="stylesheet" type="text/css" href="../MainCss/login.css"/>
    <script src="../MainJs/formValidation.js"></script>

    <title>Sign Up</title>
</head>
<body>
      <div class="container">
        <div class="login-header">
           <h1 class="login-message">Welcome to Stema</h1>
        </div>
        
        <form class="login-form" method="POST" action=<?php echo $_SERVER["PHP_SELF"];?> onsubmit="return validateSignup();">

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
            <input type="text" class="form-control" id="userName" name="userName" placeholder="Name" value=<?php echo $usrName ?>>
            <div class="error-message-container">
                <small id="errorName" class="form-text"></small>
            </div>
        </div>
        <div class="form-group">
            <input type="tel" class="form-control" id="userPhone" name="userPhone" placeholder="Phone" value=<?php echo $usrPhone ?>>
            <div class="error-message-container">
                <small id="errorPhone" class="form-text"></small>
            </div>
        </div>
        <div class="form-group">
            <input type="email" class="form-control" id="userEmail" name="userEmail" placeholder="Email" value=<?php echo $usrEmail ?>>
            <div class="error-message-container">
                <small id="errorEmail" class="form-text"></small>
            </div>
        </div>
        <div class="form-group">
            <input type="password" class="form-control" id="userPass1" name="userPass1" placeholder="Password" value=<?php echo $usrPass1 ?>>
            <div class="error-message-container">
                <small id="errorPass1" class="form-text"></small>
            </div>
        </div>
        <div class="form-group">
            <input type="password" class="form-control" id="userPass2" name="userPass2" placeholder="Confirm Password" value=<?php echo $usrPass2 ?>>
            <div class="error-message-container">
                <small id="errorPass2" class="form-text"></small>
            </div>
        </div>
        <button type="submit" class="btn">Sign Up</button>
        <div class="sign-up">
            <a href="login.php">I already have an account</a>
        </div>       
        </form>
    </div>
</body>
</html>