function openOrClose(){
  navSize = document.getElementById("mySidenav").style.width;
  if (navSize == "270px") {
      return closeNav();
  }
  return openNav();
}

function openNav() 
{
  document.getElementById("mySidenav").style.width = "270px";
}
  
function closeNav() 
{
  document.getElementById("mySidenav").style.width = "0";
}

function hideMenuItems(isLoggedIn)
{
  let status = (!isLoggedIn) ? "logged-in" : "logged-out" ;
  const itemsToHide = document.getElementsByClassName(status);
  for(let i = 0; i < itemsToHide.length; i++)
  {
    itemsToHide[i].style.display = "none";
  }
}

function goToSignUp()
{
  window.location.replace("http://localhost/STEMA/MainPhp/signup.php");
}