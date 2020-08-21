<form class="form" method="POST" action=<?php echo $phpSelf;?>>
    <span class="search-bar-wraper">
        <input class="search-bar" type="text" placeholder="<?php echo $placeHolder;?>" name="userInput" value="<?php $inpt = (isset($_POST["userInput"])) ? $userInput : "" ; echo $inpt?>">
        <input class="search-icon" type="submit" value="">
    </span>
    <?php
        $href = "";
        switch($pageIdx)
        {
            case "ING":
                $href = "ingrs.php";
                break;
            case "PRD":
                $href = "prods.php";
                break;
            case "VAR":
                $href = "varis.php";
                break;
            case "BRD":
                $href = "brnds.php";
                break;
        }    
    ?>
    <a class="button-add" href="<?php echo $href;?>">Add</a>
</form>