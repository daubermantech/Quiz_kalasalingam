<?php
session_start();

if (!isset($_SESSION['user_email'])) {
    header("Location: login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['file_path'])) {
    $user_email = $_SESSION['user_email'];
    $file_path = $_POST['file_path'];

    // Check if the file is scheduled
    $conn = new mysqli('localhost', 'root', '', 'quiz');
    if ($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    }

    $stmt_schedule = $conn->prepare("SELECT * FROM scheduled_quizzes WHERE user_email = ? AND file_path = ?");
    $stmt_schedule->bind_param("ss", $user_email, $file_path);
    $stmt_schedule->execute();
    $result_schedule = $stmt_schedule->get_result();

    // If the file is scheduled, show alert and redirect
    if ($result_schedule->num_rows > 0) {
        echo '<script>alert("Cannot delete. Quizzes are scheduled for this file.");</script>';
        $stmt_schedule->close();
        $conn->close();

        // Introduce a delay before redirecting to ensure the alert is displayed
        echo '<script>setTimeout(function() { window.location.href = "schedulequiz.php"; }, 2000);</script>';
        exit();
    }

    // Remove the file from the uploads directory
    if (unlink($file_path)) {
        // If deletion from directory is successful, remove the record from the database
        $stmt_delete = $conn->prepare("DELETE FROM uploaded_files WHERE user_email = ? AND file_path = ?");
        $stmt_delete->bind_param("ss", $user_email, $file_path);
        $stmt_delete->execute();
        $stmt_delete->close();
        $conn->close();

        echo '<script>alert("File deleted successfully.");</script>';

        // Redirect to schedulequiz.php after successful deletion
        header("Location: schedulequiz.php");
        exit();
    } else {
        echo 'Error deleting file.';
    }
} else {
    echo 'Invalid request.';
}
?>