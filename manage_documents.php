<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>SEPMS - Manage Documents</title>

    <link rel="stylesheet"
          href="manage_documents.css">

</head>


<body>


    <!-- ================= HEADER ================= -->

   <?php include "header.php"; ?>



    <!-- ================= PAGE LAYOUT ================= -->

    <div class="page_layout">


        <!-- ================= SIDEBAR ================= -->

        <!-- ================= SIDEBAR ================= -->

<aside class="sidebar">

    <div class="sidebar_heading">
        STUDENT PANEL
    </div>


    <nav class="sidebar_menu">

        <a href="student_dashboard.php"
           class="sidebar_item">
            Dashboard
        </a>


        <a href="search_apply.php"
           class="sidebar_item">
            Search & Apply
        </a>


        <a href="my_applications.php"
           class="sidebar_item active">
            My Applications
        </a>


        <a href="application_status.php"
           class="sidebar_item">
            Application Status
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


        <a href="logout.php"
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
                    Manage Documents
                </h1>

                <p>
                    View, replace, delete and upload documents
                    for your exchange application.
                </p>

            </section>



            <!-- =================================================
                     SELECTED APPLICATION
            ================================================== -->

            <section class="content_box">

                <h2>
                    Application Information
                </h2>


                <div class="application_information">


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
                            Program ID
                        </span>

                        <span class="information_value">
                            —
                        </span>

                    </div>


                    <div class="information_item">

                        <span class="information_label">
                            Program Name
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
                            Application Status
                        </span>

                        <span class="status_badge pending">
                            Pending
                        </span>

                    </div>


                </div>

            </section>



            <!-- =================================================
                       UPLOADED DOCUMENTS
            ================================================== -->

            <section class="content_box">

                <div class="section_header">

                    <div>

                        <h2>
                            My Documents
                        </h2>

                        <p>
                            Manage the documents submitted with this application.
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
                                    File
                                </th>

                                <th>
                                    Upload Date
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                            <!-- ================= DOCUMENT 1 ================= -->

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

                                    <span class="status_badge pending">
                                        Not Uploaded
                                    </span>

                                </td>


                                <td>

                                    <div class="action_buttons">

                                        <button
                                            type="button"
                                            class="view_button"
                                        >
                                            View
                                        </button>

                                        <button
                                            type="button"
                                            class="replace_button"
                                        >
                                            Replace
                                        </button>

                                        <button
                                            type="button"
                                            class="delete_button"
                                        >
                                            Delete
                                        </button>

                                    </div>

                                </td>

                            </tr>



                            <!-- ================= DOCUMENT 2 ================= -->

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


                                <td>
                                    —
                                </td>


                                <td>
                                    —
                                </td>


                                <td>

                                    <span class="status_badge pending">
                                        Not Uploaded
                                    </span>

                                </td>


                                <td>

                                    <div class="action_buttons">

                                        <button
                                            type="button"
                                            class="view_button"
                                        >
                                            View
                                        </button>

                                        <button
                                            type="button"
                                            class="replace_button"
                                        >
                                            Replace
                                        </button>

                                        <button
                                            type="button"
                                            class="delete_button"
                                        >
                                            Delete
                                        </button>

                                    </div>

                                </td>

                            </tr>



                            <!-- ================= DOCUMENT 3 ================= -->

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


                                <td>
                                    —
                                </td>


                                <td>
                                    —
                                </td>


                                <td>

                                    <span class="status_badge pending">
                                        Not Uploaded
                                    </span>

                                </td>


                                <td>

                                    <div class="action_buttons">

                                        <button
                                            type="button"
                                            class="view_button"
                                        >
                                            View
                                        </button>

                                        <button
                                            type="button"
                                            class="replace_button"
                                        >
                                            Replace
                                        </button>

                                        <button
                                            type="button"
                                            class="delete_button"
                                        >
                                            Delete
                                        </button>

                                    </div>

                                </td>

                            </tr>



                            <!-- ================= DOCUMENT 4 ================= -->

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


                                <td>
                                    —
                                </td>


                                <td>
                                    —
                                </td>


                                <td>

                                    <span class="status_badge pending">
                                        Not Uploaded
                                    </span>

                                </td>


                                <td>

                                    <div class="action_buttons">

                                        <button
                                            type="button"
                                            class="view_button"
                                        >
                                            View
                                        </button>

                                        <button
                                            type="button"
                                            class="replace_button"
                                        >
                                            Replace
                                        </button>

                                        <button
                                            type="button"
                                            class="delete_button"
                                        >
                                            Delete
                                        </button>

                                    </div>

                                </td>

                            </tr>



                            <!-- ================= DOCUMENT 5 ================= -->

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


                                <td>
                                    —
                                </td>


                                <td>
                                    —
                                </td>


                                <td>

                                    <span class="status_badge pending">
                                        Not Uploaded
                                    </span>

                                </td>


                                <td>

                                    <div class="action_buttons">

                                        <button
                                            type="button"
                                            class="view_button"
                                        >
                                            View
                                        </button>

                                        <button
                                            type="button"
                                            class="replace_button"
                                        >
                                            Replace
                                        </button>

                                        <button
                                            type="button"
                                            class="delete_button"
                                        >
                                            Delete
                                        </button>

                                    </div>

                                </td>

                            </tr>



                            <!-- ================= DOCUMENT 6 ================= -->

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


                                <td>
                                    —
                                </td>


                                <td>
                                    —
                                </td>


                                <td>

                                    <span class="status_badge pending">
                                        Not Uploaded
                                    </span>

                                </td>


                                <td>

                                    <div class="action_buttons">

                                        <button
                                            type="button"
                                            class="view_button"
                                        >
                                            View
                                        </button>

                                        <button
                                            type="button"
                                            class="replace_button"
                                        >
                                            Replace
                                        </button>

                                        <button
                                            type="button"
                                            class="delete_button"
                                        >
                                            Delete
                                        </button>

                                    </div>

                                </td>

                            </tr>



                            <!-- ================= DOCUMENT 7 ================= -->

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


                                <td>
                                    —
                                </td>


                                <td>
                                    —
                                </td>


                                <td>

                                    <span class="status_badge pending">
                                        Not Uploaded
                                    </span>

                                </td>


                                <td>

                                    <div class="action_buttons">

                                        <button
                                            type="button"
                                            class="view_button"
                                        >
                                            View
                                        </button>

                                        <button
                                            type="button"
                                            class="replace_button"
                                        >
                                            Replace
                                        </button>

                                        <button
                                            type="button"
                                            class="delete_button"
                                        >
                                            Delete
                                        </button>

                                    </div>

                                </td>

                            </tr>



                            <!-- ================= DOCUMENT 8 ================= -->

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


                                <td>
                                    —
                                </td>


                                <td>
                                    —
                                </td>


                                <td>

                                    <span class="status_badge pending">
                                        Not Uploaded
                                    </span>

                                </td>


                                <td>

                                    <div class="action_buttons">

                                        <button
                                            type="button"
                                            class="view_button"
                                        >
                                            View
                                        </button>

                                        <button
                                            type="button"
                                            class="replace_button"
                                        >
                                            Replace
                                        </button>

                                        <button
                                            type="button"
                                            class="delete_button"
                                        >
                                            Delete
                                        </button>

                                    </div>

                                </td>

                            </tr>



                            <!-- ================= DOCUMENT 9 ================= -->

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


                                <td>
                                    —
                                </td>


                                <td>
                                    —
                                </td>


                                <td>

                                    <span class="status_badge pending">
                                        Not Uploaded
                                    </span>

                                </td>


                                <td>

                                    <div class="action_buttons">

                                        <button
                                            type="button"
                                            class="view_button"
                                        >
                                            View
                                        </button>

                                        <button
                                            type="button"
                                            class="replace_button"
                                        >
                                            Replace
                                        </button>

                                        <button
                                            type="button"
                                            class="delete_button"
                                        >
                                            Delete
                                        </button>

                                    </div>

                                </td>

                            </tr>



                        </tbody>

                    </table>

                </div>

            </section>



            <!-- =================================================
                       UPLOAD NEW DOCUMENT
            ================================================== -->

            <section class="content_box">

                <h2>
                    Upload / Replace Document
                </h2>

                <p class="section_description">
                    Upload a new document or replace an existing document.
                </p>


                <form>


                    <div class="form_row">


                        <div class="form_group">

                            <label for="document_type">
                                Document Type
                            </label>

                            <select
                                id="document_type"
                                name="document_type"
                                required
                            >

                                <option value="">
                                    Select Document
                                </option>

                                <option value="passport">
                                    Passport / National ID
                                </option>

                                <option value="passport_photo">
                                    Passport-size Photograph
                                </option>

                                <option value="transcript">
                                    Academic Transcript
                                </option>

                                <option value="certificate">
                                    Academic Certificate
                                </option>

                                <option value="cv">
                                    CV / Resume
                                </option>

                                <option value="english_certificate">
                                    English Language Certificate
                                </option>

                                <option value="recommendation">
                                    Recommendation Letter
                                </option>

                                <option value="financial">
                                    Financial / Bank Statement
                                </option>

                                <option value="medical">
                                    Medical Certificate
                                </option>

                                <option value="other">
                                    Other Supporting Document
                                </option>

                            </select>

                        </div>


                        <div class="form_group">

                            <label for="document_file">
                                Select File
                            </label>

                            <input
                                type="file"
                                id="document_file"
                                name="document_file"
                                accept=".pdf,.jpg,.jpeg,.png"
                                required
                            >

                        </div>


                    </div>


                    <div class="upload_action">

                        <button
                            type="submit"
                            class="upload_button"
                        >
                            Upload Document
                        </button>

                    </div>


                </form>

            </section>



            <!-- ================= FOOTER ================= -->

            <?php include "footer.php"; ?>


        </main>


    </div>


</body>

</html>