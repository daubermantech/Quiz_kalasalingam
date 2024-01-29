<?php
session_start();

// Specify the directory for storing uploaded documents
$upload_dir = 'uploads/';

// Check if the user is logged in
if (!isset($_SESSION['user_email'])) {
    // Redirect to the login page if not logged in
    header("Location: login.html");
    exit();
}

// Check if the upload directory exists, create it if not
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0755, true); // Create with recursive permissions
}

// If a file has been uploaded
if (isset($_FILES['quiz_file']) && $_FILES['quiz_file']['error'] == UPLOAD_ERR_OK) {
    // Retrieve user's email from the session
    $user_email = $_SESSION['user_email'];

    // Sanitize original filename
    $original_filename = basename($_FILES['quiz_file']['name']);
    $original_filename = preg_replace('/[^a-zA-Z0-9.-]/', '', $original_filename);

    // Validate file type
    $file_type = mime_content_type($_FILES['quiz_file']['tmp_name']);
    if ($file_type != 'text/csv') {
        // Display an alert if the file is not a CSV
        echo '<script>alert("Invalid file type. Please upload a CSV file."); window.location.href="generateQuiz.html";</script>';
    } else {
        // Generate unique key (filename)
        $unique_key = $_SESSION['user_email'] . '_' . time(); // You can customize this key generation logic
        $target_path = $upload_dir . $unique_key . '.csv';

        // Move the uploaded file to the designated directory
        if (move_uploaded_file($_FILES['quiz_file']['tmp_name'], $target_path)) {
            echo 'File uploaded successfully!';

            // Save relevant information to the database
            $conn = new mysqli('localhost', 'root', '', 'quiz');

            if ($conn->connect_error) {
                die('Connection Failed: ' . $conn->connect_error);
            }

            // Insert information into the database (adjust the table structure accordingly)
            $stmt = $conn->prepare("INSERT INTO uploaded_files (user_email, original_file_name, unique_key, file_path, upload_timestamp) VALUES (?, ?, ?, ?, NOW())");
            $stmt->bind_param("ssss", $user_email, $original_filename, $unique_key, $target_path);
            $stmt->execute();
            $stmt->close();
            $conn->close();

            // Redirect to the dashboard or any other appropriate page
            header("Location: schedulequiz.php");
            exit();
        } else {
            die('Error uploading file.');
        }
    }
} else {
    // Handle the case where no file was uploaded
    echo 'Please select a CSV file to upload.';
}
