<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/STEMA/PhpUtils/checkLoginStatus.php");

if(!$user["userOk"])
{//AUTOMATIC LOGOUT
  logout();
}

include("../MainElements/doctype.html");
?>

<title>Stema</title>

<link rel="stylesheet" type="text/css" href="../MainCss/header.css"/>
<link rel="stylesheet" type="text/css" href="../MainCss/index.css" />
<script src="../MainJs/header.js"></script>
</head>
<body>
<div class="container main-container">
  
    <?php 
      include_once($_SERVER["DOCUMENT_ROOT"]."/Stema/MainElements/header.php");
  ?>
  
  <form method="GET" action="searchRes.php">
    <div class="search-container">
      <input class="search-icon" type="submit" value="">
      <input class="search-bar" type="text" placeholder="Start Typing..." name="userInput">
      <img class="scan-icon" src="../StemaPics/scan-image.png" alt="Scan" onclick="window.location.replace('http:/\/localhost/STEMA/MainPhp/barcodeScanner.php')">
    </div>
  </form>

  <div class="nutri-score-container">
    <img class="nutri-score-img" src="../StemaPics/nutriscore-logo-home-page.png" alt="Nutri score logo"/>
    <div class="nutri-score-explanation" >
      <p class="nutri-score-letters a">A -</p>
      <p class="nutri-score-descr a">Abscence of <br>sugars, salt, lipids</p>
      <p class="nutri-score-letters b">B -</p>
      <p class="nutri-score-descr b">No or little of <br>sugars, salt, lipids</p>
      <p class="nutri-score-letters c">C -</p>
      <p class="nutri-score-descr c">Little quatity of <br>sugars, salt, lipids</p>
      <p class="nutri-score-letters d">D -</p>
      <p class="nutri-score-descr d">Considerable quantity of <br>sugars, salt, lipids</p>
      <p class="nutri-score-letters e">E -</p>
      <p class="nutri-score-descr e">Extensive amount of <br>sugars, salt, lipids</p>
    </div>
  </div>

</div>


</body>
</html> 
