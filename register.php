<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST['Email']);
    $password = htmlspecialchars($_POST['Password']);

    $conn = new mysqli('localhost', 'root', '', 'quiz');

    if ($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    } else {
        // Check if the email already exists
        $checkStmt = $conn->prepare("SELECT email FROM registration WHERE email = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            echo "<script>alert('Email is already Registered'); window.location.href='register.html';</script>";
        } else {
            // Insert the new registration if the email doesn't exist
            $insertStmt = $conn->prepare("INSERT INTO registration(email, password) VALUES (?, ?)");
            $insertStmt->bind_param("ss", $email, $password);

            if ($insertStmt->execute()) {
                header("Location: index.html");
            } else {
                echo "error|Error: " . $insertStmt->error;
            }

            $insertStmt->close();
        }

        $checkStmt->close();
        $conn->close();
    }
}
