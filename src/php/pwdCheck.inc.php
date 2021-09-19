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
$pwd = $_POST['pwd'];

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

// ! Invalid 3 - No post was found
if (mysqli_num_rows($resultData) == 0) {
    $result["error"] = 'userNotFound';
    exit(json_encode($result));
}
while ($row = mysqli_fetch_assoc($resultData)) {
    $dataPassword = $row["usersPwd"];
}

if ($pwd == $dataPassword) {
    $result["correct"] = true;
}

// ? ----- RESPONSE -----

exit(json_encode($result));
