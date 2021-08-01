<?php
// ----- SETUP -----
require_once 'dbh.inc.php';
$result = ["error" => 'none'];
//$email = "adman44532";
//$pwd = "Password123!";
$email = $_POST["email"];
$pwd = $_POST["password"];


// ----- SQL & STMT -----
// Error 1 - Somehow field are empty
if (empty($email) || empty($pwd)) {
    $result["error"] = 'enterAllFields';
    exit(json_encode($result));
}
// SQL
$sql = "SELECT * FROM users WHERE usersUid=? OR usersEmail = ?;";
$stmt = mysqli_stmt_init($conn);
// Error 2 - SQL/STMT Failed
if (!mysqli_stmt_prepare($stmt, $sql)) {
    $result["error"] = 'stmtFailed';
    exit(json_encode($result));
}
mysqli_stmt_bind_param($stmt, "ss", $email, $email);
mysqli_stmt_execute($stmt);
$resultData = mysqli_stmt_get_result($stmt);
// Error 3 - No User exists
if (mysqli_num_rows($resultData) == 0) {
    $result["error"] = 'uidNoExist';
    exit(json_encode($result));
}
$loginData = mysqli_fetch_assoc($resultData);
mysqli_stmt_close($stmt);

// ----- PASSWORD CHECK -----
$checkPwd = password_verify($pwd, $loginData['usersPwd']);
if ($checkPwd == true) {
    $result += $loginData;
    unset($result['usersPwd']);
    exit(json_encode($result));
} // Error 4 - Wrong Password 
else if ($checkPwd == false) {
    $result["error"] = 'incorrectPassword';
    exit(json_encode($result));
}
