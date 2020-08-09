 <?php
  $loggedIn = false;
  $isAdmin  = false;

  if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"])
  {
    $loggedIn = true;

    if(isset($_SESSION["isAdmin"]) && $_SESSION["isAdmin"])
    {
      $isAdmin = true;
    }
  }
  $sideBarClosed = true;

 ?>
    <div id="mySidenav" class="sidenav">
      <div class="menu-image-container">
        <img src="../StemaPics/burgerMenuIcons/immage.png">
      </div>

      <div class="bottom-border logged-out">
        <img class="burger-icons" src="../StemaPics/burgerMenuIcons/log-in.png">
        <a href="../MainPHP/login.php">Login</a>
      </div>
      <div class="bottom-border logged-in">
        <img class="burger-icons" src="../StemaPics/burgerMenuIcons/user.svg">
        <a href="../MainPHP/myProfile.php">My Profile</a>
      </div>
      <div class="bottom-border logged-in">
        <img class="burger-icons" src="../StemaPics/burgerMenuIcons/my-favorites.png">
        <a href="../MainPHP/favorites.php">My Favorites</a>
      </div>
      <div class="bottom-border logged-in">
        <img class="burger-icons" src="../StemaPics/burgerMenuIcons/hirtory.png">
        <a href="../MainPHP/history.php">History</a>
      </div>
      <div class="bottom-border">
        <img class="burger-icons" src="../StemaPics/burgerMenuIcons/nutritional-fact.png">
        <a href="../MainPHP/nutritionFacts.php">Nutritional Facts</a>
      </div>
      <div class="bottom-border">
        <img class="burger-icons" src="../StemaPics/burgerMenuIcons/allergies.png">
        <a href="../MainPHP/alergieFacts.php">Allergies</a>
      </div>
      <div class="bottom-border">
      <img class="burger-icons" src="../StemaPics/burgerMenuIcons/additives.png">
        <a href="../MainPHP/additiveFacts.php">Additives</a>
      </div>
      <div class="bottom-border">
        <img class="burger-icons" src="../StemaPics/burgerMenuIcons/info.png">
        <a href="../MainPHP/info.php">Info</a>
      </div>
      <div class="bottom-border logged-in">
        <img class="burger-icons" src="../StemaPics/burgerMenuIcons/log-out.png">
        <a href="../MainPHP/logout.php">Logout</a>
      </div>
      <?php 
        if($isAdmin)
        {
          echo '
          <div class="bottom-border logged-in">
            <img class="burger-icons" src="../StemaPics/burgerMenuIcons/adjust.svg">
            <a href="../AdminPages/adminPage.php">Update Stema</a>
          </div>
          ';
        }
      ?>
      
    </div>

    <div class="logo-container sticky-top">
      <img id="menuIcon" src="../StemaPics/burger-menu.png" alt="Menu" style="cursor:pointer" onclick="openOrClose();">
    
      <a href="../MainPHP/index.php">
        <img id="logoName" src="../StemaPics/Stema-in-header.png" alt="Stema" style="cursor: pointer">
      </a>
      <?php
        if(!$loggedIn)
        {
          echo '
            <div class="nutritioniste-container" onClick="goToSignUp();">
              <img class="nutritioniste-image" src="../StemaPics/nutriotionst-image.png" alt=""/>
              <span class="book-now-text">Nutritionist<br/>Book<br/>Now<br/></span>
            </div>
          ';
        }
        else
        {
          echo '<span></span>';
        }
      ?>

    </div>
    <script>
      
      const isLoggedIn = <?php echo ($loggedIn)? "true" : "false" ; ?>;  
      hideMenuItems(isLoggedIn);
    </script>