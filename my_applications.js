/* =====================================================
                    SEARCH FORM
===================================================== */

const searchForm = document.querySelector(".search_box form");

const searchInput = document.querySelector(
    'input[name="search"]'
);


/* =====================================================
              SEARCH INPUT VALIDATION
===================================================== */

if (searchForm && searchInput) {

    searchForm.addEventListener(
        "submit",
        function (event) {

            searchInput.value =
                searchInput.value.trim();


            if (searchInput.value === "") {

                event.preventDefault();

                window.location.href =
                    "my_applications.php";

                return;

            }


            const searchButton =
                searchForm.querySelector(
                    'button[type="submit"]'
                );


            if (searchButton) {

                searchButton.innerText =
                    "Searching...";

                searchButton.disabled =
                    true;

            }

        }
    );

}


/* =====================================================
              PRESS "/" TO FOCUS SEARCH
===================================================== */

document.addEventListener(
    "keydown",
    function (event) {

        if (
            event.key === "/" &&
            searchInput
        ) {

            const activeElement =
                document.activeElement;


            const isTyping =
                activeElement.tagName === "INPUT" ||
                activeElement.tagName === "TEXTAREA" ||
                activeElement.tagName === "SELECT";


            if (!isTyping) {

                event.preventDefault();

                searchInput.focus();

            }

        }

    }
);


/* =====================================================
                 EDIT CONFIRMATION
===================================================== */

const editButtons =
    document.querySelectorAll(
        ".edit_btn"
    );


editButtons.forEach(
    function (button) {

        button.addEventListener(
            "click",
            function (event) {

                const confirmed =
                    confirm(
                        "Do you want to edit this application?"
                    );


                if (!confirmed) {

                    event.preventDefault();

                }

            }
        );

    }
);


/* =====================================================
                    CLEAR SEARCH
===================================================== */

const clearButton =
    document.querySelector(
        ".clear_button"
    );


if (clearButton && searchInput) {

    clearButton.addEventListener(
        "click",
        function () {

            searchInput.value = "";

        }
    );

}


/* =====================================================
               APPLICATION CARD ANIMATION
===================================================== */

const applicationCards =
    document.querySelectorAll(
        ".application_card"
    );


applicationCards.forEach(
    function (card) {

        card.addEventListener(
            "mouseenter",
            function () {

                card.style.cursor =
                    "default";

            }
        );

    }
);