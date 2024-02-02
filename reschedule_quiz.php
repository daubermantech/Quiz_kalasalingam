<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'quiz');

if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_email = $_SESSION['user_email'];
    $new_quiz_date = filter_input(INPUT_POST, 'new_quiz_date', FILTER_SANITIZE_STRING);
    $new_start_time = filter_input(INPUT_POST, 'new_start_time', FILTER_SANITIZE_STRING);
    $new_end_time = filter_input(INPUT_POST, 'new_end_time', FILTER_SANITIZE_STRING);
    $current_title = filter_input(INPUT_POST, 'current_title', FILTER_SANITIZE_STRING);

    // Validate input
    if (!validateDateFormat($new_quiz_date) || !validateTimeFormat($new_start_time) || !validateTimeFormat($new_end_time)) {
        echo 'Invalid date or time format.';
        exit;
    }

    // Validate if the rescheduled quiz is in the future
    if (!isFutureDateTime($new_quiz_date, $new_start_time)) {
        echo 'The entered value is in the past. Please select a future time.';
        exit;
    }

    $new_startDateTime = "$new_quiz_date $new_start_time";
    $new_endDateTime = "$new_quiz_date $new_end_time";

    $new_code = generateQuizCode();

    // Use prepared statement to prevent SQL injection
    $update_query = "UPDATE scheduled_quizzes SET quiz_date=?, start_time=?, end_time=?, code=? WHERE user_email=? AND title=?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssssss", $new_quiz_date, $new_startDateTime, $new_endDateTime, $new_code, $user_email, $current_title);

    if ($stmt->execute()) {
        // Display the result message
        echo 'Quiz rescheduled successfully.';
        echo '<p>New Code: ' . $new_code . '</p>';
        // Display the copy button
        echo '<button id="copyCodeBtn" onclick="copyCodeToClipboard()">Copy Code</button>';
        echo '<p id="copyMessage"></p>';
    } else {
        echo 'Error rescheduling quiz: ' . $stmt->error;
    }

    $stmt->close();
} else {
    // Display existing quiz details
    $user_email = $_SESSION['user_email'];
    $title = urldecode($_GET['title']);

    $query = "SELECT * FROM scheduled_quizzes WHERE user_email='$user_email' AND title='$title'";
    $result = mysqli_query($conn, $query);

    if ($result && $quiz = mysqli_fetch_assoc($result)) {
        // Display the existing quiz details
        echo '<h2>Reschedule Quiz: ' . $title . '</h2>';
        echo '<p>Current Details:</p>';
        echo '<p>Quiz Date: ' . $quiz['quiz_date'] . '</p>';
        echo '<p>Start Time: ' . $quiz['start_time'] . '</p>';
        echo '<p>End Time: ' . $quiz['end_time'] . '</p>';

        // Display a form for rescheduling
        echo '<form method="post" action="reschedule_quiz.php">';
        echo '<label for="new_quiz_date">New Quiz Date:</label>';
        echo '<input type="date" name="new_quiz_date" required><br>';

        echo '<label for="new_start_time">New Start Time:</label>';
        echo '<input type="time" name="new_start_time" required><br>';

        echo '<label for="new_end_time">New End Time:</label>';
        echo '<input type="time" name="new_end_time" required><br>';

        // Add a hidden field to pass the current title
        echo '<input type="hidden" name="current_title" value="' . $title . '">';

        echo '<input type="submit" value="Reschedule">';
        echo '</form>';
    } else {
        echo 'Error fetching quiz details';
    }
}

mysqli_close($conn);

function validateDateFormat($date)
{
    // Use DateTime class to validate date format and check if it's a valid date
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

function validateTimeFormat($time)
{
    // Use DateTime class to validate time format and check if it's a valid time
    $t = DateTime::createFromFormat('H:i', $time);
    return $t && $t->format('H:i') === $time;
}

function isFutureDateTime($date, $time)
{
    $dateTime = strtotime("$date $time");
    $currentTime = time();
    return $dateTime > $currentTime;
}

function generateQuizCode()
{
    return strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));
}
?>
<script>
    function copyCodeToClipboard() {
        var codeToCopy = "<?php echo $new_code; ?>";
        var tempInput = document.createElement("input");
        document.body.appendChild(tempInput);
        tempInput.value = codeToCopy;
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);
        document.getElementById("copyMessage").innerText = "Code copied to clipboard!";
    }
</script>
