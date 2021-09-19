<?php
// ? ----- VARIABLES & SETUP -----
require_once 'dbh.inc.php';
$result = ["error" => 'none'];

// ! Invalid 1 - Check if $_POST was set
if (!isset($_POST)) {
    $result["error"] = 'noPostData';
    exit(json_encode($result));
}

// ! Invalid 2 - Check if inputs are empty
if (empty($_POST["email"]) || empty($_POST["password"])) {
    $result["error"] = 'enterAllFields';
    exit(json_encode($result));
}

$email = $_POST["email"];
$pwd = $_POST["password"];

// ? ----- SQL & STMT -----

$sql = "SELECT * FROM users WHERE usersUid=? OR usersEmail = ?;";

$stmt = mysqli_stmt_init($conn);
// ! Invalid 3 - An SQL Error has occurred
if (!mysqli_stmt_prepare($stmt, $sql)) {
    $result["error"] = 'stmtFailed';
    exit(json_encode($result));
}
mysqli_stmt_bind_param($stmt, "ss", $email, $email);
mysqli_stmt_execute($stmt);

$resultData = mysqli_stmt_get_result($stmt);

// ! Invalid 4 - No user exists
if (mysqli_num_rows($resultData) == 0) {
    $result["error"] = 'uidNoExist';
    exit(json_encode($result));
}
$loginData = mysqli_fetch_assoc($resultData);
mysqli_stmt_close($stmt);

// ? ----- PASSWORD CHECK -----

$checkPwd = password_verify($pwd, $loginData['usersPwd']);
if ($checkPwd == true) {
    $result += $loginData;
    exit(json_encode($result));
}
// ! Invalid 5 - Passwords do not match
else if ($checkPwd == false) {
    $result["error"] = 'incorrectPassword';
    exit(json_encode($result));
}
