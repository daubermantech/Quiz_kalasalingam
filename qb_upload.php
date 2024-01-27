<?php
session_start();

// Specify the directory for storing uploaded documents
$upload_dir = 'uploads/';

// Check if the upload directory exists, create it if not
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0755, true); // Create with recursive permissions
}

// If a file has been uploaded
if (isset($_FILES['quiz_file']) && $_FILES['quiz_file']['error'] == UPLOAD_ERR_OK) {
    // Retrieve user's email from the session
    if (isset($_SESSION['user_email']) && !empty($_SESSION['user_email'])) {
        $user_email = $_SESSION['user_email'];

        // Validate file type
        $file_type = mime_content_type($_FILES['quiz_file']['tmp_name']);
        if ($file_type != 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
            die('Invalid file type. Please upload a Word document.');
        }

        // Sanitize filename
        $filename = basename($_FILES['quiz_file']['name']);
        $filename = preg_replace('/[^a-zA-Z0-9.-]/', '', $filename);

        // Generate unique key (filename) with user's email
        $unique_key = $user_email . '_' . time(); // You can customize this key generation logic
        $target_path = $upload_dir . $unique_key . '.docx';

        // Move the uploaded file to the designated directory
        if (move_uploaded_file($_FILES['quiz_file']['tmp_name'], $target_path)) {
            echo 'File uploaded successfully!';

            // Save relevant information to the database
            $conn = new mysqli('localhost', 'root', '', 'quiz');

            if ($conn->connect_error) {
                die('Connection Failed: ' . $conn->connect_error);
            }

            // Insert information into the database (adjust the table structure accordingly)
            $stmt = $conn->prepare("INSERT INTO registration (email, unique_key, file_path) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $user_email, $unique_key, $target_path);
            $stmt->execute();
            $stmt->close();
            $conn->close();

            // Redirect to the dashboard or any other appropriate page
            header("Location: dashboard.html");
            exit();
        } else {
            die('Error uploading file.');
        }
    } else {
        die('User email not available. Please log in again.');
    }
} else {
    // Handle the case where no file was uploaded
    echo 'Please select a Word document to upload.';
}
