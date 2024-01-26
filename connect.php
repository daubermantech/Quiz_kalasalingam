<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST['Email']);
    $password = htmlspecialchars($_POST['Password']);

    // Check if email or password is empty
    if (empty($email) || empty($password)) {
        echo "Invalid input. Please fill all fields";
        exit();
    }

    $conn = new mysqli('localhost', 'root', '', 'Quiz');

    if ($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    } else {
        // Perform login only if email and password are not empty and account_activation_hash is NULL
        $stmt = $conn->prepare("SELECT * FROM registration WHERE email = ? AND password = ? AND account_activation_hash IS NULL");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            header("Location:dashboard.html");
            // You can redirect the user to another page or perform further actions here
        } else {
            echo "<script>alert('Login failed. Invalid email, password, or account not activated.'); window.location.href='login.html';</script>";
        }

        $stmt->close();
        $conn->close();
    }
}
