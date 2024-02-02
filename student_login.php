<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST['Email']);
    $password = htmlspecialchars($_POST['Password']);

    // Check if email or password is empty
    if (empty($email) || empty($password)) {
        echo "Invalid input. Please fill all fields";
        exit();
    }

    $conn = new mysqli('localhost', 'root', '', 'quiz');

    if ($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    } else {
        // Prepare and execute a query to get the hashed password for the given email
        $stmt = $conn->prepare("SELECT email, password FROM student_db WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Check if the stored password is hashed
            if (password_verify($password, $user['password'])) {
                // Password is correct (hashed), set session variables or perform other actions
                session_start();
                $_SESSION['user_email'] = $user['email'];
                header("Location: student_dashboard.php"); // Redirect to the dashboard page
                exit();
            } elseif ($password === $user['password']) {
                // Password is correct (default plain text), set session variables or perform other actions
                session_start();
                $_SESSION['user_email'] = $user['email'];
                header("Location: student_dashboard.php"); // Redirect to the dashboard page
                exit();
            } else {
                // Password is incorrect
                echo "<script>alert('Login failed. Invalid email or password.'); window.location.href='student_login.html';</script>";
            }
        } else {
            echo "<script>alert('Login failed. Invalid email or password.'); window.location.href='student_login.html';</script>";
        }

        $stmt->close();
        $conn->close();
    }
}
?>