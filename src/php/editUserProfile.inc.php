<?php
// ? ----- VARIABLES & SETUP -----
require_once 'dbh.inc.php';
$result = ["error" => 'none', "newDesc" => ''];

$usersID = $_POST["usersID"];

$newDesc = $_POST["newDesc"];

// ? ----------- UPDATE DATA -----------------

$sql = "UPDATE users SET usersDesc = ? WHERE usersID = ?;";
$stmt = mysqli_stmt_init($conn);
// ! Invalid 5 - STMT Failed
if (!mysqli_stmt_prepare($stmt, $sql)) {
    $result["error"] = "stmtfailed1";
    exit(json_encode($result));
} else {
    mysqli_stmt_bind_param($stmt, "si", $newDesc, $usersID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// ? ----------- FINDS IN DB -----------------
$sql = "SELECT usersDesc FROM users WHERE usersID = ?;";
$stmt = mysqli_stmt_init($conn);
// ! Invalid 6 - STMT Failed
if (!mysqli_stmt_prepare($stmt, $sql)) {
    $result["error"] = "stmtfailed2";
    exit(json_encode($result));
} else {
    mysqli_stmt_bind_param($stmt, "i", $usersID);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($resultData)) {
        $result["newDesc"] = $row["usersDesc"];
    }
    mysqli_stmt_close($stmt);
    exit(json_encode($result));
}
