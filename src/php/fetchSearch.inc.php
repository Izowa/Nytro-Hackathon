<?php
// ? ----- VARIABLES & SETUP -----
require_once 'dbh.inc.php';
$result = ["error" => 'none', "reachedMax" => false];
$data;

// ! Invalid 1 - Check if $_POST was set
if (!isset($_POST)) {
    $result["error"] = 'noPostData';
    exit(json_encode($result));
}

$start = $_POST['start'];
$limit = $_POST['limit'];
$searchTerm = $_POST['searchTerm'];

// ? ----- SQL & STMT -----
$sql = "SELECT posts.*, users.usersUid, users.usersPublicKey FROM posts LEFT JOIN users ON posts.usersID=users.usersID WHERE postsTitle LIKE CONCAT('%',?,'%') OR postsDesc LIKE CONCAT('%',?,'%') ORDER BY postsID DESC LIMIT ?, ?;";

$stmt = mysqli_stmt_init($conn);
// ! Invalid 2 - An SQL error has occurred
if (!mysqli_stmt_prepare($stmt, $sql)) {
    $result["error"] = 'stmtFailed';
    exit(json_encode($result));
}
mysqli_stmt_bind_param($stmt, "ssii", $searchTerm, $searchTerm, $start, $limit);
mysqli_stmt_execute($stmt);

$resultData = mysqli_stmt_get_result($stmt);

// ! Invalid 3 - No post was found
if (mysqli_num_rows($resultData) == 0) {
    $result["reachedMax"] = true;
    exit(json_encode($result));
}
while ($row = mysqli_fetch_assoc($resultData)) {
    $data[] = $row;
}
$result += ['posts' => array_values($data)];
exit(json_encode($result));