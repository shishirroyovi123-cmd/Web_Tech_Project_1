function validateLogin() {

    let username =
        document.getElementById("username").value.trim();

    let password =
        document.getElementById("password").value;


    let error =
        document.getElementById("js_error");


    /* Clear previous error */

    error.innerHTML = "";

    error.style.display = "none";


    /* Check all fields */

    if (username == "" || password == "") {

        error.innerHTML =
            "All fields are required.";

        error.style.display = "block";

        return false;

    }


    /* Check username */

    if (username.length < 3) {

        error.innerHTML =
            "Username or User ID must be at least 3 characters.";

        error.style.display = "block";

        return false;

    }


    /* Check password */

    if (password.length < 6) {

        error.innerHTML =
            "Password must be at least 6 characters.";

        error.style.display = "block";

        return false;

    }


    return true;

}