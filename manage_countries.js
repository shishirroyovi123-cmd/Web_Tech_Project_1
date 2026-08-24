function validateCountry() {

    let countryID =
        document.getElementById("country_id").value.trim();

    let countryName =
        document.getElementById("country_name").value.trim();

    let region =
        document.getElementById("region").value.trim();


    let error =
        document.getElementById("js_error");


    /* Clear previous error */

    error.innerHTML = "";

    error.style.display = "none";


    /* Check empty fields */

    if (
        countryID == "" ||
        countryName == "" ||
        region == ""
    ) {

        error.innerHTML =
            "All fields are required.";

        error.style.display = "block";

        return false;

    }


    /* Country ID */

    if (isNaN(countryID)) {

        error.innerHTML =
            "Country ID must contain numbers only.";

        error.style.display = "block";

        return false;

    }


    /* Country Name */

    if (!/^[A-Za-z ]+$/.test(countryName)) {

        error.innerHTML =
            "Country name must contain letters only.";

        error.style.display = "block";

        return false;

    }


    /* Region */

    if (!/^[A-Za-z ]+$/.test(region)) {

        error.innerHTML =
            "Region must contain letters only.";

        error.style.display = "block";

        return false;

    }


    return true;

}


/* ================= SEARCH ================= */

function searchCountry() {

    let search =
        document.getElementById("search_country").value.trim();


    if (search == "") {

        alert("Please enter a country name.");

        return;

    }

}