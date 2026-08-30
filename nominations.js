function validateNomination() {

    let nominationID =
        document.getElementById("nomination_id").value.trim();

    let nominationDate =
        document.getElementById("nomination_date").value;

    let applicationID =
        document.getElementById("application_id").value;

    let universityID =
        document.getElementById("university_id").value;

    let status =
        document.getElementById("status").value;


    let error =
        document.getElementById("js_error");


    /* Clear previous error */

    error.innerHTML = "";

    error.style.display = "none";


    /* Check empty fields */

    if (
        nominationID == "" ||
        nominationDate == "" ||
        applicationID == "" ||
        universityID == "" ||
        status == ""
    ) {

        error.innerHTML =
            "All fields are required.";

        error.style.display = "block";

        return false;

    }


    /* Check Nomination ID */

    if (isNaN(nominationID)) {

        error.innerHTML =
            "Nomination ID must contain numbers only.";

        error.style.display = "block";

        return false;

    }


    return true;

}


/* ================= SEARCH ================= */

function searchNomination() {

    let search =
        document.getElementById("search_nomination").value.trim();


    if (search == "") {

        alert("Please enter a nomination to search.");

        return;

    }


    alert("Search will work after database connection.");

}