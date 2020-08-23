<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/STEMA/PhpUtils/checkLoginStatus.php");

    if(!$user["userOk"])
    {//AUTOMATIC LOGOUT
      logout();
    }
    
    $sql = "SELECT * FROM users WHERE role = 'ADMIN' ;";

    $query = mysqli_query($dbConx, $sql);  
    
    include("../MainElements/doctype.html");
?>
    <title>Stema Info</title>

    <link rel="stylesheet" type="text/css" href="../MainCss/header.css"/>
    <link rel="stylesheet" type="text/css" href="../MainCss/info.css"/>
    <script src="../MainJs/header.js"></script>

</head>

    <body>
        <div class="container main-container">
            <?php 
                include_once($_SERVER["DOCUMENT_ROOT"]."/Stema/MainElements/header.php");
            ?>

            <h4 class="info-title">
                About This App
            </h4>
            <p class="info">
                Search and scan food to get ingredients, additives and nutrition facts.</br>
                The app allows you to scan multiple products already contained in the stema database.</br>
            </p>

            <h4 class="info-title">
                About Stema
            </h4>

            <p class="info">
                Stema is a non-profit company whose sole purpose is to allow users to choose products that are good for them.</br>
                </br>
                We don't make up things, we rely on pure sience!
            </p>

            <h4 class="info-title">
                How To Use Stema?
            </h4>

            <div class="info">
                    <p>-In the home page, type the name of a product, its variant or a barcode then press in the search icon.</p>
                    <p>-You can also search for a product by pressing on the "scan" icon in the home page then scanning its actual barcode.</p>
                    <p>-By pressing on one of the search results, you will see the its details.</p>
                    <p>-A list of Nutrinional, allegies and additives facts of food ingredients is available by pressing on the burger menu icon then choosing:</br>
                    "Nutritional Facts", "Allergies" or "Additives"</p>
                    <p>-If you don't have an account you can sign up for free by pressing on the burger menu icon then on Login or you can press on "Book Now" on the top right corner.</p>
                    <p>-Some features will be available once you log in!</p>
                    <p>-Once you have logged in you can view your phone number and your email address or change your name and password by pressing on the burger menu icon then "My Profile".</p>
                    <p>-You can add a product to your favorite list by pressing the heart icon in the details page.</p>
                    <p>-You can see you list of favorite products by pressing on the burger menu icon then on "My Favorites".</p>
                    <p>-You can see the last 50 products that you have searched for by pressing on the burger menu icon then on "history".</p>
                </ul>
            </div>
            
            <h4 class="info-title">
                Our Team
            </h4>
            <p class="info">All product information is filled in by our nutritionist, she is one of the best in her field.</br>
                <?php
                    $row = mysqli_fetch_assoc($query);
                    $nutriName = $row["name"];
                    $nutriEmail = $row["email"];
                    mysqli_free_result($query);
                ?>
                </br>
                <strong>Name :</strong> <?php echo $nutriName?></br>
                <strong> Email :</strong> <?php echo $nutriEmail?> 
            </p>
        </div>
    </body>

</html>