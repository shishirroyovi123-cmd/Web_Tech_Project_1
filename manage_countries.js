function validateCountry() {

    let countryID =
        document.getElementById("country_id").value.trim();

    let countryName =
        document.getElementById("country_name").value.trim();

    let region =
        document.getElementById("region").value.trim();

    let error =
        document.getElementById("js_error");


    error.innerHTML = "";
    error.style.display = "none";


    if (
        countryID === "" ||
        countryName === "" ||
        region === ""
    ) {

        error.innerHTML = "All fields are required.";
        error.style.display = "block";

        return false;
    }


    if (!/^[0-9]+$/.test(countryID)) {

        error.innerHTML =
            "Country ID must contain numbers only.";

        error.style.display = "block";

        return false;
    }


    if (!/^[A-Za-z ]+$/.test(countryName)) {

        error.innerHTML =
            "Country name must contain letters only.";

        error.style.display = "block";

        return false;
    }


    if (!/^[A-Za-z ]+$/.test(region)) {

        error.innerHTML =
            "Region must contain letters only.";

        error.style.display = "block";

        return false;
    }


    return true;
}


/* =========================================================
   AJAX SEARCH
   ========================================================= */

function searchCountry() {

    let searchBox =
        document.getElementById("search_country");

    let tableBody =
        document.getElementById("country_table_body");


    if (!searchBox || !tableBody) {

        console.log("Search elements not found.");

        return;
    }


    let search =
        searchBox.value.trim();


    if (search === "") {

        alert(
            "Please enter a country ID, name or region."
        );

        return;
    }


    let xhr =
        new XMLHttpRequest();


    xhr.open(
        "GET",
        "controllers/CountryController.php?search=" +
        encodeURIComponent(search),
        true
    );


    xhr.onload = function () {

        if (xhr.status === 200) {

            tableBody.innerHTML =
                xhr.responseText;

        } else {

            alert(
                "Search failed. Status: " +
                xhr.status
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
            document.getElementById("search_country");


        if (searchBox) {

            searchBox.addEventListener(
                "keydown",
                function (event) {

                    if (event.key === "Enter") {

                        event.preventDefault();

                        searchCountry();

                    }

                }
            );

        }

    }
);