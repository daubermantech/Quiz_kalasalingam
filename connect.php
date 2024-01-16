<?php
$email = htmlspecialchars($_POST['Email']);
$password = htmlspecialchars($_POST['Password']);

$conn = new mysqli('localhost', 'root', '', 'Quiz');

if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
} else {
    $stmt = $conn->prepare("INSERT INTO registration(email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $password);

    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
