<?php
  $pageName = basename($_SERVER["PHP_SELF"], ".php");
  $selCls   = "selected";
?>
    <div id="mySidenav" class="sidenav">
      <div class="menu-image-container">
        <img src="../StemaPics/burgerMenuIcons/settings.png" style="width: 150px; height: 150px">
      </div>
      <div class="bottom-border <?php if($pageName == "generalInfo") echo $selCls;?>">
        <a href="../AdminPages/generalInfo.php">General Info</a>
      </div>
      <div class="bottom-border <?php if($_GET["idx"] == "ING") echo $selCls;?>">
        <a href="../AdminPages/manage.php?idx=ING">Manage Ingredients</a>
      </div>
      <div class="bottom-border <?php if($_GET["idx"] == "PRD") echo $selCls;?>">
        <a href="../AdminPages/manage.php?idx=PRD">Manage Products</a>
      </div>
      <div class="bottom-border <?php if($_GET["idx"] == "VAR") echo $selCls;?>">
        <a href="../AdminPages/manage.php?idx=VAR">Manage Variants</a>
      </div>
      <div class="bottom-border <?php if($_GET["idx"] == "BRD") echo $selCls;?>">
        <a href="../AdminPages/manage.php?idx=BRD">Manage Brands</a>
      </div>
      <div class="bottom-border">
        <a href="../MainPHP/index.php">Home Page</a>
      </div>
      
    </div>

    <div class="logo-container sticky-top">
      <img id="menuIcon" src="../StemaPics/burger-menu.png" alt="Menu" style="cursor:pointer" onclick="openOrClose();">
    
      <a href="../MainPHP/index.php">
        <img id="logoName" src="../StemaPics/Stema-in-header.png" alt="Stema" style="cursor: pointer">
      </a>
      <span></span>
    </div>
    