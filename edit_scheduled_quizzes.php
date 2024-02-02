<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'quiz');
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

$user_email = $_SESSION['user_email'];

// Fetch already scheduled quizzes
$query = "SELECT * FROM scheduled_quizzes WHERE user_email = '$user_email'";
$result = mysqli_query($conn, $query);

if ($result) {
    $quizzes = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $quizzes[] = $row;
    }
} else {
    echo 'Error fetching scheduled quizzes';
    exit;
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scheduled Quizzes</title>
</head>

<body>

    <h2>Scheduled Quizzes</h2>

    <?php if (!empty($quizzes)) : ?>
        <table border="1">
            <tr>
                <th>Title</th>
                <th>Quiz Date</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Generated Code</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($quizzes as $quiz) : ?>
                <tr>
                    <td><?= $quiz['title']; ?></td>
                    <td><?= $quiz['quiz_date']; ?></td>
                    <td><?= $quiz['start_time']; ?></td>
                    <td><?= $quiz['end_time']; ?></td>
                    <td><?= $quiz['code']; ?></td>
                    <td>
                        <a href="reschedule_quiz.php?title=<?= urlencode($quiz['title']); ?>">Reschedule</a> |
                        <a href="delete_quiz.php?title=<?= urlencode($quiz['title']); ?>">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else : ?>
        <p>No quizzes scheduled.</p>
    <?php endif; ?>

</body>

</html>