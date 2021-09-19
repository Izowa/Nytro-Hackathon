<?php
// ? ----- VARIABLES & SETUP -----
$result = ['error' => 'none', 'correct' => false];
require_once 'dbh.inc.php';
$dataPassword;


// ? ----- VALIDATE USER -----

// ! Invalid 1 - Check if $_POST was set
if (!isset($_POST)) {
    $result["error"] = 'noPostData';
    exit(json_encode($result));
}

$postsID = $_POST['postsID'];
settype($postsID, "integer");
$usersID = $_POST['usersID'];
settype($usersID, "integer");
$pwd = $_POST['usersPwd'];

// ? ----- SQL & STMT -----

$sql = "SELECT * from users WHERE usersID=?";

$stmt = mysqli_stmt_init($conn);
// ! Invalid 2 - An SQL error has occurred
if (!mysqli_stmt_prepare($stmt, $sql)) {
    $result["error"] = 'stmtFailed';
    exit(json_encode($result));
}
mysqli_stmt_bind_param($stmt, "i", $usersID);
mysqli_stmt_execute($stmt);

$resultData = mysqli_stmt_get_result($stmt);

// ! Invalid 3 - No user was found
if (mysqli_num_rows($resultData) == 0) {
    $result["error"] = 'userNotFound';
    exit(json_encode($result));
}
while ($row = mysqli_fetch_assoc($resultData)) {
    $dataPassword = $row["usersPwd"];
}

if ($pwd == $dataPassword) {
    $result["correct"] = true;
} else {
    exit(json_encode($result));
}

if ($result['correct'] == true) {
    // ? ----- SQL & STMT -----

    $sql = "DELETE FROM posts WHERE postsID=?;";

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        // ! Invalid 2 - An SQL error has occurred
        $result["error"] = 'stmtFailed';
        exit(json_encode($result));
    }
    mysqli_stmt_bind_param($stmt, "i", $postsID);
    mysqli_stmt_execute($stmt);

    // ? ----- SQL & STMT -----

    $sql = "DELETE FROM postsImg WHERE postsID=?;";

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        // ! Invalid 2 - An SQL error has occurred
        $result["error"] = 'stmtFailed2';
        exit(json_encode($result));
    }
    mysqli_stmt_bind_param($stmt, "i", $postsID);
    mysqli_stmt_execute($stmt);

    // ? ----- RESPONSE -----

    exit(json_encode($result));
}
