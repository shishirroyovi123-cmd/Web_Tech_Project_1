function validateLogin() {

    let username =
        document.getElementById("username").value.trim();

    let password =
        document.getElementById("password").value;

    let error =
        document.getElementById("js_error");


    /* ================= CLEAR PREVIOUS ERROR ================= */

    error.innerHTML = "";

    error.style.display = "none";


    /* ================= CHECK EMPTY FIELDS ================= */

    if (username == "" || password == "") {

        error.innerHTML =
            "All fields are required.";

        error.style.display = "block";

        return false;

    }


    /* ================= CHECK USERNAME ================= */

    /*
        If the input contains only numbers,
        treat it as User ID.

        User ID can be:
        1
        2
        3
        10
        100

        So no minimum length restriction.
    */

    if (!/^\d+$/.test(username)) {

        if (username.length < 3) {

            error.innerHTML =
                "Username must be at least 3 characters.";

            error.style.display = "block";

            return false;

        }

    }


    /* ================= CHECK PASSWORD ================= */

    if (password.length < 6) {

        error.innerHTML =
            "Password must be at least 6 characters.";

        error.style.display = "block";

        return false;

    }


    /* ================= VALID ================= */

    return true;

}