<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SEPMS - Manage Universities</title>

    <link rel="stylesheet" href="manage_universities.css">

</head>

<body>

    <!-- ================= HEADER ================= -->

    <?php include "header.php"; ?>


    <!-- ================= MAIN LAYOUT ================= -->

    <div class="dashboard_container">


        <!-- ================= SIDEBAR ================= -->

       <!-- ================= SIDEBAR ================= -->

<aside class="sidebar">

    <div class="sidebar_title">
        ADMIN PANEL
    </div>


    <a href="admin_dashboard.php">
        Dashboard
    </a>


    <a href="manage_students.php">
        Students
    </a>


    <a href="manage_coordinators.php">
        Coordinators
    </a>


    <a href="manage_countries.php">
        Countries
    </a>


    <a href="manage_universities.php">
        Universities
    </a>


    <a href="manage_exchange_programs.php">
        Exchange Programs
    </a>


    <a href="applications.php">
        Applications
    </a>


    <a href="documents.php">
        Documents
    </a>


    <a href="scholarships.php">
        Scholarships
    </a>


    <a href="nominations.php">
        Nominations
    </a>


    <a href="exchange_records.php">
        Exchange Records
    </a>


    <div class="sidebar_bottom">

        <a href="update_profile.php">
            Profile
        </a>


        <a href="change_password.php">
            Change Password
        </a>


        <a href="logout.php">
            Logout
        </a>

    </div>

</aside>


        <!-- ================= MAIN CONTENT ================= -->

        <main class="main_content">


            <!-- Page Header -->

            <div class="page_header">

                <h1>
                    Manage Universities
                </h1>

                <p>
                    Add, view, edit, delete and search universities.
                </p>

            </div>


            <!-- ================= ADD UNIVERSITY ================= -->

            <section class="form_section">

                <h2>
                    Add University
                </h2>


                <form>

                    <table>

                        <!-- University ID -->

                        <tr>

                            <td>
                                <label for="university_id">
                                    University ID
                                </label>
                            </td>

                            <td>

                                <input
                                    type="text"
                                    id="university_id"
                                    name="university_id"
                                    placeholder="Enter university ID"
                                >

                            </td>

                        </tr>


                        <!-- University Name -->

                        <tr>

                            <td>
                                <label for="university_name">
                                    University Name
                                </label>
                            </td>

                            <td>

                                <input
                                    type="text"
                                    id="university_name"
                                    name="university_name"
                                    placeholder="Enter university name"
                                >

                            </td>

                        </tr>


                        <!-- Country -->

                        <tr>

                            <td>
                                <label for="country">
                                    Country
                                </label>
                            </td>

                            <td>

                                <select
                                    id="country"
                                    name="country"
                                >

                                    <option
                                        value=""
                                        selected
                                        disabled
                                    >
                                        Select country
                                    </option>

                                </select>

                            </td>

                        </tr>


                        <!-- University Email -->

                        <tr>

                            <td>
                                <label for="university_email">
                                    University Email
                                </label>
                            </td>

                            <td>

                                <input
                                    type="email"
                                    id="university_email"
                                    name="university_email"
                                    placeholder="Enter university email"
                                >

                            </td>

                        </tr>


                        <!-- University Address -->

                        <tr>

                            <td>
                                <label for="university_address">
                                    University Address
                                </label>
                            </td>

                            <td>

                                <textarea
                                    id="university_address"
                                    name="university_address"
                                    placeholder="Enter university address"
                                ></textarea>

                            </td>

                        </tr>


                        <!-- Buttons -->

                        <tr>

                            <td colspan="2">

                                <div class="button_area">

                                    <button
                                        type="submit"
                                        class="save_btn"
                                    >
                                        Save
                                    </button>

                                    <button
                                        type="reset"
                                        class="reset_btn"
                                    >
                                        Reset
                                    </button>

                                </div>

                            </td>

                        </tr>

                    </table>

                </form>

            </section>


            <!-- ================= UNIVERSITY LIST ================= -->

            <section class="list_section">


                <div class="list_header">

                    <div>

                        <h2>
                            University List
                        </h2>

                        <p>
                            View and manage registered universities.
                        </p>

                    </div>


                    <!-- Search -->

                    <div class="search_area">

                        <input
                            type="text"
                            placeholder="Search university"
                        >

                        <button type="button">
                            Search
                        </button>

                    </div>

                </div>


                <!-- University Table -->

                <div class="table_container">

                    <table class="university_table">

                        <thead>

                            <tr>

                                <th>
                                    University ID
                                </th>

                                <th>
                                    University Name
                                </th>

                                <th>
                                    Country
                                </th>

                                <th>
                                    Email
                                </th>

                                <th>
                                    Address
                                </th>

                                <th>
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <!-- Empty Data -->

                            <tr>

                                <td
                                    colspan="6"
                                    class="empty_data"
                                >
                                    No universities available.
                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </section>
            <?php include "footer.php"; ?>

        </main>

    </div>

</body>

</html>