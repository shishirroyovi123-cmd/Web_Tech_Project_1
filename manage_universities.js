/* =========================================================
   SEPMS - MANAGE UNIVERSITIES
   ========================================================= */


/* =========================================================
   FORM VALIDATION
   ========================================================= */

function validateUniversity() {

    let universityID =
        document
        .getElementById("university_id")
        .value
        .trim();


    let universityName =
        document
        .getElementById("university_name")
        .value
        .trim();


    let country =
        document
        .getElementById("country")
        .value;


    let universityEmail =
        document
        .getElementById("university_email")
        .value
        .trim();


    let universityAddress =
        document
        .getElementById("university_address")
        .value
        .trim();


    let error =
        document
        .getElementById("js_error");


    /* ================= CLEAR ERROR ================= */

    error.innerHTML = "";

    error.style.display = "none";


    /* ================= EMPTY CHECK ================= */

    if (
        universityID === "" ||
        universityName === "" ||
        country === "" ||
        universityEmail === "" ||
        universityAddress === ""
    ) {

        error.innerHTML =
            "All fields are required.";

        error.style.display =
            "block";

        return false;
    }


    /* ================= UNIVERSITY ID ================= */

    if (
        !/^[0-9]+$/.test(
            universityID
        )
    ) {

        error.innerHTML =
            "University ID must contain numbers only.";

        error.style.display =
            "block";

        return false;
    }


    /* ================= UNIVERSITY NAME ================= */

    if (
        !/^[A-Za-z0-9 .,&'-]+$/.test(
            universityName
        )
    ) {

        error.innerHTML =
            "Invalid university name.";

        error.style.display =
            "block";

        return false;
    }


    /* ================= EMAIL ================= */

    let emailPattern =
        /^[^\s@]+@[^\s@]+\.[^\s@]+$/;


    if (
        !emailPattern.test(
            universityEmail
        )
    ) {

        error.innerHTML =
            "Please enter a valid email address.";

        error.style.display =
            "block";

        return false;
    }


    /* ================= ADDRESS ================= */

    if (
        universityAddress.length < 3
    ) {

        error.innerHTML =
            "Please enter a valid university address.";

        error.style.display =
            "block";

        return false;
    }


    return true;
}



/* =========================================================
   SEARCH UNIVERSITY
   ========================================================= */

function searchUniversity() {

    let searchBox =
        document
        .getElementById(
            "search_university"
        );


    let tableBody =
        document
        .getElementById(
            "university_table_body"
        );


    /* ================= CHECK ELEMENTS ================= */

    if (!searchBox) {

        console.log(
            "Search box not found."
        );

        return;
    }


    if (!tableBody) {

        console.log(
            "University table body not found."
        );

        return;
    }


    /* ================= SEARCH VALUE ================= */

    let search =
        searchBox
        .value
        .trim();


    /* ================= EMPTY SEARCH ================= */

    if (search === "") {

        alert(
            "Please enter a university ID, name, country, email or address."
        );

        return;
    }


    /* ================= AJAX ================= */

    let xhr =
        new XMLHttpRequest();


    xhr.open(
        "GET",
        "controllers/UniversityController.php?search=" +
        encodeURIComponent(search),
        true
    );


    /* ================= RESPONSE ================= */

    xhr.onload =
        function () {


            if (
                xhr.status === 200
            ) {

                tableBody.innerHTML =
                    xhr.responseText;

            }
            else {

                alert(
                    "Search failed."
                );

            }

        };


    /* ================= CONNECTION ERROR ================= */

    xhr.onerror =
        function () {

            alert(
                "Could not connect to the server."
            );

        };


    /* ================= SEND ================= */

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
                "search_university"
            );


        if (searchBox) {


            searchBox.addEventListener(
                "keydown",
                function (event) {


                    if (
                        event.key === "Enter"
                    ) {

                        event.preventDefault();

                        searchUniversity();

                    }

                }
            );

        }

    }
);