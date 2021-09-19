<?php
// ? ----- VARIABLES & SETUP -----

require_once 'dbh.inc.php';
$result = ["error" => 'none', "posts" => null];
$data;

// ! Invalid 1 - Check if $_POST was set
if (!isset($_POST)) {
    $result["error"] = 'noPostData';
    exit(json_encode($result));
}

// Grabs the data
$usersID = $_POST['usersID'];
// Checks if the data is the Username or the ID
$usersIDRaw = $usersID;
settype($usersID, "int");
if ($usersID == 0){
    $usersID = $usersIDRaw;
}

// ? ----- SQL & STMT -----

$sql = "SELECT * FROM posts WHERE";
if (gettype($usersID) == "integer") {
    $sql .= " usersID = ?";
} else if (gettype($usersID) == "string") {
    $sql .= " usersUid = ?";
}
$sql .= " ORDER BY postsID DESC;";

$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    // ! Invalid 2 - An SQL error has occurred
    $result["error"] = 'stmtFailed';
    exit(json_encode($result));
}
if (gettype($usersID) == "integer") {
    mysqli_stmt_bind_param($stmt, "i", $usersID);
} else if (gettype($usersID) == "string") {
    mysqli_stmt_bind_param($stmt, "s", $usersID);
}
mysqli_stmt_execute($stmt);

$resultData = mysqli_stmt_get_result($stmt);

// ! Invalid 3 - Check if at the bottom of the list
if (mysqli_num_rows($resultData) == 0) {
    $result["posts"] = 'none';
    exit(json_encode($result));
}
while ($row = mysqli_fetch_assoc($resultData)) {
    $data[] = $row;
}

// ? ----- RESPONSE -----

$result["posts"] = array_values($data);
exit(json_encode($result));