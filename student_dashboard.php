<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['user_email'])) {
    // Redirect to the login page
    header("Location: student_login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link href='https://fonts.googleapis.com/css?family=League Spartan' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href='https://fonts.googleapis.com/css?family=Commissioner' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Trade Winds' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/css?family=Outfit' rel='stylesheet'>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <link rel="stylesheet" href="./dashboard.css">
    <title>DashBoard</title>
</head>

<body>
    <div class="header_section">
        <div class="header">
            <h3>STUDENT ASSESSMENT PORTAL(FACULTY LOGIN)</h3>
        </div>
        <div class="header_logo">
            <i class="fa-regular fa-bell" style="color: #5A57FF;"></i>
            <button id="logoutButton" class="logout_button">Logout</button>
        </div>
    </div>

    <section class="center_part">
        <div class="Links">
            <img src="./images/large_Kalasalingam_Academy_of_Research_and_Education_Virudhunagar_aeb7350844_a1649b2e88 (1).png" height="70%" width="90%">
            <a href="#" class="links">Dashboard </a>
            <a href="./generateQuiz.php">Generate Quiz</a>
            <a href="#">Edit Generated Quiz</a>
            <a href="#">Schedule Quiz</a>
            <a href="#">View Marks</a>
            <a href="#">Generate Report</a>
        </div>
        <h3 class="body_heading">Dashboard</h3>
        <div class="cards">
            <a href="./generateQuiz.php">
                <div class="cards1">
                    <img src="./images/Quiz-comic-pop-art-style-illustration-on-transparent-background-PNG.png" width="90px" height="90px">
                    <h3>Generate Quiz</h3>
                </div>
            </a>

            <a href="./schedulequiz.php">
                <div class="cards2">
                    <img src="./images/quiz.png" width="90%" height="70%">
                    <h3>Schedule Quiz</h3>
                </div>
            </a>
        </div>

        <div class="card1">
            <a href="./edit_scheduled_quizzes.php">
                <div class="cards3">
                    <img src="./images/quiz (1).png" width="90px" height="90px"><br>
                    <h3>Edit Generated Quiz</h3>
                </div>
            </a>
            <a href="#">
                <div class="cards4">
                    <img src="./images/result.png" width="90px" height="90px"><br>
                    <h3>View Marks</h3>
                </div>
            </a>
        </div>
    </section>
    <script>
        // Logout button click event
        document.getElementById('logoutButton').addEventListener('click', function() {
            // Send a logout request to dashboard.php
            fetch('logout.php', {
                    method: 'POST',
                    body: new URLSearchParams({
                        'logout': '1'
                    })
                })
                .then(response => response.text())
                .then(data => {
                    // Handle the response if needed
                    console.log(data);

                    // You can perform additional actions after logout if needed
                    // For example, redirect to the login page
                    window.location.href = 'student_login.html';
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
</body>

</html>