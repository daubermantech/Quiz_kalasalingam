<!DOCTYPE html>
<html lang="en">

<head>
    <title>Schedule Quiz</title>
    <style>
        * {
            padding: 0;
            margin: 0;
        }

        body {
            display: flex;
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, rgba(0, 36, 176, 61%), rgba(219, 0, 158, 54%));
        }

        .links {
            display: block;
            color: white;
            text-decoration: none;
            font-size: 20px;
            margin-top: 30px;


        }
        .links:hover{
            /* color: black; */
            border:1px solid black;
            border-radius: 20px;
            padding: 20px;
            background-color: black;
        }

        #sidebar {
            width: 250px;
            background: rgba(255, 255, 255, 0.3);
            color: #fff;
            padding: 20px;
            position: fixed;
            height: 100vh;

          
        }

        #content {
            flex: 1;
            padding: 20px;
            margin-left: 25%;
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .card {
            width: 300px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .card:hover{
            background-color: black;
            color:white;
            
        }
    </style>
</head>

<body>

    <div id="sidebar">
        <h2>Sidebar Menu</h2>
        <!-- Add your sidebar menu items here -->
        <div class="link">
            <a href="./dashboard.php" class="links">Dashboard </a>
            <a href="./generateQuiz.php" class="links">Generate Quiz</a>
            <a href="#" class="links">Edit Generated Quiz</a>
            <a href="./schedulequiz.php" class="links">Schedule Quiz</a>
            <a href="#" class="links">View Marks</a>
            <a href="#" class="links">Generate Report</a>
        </div>
    </div>

    <div id="content">
        <h1>Schedule Quiz</h1><br><br>

        <?php
        session_start();

        // Check if the user is logged in
        if (!isset($_SESSION['user_email'])) {
            // Redirect to the login page if not logged in
            header("Location: login.html");
            exit();
        }

        // Fetch uploaded documents for the logged-in user
        $conn = new mysqli('localhost', 'root', '', 'quiz');
        if ($conn->connect_error) {
            die('Connection Failed: ' . $conn->connect_error);
        }

        $user_email = $_SESSION['user_email'];
        $stmt = $conn->prepare("SELECT * FROM uploaded_files WHERE user_email = ?");
        $stmt->bind_param("s", $user_email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if there are any documents
        if ($result->num_rows > 0) {
            echo '<div class="card-container">';

            while ($row = $result->fetch_assoc()) {
                // Display a card for each uploaded document with box-shadow
                echo '<div class="card" data-file-path="' . $row['file_path'] . '">';
                echo '<h4>File Name: ' . $row['original_file_name'] . '</h4>';
                echo '<p>Uploaded date and Time: ' . $row['upload_timestamp'] . '</p>';
                echo '<a href="view_document.php?file_path=' . $row['file_path'] . '">View Document</a>';// Add a button/link to view the document content on another page
                echo '</div>';
            }

            echo '</div>';
        } else {
            // Display a message if no documents are found
            echo '<p>No uploaded documents available.</p>';
        }

        $stmt->close();
        $conn->close();
        ?>
    </div>

</body>

</html>