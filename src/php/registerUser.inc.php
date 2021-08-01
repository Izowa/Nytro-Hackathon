<?php
// ? ----- VARIABLES & SETUP -----
require_once 'dbh.inc.php';
$result = ["error" => 'none'];

// ! Invalid 1 - Check if $_POST was set
if (!isset($_POST)) {
    $result["error"] = 'noPostData';
    exit(json_encode($result));
}

// * Main Data

$username = $_POST['username'];
$email = $_POST['email'];
$pwd = $_POST['pwd'];
$pwdRepeat = $_POST['pwdRepeat'];
$hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
$publicKey = $_POST['publicKey'];
$defaultDesc = "This is a basic description";
$defaultPfp = "default.jpg";

// ? ----- CHECK USER DATA -----

// ! Invalid 1 - Field are empty
if (empty($username) || empty($email) || empty($pwd)) {
    $result["error"] = 'enterAllFields';
    exit(json_encode($result));
}

// ! Invalid 2 - Field are empty
if ($pwd != $pwdRepeat) {
    $result["error"] = 'notSamePwd';
    exit(json_encode($result));
}

$sql = "SELECT * FROM users WHERE usersUid= ? OR usersEmail= ?;";

$stmt = mysqli_stmt_init($conn);
// ! Invalid 3 - An SQL error has occurred
if (!mysqli_stmt_prepare($stmt, $sql)) {
    $result["error"] = 'stmtFailed1';
    exit(json_encode($result));
}
mysqli_stmt_bind_param($stmt, "ss", $username, $email);
mysqli_stmt_execute($stmt);
$usersID = mysqli_insert_id($conn);
$resultData = mysqli_stmt_get_result($stmt);
// ! Invalid 4 - User Already Exists
if (mysqli_num_rows($resultData) != 0) {
    $regData = mysqli_fetch_assoc($resultData);
    if ($regData['usersUid'] == $username) {
        $result["error"] = 'uidExist';
        exit(json_encode($result));
    }
    if ($regData['usersEmail'] == $email) {
        $result["error"] = 'emailExist';
        exit(json_encode($result));
    }
}

// ? ----- CREATE THE USER -----

$sql = "INSERT INTO users (usersUid, usersEmail, usersPfp, usersDesc, usersPwd, usersPublicKey) VALUES (?, ?, ?, ?, ?, ?);";
$stmt = mysqli_stmt_init($conn);
// ! Invalid 5 - An SQL Error has occurred
if (!mysqli_stmt_prepare($stmt, $sql)) {
    $result["error"] = 'stmtFailed2';
    exit(json_encode($result));
}
mysqli_stmt_bind_param($stmt, "ssssss", $username, $email, $defaultPfp, $defaultDesc, $hashedPwd, $publicKey);
mysqli_stmt_execute($stmt);
$usersID = mysqli_insert_id($conn);
mysqli_stmt_close($stmt);

// ? ----- CHECK USER CREATED -----

$sql = "SELECT * FROM users WHERE usersID = ?;";
$stmt2 = mysqli_stmt_init($conn);
// ! Invalid 6 - An SQL Error has occurred
if (!mysqli_stmt_prepare($stmt2, $sql)) {
    $result["error"] = 'stmtFailed2';
    exit(json_encode($result));
}
mysqli_stmt_bind_param($stmt2, "i", $usersID);
mysqli_stmt_execute($stmt2);
$resultData = mysqli_stmt_get_result($stmt2);
// ! Invalid 7 - An SQL Error has occurred
if (mysqli_num_rows($resultData) == 0) {
    $result["error"] = 'uidNoExist';
    echo var_dump($resultData);
    echo $usersID;
    exit(json_encode($result));
}
$resData = mysqli_fetch_assoc($resultData);
mysqli_stmt_close($stmt2);

// ? ----- RESPONSE -----

$result += $resData;
unset($result['usersPwd']);
exit(json_encode($result));