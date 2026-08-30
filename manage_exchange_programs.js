/* =========================================================
   SEPMS - MANAGE EXCHANGE PROGRAMS
   ========================================================= */


/* =========================================================
   VALIDATION
   ========================================================= */

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


    error.innerHTML = "";

    error.style.display = "none";


    /* =====================================================
       EMPTY CHECK
       ===================================================== */

    if (
        programID === "" ||
        programName === "" ||
        country === "" ||
        university === "" ||
        startDate === "" ||
        endDate === "" ||
        deadline === "" ||
        seats === "" ||
        description === ""
    ) {

        error.innerHTML =
            "All fields are required.";

        error.style.display =
            "block";

        return false;
    }


    /* =====================================================
       PROGRAM ID
       ===================================================== */

    if (!/^[0-9]+$/.test(programID)) {

        error.innerHTML =
            "Program ID must contain numbers only.";

        error.style.display =
            "block";

        return false;
    }


    /* =====================================================
       PROGRAM NAME
       ===================================================== */

    if (
        !/^[A-Za-z0-9 .,&'()\-]+$/.test(
            programName
        )
    ) {

        error.innerHTML =
            "Program name contains invalid characters.";

        error.style.display =
            "block";

        return false;
    }


    /* =====================================================
       AVAILABLE SEATS
       ===================================================== */

    if (
        !/^[0-9]+$/.test(seats) ||
        parseInt(seats) < 1
    ) {

        error.innerHTML =
            "Available seats must be at least 1.";

        error.style.display =
            "block";

        return false;
    }


    /* =====================================================
       DATE VALIDATION
       ===================================================== */

    if (endDate < startDate) {

        error.innerHTML =
            "End date cannot be before start date.";

        error.style.display =
            "block";

        return false;
    }


    if (deadline >= startDate) {

        error.innerHTML =
            "Application deadline must be before the program start date.";

        error.style.display =
            "block";

        return false;
    }


    return true;
}



/* =========================================================
   COUNTRY → UNIVERSITY
   ========================================================= */

function loadUniversities(
    countryID,
    selectedUniversityID = ""
) {

    let university =
        document.getElementById(
            "university"
        );


    /* No country selected */

    if (countryID === "") {

        university.innerHTML =
            '<option value="">Select country first</option>';

        university.disabled = true;

        return;
    }


    /* Loading message */

    university.innerHTML =
        '<option value="">Loading universities...</option>';

    university.disabled = true;


    let xhr =
        new XMLHttpRequest();


    xhr.open(
        "GET",
        "controllers/ExchangeProgramController.php?get_universities_by_country=" +
        encodeURIComponent(countryID),
        true
    );


    xhr.onload = function () {

        if (xhr.status === 200) {

            university.innerHTML =
                xhr.responseText;

            university.disabled =
                false;


            /* Select university when editing */

            if (
                selectedUniversityID !== ""
            ) {

                university.value =
                    selectedUniversityID;

            }

        }
        else {

            university.innerHTML =
                '<option value="">Unable to load universities</option>';

            university.disabled =
                true;
        }

    };


    xhr.onerror = function () {

        university.innerHTML =
            '<option value="">Could not connect to server</option>';

        university.disabled =
            true;
    };


    xhr.send();
}



/* =========================================================
   COUNTRY CHANGE
   ========================================================= */

document.addEventListener(
    "DOMContentLoaded",
    function () {

        let country =
            document.getElementById(
                "country"
            );


        if (country) {

            country.addEventListener(
                "change",
                function () {

                    loadUniversities(
                        this.value
                    );

                }
            );

        }

    }
);



/* =========================================================
   EDIT PROGRAM
   ========================================================= */

function editProgram(programID) {

    let xhr =
        new XMLHttpRequest();


    xhr.open(
        "GET",
        "controllers/ExchangeProgramController.php?get_program=" +
        encodeURIComponent(programID),
        true
    );


    xhr.onload = function () {

        if (xhr.status !== 200) {

            alert(
                "Could not load program."
            );

            return;
        }


        let program;


        try {

            program =
                JSON.parse(
                    xhr.responseText
                );

        }
        catch (error) {

            alert(
                "Invalid server response."
            );

            console.log(
                xhr.responseText
            );

            return;
        }


        if (program.error) {

            alert(
                program.error
            );

            return;
        }


        /* =================================================
           FILL FORM
           ================================================= */

        document
            .getElementById("program_id")
            .value =
                program.program_id;


        document
            .getElementById("program_name")
            .value =
                program.program_name;


        document
            .getElementById("country")
            .value =
                program.country_id;


        /*
         * IMPORTANT:
         * Load only universities belonging
         * to the selected country.
         */

        loadUniversities(
            program.country_id,
            program.university_id
        );


        document
            .getElementById("start_date")
            .value =
                program.start_date;


        document
            .getElementById("end_date")
            .value =
                program.end_date;


        document
            .getElementById("deadline")
            .value =
                program.deadline;


        document
            .getElementById("available_seats")
            .value =
                program.available_seats;


        document
            .getElementById("description")
            .value =
                program.description;


        /* =================================================
           CHANGE FORM TO EDIT MODE
           ================================================= */

        document
            .getElementById("form_title")
            .innerHTML =
                "Edit Exchange Program";


        let submitButton =
            document.getElementById(
                "submit_button"
            );


        submitButton.innerHTML =
            "Update";


        submitButton.name =
            "update_program";


        document
            .getElementById(
                "cancel_edit_button"
            )
            .style.display =
                "inline-block";


        /* Program ID should not change */

        document
            .getElementById(
                "program_id"
            )
            .readOnly =
                true;


        /* Scroll to form */

        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });

    };


    xhr.onerror = function () {

        alert(
            "Could not connect to the server."
        );

    };


    xhr.send();
}



/* =========================================================
   CANCEL EDIT
   ========================================================= */

function cancelEdit() {

    let form =
        document.getElementById(
            "program_form"
        );


    form.reset();


    /*
     * Reset university dropdown
     */

    let university =
        document.getElementById(
            "university"
        );


    university.innerHTML =
        '<option value="">Select country first</option>';

    university.disabled =
        true;


    document
        .getElementById(
            "form_title"
        )
        .innerHTML =
            "Add Exchange Program";


    let submitButton =
        document.getElementById(
            "submit_button"
        );


    submitButton.innerHTML =
        "Save";


    submitButton.name =
        "add_program";


    document
        .getElementById(
            "cancel_edit_button"
        )
        .style.display =
            "none";


    document
        .getElementById(
            "program_id"
        )
        .readOnly =
            false;


    document
        .getElementById(
            "js_error"
        )
        .style.display =
            "none";
}



/* =========================================================
   SEARCH
   ========================================================= */

function searchProgram() {

    let searchBox =
        document.getElementById(
            "search_program"
        );


    let tableBody =
        document.getElementById(
            "program_table_body"
        );


    let search =
        searchBox.value.trim();


    if (search === "") {

        window.location.href =
            "manage_exchange_programs.php";

        return;
    }


    let xhr =
        new XMLHttpRequest();


    xhr.open(
        "GET",
        "controllers/ExchangeProgramController.php?search=" +
        encodeURIComponent(search),
        true
    );


    xhr.onload = function () {

        if (xhr.status === 200) {

            tableBody.innerHTML =
                xhr.responseText;

        }
        else {

            alert(
                "Search failed."
            );

        }

    };


    xhr.onerror = function () {

        alert(
            "Could not connect to the server."
        );

    };


    xhr.send();
}



/* =========================================================
   ENTER KEY SEARCH
   ========================================================= */

document.addEventListener(
    "DOMContentLoaded",
    function () {

        let searchBox =
            document.getElementById(
                "search_program"
            );


        if (searchBox) {

            searchBox.addEventListener(
                "keydown",
                function (event) {

                    if (
                        event.key === "Enter"
                    ) {

                        event.preventDefault();

                        searchProgram();

                    }

                }
            );

        }

    }
);