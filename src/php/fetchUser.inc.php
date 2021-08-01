<?php
include 'dbh.inc.php';
$sql = "SELECT * FROM users WHERE";
$profile = $_POST["profile"];

// Need to check if the value was a username or ID
$profileRAW = $profile;
settype($profile, "int");
if ($profile == 0){
    $profile = $profileRAW;
}
if (gettype($profile) == "integer") {
    $sql .= " usersID = ?;";
} else if (gettype($profile) == "string") {
    $sql .= " usersUid = ?;";
}

$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    exit(json_encode("stmtFailed"));
}
mysqli_stmt_bind_param($stmt, "ii", $start, $limit);
mysqli_stmt_execute($stmt);

$resultData = mysqli_stmt_get_result($stmt);
$result = array();
if (mysqli_num_rows($resultData) > 0) {
    while ($row = mysqli_fetch_assoc($resultData)) {
        $result[] = $row;
    }
    exit(json_encode($result));
} else {
    exit(json_encode("userNotFound"));
}
