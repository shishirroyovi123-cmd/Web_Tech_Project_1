function validateSearch() {

    let applicationID =
        document.getElementById("application_id").value.trim();

    let student =
        document.getElementById("student").value.trim();

    let program =
        document.getElementById("program").value;

    let country =
        document.getElementById("country").value;

    let status =
        document.getElementById("status").value;


    let error =
        document.getElementById("js_error");


    error.innerHTML = "";

    error.style.display = "none";


    /* If Application ID is entered, check it */

    if (applicationID != "") {

        if (isNaN(applicationID)) {

            error.innerHTML =
                "Application ID must contain numbers only.";

            error.style.display = "block";

            return false;

        }

    }


    /* Check if at least one search/filter is used */

    if (
        applicationID == "" &&
        student == "" &&
        program == "" &&
        country == "" &&
        status == ""
    ) {

        error.innerHTML =
            "Please enter or select something to search.";

        error.style.display = "block";

        return false;

    }


    return true;

}