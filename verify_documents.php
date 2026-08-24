<?php

$error = "";
$success = "";


/* ================= PHP FORM HANDLING ================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    /* Load Application */

    if (isset($_POST["load_application"])) {

        $application_id = trim($_POST["application_id"]);

        if ($application_id == "") {

            $error = "Please select an application.";

        } elseif (!is_numeric($application_id)) {

            $error = "Application ID must contain numbers only.";

        } else {

            $success = "Application loaded successfully.";

        }

    }


    /* Verify Document */

    if (isset($_POST["verify_document"])) {

        $document = $_POST["document"];

        $success = $document . " verified successfully.";

    }


    /* Reject Document */

    if (isset($_POST["reject_document"])) {

        $document = $_POST["document"];

        $success = $document . " rejected.";

    }


    /* Save Remarks */

    if (isset($_POST["save_remarks"])) {

        $remark = trim($_POST["coordinator_remark"]);

        if ($remark == "") {

            $error = "Please enter remarks.";

        } else {

            $success = "Remarks saved successfully.";

        }

    }


    /* Approve Application */

    if (isset($_POST["approve_application"])) {

        $success = "Application approved successfully.";

    }


    /* Reject Application */

    if (isset($_POST["reject_application"])) {

        $success = "Application rejected successfully.";

    }

}

?>


<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>SEPMS - Verify Documents</title>

    <link rel="stylesheet"
          href="verify_documents.css">

</head>


<body>


<!-- ================= HEADER ================= -->

<?php include "header.php"; ?>



<!-- ================= PAGE LAYOUT ================= -->

<div class="page_layout">


    <!-- ================= SIDEBAR ================= -->

    <aside class="sidebar">


        <div class="sidebar_heading">
            COORDINATOR PANEL
        </div>


        <nav class="sidebar_menu">


            <a href="coordinator_dashboard.php"
               class="sidebar_item">

                Dashboard

            </a>


            <a href="review_applications.php"
               class="sidebar_item">

                Review Applications

            </a>


            <a href="verify_documents.php"
               class="sidebar_item active">

                Verify Documents

            </a>


            <a href="manage_nominations.php"
               class="sidebar_item">

               Manage Nominations

            </a>


        </nav>


        <div class="sidebar_bottom">


            <a href="update_profile.php"
               class="sidebar_item">

                Update Profile

            </a>


            <a href="change_password.php"
               class="sidebar_item">

                Change Password

            </a>


            <a href="login.php"
               class="sidebar_item logout">

                Logout

            </a>


        </div>


    </aside>



    <!-- ================= MAIN CONTENT ================= -->

    <main class="main_content">


        <!-- ================= PAGE HEADER ================= -->

        <section class="page_header">

            <h1>
                Verify Documents & Application
            </h1>

            <p>
                Verify submitted documents and make the final
                application decision.
            </p>

        </section>



        <!-- ================= PHP MESSAGE ================= -->

        <?php

        if ($error != "") {

            echo "

            <p style='
                color:red;
                text-align:center;
            '>

                $error

            </p>

            ";

        }


        if ($success != "") {

            echo "

            <p style='
                color:green;
                text-align:center;
            '>

                $success

            </p>

            ";

        }

        ?>



        <!-- ================= SELECT APPLICATION ================= -->

        <section class="content_box">


            <h2>
                Select Application
            </h2>


            <p class="section_description">

                Select the application whose documents you want
                to verify.

            </p>


            <form method="POST"
                  action="">


                <div class="application_selector">


                    <div class="form_group">


                        <label for="application_id">

                            Application ID

                        </label>


                        <select
                            id="application_id"
                            name="application_id"
                        >

                            <option value="">

                                Select Application

                            </option>

                            <!-- Database applications will come here later -->

                        </select>


                    </div>


                    <button
                        type="submit"
                        name="load_application"
                        class="load_button"
                    >

                        Load Application

                    </button>


                </div>


            </form>


        </section>



        <!-- ================= APPLICATION INFORMATION ================= -->

        <section class="content_box">


            <h2>
                Application Information
            </h2>


            <div class="information_grid">


                <div class="information_item">

                    <span class="information_label">
                        Application ID
                    </span>

                    <span class="information_value">
                        —
                    </span>

                </div>


                <div class="information_item">

                    <span class="information_label">
                        Student ID
                    </span>

                    <span class="information_value">
                        —
                    </span>

                </div>


                <div class="information_item">

                    <span class="information_label">
                        Student Name
                    </span>

                    <span class="information_value">
                        —
                    </span>

                </div>


                <div class="information_item">

                    <span class="information_label">
                        Program
                    </span>

                    <span class="information_value">
                        —
                    </span>

                </div>


                <div class="information_item">

                    <span class="information_label">
                        University
                    </span>

                    <span class="information_value">
                        —
                    </span>

                </div>


                <div class="information_item">

                    <span class="information_label">
                        Country
                    </span>

                    <span class="information_value">
                        —
                    </span>

                </div>


                <div class="information_item">

                    <span class="information_label">
                        Application Date
                    </span>

                    <span class="information_value">
                        —
                    </span>

                </div>


                <div class="information_item">

                    <span class="information_label">
                        Application Status
                    </span>

                    <span class="status_badge pending">
                        Pending
                    </span>

                </div>


            </div>

        </section>



        <!-- ================= SUBMITTED DOCUMENTS ================= -->

        <section class="content_box">


            <div class="section_header">


                <div>

                    <h2>
                        Submitted Documents
                    </h2>

                    <p class="section_description">

                        Documents submitted by the student
                        for this application.

                    </p>

                </div>


            </div>



            <div class="document_table_container">


                <table class="document_table">


                    <thead>

                        <tr>

                            <th>
                                Document
                            </th>

                            <th>
                                File Name
                            </th>

                            <th>
                                Upload Date
                            </th>

                            <th>
                                Verification Status
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                        <!-- PASSPORT -->

                        <tr>

                            <td>

                                <div class="document_name">

                                    <strong>
                                        Passport / National ID
                                    </strong>

                                    <span class="required">
                                        Required
                                    </span>

                                </div>

                            </td>

                            <td>
                                —
                            </td>

                            <td>
                                —
                            </td>

                            <td>

                                <span class="document_status pending">
                                    Pending
                                </span>

                            </td>

                            <td>

                                <div class="document_actions">


                                    <button
                                        type="button"
                                        class="view_button"
                                    >
                                        View
                                    </button>


                                    <form method="POST"
                                          action=""
                                          style="display:inline;">

                                        <input
                                            type="hidden"
                                            name="document"
                                            value="Passport / National ID"
                                        >

                                        <button
                                            type="submit"
                                            name="verify_document"
                                            class="verify_button"
                                        >
                                            Verify
                                        </button>

                                    </form>


                                    <form method="POST"
                                          action=""
                                          style="display:inline;">

                                        <input
                                            type="hidden"
                                            name="document"
                                            value="Passport / National ID"
                                        >

                                        <button
                                            type="submit"
                                            name="reject_document"
                                            class="reject_button"
                                        >
                                            Reject
                                        </button>

                                    </form>


                                </div>

                            </td>

                        </tr>



                        <!-- PHOTOGRAPH -->

                        <tr>

                            <td>

                                <div class="document_name">

                                    <strong>
                                        Passport-size Photograph
                                    </strong>

                                    <span class="required">
                                        Required
                                    </span>

                                </div>

                            </td>

                            <td>—</td>

                            <td>—</td>

                            <td>

                                <span class="document_status pending">
                                    Pending
                                </span>

                            </td>

                            <td>

                                <div class="document_actions">

                                    <button
                                        type="button"
                                        class="view_button"
                                    >
                                        View
                                    </button>


                                    <form method="POST"
                                          style="display:inline;">

                                        <input
                                            type="hidden"
                                            name="document"
                                            value="Passport-size Photograph"
                                        >

                                        <button
                                            type="submit"
                                            name="verify_document"
                                            class="verify_button"
                                        >
                                            Verify
                                        </button>

                                    </form>


                                    <form method="POST"
                                          style="display:inline;">

                                        <input
                                            type="hidden"
                                            name="document"
                                            value="Passport-size Photograph"
                                        >

                                        <button
                                            type="submit"
                                            name="reject_document"
                                            class="reject_button"
                                        >
                                            Reject
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>



                        <!-- TRANSCRIPT -->

                        <tr>

                            <td>

                                <div class="document_name">

                                    <strong>
                                        Academic Transcript
                                    </strong>

                                    <span class="required">
                                        Required
                                    </span>

                                </div>

                            </td>

                            <td>—</td>

                            <td>—</td>

                            <td>

                                <span class="document_status pending">
                                    Pending
                                </span>

                            </td>

                            <td>

                                <div class="document_actions">

                                    <button
                                        type="button"
                                        class="view_button"
                                    >
                                        View
                                    </button>


                                    <form method="POST"
                                          style="display:inline;">

                                        <input
                                            type="hidden"
                                            name="document"
                                            value="Academic Transcript"
                                        >

                                        <button
                                            type="submit"
                                            name="verify_document"
                                            class="verify_button"
                                        >
                                            Verify
                                        </button>

                                    </form>


                                    <form method="POST"
                                          style="display:inline;">

                                        <input
                                            type="hidden"
                                            name="document"
                                            value="Academic Transcript"
                                        >

                                        <button
                                            type="submit"
                                            name="reject_document"
                                            class="reject_button"
                                        >
                                            Reject
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>



                        <!-- CERTIFICATE -->

                        <tr>

                            <td>

                                <div class="document_name">

                                    <strong>
                                        Academic Certificate
                                    </strong>

                                    <span class="optional">
                                        Optional
                                    </span>

                                </div>

                            </td>

                            <td>—</td>

                            <td>—</td>

                            <td>

                                <span class="document_status pending">
                                    Pending
                                </span>

                            </td>

                            <td>

                                <div class="document_actions">

                                    <button
                                        type="button"
                                        class="view_button"
                                    >
                                        View
                                    </button>


                                    <form method="POST"
                                          style="display:inline;">

                                        <input
                                            type="hidden"
                                            name="document"
                                            value="Academic Certificate"
                                        >

                                        <button
                                            type="submit"
                                            name="verify_document"
                                            class="verify_button"
                                        >
                                            Verify
                                        </button>

                                    </form>


                                    <form method="POST"
                                          style="display:inline;">

                                        <input
                                            type="hidden"
                                            name="document"
                                            value="Academic Certificate"
                                        >

                                        <button
                                            type="submit"
                                            name="reject_document"
                                            class="reject_button"
                                        >
                                            Reject
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>



                        <!-- CV -->

                        <tr>

                            <td>

                                <div class="document_name">

                                    <strong>
                                        CV / Resume
                                    </strong>

                                    <span class="optional">
                                        Optional
                                    </span>

                                </div>

                            </td>

                            <td>—</td>

                            <td>—</td>

                            <td>

                                <span class="document_status pending">
                                    Pending
                                </span>

                            </td>

                            <td>

                                <div class="document_actions">

                                    <button
                                        type="button"
                                        class="view_button"
                                    >
                                        View
                                    </button>


                                    <form method="POST"
                                          style="display:inline;">

                                        <input
                                            type="hidden"
                                            name="document"
                                            value="CV / Resume"
                                        >

                                        <button
                                            type="submit"
                                            name="verify_document"
                                            class="verify_button"
                                        >
                                            Verify
                                        </button>

                                    </form>


                                    <form method="POST"
                                          style="display:inline;">

                                        <input
                                            type="hidden"
                                            name="document"
                                            value="CV / Resume"
                                        >

                                        <button
                                            type="submit"
                                            name="reject_document"
                                            class="reject_button"
                                        >
                                            Reject
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>



                        <!-- ENGLISH CERTIFICATE -->

                        <tr>

                            <td>

                                <div class="document_name">

                                    <strong>
                                        English Language Certificate
                                    </strong>

                                    <span class="optional">
                                        Optional
                                    </span>

                                </div>

                            </td>

                            <td>—</td>

                            <td>—</td>

                            <td>

                                <span class="document_status pending">
                                    Pending
                                </span>

                            </td>

                            <td>

                                <div class="document_actions">

                                    <button
                                        type="button"
                                        class="view_button"
                                    >
                                        View
                                    </button>


                                    <form method="POST"
                                          style="display:inline;">

                                        <input
                                            type="hidden"
                                            name="document"
                                            value="English Language Certificate"
                                        >

                                        <button
                                            type="submit"
                                            name="verify_document"
                                            class="verify_button"
                                        >
                                            Verify
                                        </button>

                                    </form>


                                    <form method="POST"
                                          style="display:inline;">

                                        <input
                                            type="hidden"
                                            name="document"
                                            value="English Language Certificate"
                                        >

                                        <button
                                            type="submit"
                                            name="reject_document"
                                            class="reject_button"
                                        >
                                            Reject
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>



                        <!-- RECOMMENDATION -->

                        <tr>

                            <td>

                                <div class="document_name">

                                    <strong>
                                        Recommendation Letter
                                    </strong>

                                    <span class="optional">
                                        Optional
                                    </span>

                                </div>

                            </td>

                            <td>—</td>

                            <td>—</td>

                            <td>

                                <span class="document_status pending">
                                    Pending
                                </span>

                            </td>

                            <td>

                                <div class="document_actions">

                                    <button
                                        type="button"
                                        class="view_button"
                                    >
                                        View
                                    </button>


                                    <form method="POST"
                                          style="display:inline;">

                                        <input
                                            type="hidden"
                                            name="document"
                                            value="Recommendation Letter"
                                        >

                                        <button
                                            type="submit"
                                            name="verify_document"
                                            class="verify_button"
                                        >
                                            Verify
                                        </button>

                                    </form>


                                    <form method="POST"
                                          style="display:inline;">

                                        <input
                                            type="hidden"
                                            name="document"
                                            value="Recommendation Letter"
                                        >

                                        <button
                                            type="submit"
                                            name="reject_document"
                                            class="reject_button"
                                        >
                                            Reject
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>



                        <!-- FINANCIAL -->

                        <tr>

                            <td>

                                <div class="document_name">

                                    <strong>
                                        Financial / Bank Statement
                                    </strong>

                                    <span class="optional">
                                        Optional
                                    </span>

                                </div>

                            </td>

                            <td>—</td>

                            <td>—</td>

                            <td>

                                <span class="document_status pending">
                                    Pending
                                </span>

                            </td>

                            <td>

                                <div class="document_actions">

                                    <button
                                        type="button"
                                        class="view_button"
                                    >
                                        View
                                    </button>


                                    <form method="POST"
                                          style="display:inline;">

                                        <input
                                            type="hidden"
                                            name="document"
                                            value="Financial / Bank Statement"
                                        >

                                        <button
                                            type="submit"
                                            name="verify_document"
                                            class="verify_button"
                                        >
                                            Verify
                                        </button>

                                    </form>


                                    <form method="POST"
                                          style="display:inline;">

                                        <input
                                            type="hidden"
                                            name="document"
                                            value="Financial / Bank Statement"
                                        >

                                        <button
                                            type="submit"
                                            name="reject_document"
                                            class="reject_button"
                                        >
                                            Reject
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>



                        <!-- MEDICAL -->

                        <tr>

                            <td>

                                <div class="document_name">

                                    <strong>
                                        Medical Certificate
                                    </strong>

                                    <span class="optional">
                                        Optional
                                    </span>

                                </div>

                            </td>

                            <td>—</td>

                            <td>—</td>

                            <td>

                                <span class="document_status pending">
                                    Pending
                                </span>

                            </td>

                            <td>

                                <div class="document_actions">

                                    <button
                                        type="button"
                                        class="view_button"
                                    >
                                        View
                                    </button>


                                    <form method="POST"
                                          style="display:inline;">

                                        <input
                                            type="hidden"
                                            name="document"
                                            value="Medical Certificate"
                                        >

                                        <button
                                            type="submit"
                                            name="verify_document"
                                            class="verify_button"
                                        >
                                            Verify
                                        </button>

                                    </form>


                                    <form method="POST"
                                          style="display:inline;">

                                        <input
                                            type="hidden"
                                            name="document"
                                            value="Medical Certificate"
                                        >

                                        <button
                                            type="submit"
                                            name="reject_document"
                                            class="reject_button"
                                        >
                                            Reject
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>



                        <!-- OTHER -->

                        <tr>

                            <td>

                                <div class="document_name">

                                    <strong>
                                        Other Supporting Document
                                    </strong>

                                    <span class="optional">
                                        Optional
                                    </span>

                                </div>

                            </td>

                            <td>—</td>

                            <td>—</td>

                            <td>

                                <span class="document_status pending">
                                    Pending
                                </span>

                            </td>

                            <td>

                                <div class="document_actions">

                                    <button
                                        type="button"
                                        class="view_button"
                                    >
                                        View
                                    </button>


                                    <form method="POST"
                                          style="display:inline;">

                                        <input
                                            type="hidden"
                                            name="document"
                                            value="Other Supporting Document"
                                        >

                                        <button
                                            type="submit"
                                            name="verify_document"
                                            class="verify_button"
                                        >
                                            Verify
                                        </button>

                                    </form>


                                    <form method="POST"
                                          style="display:inline;">

                                        <input
                                            type="hidden"
                                            name="document"
                                            value="Other Supporting Document"
                                        >

                                        <button
                                            type="submit"
                                            name="reject_document"
                                            class="reject_button"
                                        >
                                            Reject
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>


                    </tbody>

                </table>

            </div>

        </section>



        <!-- ================= VERIFICATION SUMMARY ================= -->

        <section class="content_box">


            <h2>
                Verification Summary
            </h2>


            <div class="summary_grid">


                <div class="summary_card">

                    <span class="summary_label">
                        Total Documents
                    </span>

                    <strong>
                        10
                    </strong>

                </div>


                <div class="summary_card">

                    <span class="summary_label">
                        Verified
                    </span>

                    <strong class="verified_number">
                        0
                    </strong>

                </div>


                <div class="summary_card">

                    <span class="summary_label">
                        Rejected
                    </span>

                    <strong class="rejected_number">
                        0
                    </strong>

                </div>


                <div class="summary_card">

                    <span class="summary_label">
                        Pending
                    </span>

                    <strong class="pending_number">
                        10
                    </strong>

                </div>


            </div>

        </section>



        <!-- ================= COORDINATOR REMARKS ================= -->

        <section class="content_box">


            <h2>
                Coordinator Remarks
            </h2>


            <p class="section_description">

                Add remarks regarding document verification
                or the application decision.

            </p>


            <form method="POST"
                  action="">


                <div class="remark_group">


                    <label for="coordinator_remark">

                        Remarks

                    </label>


                    <textarea
                        id="coordinator_remark"
                        name="coordinator_remark"
                        rows="6"
                        placeholder="Enter your remarks here..."
                    ></textarea>


                </div>


                <button
                    type="submit"
                    name="save_remarks"
                    class="save_button"
                >

                    Save Remarks

                </button>


            </form>


        </section>



        <!-- ================= APPLICATION DECISION ================= -->

        <section class="content_box decision_section">


            <h2>
                Application Decision
            </h2>


            <p class="section_description">

                After completing document verification,
                make the final decision for this application.

            </p>


            <div class="decision_box">


                <div class="decision_status">


                    <span class="decision_label">

                        Current Application Status

                    </span>


                    <span class="status_badge pending">

                        Pending Verification

                    </span>


                </div>


                <div class="decision_actions">


                    <form method="POST"
                          style="display:inline;">

                        <button
                            type="submit"
                            name="approve_application"
                            class="approve_button"
                        >

                            Approve Application

                        </button>

                    </form>


                    <form method="POST"
                          style="display:inline;">

                        <button
                            type="submit"
                            name="reject_application"
                            class="reject_application_button"
                        >

                            Reject Application

                        </button>

                    </form>


                </div>


            </div>


        </section>



        <!-- ================= FOOTER ================= -->

        <?php include "footer.php"; ?>

    </main>


</div>


</body>

</html>