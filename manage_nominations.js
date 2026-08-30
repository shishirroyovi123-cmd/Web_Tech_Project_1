(function () {

    document.addEventListener(
        "DOMContentLoaded",
        function () {

            autoHideMessages();
            setDefaultDate();
            setupApplicationUniversity();

        }
    );


    /* =====================================================
       AUTO HIDE MESSAGE
    ===================================================== */

    function autoHideMessages() {

        var errorMsg =
            document.querySelector(
                '[style*="color:red"]'
            );

        var successMsg =
            document.querySelector(
                '[style*="color:green"]'
            );


        if (errorMsg) {

            setTimeout(
                function () {

                    errorMsg.style.display = "none";

                },
                5000
            );

        }


        if (successMsg) {

            setTimeout(
                function () {

                    successMsg.style.display = "none";

                },
                5000
            );

        }

    }


    /* =====================================================
       DEFAULT DATE
    ===================================================== */

    function setDefaultDate() {

        var dateInput =
            document.getElementById(
                "nomination_date"
            );


        if (
            dateInput &&
            dateInput.value === ""
        ) {

            var today = new Date();

            var year =
                today.getFullYear();

            var month =
                String(
                    today.getMonth() + 1
                ).padStart(2, "0");

            var day =
                String(
                    today.getDate()
                ).padStart(2, "0");


            dateInput.value =
                year +
                "-" +
                month +
                "-" +
                day;

        }

    }


    /* =====================================================
       SHOW SECTION
    ===================================================== */

    window.showSection =
        function (section) {

            var element =
                document.getElementById(
                    section
                );


            if (element) {

                element.scrollIntoView(
                    {
                        behavior: "smooth",
                        block: "start"
                    }
                );

            }

        };


    /* =====================================================
       BASIC AJAX - EDIT NOMINATION
    ===================================================== */

    window.editNomination =
        function (nominationId) {


            var updateSection =
                document.getElementById(
                    "update"
                );


            if (updateSection) {

                updateSection.style.display =
                    "block";

            }


            var request =
                new XMLHttpRequest();


            request.open(
                "GET",
                "manage_nominations.php?ajax=get_nomination&nomination_id=" +
                nominationId,
                true
            );


            request.onreadystatechange =
                function () {


                    if (
                        request.readyState === 4
                    ) {


                        if (
                            request.status === 200
                        ) {


                            var data;


                            try {

                                data =
                                    JSON.parse(
                                        request.responseText
                                    );

                            }

                            catch (error) {

                                showToast(
                                    "Invalid server response.",
                                    "error"
                                );

                                return;

                            }


                            if (
                                data.success
                            ) {


                                document.getElementById(
                                    "update_nomination_id"
                                ).value =
                                    data.data.nomination_id || "";


                                document.getElementById(
                                    "update_application_id"
                                ).value =
                                    data.data.application_id || "";


                                document.getElementById(
                                    "update_university_id"
                                ).value =
                                    data.data.university_id || "";


                                document.getElementById(
                                    "update_date"
                                ).value =
                                    data.data.nomination_date || "";


                                document.getElementById(
                                    "update_status"
                                ).value =
                                    data.data.status || "";


                                showToast(
                                    "Nomination loaded!",
                                    "success"
                                );

                            }


                            else {

                                showToast(
                                    data.message ||
                                    "Error loading nomination.",
                                    "error"
                                );

                            }

                        }


                        else {

                            showToast(
                                "Error loading nomination.",
                                "error"
                            );

                        }

                    }

                };


            request.send();


            if (updateSection) {

                updateSection.scrollIntoView(
                    {
                        behavior: "smooth",
                        block: "start"
                    }
                );

            }

        };


    /* =====================================================
       UPDATE STATUS SECTION
    ===================================================== */

    window.updateStatus =
        function (
            nominationId,
            currentStatus
        ) {


            var statusSection =
                document.getElementById(
                    "status"
                );


            if (statusSection) {


                statusSection.style.display =
                    "block";


                document.getElementById(
                    "status_nomination_id"
                ).value =
                    nominationId;


                document.getElementById(
                    "current_status"
                ).value =
                    currentStatus;


                document.getElementById(
                    "new_status"
                ).value =
                    "";


                statusSection.scrollIntoView(
                    {
                        behavior: "smooth",
                        block: "start"
                    }
                );

            }

        };


    /* =====================================================
       CANCEL EDIT
    ===================================================== */

    window.cancelEdit =
        function () {


            var updateSection =
                document.getElementById(
                    "update"
                );


            if (updateSection) {


                updateSection.style.display =
                    "none";


                document.getElementById(
                    "update_nomination_id"
                ).value =
                    "";


                document.getElementById(
                    "update_application_id"
                ).value =
                    "";


                document.getElementById(
                    "update_university_id"
                ).value =
                    "";


                document.getElementById(
                    "update_date"
                ).value =
                    "";


                document.getElementById(
                    "update_status"
                ).value =
                    "";

            }

        };


    /* =====================================================
       CANCEL STATUS
    ===================================================== */

    window.cancelStatus =
        function () {


            var statusSection =
                document.getElementById(
                    "status"
                );


            if (statusSection) {


                statusSection.style.display =
                    "none";


                document.getElementById(
                    "status_nomination_id"
                ).value =
                    "";


                document.getElementById(
                    "current_status"
                ).value =
                    "";


                document.getElementById(
                    "new_status"
                ).value =
                    "";

            }

        };


    /* =====================================================
       SEARCH NOMINATION
    ===================================================== */

    window.searchNomination =
        function () {


            var input =
                document.getElementById(
                    "search_nomination"
                );


            var filterStatus =
                document.getElementById(
                    "filter_status"
                );


            var tableBody =
                document.getElementById(
                    "nomination_table_body"
                );


            if (
                !input ||
                !filterStatus ||
                !tableBody
            ) {

                return;

            }


            var searchTerm =
                input.value
                .toLowerCase()
                .trim();


            var statusFilter =
                filterStatus.value
                .toLowerCase()
                .trim();


            var rows =
                tableBody.getElementsByTagName(
                    "tr"
                );


            for (
                var i = 0;
                i < rows.length;
                i++
            ) {


                var row =
                    rows[i];


                var cells =
                    row.getElementsByTagName(
                        "td"
                    );


                if (
                    cells.length === 0
                ) {

                    continue;

                }


                var rowText =
                    row.textContent
                    .toLowerCase();


                var statusCell =
                    cells[7];


                var statusText =
                    "";


                if (statusCell) {

                    statusText =
                        statusCell.textContent
                        .toLowerCase()
                        .trim();

                }


                var matchSearch =
                    searchTerm === "" ||
                    rowText.indexOf(
                        searchTerm
                    ) !== -1;


                var matchStatus =
                    statusFilter === "" ||
                    statusText.indexOf(
                        statusFilter
                    ) !== -1;


                if (
                    matchSearch &&
                    matchStatus
                ) {

                    row.style.display = "";

                }

                else {

                    row.style.display =
                        "none";

                }

            }

        };


    /* =====================================================
       CREATE VALIDATION
    ===================================================== */

    window.validateCreateNomination =
        function () {


            var appId =
                document.getElementById(
                    "application_id"
                );


            var uniId =
                document.getElementById(
                    "university_id"
                );


            var status =
                document.getElementById(
                    "nomination_status"
                );


            if (
                !appId ||
                appId.value === ""
            ) {

                showToast(
                    "Please select an approved application.",
                    "error"
                );

                return false;

            }


            if (
                !uniId ||
                uniId.value === ""
            ) {

                showToast(
                    "Please select a host university.",
                    "error"
                );

                return false;

            }


            if (
                !status ||
                status.value === ""
            ) {

                showToast(
                    "Please select a status.",
                    "error"
                );

                return false;

            }


            return true;

        };


    /* =====================================================
       UPDATE VALIDATION
    ===================================================== */

    window.validateUpdateNomination =
        function () {


            var nominationId =
                document.getElementById(
                    "update_nomination_id"
                );


            var applicationId =
                document.getElementById(
                    "update_application_id"
                );


            var universityId =
                document.getElementById(
                    "update_university_id"
                );


            var status =
                document.getElementById(
                    "update_status"
                );


            if (
                !nominationId ||
                nominationId.value === ""
            ) {

                showToast(
                    "Invalid Nomination ID.",
                    "error"
                );

                return false;

            }


            if (
                !applicationId ||
                applicationId.value === ""
            ) {

                showToast(
                    "Enter valid Application ID.",
                    "error"
                );

                return false;

            }


            if (
                !universityId ||
                universityId.value === ""
            ) {

                showToast(
                    "Select a university.",
                    "error"
                );

                return false;

            }


            if (
                !status ||
                status.value === ""
            ) {

                showToast(
                    "Select a status.",
                    "error"
                );

                return false;

            }


            return true;

        };


    /* =====================================================
       STATUS VALIDATION
    ===================================================== */

    window.validateStatus =
        function () {


            var nominationId =
                document.getElementById(
                    "status_nomination_id"
                );


            var newStatus =
                document.getElementById(
                    "new_status"
                );


            var currentStatus =
                document.getElementById(
                    "current_status"
                );


            if (
                !nominationId ||
                nominationId.value === ""
            ) {

                showToast(
                    "Invalid Nomination ID.",
                    "error"
                );

                return false;

            }


            if (
                !newStatus ||
                newStatus.value === ""
            ) {

                showToast(
                    "Please select a new status.",
                    "error"
                );

                return false;

            }


            if (
                currentStatus &&
                newStatus.value ===
                currentStatus.value
            ) {

                showToast(
                    "New status must be different.",
                    "error"
                );

                return false;

            }


            return true;

        };


    /* =====================================================
       AUTO SELECT UNIVERSITY
    ===================================================== */

    function setupApplicationUniversity() {


        var appSelect =
            document.getElementById(
                "application_id"
            );


        var uniSelect =
            document.getElementById(
                "university_id"
            );


        if (
            !appSelect ||
            !uniSelect
        ) {

            return;

        }


        appSelect.addEventListener(
            "change",
            function () {


                var selectedOption =
                    appSelect.options[
                        appSelect.selectedIndex
                    ];


                var universityId =
                    selectedOption.getAttribute(
                        "data-university-id"
                    );


                if (universityId) {

                    uniSelect.value =
                        universityId;

                }

            }
        );

    }


    /* =====================================================
       SIMPLE TOAST
    ===================================================== */

    function showToast(
        message,
        type
    ) {


        var oldToast =
            document.getElementById(
                "simple_toast"
            );


        if (oldToast) {

            oldToast.remove();

        }


        var toast =
            document.createElement(
                "div"
            );


        toast.id =
            "simple_toast";


        toast.className =
            "custom-toast " + type;


        toast.innerHTML =
            message;


        document.body.appendChild(
            toast
        );


        setTimeout(
            function () {

                toast.style.display =
                    "none";

            },
            4000
        );

    }


})();