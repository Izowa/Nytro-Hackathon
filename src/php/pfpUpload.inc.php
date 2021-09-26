<?php
// ? ----- VARIABLES & SETUP -----
require_once 'dbh.inc.php';
$result = ["error" => 'none', "pfp" => ''];

$usersID = $_POST["usersID"];

$location = "../WebStorage/nya/pfps/";
$file_name = $_FILES[0]["name"];
$file_temp = $_FILES[0]["tmp_name"];
$file_size = $_FILES[0]["size"];
$target_file = $location . basename($_FILES[0]["name"]);
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// ! Invalid 1 - Check if an image
$check = getimagesize($file_temp);
if ($check == false) {
    $result["error"] = "imageNot";
    exit(json_encode($result));
}

// ! Invalid 2 - Does the file exist
if (file_exists($target_file)) {
    $result["error"] = "imageExists";
    exit(json_encode($result));
}

// ! Invalid 3 - File is too large
if ($file_size > 16000000) {
    $result["error"] = "imageSize";
    exit(json_encode($result));
}

// ! Invalid 4 - Check for certain formats
if (
    $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif"
) {
    $result["error"] = "imageType";
    exit(json_encode($result));
}

// ? ----------- UPLOAD FILE -----------------

$file_new_name = 'pfp' . $usersID . '.jpg';
move_uploaded_file($file_temp, $location . $file_new_name);

$sql = "UPDATE users SET usersPfp = ? WHERE usersID = ?;";
$stmt = mysqli_stmt_init($conn);
// ! Invalid 5 - STMT Failed
if (!mysqli_stmt_prepare($stmt, $sql)) {
    $result["error"] = "stmtfailed1";
    exit(json_encode($result));
} else {
    mysqli_stmt_bind_param($stmt, "si", $file_new_name, $usersID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// ? ----------- FINDS IN DB -----------------
$sql = "SELECT usersPfp FROM users WHERE usersID = ?;";
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
        $result["pfp"] = $row["usersPfp"];
    }
    mysqli_stmt_close($stmt);
    exit(json_encode($result));
}
