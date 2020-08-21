function validateSignup(){
    valid = true;

    //0 -->Name; 1-->Phone; 2-->Email; 3-->Pass1; 4-->Pass2  
    let inputs = document.getElementsByTagName("input");

    let errorName  = document.getElementById("errorName");
    let errorPhone = document.getElementById("errorPhone");
    let errorEmail = document.getElementById("errorEmail");
    let errorPass1 = document.getElementById("errorPass1");
    let errorPass2 = document.getElementById("errorPass2");

    if(inputs[0].value == "")
    {
        inputs[0].style.borderColor = "red";        
        errorName.innerHTML = "Enter your name";
    }
    else
    {
        inputs[0].style.borderColor = "green";
        errorName.innerHTML = "";
    }

    if(inputs[1].value == "")
    {
        inputs[1].style.borderColor = "red";
        errorPhone.innerHTML = "Enter your phone number"
    }
    else if(isNaN(inputs[1].value))
    {
        inputs[1].style.borderColor = "red";
        errorPhone.innerHTML = "Invalid phone number";
    }
    else
    {
        inputs[1].style.borderColor = "green";
        errorPhone.innerHTML = "";
    }
    

    if(inputs[2].value == "")
    {
        inputs[2].style.borderColor = "red";
        errorEmail.innerHTML = "Enter your email address";
    }
    else if(!inputs[2].value.includes("@"))
    {
        inputs[2].style.borderColor = "red";
        errorEmail.innerHTML = "Invalid email address";
    }
    else
    {
        inputs[2].style.borderColor = "green";
        errorEmail.innerHTML = "";
    }

    if(inputs[3].value == "")
    {
        inputs[3].style.borderColor = "red";
        errorPass1.innerHTML = "Enter your password";
    }
    else if(inputs[3].value.length < 4)
    {
        inputs[3].style.borderColor = "red";
        errorPass1.innerHTML = "Use 4 characters or more for your password";
    }
    else if(inputs[4].value != inputs[3].value)
    {
        inputs[3].style.borderColor = "red";
        inputs[4].style.borderColor = "red";
        errorPass1.innerHTML = "";
        errorPass2.innerHTML = "Passwords didn't match, please try again";
    }
    else
    {
        inputs[3].style.borderColor = "green";
        inputs[4].style.borderColor = "green";
        errorPass1.innerHTML = "";
        errorPass2.innerHTML = "";
    }

    for(let i = 0; i < inputs.length; i++)
    {
        if(inputs[i].style.borderColor != "green")
        {
            valid = false;
            break;
        }
    }

    return valid;
    
}

function valdateLogin()
{
    let valid = true;

    let inputs = document.getElementsByTagName("input");

    let errorPhone = document.getElementById("errorPhone");
    let errorPass  = document.getElementById("errorPass");

    if(inputs[0].value == "")
    {
        errorPhone.innerHTML = "Please enter your phone number";
        inputs[0].style.borderColor = "red";
    }
    else if(isNaN(inputs[0].value))
    {
        errorPhone.innerHTML = "Invalid phone number";
        inputs[0].style.borderColor = "red";
    }
    else
    {
        errorPhone.innerHTML = "";
        inputs[0].style.borderColor = "green";
    }

    if(inputs[1].value == "")
    {
        errorPass.innerHTML = "Please enter your password";
        inputs[1].style.borderColor = "red";
    }
    else if(inputs[1].value.length < 4)
    {
        errorPass.innerHTML = "Use 4 characters or more for your password";
        inputs[1].style.borderColor = "red";
    }
    else
    {
        errorPass.innerHTML = "";
        inputs[1].style.borderColor = "green";
    }

    for(let i = 0; i < inputs.length; i++)
    {
        if(inputs[i].style.borderColor != "green")
        {
            valid = false;
            break;
        }
    }

    return valid;

}

function validateForgPass()
{
    let valid = true;

    let emailInput = document.getElementById("userEmail");

    let errorEmail = document.getElementById("errorEmail");

    if(emailInput.value == "")
    {
        emailInput.style.borderColor = "red";
        errorEmail.innerHTML = "Enter your email";
    }
    else if(!emailInput.value.includes("@"))
    {
        emailInput.style.borderColor = "red";
        errorEmail.innerHTML = "Invalid email address";
    }
    else
    {
        emailInput.style.borderColor = "green";
        errorEmail.innerHTML = "";
    }

    if(emailInput.style.borderColor != "green")
    {
        valid = false;
    }

    return valid;
}

function validateMyProfile()
{
    let valid = true;

    let inputs = document.getElementsByClassName("editable");

    let errorName     = document.getElementById("errorName");
    let errorNewPass1 = document.getElementById("errorNewPass1");
    let errorNewPass2 = document.getElementById("errorNewPass2");

    if(inputs[0].value == "")
    {
        inputs[0].style.borderColor = "red";
        errorName.innerHTML = "Enter your name";
    }
    else
    {
        inputs[0].style.borderColor = "green";
        errorName.innerHTML = "";
    }

    if(inputs[1].value != "")
    {
        inputs[1].style.borderColor = "green";
        
        if(inputs[2].value.length < 4)
        {
            inputs[2].style.borderColor = "red";
            errorNewPass1.innerHTML = "Use 4 characters or more for your password ";
        }
        else
        {
            inputs[2].style.borderColor = "green";
            errorNewPass1.innerHTML = "";

            if(inputs[3].value != inputs[2].value)
            {
                inputs[2].style.borderColor = "red";
                inputs[3].style.borderColor = "red";
                errorNewPass2.innerHTML = "Passwords didn't match, please try again";
            }
            else
            {
                inputs[2].style.borderColor = "green";
                inputs[3].style.borderColor = "green";
                errorNewPass2.innerHTML = "";   
            }
        }
    }
    
    for(let i = 0; i < inputs.length; i++)
    {
        if(inputs[i].style.borderColor != "green")
        {
            valid = false;
            break;
        }
    }

    return valid;

}

function validateIngrs()
{
    let valid = true;

    let IngrName   = document.getElementById("IngrName");
    let errorName  = document.getElementById("errorName");
    let nutriFacts = document.getElementsByClassName("nutriFacts");
    let errorId    = "";

    let fuData     = document.getElementById("file-input");
    let errorImage = document.getElementById("errorImage");

    let fileUploadPath = fuData.value;

    if (fileUploadPath != '')
    {
        var extension = fileUploadPath.substring(fileUploadPath.lastIndexOf('.') + 1).toLowerCase();

        if (extension == "jpg" || extension == "png" || extension == "jpeg" || extension == "gif") 
        {
            if (fuData.files && fuData.files[0]) 
            {
                var size = fuData.files[0].size;
                if(size > 500000)
                {
                    valid = false;
                    errorImage.innerHTML = "Image size is bigger than 500KB";
                }
                else
                {
                    errorImage.innerHTML = "";
                }
            }
        }
        else 
        {
            valid = false;
            errorImage.innerHTML = "Photo only allows file types of JPG, PNG, JPEG, GIF";
        }
    }

    if(IngrName.value == "")
    {
        IngrName.style.borderColor = "red";
        errorName.innerHTML = "Ingredient Name is empty";
        valid = false;
    }

    for(let i = 0; i < nutriFacts.length; i++)
    {
        if(isNaN(nutriFacts[i].value))
        {
            valid = false;
            nutriFacts[i].style.borderColor = "red";
            errorId = "errorNutriFact" + i;
            document.getElementById(errorId).innerHTML = "Fact is not numeric";
        }
    }

    return valid;
}

function validateProds()
{
    let valid = true;
    let score = ["A", "B", "C", "D", "E"];
    let qtyInputNbr = 0;

    //0 -->NAME; 1-->SCORE; 2-->INFO
    let inputs = document.getElementsByClassName("product");
    let qtys   = document.getElementsByClassName("quantity-input");
    
    let errorName  = document.getElementById("errorProduct0");
    let errorScore = document.getElementById("errorProduct1");
    let errorBrand = document.getElementById("errorBrand");
    let errorQty   = document.getElementById("errorQty");
    let brand      = document.getElementById("brand");

    if(inputs[0].value == "")
    {
        inputs[0].style.borderColor = "red";        
        errorName.innerHTML = "Product name is empty";
    }
    else
    {
        inputs[0].style.borderColor = "green";
        errorName.innerHTML = "";
    }

    if(inputs[1].value == "")
    {
        inputs[1].style.borderColor = "red";
        errorScore.innerHTML = "Nutri Score is empty"
    }
    else if(score.indexOf(inputs[1].value.toUpperCase()) === -1)
    {
        inputs[1].style.borderColor = "red";
        errorScore.innerHTML = "Choose between 'A','B','C','D','E'";
    }
    else
    {
        inputs[1].style.borderColor = "green";
        errorScore.innerHTML = "";
    }

    if(brand.value == "")
    {
        brand.style.borderColor = "red";
        errorBrand.innerHTML = "Product brand is empty";
    }
    else
    {
        brand.style.borderColor = "green";
        errorBrand.innerHTML = "";
    }

    inputs[2].style.borderColor = "green"; //INFO CAN BE EMPTY

    for(let i = 0; i < inputs.length; i++)
    {
        if(inputs[i].style.borderColor != "green")
        {
            valid = false;
            break;
        }
    }

    if(brand.style.borderColor != "green")
    {
        valid = false;
    }

    for(let i = 0; i < qtys.length; i++)
    {   
        if(!qtys[i].disabled)
        {
            if(qtys[i].value == "")
            {
                qtys[i].style.borderColor = "red";
                valid = false;
            }
            else
            {
                qtys[i].style.borderColor = "green";
            }
        }
        else
        {
            qtyInputNbr++;
            qtys[i].style.border = "1px solid #ced4da";
        }
    }

    if(qtyInputNbr == qtys.length)
    {
        errorQty.innerHTML = "No ingredient is selected!";
        valid = false;
    }

    return valid;
}

function validateVaris()
{
    let valid = true;

    let variName   = document.getElementById("Name");
    let variProd   = document.getElementById("productId");
    let variVol    = document.getElementById("Volume");
    let variBCode  = document.getElementById("BarCode");

    let errorName  = document.getElementById("errorName");
    let errorProd  = document.getElementById("errorProduct");
    let errorVol   = document.getElementById("errorVolume");
    let errorBCode = document.getElementById("errorBarCode");

    let fuData1    = document.getElementById("file-input-image1");
    let fuData2    = document.getElementById("file-input-image2");
    let fuData3    = document.getElementById("file-input-image3");
    
    let errorImg1  = document.getElementById("errorImage1");
    let errorImg2  = document.getElementById("errorImage2");
    let errorImg3  = document.getElementById("errorImage3");

    let fileUploadPath1 = fuData1.value;
    let fileUploadPath2 = fuData2.value;
    let fileUploadPath3 = fuData3.value;

    if (fileUploadPath1 != '')
    {
        var extension = fileUploadPath1.substring(fileUploadPath1.lastIndexOf('.') + 1).toLowerCase();

        if (extension == "jpg" || extension == "png" || extension == "jpeg" || extension == "gif") 
        {
            if (fuData1.files && fuData1.files[0]) 
            {
                var size = fuData1.files[0].size;
                if(size > 500000)
                {
                    valid = false;
                    errorImg1.innerHTML = "Image size is bigger than 500KB";
                }
                else
                {
                    errorImg1.innerHTML = "";
                }
            }
        }
        else 
        {
            valid = false;
            errorImg1.innerHTML = "Photo only allows file types of JPG, PNG, JPEG, GIF";
        }
    }

    if (fileUploadPath2 != '')
    {
        var extension = fileUploadPath2.substring(fileUploadPath2.lastIndexOf('.') + 1).toLowerCase();

        if (extension == "jpg" || extension == "png" || extension == "jpeg" || extension == "gif") 
        {
            if (fuData2.files && fuData2.files[0]) 
            {
                var size = fuData2.files[0].size;
                if(size > 500000)
                {
                    valid = false;
                    errorImg2.innerHTML = "Image size is bigger than 500KB";
                }
                else
                {
                    errorImg2.innerHTML = "";
                }
            }
        }
        else 
        {
            valid = false;
            errorImg2.innerHTML = "Photo only allows file types of JPG, PNG, JPEG, GIF";
        }
    }

    if (fileUploadPath3 != '')
    {
        var extension = fileUploadPath3.substring(fileUploadPath3.lastIndexOf('.') + 1).toLowerCase();

        if (extension == "jpg" || extension == "png" || extension == "jpeg" || extension == "gif") 
        {
            if (fuData3.files && fuData3.files[0]) 
            {
                var size = fuData3.files[0].size;
                if(size > 500000)
                {
                    valid = false;
                    errorImg3.innerHTML = "Image size is bigger than 500KB";
                }
                else
                {
                    errorImg3.innerHTML = "";
                }
            }
        }
        else 
        {
            valid = false;
            errorImg3.innerHTML = "Photo only allows file types of JPG, PNG, JPEG, GIF";
        }
    }

    if(variName.value == "")
    {
        variName.style.borderColor = "red";
        errorName.innerHTML = "Variant Name is empty";
        valid = false;
    }
    else
    {
        variName.style.borderColor = "green";
        errorName.innerHTML = "";
    }

    if(variProd.value == "")
    {
        variProd.style.borderColor = "red";
        errorProd.innerHTML = "Product is empty";
        valid = false;
    }
    else
    {
        variProd.style.borderColor = "green";
        errorProd.innerHTML = "";
    }

    if(variVol.value == "")
    {
        variVol.style.borderColor = "red";
        errorVol.innerHTML = "Volume Name is empty";
        valid = false;
    }
    else
    {
        variVol.style.borderColor = "green";
        errorVol.innerHTML = "";
    }

    if(variBCode.value == "")
    {
        variBCode.style.borderColor = "red";
        errorBCode.innerHTML = "Bar Code is empty";
        valid = false;
    }
    else
    {
        variBCode.style.borderColor = "green";
        errorBCode.innerHTML = "";
    }

    return valid;
}

function validateBrnds()
{
    let valid = true;
    let brandName = document.getElementById("Name");
    let errorName = document.getElementById("errorName");

    if(brandName.value == "")
    {
        valid = false;
        brandName.style.borderColor = "red";
        errorName.innerHTML = "Brand name is empty";
    }
    else
    {
        brandName.style.borderColor = "green";
        errorName.innerHTML = "";
    }

    return valid;
}