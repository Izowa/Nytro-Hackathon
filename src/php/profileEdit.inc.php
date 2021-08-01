<?php
session_start();
require_once 'dbh.inc.php';
$usersID = $_POST["usersID"];
$result = ["error" => 'none'];
$user = array();
if (isset($_POST["usersDesc"])) {
    // ---------------------- Description ----------------------
    $newDesc = $_POST["usersDesc"];

    $sql = "UPDATE users SET usersDesc=? WHERE usersUid=?";

    $stmt = mysqli_stmt_init($conn);
    // Checks if the prepare succeeds, basically runs the code with dummy values
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        $result["error"] = 'stmtFailed';
        exit(json_encode($result));
    } else {
        // If it succeeds, bind the ? from the sql with the parameters
        mysqli_stmt_bind_param($stmt, "ss", $newDesc, $username);
        mysqli_stmt_execute($stmt);
        $_SESSION['usersDesc'] = $newDesc;
        mysqli_stmt_close($stmt);
        $result = ["error" => 'none'];
    }
}
// ---------------------- Profile Picture ----------------------
if (isset($_FILES)) {

    $location = "../../WebStorage/nya/pfps/";
    $file_name = $_FILES[0]["name"];
    $file_temp = $_FILES[0]["tmp_name"];
    $file_size = $_FILES[0]["size"];
    $target_file = $location . basename($_FILES[0]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $uploadOk = 1;

    // ! Invalid 3 - Check if image file is a actual image or fake image
    $check = getimagesize($file_temp[$i]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
    }

    // ! Invalid 4 - Check if file already exists
    if (file_exists($target_file[$i])) {
        $uploadOk = 0;
    }

    // ! Invalid 5 - Check file size
    if ($file_size > 16000000) {
        $uploadOk = 0;
    }

    // ! Invalid 6 - Allow certain file formats
    if (
        $imageFileType != "jpg" && $imageFileType[$i] != "png" && $imageFileType[$i] != "jpeg"
        && $imageFileType != "gif"
    ) {
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        $result["error"] = 'fileNotSupported';
        exit(json_encode($result));
    }

    $file_new_name = 'pfp' . $usersID . '.jpg';
    move_uploaded_file($file_temp, $location . $file_new_name);
    //echo "The file " . htmlspecialchars(basename($file_name)) . " has been uploaded.";

    // Works Up to here
    $sql = "UPDATE users SET usersPfp = ? WHERE usersID = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        $result["error"] = 'stmtFailed';
        exit(json_encode($result));
    } else {
        mysqli_stmt_bind_param($stmt, "si", $file_new_name, $usersID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    $sql = "SELECT usersPfp FROM users WHERE usersID = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        $result["error"] = 'stmtFailed';
        exit(json_encode($result));
    } else {
        mysqli_stmt_bind_param($stmt, "i", $usersID);
        mysqli_stmt_execute($stmt);
        $resultData = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($resultData)) {
            //echo $row["usersPfp"];
            $user = $row;
        }
        mysqli_stmt_close($stmt);
        $result["error"] = 'none';
    }
}
// SQL
$sql = "SELECT * FROM users WHERE usersID=?;";
$stmt = mysqli_stmt_init($conn);
// Error 2 - SQL/STMT Failed
if (!mysqli_stmt_prepare($stmt, $sql)) {
    $result["error"] = 'stmtFailed';
    exit(json_encode($result));
}
mysqli_stmt_bind_param($stmt, "i", $usersID);
mysqli_stmt_execute($stmt);
$resultData = mysqli_stmt_get_result($stmt);
$loginData = mysqli_fetch_assoc($resultData);
mysqli_stmt_close($stmt);
$result += $loginData;
unset($result['usersPwd']);
exit(json_encode($result));
