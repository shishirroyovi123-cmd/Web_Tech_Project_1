function validateForgotPassword() {

    let recovery =
        document.getElementById("recovery").value.trim();


    let error =
        document.getElementById("js_error");


    /* Clear previous error */

    error.innerHTML = "";

    error.style.display = "none";


    /* Empty field */

    if (recovery == "") {

        error.innerHTML =
            "All fields are required.";

        error.style.display = "block";

        return false;

    }


    /* Check email */

    if (recovery.includes("@")) {

        if (
            !recovery.includes(".")
        ) {

            error.innerHTML =
                "Please enter a valid email address.";

            error.style.display = "block";

            return false;

        }

    }


    /* Check phone */

    else {

        if (
            isNaN(recovery) ||
            recovery.length < 10 ||
            recovery.length > 15
        ) {

            error.innerHTML =
                "Please enter a valid email or phone number.";

            error.style.display = "block";

            return false;

        }

    }


    return true;

}