<form class="form" method="POST" action=<?php echo $phpSelf;?>>
    <span class="search-bar-wraper">
        <input class="search-bar" type="text" placeholder="<?php echo $placeHolder;?>" name="userInput" value="<?php $inpt = (isset($_POST["userInput"])) ? $userInput : "" ; echo $inpt?>">
        <input class="search-icon" type="submit" value="">
    </span>
    <a class="button-add" href="ingrs.php">Add</a>
</form>