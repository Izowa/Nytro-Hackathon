<?php
// ? ----- VARIABLES & SETUP -----
require_once 'dbh.inc.php';
$result = ["error" => 'none'];

// ! Invalid 1 - Check if $_POST was set
if (!isset($_POST)) {
    $result["error"] = 'noPostData';
    exit(json_encode($result));
}

$txString = $_POST['txString'];
$usersID = $_POST['usersID'];
$Data = $_POST['Data'];

// ? ----- SQL & STMT -----

$sql = "INSERT INTO Transactions (Tx_String, usersID, Data) VALUES (?, ?, ?);";

$stmt = mysqli_stmt_init($conn);
// ! Invalid 2 - An SQL error has occurred
if (!mysqli_stmt_prepare($stmt, $sql)) {
    $result["error"] = 'stmtFailed';
    exit(json_encode($result));
}
mysqli_stmt_bind_param($stmt, "sis", $txString, $usersID, $Data);
mysqli_stmt_execute($stmt);

$resultData = mysqli_stmt_get_result($stmt);

exit(json_encode($result));