function validateProgram() {

    let programID =
        document.getElementById("program_id").value.trim();

    let programName =
        document.getElementById("program_name").value.trim();

    let country =
        document.getElementById("country").value;

    let university =
        document.getElementById("university").value;

    let startDate =
        document.getElementById("start_date").value;

    let endDate =
        document.getElementById("end_date").value;

    let deadline =
        document.getElementById("deadline").value;

    let seats =
        document.getElementById("available_seats").value;

    let description =
        document.getElementById("description").value.trim();


    let error =
        document.getElementById("js_error");


    /* Clear previous error */

    error.innerHTML = "";

    error.style.display = "none";


    /* Check empty fields */

    if (
        programID == "" ||
        programName == "" ||
        country == "" ||
        university == "" ||
        startDate == "" ||
        endDate == "" ||
        deadline == "" ||
        seats == "" ||
        description == ""
    ) {

        error.innerHTML =
            "All fields are required.";

        error.style.display = "block";

        return false;

    }


    /* Check Program ID */

    if (isNaN(programID)) {

        error.innerHTML =
            "Program ID must contain numbers only.";

        error.style.display = "block";

        return false;

    }


    /* Check Program Name */

    if (!/^[A-Za-z0-9 ]+$/.test(programName)) {

        error.innerHTML =
            "Program name contains invalid characters.";

        error.style.display = "block";

        return false;

    }


    /* Check seats */

    if (seats < 1) {

        error.innerHTML =
            "Available seats must be at least 1.";

        error.style.display = "block";

        return false;

    }


    /* Check dates */

    if (endDate < startDate) {

        error.innerHTML =
            "End date cannot be before start date.";

        error.style.display = "block";

        return false;

    }


    /* Check deadline */

    if (deadline > startDate) {

        error.innerHTML =
            "Application deadline should be before the program start date.";

        error.style.display = "block";

        return false;

    }


    return true;

}


/* ================= SEARCH ================= */

function searchProgram() {

    let search =
        document.getElementById("search_program").value.trim();


    if (search == "") {

        alert("Please enter a program name.");

        return;

    }

}