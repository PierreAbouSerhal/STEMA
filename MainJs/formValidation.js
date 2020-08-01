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