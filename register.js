function validateForm() {

    let name =
        document.getElementById("name").value.trim();

    let user_id =
        document.getElementById("user_id").value.trim();

    let email =
        document.getElementById("email").value.trim();

    let username =
        document.getElementById("username").value.trim();

    let password =
        document.getElementById("password").value;

    let confirm_password =
        document.getElementById("confirm_password").value;

    let role =
        document.getElementById("role").value;


    let error =
        document.getElementById("js_error");


    /* Clear previous error */

    error.innerHTML = "";

    error.style.display = "none";


    /* ================= ALL FIELDS EMPTY ================= */

    if (
        name == "" ||
        user_id == "" ||
        email == "" ||
        username == "" ||
        password == "" ||
        confirm_password == "" ||
        role == ""
    ) {

        error.innerHTML =
            "All fields are required.";

        error.style.display = "block";

        return false;

    }


    /* ================= NAME ================= */

    if (!/^[A-Z][a-zA-Z ]*$/.test(name)) {

        error.innerHTML =
            "Name must start with an uppercase letter.";

        error.style.display = "block";

        return false;

    }


    /* ================= EMAIL ================= */

    if (!email.includes("@")) {

        error.innerHTML =
            "Please enter a valid email address.";

        error.style.display = "block";

        return false;

    }


    /* ================= USERNAME ================= */

    if (username.length < 3) {

        error.innerHTML =
            "Username must be at least 3 characters.";

        error.style.display = "block";

        return false;

    }


    /* ================= PASSWORD ================= */

    if (password.length < 6) {

        error.innerHTML =
            "Password must be at least 6 characters.";

        error.style.display = "block";

        return false;

    }


    /* ================= CONFIRM PASSWORD ================= */

    if (password != confirm_password) {

        error.innerHTML =
            "Passwords do not match.";

        error.style.display = "block";

        return false;

    }


    /* ================= ROLE ================= */

    if (role == "") {

        error.innerHTML =
            "Please select your role.";

        error.style.display = "block";

        return false;

    }


    /* ================= VALID ================= */

    return true;

}