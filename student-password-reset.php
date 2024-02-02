<?php

$email = $_POST["email"];

$token = bin2hex(random_bytes(16));
$token_hash = hash("sha256", $token);
$expiry = date("Y-m-d H:i:s", time() + 60 * 30);

$host = "localhost";
$dbname = "quiz";
$username = "root";
$dbPassword = ""; // Change this variable name to avoid conflict

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $dbPassword);

    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare the SQL statement with placeholders
    $sql = "UPDATE student_db
            SET reset_token_hash = :token_hash,
                reset_token_expires_at = :expiry
            WHERE email = :email";

    $stmt = $pdo->prepare($sql);

    // Bind the values to placeholders
    $stmt->bindValue(':token_hash', $token_hash);
    $stmt->bindValue(':expiry', $expiry);
    $stmt->bindValue(':email', $email);

    // Execute the query
    $stmt->execute();

    if ($stmt->rowCount()) {
        // Include mailer configuration
        $mail = require __DIR__ . "/mailer.php";

        // Set mail details
        $mail->setFrom("noreply@example.com");
        $mail->addAddress($email);
        $mail->Subject = "Password Reset";
        $mail->Body = <<<END
        Click <a href="http://localhost/vijay/student-reset-password.php?token=$token">here</a> 
        to reset your password.
        END;

        // Try to send the email
        try {
            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
        }
    }

    echo "Message sent, please check your inbox.";
} catch (PDOException $e) {
    echo "error|Connection failed: " . $e->getMessage();
}
?>