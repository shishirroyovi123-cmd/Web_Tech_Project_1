function validateUniversity() {

    let universityID =
        document.getElementById("university_id").value.trim();

    let universityName =
        document.getElementById("university_name").value.trim();

    let country =
        document.getElementById("country").value;

    let universityEmail =
        document.getElementById("university_email").value.trim();

    let universityAddress =
        document.getElementById("university_address").value.trim();


    let error =
        document.getElementById("js_error");


    /* Clear previous error */

    error.innerHTML = "";

    error.style.display = "none";


    /* Check empty fields */

    if (
        universityID == "" ||
        universityName == "" ||
        country == "" ||
        universityEmail == "" ||
        universityAddress == ""
    ) {

        error.innerHTML =
            "All fields are required.";

        error.style.display = "block";

        return false;

    }


    /* Check University ID */

    if (isNaN(universityID)) {

        error.innerHTML =
            "University ID must contain numbers only.";

        error.style.display = "block";

        return false;

    }


    /* Check University Name */

    if (!/^[A-Za-z0-9 .,&'-]+$/.test(universityName)) {

        error.innerHTML =
            "University name contains invalid characters.";

        error.style.display = "block";

        return false;

    }


    /* Check Email */

    if (!universityEmail.includes("@")) {

        error.innerHTML =
            "Please enter a valid university email.";

        error.style.display = "block";

        return false;

    }


    /* Check Address */

    if (universityAddress.length < 5) {

        error.innerHTML =
            "Please enter a valid university address.";

        error.style.display = "block";

        return false;

    }


    return true;

}


/* ================= SEARCH ================= */

function searchUniversity() {

    let search =
        document.getElementById("search_university").value.trim();


    if (search == "") {

        alert("Please enter a university name.");

        return;

    }


    alert("Search will work after database connection.");

}