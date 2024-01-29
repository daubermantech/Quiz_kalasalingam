<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Quiz</title>
</head>

<body>

    <h1>Generate Quiz From Word Document</h1>

    <form action="./qb_upload.php" method="post" enctype="multipart/form-data">
        <label for="quiz_file">Select Word Document:</label>
        <input type="file" id="quiz_file" name="quiz_file">
        <button type="submit">Upload and Generate Quiz</button>
    </form>

    <div id="response"></div>

</body>

</html>

<?php
// Start the session at the end of the file
session_start();

// You can use the session to check if the user is logged in, etc.
// For example:
if (!isset($_SESSION['user_email'])) {
    // Redirect to the login page if not logged in
    header("Location: login.html");
    exit();
}
?>