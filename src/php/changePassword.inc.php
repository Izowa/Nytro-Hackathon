<?php
require_once 'functions.inc.php';
$selector = $_POST['selector'];
$validator = $_POST['validator'];
$pwd = $_POST['pwd'];
$pwdRepeat = $_POST['pwd-repeat'];

if (empty($selector) || empty($validator)) {
    echo "Could not validate your request!";
    header("Location: ../create-new-password.php?selector=$selector&validator=$validator&newpwd=empty");
    exit();
} elseif ($pwd != $pwdRepeat) {
    header("Location: ../create-new-password.php?selector=$selector&validator=$validator&newpwd=pwdnotsame");
    exit();
}

// Validate password strength
$uppercase = preg_match('@[A-Z]@', $pwd);
$lowercase = preg_match('@[a-z]@', $pwd);
$number    = preg_match('@[0-9]@', $pwd);
$specialChars = preg_match('@[^\w]@', $pwd);

if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($pwd) < 8) {
    echo " Strong Password ";
} else {
    echo "Could not validate your request!";
    header("Location: ../create-new-password.php?selector=$selector&validator=$validator&newpwd=empty");
    exit();
}

$currentDate = date("U");

require 'dbh.inc.php';

// Check if the current request is valid
$sql = "SELECT * FROM pwdReset WHERE pwdResetSelector=? AND pwdResetExpires >=" . $currentDate;
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../create-new-password.php?selector=$selector&validator=$validator&newpwd=stmtfailed");
    exit();
} else {
    mysqli_stmt_bind_param($stmt, "s", $selector);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    if (!$row = mysqli_fetch_assoc($result)) {
        header("Location: ../create-new-password.php?selector=$selector&validator=$validator&newpwd=norows");
        exit();
    } else {
        $tokenBin = hex2bin($validator);
        $tokenCheck = password_verify($tokenBin, $row['pwdResetToken']);

        if ($tokenCheck === false) {
            echo "You need to resubmit your reset request";
            exit();
        } else if ($tokenCheck === true) {
            $tokenEmail = $row['pwdResetEmail'];
            // Find the user to which we are updating the pwd
            $sql = "SELECT * FROM users WHERE usersEmail=?;";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../create-new-password.php?selector=$selector&validator=$validator&newpwd=stmtfailed");
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                if (!$row = mysqli_fetch_assoc($result)) {
                    header("Location: ../create-new-password.php?selector=$selector&validator=$validator&newpwd=nouserfound");
                    exit();
                } else {
                    // Generates the new hashed password and updates the database with it
                    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
                    $sql = "UPDATE users SET usersPwd=? WHERE usersEmail=?;";
                    $stmt = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        header("Location: ../create-new-password.php?selector=$selector&validator=$validator&newpwd=noupdate");
                        exit();
                    } else {
                        mysqli_stmt_bind_param($stmt, "ss", $hashedPwd, $tokenEmail);
                        mysqli_stmt_execute($stmt);
                    }

                    // Deletes the tokens from the database
                    $sql = "DELETE FROM pwdReset WHERE pwdResetEmail=?;";
                    $stmt = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        header("Location: ../index.php?error=stmtfailed");
                        exit();
                    } else {
                        mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
                        mysqli_stmt_execute($stmt);
                        header("Location: ../login.php?newpwd=passwordUpdated");
                    }
                }
            }
        }
    }
}
mysqli_close($conn);
