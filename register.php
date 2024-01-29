<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST['Email']);
    $inputPassword = htmlspecialchars($_POST['Password']);

    // Generate activation token
    $activation_token = bin2hex(random_bytes(16));
    $activation_token_hash = hash("sha256", $activation_token);

    $host = "localhost";
    $dbname = "quiz";
    $username = "root";
    $dbPassword = ""; // Change this variable name to avoid conflict

    try {
        // Create a new PDO instance
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $dbPassword);

        // Set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if the email already exists
        $checkStmt = $pdo->prepare("SELECT email FROM registration WHERE email = ?");
        $checkStmt->bindParam(1, $email);
        $checkStmt->execute();
        $result = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if ($result['email'] == $email) {
            echo "<script>alert('Email is already Registered'); window.location.href='index.html';</script>";
        } else {
            // Insert the new registration if the email doesn't exist
            $insertStmt = $pdo->prepare("INSERT INTO registration(email, password, account_activation_hash) VALUES (?, ?, ?)");
            $insertStmt->bindParam(1, $email);
            $insertStmt->bindParam(2, $inputPassword);
            $insertStmt->bindParam(3, $activation_token_hash);

            if ($insertStmt->execute()) {
                $mail = require __DIR__  . "/mailer.php";

                $mail->setFrom("vijaykmr2422@gmail.com");
                $mail->addAddress($email); // Change this line to use the correct variable
                $mail->Subject = "Account Activation";
                $mail->Body = <<<END
                Click <a href="http://localhost/vijay/activate-account.php?token=$activation_token">here</a> 
                to activate your account.
                END;

                try {
                    $mail->send();
                    header("Location: signup success.html");
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
                    exit;
                }
            } else {
                echo "error|Error: Unable to execute the SQL statement.";
            }
        }
    } catch (PDOException $e) {
        echo "error|Connection failed: " . $e->getMessage();
    }
}
