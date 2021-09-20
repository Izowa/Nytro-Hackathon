<?php
// ? ----- VARIABLES & SETUP -----
require_once 'dbh.inc.php';
$result = ["error" => 'none'];
// ! Invalid 1 - Check if $_POST was set
if (!isset($_POST)) {
    $result["error"] = 'noPostData';
    exit(json_encode($result));
}

// Main Post

$usersID = $_POST['usersID'];

$title = $_POST['title'];

$description = $_POST['description'];

$nsfw = $_POST['nsfw'];

$tags = array();
$isTags = 0;
if (isset($_POST['tags'])) {
    $tags = explode(',', $_POST['tags']);
    $isTags = 1;
} else {
    $tags = null;
}


// Images

$isFiles = 1;
if (count($_FILES) == 0) {
    $isFiles = 0;
}

// ! Invalid 1 - Check if user has given too many images
if (count($_FILES) > 3) {
    $result["error"] = "maxImages";
    exit(json_encode($result));
}

$uploadOk = array();

$location = "/webStorage/nya/postImgs/";
$file_temp = array();
$file_size = array();
$target_file = array();
$imageFileType = array();

// CHECK IMAGES

if ($isFiles == 1) {
    for ($i = 0; $i < count($_FILES); $i++) {
        $file_temp[$i] = $_FILES[$i]["tmp_name"];
        $file_size[$i] = $_FILES[$i]["size"];
        $target_file[$i] = $location . basename($_FILES[$i]["name"]);
        $imageFileType[$i] = strtolower(pathinfo($target_file[$i], PATHINFO_EXTENSION));
        $uploadOk[$i] = 1;

        // ! Invalid 1 - Check if an image
        $check = getimagesize($file_temp[$i]);
        //echo var_dump(getimagesize($file_temp[$i]));
        if ($check == false) {
            $result["error"] = "imageNot";
            exit(json_encode($result));
        }

        // ! Invalid 2 - Does the file exist
        if (file_exists($target_file[$i])) {
            $result["error"] = "imageExists";
            exit(json_encode($result));
        }

        // ! Invalid 4 - Check for certain formats
        if (
            $imageFileType[$i] != "jpg" && $imageFileType[$i] != "png" && $imageFileType[$i] != "jpeg"
            && $imageFileType[$i] != "gif"
        ) {
            $result["error"] = "imageType";
            exit(json_encode($result));
        }
    }
}

// Check if any of the images are bad
for ($i = 0; $i < count($_FILES); $i++) {
    if ($uploadOk[$i] == 0) {
        $result["error"] = 'noGoodImage';
        exit(json_encode($result));
    }
}


// ? ----- INSERT POST DATA -----

$sql = "INSERT INTO posts (usersID, postsTitle, postsDesc, postsNSFW, postsImgs) VALUES (?, ?, ?, ?, ?);";
$stmt = mysqli_stmt_init($conn);
// ! Invalid 7 - SQL/STMT Failed
if (!mysqli_stmt_prepare($stmt, $sql)) {
    $result["error"] = 'stmtFailed1';
    exit(json_encode($result));
}
mysqli_stmt_bind_param($stmt, "isssi", $usersID, $title, $description, $nsfw, $isFiles);
mysqli_stmt_execute($stmt);
$postsID = mysqli_insert_id($conn);
mysqli_stmt_close($stmt);

// ! Invalid 8 - No post was just created
if (empty($postsID)) {
    $result["error"] = 'postNotFound';
    exit(json_encode($result));
}

// ? ----- INSERT IMAGES -----

for ($i = 0; $i < count($_FILES); $i++) {
    // Moves the file with the new name to the $location path
    $file_new_name = 'post' . $postsID . "u" . $usersID . "i" . $i . '.jpg';
    $mup = move_uploaded_file($file_temp[$i], $location . $file_new_name);
    echo $mup;
    // Updates the partsImg to include the new imgs
    $sql = "INSERT INTO postsImg (postsID, usersID, postsImgPath) VALUES (?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    // ! Invalid 9 - Something wrong with STMT
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        $result["error"] = 'stmtFailedImage';
        exit(json_encode($result));
    } else {
        mysqli_stmt_bind_param($stmt, "iis", $postsID, $usersID, $file_new_name);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

// ? ----- INSERT TAGS -----

if ($isTags == 1) {
    for ($i = 0; $i < count($tags); $i++) {
        $sql = "INSERT INTO postsTags (postsID, tagsValue) VALUES (?, ?);";
        $stmt = mysqli_stmt_init($conn);
        // ! Invalid 10 - SQL/STMT Failed
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            $result["error"] = 'stmtFailed3';
            exit(json_encode($result));
        }
        mysqli_stmt_bind_param($stmt, "is", $postsID, $tags[$i]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

// ? ----- RESPONSE -----

exit(json_encode($result));
