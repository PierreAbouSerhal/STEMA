<?php
session_set_cookie_params(0, "/", "localhost", true, true);
session_start();
include_once($_SERVER["DOCUMENT_ROOT"]."/STEMA/PhpUtils/dbConx.php");

$user = array("userId" => "", "userOk" => false);
$userPhone = "";
$userEmail = "";
$userName  = "";
$isAdmin   = false;

//USER VERIFICATION FUNCTION
function evalLoggedUser($conx, $hashedToken, $user){
    $out = $user;

    $sql = "SELECT COUNT(*) AS rowNbr, userId FROM userTokens WHERE hashedToken = '".$hashedToken."';";
    $query = mysqli_query($conx, $sql);
    $res = mysqli_fetch_assoc($query);

    mysqli_free_result($query);

    if($res["rowNbr"] == 1)
    {
        $out["userId"] = $res["userId"];
        $out["userOk"] = true;
        return $out;
    }
}

function logout()
{
    if(isset($_COOKIE["userToken"]))
    {
        setcookie("userToken", "", 0, "/");
    }
    $_SESSION = array();
    session_destroy();
}

if(isset($_SESSION["userToken"]) && isset($_SESSION["loggedin"]))
{
    if($_SESSION["loggedin"])
    {
        $hashedToken = hash("sha256", $_SESSION["userToken"]);

        $userPhone = $_SESSION["userPhone"];
        $userEmail = $_SESSION["userEmail"];
        $userName  = $_SESSION["userName"];
        $isAdmin   = $_SESSION["isAdmin"];
        $user = evalLoggedUser($dbConx, $hashedToken, $user);
    }
}
else if(isset($_COOKIE["userToken"]))
{
    $hashedToken = hash("sha256", $_COOKIE["userToken"]);
    
    $user = evalLoggedUser($dbConx, $hashedToken, $user);

    if($user["userOk"])
    {
        $userId = $user["userId"];

        //REINITIATE SESSION VARIABLES
        $sqlCheck = "SELECT *, COUNT(*) AS rowNbr, role FROM users WHERE id = ".$userId.";";
        $queryCheck = mysqli_query($dbConx, $sqlCheck);
        $resCheck = mysqli_fetch_assoc($queryCheck);

        if($resCheck["rowNbr"] == 1)
        {
            $_SESSION["loggedin"]  = true;
            $_SESSION["userPhone"] = $resCheck["phone"];
            $_SESSION["userName"]  = $resCheck["name"];
            $_SESSION["userEmail"] = $resCheck["email"];
            $_SESSION["userToken"] = $_COOKIE["userToken"];
            $_SESSION["isAdmin"]   = ($resCheck["role"] == "ADMIN") ? true : false;

            $userPhone = $resCheck["phone"];
            $userEmail = $resCheck["email"];
            $userName  = $resCheck["name"];
            $isAdmin   = $_SESSION["isAdmin"];

        }
    }
}
?>