<?php
include 'dbh.inc.php';
$result = ["error" => 'none'];
$sql = "SELECT posts.*, users.usersUid, users.usersPfp, users.usersPublicKey FROM posts LEFT JOIN users ON posts.usersID=users.usersID WHERE postsID=?;";
$postsID = $_POST['postsID'];
//$postsID = 1;

$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    $result["error"] = 'stmtFailed';
    exit(json_encode($result));
}
mysqli_stmt_bind_param($stmt, "i", $postsID);
mysqli_stmt_execute($stmt);

$resultData = mysqli_stmt_get_result($stmt);
$postData;
if (mysqli_num_rows($resultData) == 0) {
    $result["error"] = 'postNotFound';
    exit(json_encode($result));
}
while ($row = mysqli_fetch_assoc($resultData)) {
    $postData[] = $row;
    //echo var_dump($row);
}
$result += $postData;
//echo var_dump($result);
exit(json_encode($result));