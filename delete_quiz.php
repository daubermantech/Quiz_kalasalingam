<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'quiz');
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['title'])) {
    $user_email = $_SESSION['user_email'];
    $title = urldecode($_GET['title']);

    // Delete the selected quiz
    $query = "DELETE FROM scheduled_quizzes WHERE user_email='$user_email' AND title='$title'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        header('Location: edit_scheduled_quizzes.php');
        exit;
    } else {
        echo 'Error deleting quiz';
    }
} else {
    echo 'Invalid request';
}

mysqli_close($conn);

