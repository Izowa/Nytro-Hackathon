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

// ? ----- SQL & STMT -----

$sql = "SELECT comments.*, users.usersUid, users.usersPfp FROM comments LEFT JOIN users ON comments.usersID=users.usersID WHERE postsID=?;";

$stmt = mysqli_stmt_init($conn);
// ! Invalid 2 - An SQL error has occurred
if (!mysqli_stmt_prepare($stmt, $sql)) {
    $result["error"] = 'stmtFailed';
    exit(json_encode($result));
}
mysqli_stmt_bind_param($stmt, "i", $postsID);
mysqli_stmt_execute($stmt);

$resultData = mysqli_stmt_get_result($stmt);

// ! Invalid 3 - No post was found
if (mysqli_num_rows($resultData) == 0) {
    $result["error"] = 'commentNotFound';
    exit(json_encode($result));
}
while ($row = mysqli_fetch_assoc($resultData)) {
    $data[] = $row;
}

// ? ----- RESPONSE -----

$result += ['comments' => $data];
exit(json_encode($result));
