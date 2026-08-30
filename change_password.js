function validatePassword() {

    let currentPassword =
        document.getElementById("current_password").value;

    let newPassword =
        document.getElementById("new_password").value;

    let confirmPassword =
        document.getElementById("confirm_password").value;


    let error =
        document.getElementById("js_error");


    /* Clear previous error */

    error.innerHTML = "";

    error.style.display = "none";


    /* Check empty fields */

    if (
        currentPassword == "" ||
        newPassword == "" ||
        confirmPassword == ""
    ) {

        error.innerHTML =
            "All fields are required.";

        error.style.display = "block";

        return false;

    }


    /* Check new password length */

    if (newPassword.length < 6) {

        error.innerHTML =
            "New password must be at least 6 characters.";

        error.style.display = "block";

        return false;

    }


    /* Check password match */

    if (newPassword != confirmPassword) {

        error.innerHTML =
            "New passwords do not match.";

        error.style.display = "block";

        return false;

    }


    /* Check same password */

    if (currentPassword == newPassword) {

        error.innerHTML =
            "New password must be different from current password.";

        error.style.display = "block";

        return false;

    }


    return true;

}