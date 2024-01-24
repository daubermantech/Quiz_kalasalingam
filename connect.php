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
        // Perform login only if email and password are not empty
        $stmt = $conn->prepare("SELECT * FROM registration WHERE email = ? AND password = ?");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
             header("Location: Faculty.html");
            // You can redirect the user to another page or perform further actions here
        } else {
            echo "<script>alert('Login failed. Invalid email or password.'); window.location.href='index.html';</script>";
        }

        $stmt->close();
        $conn->close();
    }
}
?>
