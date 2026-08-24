function validateCreateNomination() {

    let applicationID =
        document.getElementById("application_id").value.trim();

    let studentID =
        document.getElementById("student_id").value.trim();

    let studentName =
        document.getElementById("student_name").value.trim();

    let program =
        document.getElementById("program").value;

    let university =
        document.getElementById("university").value;

    let country =
        document.getElementById("country").value;

    let date =
        document.getElementById("nomination_date").value;

    let status =
        document.getElementById("nomination_status").value;

    let remark =
        document.getElementById("nomination_remark").value.trim();


    let error =
        document.getElementById("js_error");


    error.innerHTML = "";

    error.style.display = "none";


    if (
        applicationID == "" ||
        studentID == "" ||
        studentName == "" ||
        program == "" ||
        university == "" ||
        country == "" ||
        date == "" ||
        status == "" ||
        remark == ""
    ) {

        error.innerHTML =
            "All nomination fields are required.";

        error.style.display = "block";

        return false;

    }


    if (isNaN(applicationID)) {

        error.innerHTML =
            "Application ID must contain numbers only.";

        error.style.display = "block";

        return false;

    }


    if (isNaN(studentID)) {

        error.innerHTML =
            "Student ID must contain numbers only.";

        error.style.display = "block";

        return false;

    }


    if (!/^[A-Za-z ]+$/.test(studentName)) {

        error.innerHTML =
            "Student name must contain letters only.";

        error.style.display = "block";

        return false;

    }


    return true;

}


/* ================= UPDATE NOMINATION ================= */

function validateUpdateNomination() {

    let nominationID =
        document.getElementById("update_nomination_id").value.trim();

    let applicationID =
        document.getElementById("update_application_id").value.trim();

    let studentID =
        document.getElementById("update_student_id").value.trim();

    let program =
        document.getElementById("update_program").value;

    let university =
        document.getElementById("update_university").value;

    let country =
        document.getElementById("update_country").value;

    let date =
        document.getElementById("update_date").value;

    let status =
        document.getElementById("update_status").value;

    let remark =
        document.getElementById("update_remark").value.trim();


    let error =
        document.getElementById("js_error");


    error.innerHTML = "";

    error.style.display = "none";


    if (
        nominationID == "" ||
        applicationID == "" ||
        studentID == "" ||
        program == "" ||
        university == "" ||
        country == "" ||
        date == "" ||
        status == "" ||
        remark == ""
    ) {

        error.innerHTML =
            "All update fields are required.";

        error.style.display = "block";

        return false;

    }


    if (
        isNaN(nominationID) ||
        isNaN(applicationID) ||
        isNaN(studentID)
    ) {

        error.innerHTML =
            "IDs must contain numbers only.";

        error.style.display = "block";

        return false;

    }


    return true;

}


/* ================= UPDATE STATUS ================= */

function validateStatus() {

    let nominationID =
        document.getElementById("status_nomination_id").value.trim();

    let currentStatus =
        document.getElementById("current_status").value;

    let newStatus =
        document.getElementById("new_status").value;

    let remark =
        document.getElementById("status_remark").value.trim();


    let error =
        document.getElementById("js_error");


    error.innerHTML = "";

    error.style.display = "none";


    if (
        nominationID == "" ||
        currentStatus == "" ||
        newStatus == "" ||
        remark == ""
    ) {

        error.innerHTML =
            "All status fields are required.";

        error.style.display = "block";

        return false;

    }


    if (isNaN(nominationID)) {

        error.innerHTML =
            "Nomination ID must contain numbers only.";

        error.style.display = "block";

        return false;

    }


    if (currentStatus == newStatus) {

        error.innerHTML =
            "New status must be different from current status.";

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

        alert("Please enter a nomination ID, application ID, student ID or name.");

        return;

    }


    alert("Search will work after database connection.");

}