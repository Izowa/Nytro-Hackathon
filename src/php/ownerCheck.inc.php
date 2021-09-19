<?php
// ? ----- VARIABLES & SETUP -----
require_once 'dbh.inc.php';
$result = ['error' => 'none', 'correct' => false];
$dataPassword;

// ! Invalid 1 - Check if $_POST was set
if (!isset($_POST)) {
    $result["error"] = 'noPostData';
    exit(json_encode($result));
}

$usersID = $_POST['usersID'];
$postsID = $_POST['postsID'];

// ? ----- SQL & STMT -----

$sql = "SELECT * from posts WHERE usersID=? AND postsID=?";

$stmt = mysqli_stmt_init($conn);
// ! Invalid 2 - An SQL error has occurred
if (!mysqli_stmt_prepare($stmt, $sql)) {
    $result["error"] = 'stmtFailed';
    exit(json_encode($result));
}
mysqli_stmt_bind_param($stmt, "ii", $usersID, $postsID);
mysqli_stmt_execute($stmt);

$resultData = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($resultData) == 0) {
    $result["correct"] = false;
    exit(json_encode($result));
} else {
    $result['correct'] = true;
    exit(json_encode($result));
}
