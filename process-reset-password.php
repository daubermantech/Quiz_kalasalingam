<?php
$token = $_POST["token"];
$password = $_POST["password"];

$host = "localhost";
$dbname = "quiz";
$username = "root";
$dbPassword = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Hash the token
    $token_hash = hash("sha256", $token);

    $sql = "SELECT * FROM registration
            WHERE reset_token_hash = :token_hash";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':token_hash', $token_hash, PDO::PARAM_STR); // Use $token_hash here
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user === false) {
        die("Token not found");
    }

    if (isset($user["reset_token_expires_at"]) && strtotime($user["reset_token_expires_at"]) <= time()) {
        die("Token has expired");
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Update the user's password and clear the reset token
    $update_sql = "UPDATE registration
                   SET password = :hashed_password,
                       reset_token_hash = NULL,
                       reset_token_expires_at = NULL
                   WHERE email = :email";

    $update_stmt = $pdo->prepare($update_sql);
    $update_stmt->bindParam(':hashed_password', $hashed_password, PDO::PARAM_STR);
    $update_stmt->bindParam(':email', $user["email"], PDO::PARAM_INT);
    $update_stmt->execute();

    // Display message and provide a link to login.html
    echo "Password updated. You can now <a href='login.html'>login</a>.";
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>