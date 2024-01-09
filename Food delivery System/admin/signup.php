<!DOCTYPE html>
<html lang="en">

<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

if (isset($_POST['signup'])) {
    $newUsername = $_POST['newUsername'];
    $newPassword = $_POST['newPassword'];

    if (!empty($newUsername) && !empty($newPassword)) {
        // Check if the username is not already taken
        $checkQuery = "SELECT * FROM admin WHERE username=?";
        $stmtCheck = $db->prepare($checkQuery);
        $stmtCheck->bind_param("s", $newUsername);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();

        if ($resultCheck->num_rows == 0) {
            // Username is available, proceed with signup
            $hashedPassword = md5($newPassword);

            $insertQuery = "INSERT INTO admin (username, password) VALUES (?, ?)";
            $stmtInsert = $db->prepare($insertQuery);
            $stmtInsert->bind_param("ss", $newUsername, $hashedPassword);

            if ($stmtInsert->execute()) {
                $success = "Signup successful! You can now login.";
            } else {
                $message = "Error in signup. Please try again.";
            }

            $stmtInsert->close();
        } else {
            $message = "Username already taken. Choose a different username.";
        }

        $stmtCheck->close();
    } else {
        $message = "Both username and password are required for signup.";
    }
}
?>

<head>
    <meta charset="UTF-8">
    <title>Admin Signup</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">

    <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900'>
    <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Montserrat:400,700'>
    <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>

    <link rel="stylesheet" href="css/login.css">

</head>

<body>

    <div class="container">
        <div class="info">
            <h1>Admin Panel </h1>
        </div>
    </div>
    <div class="form">
        <div class="thumbnail"><img src="images/manager.png" /></div>
        <span style="color:red;"><?php echo isset($message) ? htmlentities($message) : ''; ?></span>
        <span style="color:green;"><?php echo isset($success) ? htmlentities($success) : ''; ?></span>
        <form class="login-form" action="signup.php" method="post">
            <input type="text" placeholder="New Username" name="newUsername" />
            <input type="password" placeholder="New Password" name="newPassword" />
            <input type="submit" name="signup" value="Signup" />
        </form>

        <p>Already have an account? <a href="index.php">Login here</a></p>
    </div>
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src='js/index.js'></script>
</body>

</html>