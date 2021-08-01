<?php
// ? ----- VARIABLES & SETUP -----
require_once 'dbh.inc.php';
$result = ["error" => 'none'];
$data;

// ! Invalid 1 - Check if $_POST was set
if (!isset($_POST)) {
    $result["error"] = 'noPostData';
    exit(json_encode($result));
}

$postsID = $_POST['postsID'];
$usersID = $_POST['usersID'];
$commentValue = $_POST['comment'];

// ? ----- SQL & STMT -----

$sql = "INSERT INTO comments (usersID, postsID, commentValue) VALUES (?, ?, ?);";

$stmt = mysqli_stmt_init($conn);
// ! Invalid 2 - An SQL error has occurred
if (!mysqli_stmt_prepare($stmt, $sql)) {
    $result["error"] = 'stmtFailed';
    exit(json_encode($result));
}
mysqli_stmt_bind_param($stmt, "iis", $usersID, $postsID, $commentValue);
mysqli_stmt_execute($stmt);

// ? ----- RESPONSE -----
exit(json_encode($result));
