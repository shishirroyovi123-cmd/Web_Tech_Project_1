<?php

require_once __DIR__ . "/../config/db.php";


/* =========================================================
   ONLY POST REQUEST
========================================================= */

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: ../register.php");

    exit();

}


/* =========================================================
   GET FORM DATA
========================================================= */

$name =
    trim($_POST["name"] ?? "");

$userID =
    trim($_POST["user_id"] ?? "");

$email =
    trim($_POST["email"] ?? "");

$username =
    trim($_POST["username"] ?? "");

$password =
    $_POST["password"] ?? "";

$confirmPassword =
    $_POST["confirm_password"] ?? "";

$role =
    $_POST["role"] ?? "";



/* =========================================================
   EMPTY CHECK
========================================================= */

if (
    $name === "" ||
    $userID === "" ||
    $email === "" ||
    $username === "" ||
    $password === "" ||
    $confirmPassword === "" ||
    $role === ""
) {

    header(
        "Location: ../register.php?error="
        . urlencode("All fields are required.")
    );

    exit();

}


/* =========================================================
   USER ID CHECK
========================================================= */

if (!ctype_digit($userID)) {

    header(
        "Location: ../register.php?error="
        . urlencode("User ID must contain numbers only.")
    );

    exit();

}

$userID = (int)$userID;



/* =========================================================
   NAME CHECK
========================================================= */

if (!preg_match("/^[A-Z][a-zA-Z ]*$/", $name)) {

    header(
        "Location: ../register.php?error="
        . urlencode(
            "Name must start with an uppercase letter."
        )
    );

    exit();

}



/* =========================================================
   EMAIL CHECK
========================================================= */

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    header(
        "Location: ../register.php?error="
        . urlencode(
            "Please enter a valid email address."
        )
    );

    exit();

}



/* =========================================================
   USERNAME CHECK
========================================================= */

if (!preg_match("/^[A-Za-z0-9_]+$/", $username)) {

    header(
        "Location: ../register.php?error="
        . urlencode(
            "Username can contain letters, numbers and underscore only."
        )
    );

    exit();

}



/* =========================================================
   PASSWORD CHECK
========================================================= */

if (strlen($password) < 6) {

    header(
        "Location: ../register.php?error="
        . urlencode(
            "Password must be at least 6 characters."
        )
    );

    exit();

}


if ($password !== $confirmPassword) {

    header(
        "Location: ../register.php?error="
        . urlencode(
            "Passwords do not match."
        )
    );

    exit();

}



/* =========================================================
   ROLE CHECK
========================================================= */

if (
    $role !== "student" &&
    $role !== "coordinator"
) {

    header(
        "Location: ../register.php?error="
        . urlencode(
            "Please select Student or Coordinator."
        )
    );

    exit();

}



/* =========================================================
   CHECK USER ID
========================================================= */

$sql = "
    SELECT user_id
    FROM users
    WHERE user_id = ?
";


$stmt = mysqli_prepare(
    $conn,
    $sql
);


if (!$stmt) {

    header(
        "Location: ../register.php?error="
        . urlencode(
            "Database error."
        )
    );

    exit();

}


mysqli_stmt_bind_param(
    $stmt,
    "i",
    $userID
);


mysqli_stmt_execute($stmt);

mysqli_stmt_store_result($stmt);


if (mysqli_stmt_num_rows($stmt) > 0) {

    mysqli_stmt_close($stmt);

    header(
        "Location: ../register.php?error="
        . urlencode(
            "This User ID is already registered."
        )
    );

    exit();

}


mysqli_stmt_close($stmt);



/* =========================================================
   CHECK EMAIL
========================================================= */

$sql = "
    SELECT user_id
    FROM users
    WHERE email = ?
";


$stmt = mysqli_prepare(
    $conn,
    $sql
);


if (!$stmt) {

    header(
        "Location: ../register.php?error="
        . urlencode(
            "Database error."
        )
    );

    exit();

}


mysqli_stmt_bind_param(
    $stmt,
    "s",
    $email
);


mysqli_stmt_execute($stmt);

mysqli_stmt_store_result($stmt);


if (mysqli_stmt_num_rows($stmt) > 0) {

    mysqli_stmt_close($stmt);

    header(
        "Location: ../register.php?error="
        . urlencode(
            "This email is already registered."
        )
    );

    exit();

}


mysqli_stmt_close($stmt);



/* =========================================================
   CHECK USERNAME
========================================================= */

$sql = "
    SELECT user_id
    FROM users
    WHERE username = ?
";


$stmt = mysqli_prepare(
    $conn,
    $sql
);


if (!$stmt) {

    header(
        "Location: ../register.php?error="
        . urlencode(
            "Database error."
        )
    );

    exit();

}


mysqli_stmt_bind_param(
    $stmt,
    "s",
    $username
);


mysqli_stmt_execute($stmt);

mysqli_stmt_store_result($stmt);


if (mysqli_stmt_num_rows($stmt) > 0) {

    mysqli_stmt_close($stmt);

    header(
        "Location: ../register.php?error="
        . urlencode(
            "This username is already registered."
        )
    );

    exit();

}


mysqli_stmt_close($stmt);



/* =========================================================
   HASH PASSWORD
========================================================= */

$hashedPassword =
    password_hash(
        $password,
        PASSWORD_DEFAULT
    );



/* =========================================================
   INSERT INTO USERS
========================================================= */

$sql = "
    INSERT INTO users
    (
        user_id,
        name,
        email,
        username,
        password,
        role
    )
    VALUES
    (
        ?,
        ?,
        ?,
        ?,
        ?,
        ?
    )
";


$stmt = mysqli_prepare(
    $conn,
    $sql
);


if (!$stmt) {

    header(
        "Location: ../register.php?error="
        . urlencode(
            "Could not prepare registration."
        )
    );

    exit();

}


mysqli_stmt_bind_param(
    $stmt,
    "isssss",
    $userID,
    $name,
    $email,
    $username,
    $hashedPassword,
    $role
);



/* =========================================================
   SAVE USER
========================================================= */

if (mysqli_stmt_execute($stmt)) {

    mysqli_stmt_close($stmt);

    header(
        "Location: ../register.php?success=1"
    );

    exit();

}



/* =========================================================
   INSERT FAILED
========================================================= */

$errorMessage =
    mysqli_error($conn);


mysqli_stmt_close($stmt);


header(
    "Location: ../register.php?error="
    . urlencode(
        "Registration failed: " . $errorMessage
    )
);

exit();

?>