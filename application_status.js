function validateStatus()
{
    var applicationId =
        document.getElementById("application_id");

    var error =
        document.getElementById("js_error");

    if (applicationId.value == "")
    {
        error.innerHTML =
            "Please select an application.";

        error.style.display = "block";

        return false;
    }

    error.style.display = "none";

    return true;
}


function loadApplicationStatus()
{
    var applicationId =
        document.getElementById("application_id").value;

    var error =
        document.getElementById("js_error");


    if (applicationId == "")
    {
        error.innerHTML =
            "Please select an application.";

        error.style.display = "block";

        return;
    }


    error.style.display = "none";


    var xhr = new XMLHttpRequest();


    xhr.open(
        "GET",
        "application_status.php?ajax=get_application&application_id="
        + applicationId,
        true
    );


    xhr.onreadystatechange = function()
    {
        if (xhr.readyState == 4)
        {
            if (xhr.status == 200)
            {
                var data = JSON.parse(xhr.responseText);


                if (data.success)
                {
                    showApplicationData(data);
                }
                else
                {
                    error.innerHTML = data.message;
                    error.style.display = "block";
                }
            }
            else
            {
                error.innerHTML =
                    "Error loading application data.";

                error.style.display = "block";
            }
        }
    };


    xhr.send();
}


function showApplicationData(data)
{
    var application = data.application;


    // ================= APPLICATION INFORMATION =================

    document.getElementById("application_id_value").innerHTML =
        application.application_id;

    document.getElementById("program_id_value").innerHTML =
        application.program_id;

    document.getElementById("program_name_value").innerHTML =
        application.program_name;

    document.getElementById("university_value").innerHTML =
        application.university_name;

    document.getElementById("country_value").innerHTML =
        application.country_name;

    document.getElementById("application_date_value").innerHTML =
        application.application_date;



    // ================= APPLICATION STATUS =================

    var applicationStatus =
        application.application_status.toLowerCase();


    // Step 1
    document.getElementById("submitted_status").innerHTML =
        "Submitted";


    // Step 2
    if (
        applicationStatus == "pending" ||
        applicationStatus == "submitted"
    )
    {
        document.getElementById("review_status").innerHTML =
            "Pending";
    }
    else
    {
        document.getElementById("review_status").innerHTML =
            "Reviewed";
    }


    // Step 3
    if (applicationStatus == "approved")
    {
        document.getElementById("decision_status").innerHTML =
            "Approved";
    }
    else if (applicationStatus == "rejected")
    {
        document.getElementById("decision_status").innerHTML =
            "Rejected";
    }
    else if (applicationStatus == "nominated")
    {
        document.getElementById("decision_status").innerHTML =
            "Approved";
    }
    else
    {
        document.getElementById("decision_status").innerHTML =
            "Pending";
    }



    // ================= COORDINATOR REMARKS =================

    document.getElementById("remark_status").innerHTML =
        application.application_status;

    document.getElementById("remarks_text").innerHTML =
        "Current application status: "
        + application.application_status;



    // ================= NOMINATION =================

    if (data.nomination != null)
    {
        document.getElementById(
            "nomination_status_value"
        ).innerHTML =
            data.nomination.status;


        document.getElementById(
            "nomination_date_value"
        ).innerHTML =
            data.nomination.nomination_date;


        document.getElementById(
            "nomination_remarks_value"
        ).innerHTML =
            "Nomination information available.";
    }
    else
    {
        document.getElementById(
            "nomination_status_value"
        ).innerHTML = "—";


        document.getElementById(
            "nomination_date_value"
        ).innerHTML = "—";


        document.getElementById(
            "nomination_remarks_value"
        ).innerHTML =
            "No nomination available.";
    }



    // ================= EXCHANGE RECORD =================

    var recordBody =
        document.getElementById("exchange_record_body");


    if (data.exchangeRecord != null)
    {
        var record = data.exchangeRecord;


        recordBody.innerHTML =
            "<tr>" +
            "<td>" + record.program_name + "</td>" +
            "<td>" + record.university_name + "</td>" +
            "<td>" + record.country_name + "</td>" +
            "<td>" + record.start_date + "</td>" +
            "<td>" + record.end_date + "</td>" +
            "<td>" + record.status + "</td>" +
            "</tr>";
    }
    else
    {
        recordBody.innerHTML =
            "<tr>" +
            "<td colspan='6' class='empty_data'>" +
            "No exchange record available." +
            "</td>" +
            "</tr>";
    }
}