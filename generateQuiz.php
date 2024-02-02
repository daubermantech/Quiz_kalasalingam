<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Quiz</title>
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            font-family: Arial, sans-serif;
        }

        .links {
            display: block;
            color: white;
            text-decoration: none;
            font-size: 20px;
            margin-bottom: 50px;
            margin-left: 20px;
        }

        .links:hover {
            color: black;
        }

        #sidebar {
            width: 20%;
            background-color: #9328FF;
            color: #fff;
            border-radius: 0px 40px 40px 0px;
            position: fixed;
            height: 100vh;
        }

        #content {
            flex: 1;
            padding: 20px;
            margin-left: 25%;
        }

        #sidebar img {
            margin-top: 10px;
            margin-left: 10px;
            border-radius: 25px;
            margin-bottom: 30px;
        }

        .upload {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin-left: 40%;
        }

        .upload1 {
            margin-bottom: 20px;
            margin-left: 21%;
            margin-top: 8px;
            display: flex;
            position: absolute;
        }

        .upload2 {
            max-width: 400px;
            width: 100%;
            text-align: center;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .upload2 label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .upload2 input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .upload2 button {
            background-color: #9328FF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .upload2 button:hover {
            background-color: #6F1CFF;
        }

        #response {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div id="sidebar">
        <img src="./large_Kalasalingam_Academy_of_Research_and_Education_Virudhunagar_aeb7350844_a1649b2e88 (1).png" height="20%" width="90%">
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
    <h1 class="upload1">Generate Quiz From CSV</h1>


    <main class="upload">
        <div class="upload2">
            <!-- <h1 class="upload1">Generate Quiz From CSV</h1> -->

            <form action="./qb_upload.php" method="post" enctype="multipart/form-data">
                <label for="quiz_file">Select a csv file:</label>
                <input type="file" id="quiz_file" name="quiz_file">
                <button type="submit">Upload and Generate Quiz</button>
            </form>

            <div id="response"></div>
        </div>
    </main>

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

</body>

</html>