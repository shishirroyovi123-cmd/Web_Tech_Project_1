function validateApplication() {

    let department =
        document.getElementById("department").value.trim();

    let cgpa =
        document.getElementById("cgpa").value;

    let semester =
        document.getElementById("semester").value;

    let study_term =
        document.getElementById("study_term").value;

    let statement =
        document.getElementById("statement_of_purpose").value.trim();


    let passport =
        document.getElementById("passport_id").files.length;

    let photo =
        document.getElementById("passport_photo").files.length;

    let transcript =
        document.getElementById("academic_transcript").files.length;

    let declaration =
        document.getElementById("declaration").checked;


    let error =
        document.getElementById("js_error");


    error.innerHTML = "";

    error.style.display = "none";


    /* Check required fields */

    if (
        department == "" ||
        cgpa == "" ||
        semester == "" ||
        study_term == "" ||
        statement == ""
    ) {

        error.innerHTML =
            "All required fields are required.";

        error.style.display = "block";

        return false;

    }


    /* Check CGPA */

    if (cgpa < 0 || cgpa > 4) {

        error.innerHTML =
            "CGPA must be between 0 and 4.";

        error.style.display = "block";

        return false;

    }


    /* Check semester */

    if (semester < 1) {

        error.innerHTML =
            "Please enter a valid semester.";

        error.style.display = "block";

        return false;

    }


    /* Check statement */

    if (statement.length < 20) {

        error.innerHTML =
            "Statement of Purpose must contain at least 20 characters.";

        error.style.display = "block";

        return false;

    }


    /* Check Passport / NID */

    if (passport == 0) {

        error.innerHTML =
            "Please upload Passport or National ID.";

        error.style.display = "block";

        return false;

    }


    /* Check Photograph */

    if (photo == 0) {

        error.innerHTML =
            "Please upload Passport-size Photograph.";

        error.style.display = "block";

        return false;

    }


    /* Check Transcript */

    if (transcript == 0) {

        error.innerHTML =
            "Please upload Academic Transcript.";

        error.style.display = "block";

        return false;

    }


    /* Check Declaration */

    if (!declaration) {

        error.innerHTML =
            "Please accept the declaration.";

        error.style.display = "block";

        return false;

    }


    return true;

}