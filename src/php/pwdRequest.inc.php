<?php
if (isset($_POST['reset-submit'])) {
    $selector = bin2hex(random_bytes(8));
    $token = random_bytes(32); // Needs to be longer so it is secure

    $url = "localhost/pr/create-new-password.php?selector=" . $selector . "&validator=" . bin2hex($token);

    // Creates the expire date that is 1hr ahead of the current time/date
    $expires = date("U") + 1800;

    

    require 'dbh.inc.php';

    $usersEmail = $_POST["email"];

    // First Delete any duplicate ones cause we dont want multiple

    $sql = "DELETE FROM pwdReset WHERE pwdResetEmail=?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)){
        header("Location: ../index.php?error=stmtfailed");
        exit();
    }
    else {
        mysqli_stmt_bind_param($stmt, "s", $usersEmail);
        mysqli_stmt_execute($stmt);
    }

    // Add the token to the database

    $sql = "INSERT INTO pwdReset (pwdResetEmail, pwdResetSelector, pwdResetToken, pwdResetExpires) VALUES (?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)){
        header("Location: ../index.php?error=stmtfailed");
        exit();
    }
    else {
        // Hash the token to ensure that if someone get into the database its harder to get that info
        $hashedToken = password_hash($token, PASSWORD_DEFAULT);
        mysqli_stmt_bind_param($stmt, "ssss", $usersEmail, $selector, $hashedToken, $expires);
        mysqli_stmt_execute($stmt);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    // EMAIL
    $to = $usersEmail;

    $subject = "Nyaz Password Reset";

    $message = "<p>This is a password reset request. The link to reset is below, if you didn't make this request please ignore this email.</p>";

    $message .= "<p>Here is your password reset link: </p>";
    $message .= '<a href="' . $url . '">' . $url . '</a>';

    $headers = "From: sellmycarparts <support@sellmycarparts.com>\r\n";
    $headers .= "Reply-To: support@sellmycarparts.com\r\n";
    $headers .= "Content-type: text/html\r\n";

    mail($to, $subject, $message, $headers);

    header("Location: ../reset-password.php?reset=success");

}
else {
    header("Location: ../index.php");
}
