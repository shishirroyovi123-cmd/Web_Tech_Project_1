function validateStatus() {

    let application =
        document.getElementById("application_id").value;


    let error =
        document.getElementById("js_error");


    error.innerHTML = "";

    error.style.display = "none";


    /* Check application */

    if (application == "") {

        error.innerHTML =
            "Please select an application.";

        error.style.display = "block";

        return false;

    }


    return true;

}