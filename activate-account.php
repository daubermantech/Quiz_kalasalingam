<?php

$token = isset($_GET["token"]) ? $_GET["token"] : null;

if (empty($token)) {
    die("Invalid token");
}

$token_hash = hash("sha256", $token);

// Include your database connection file
$pdo = require __DIR__ . "/database.php";

try {
    // Prepare and execute the SELECT query to find the user with the given activation token
    $sqlSelect = "SELECT * FROM registration WHERE account_activation_hash = ?";
    $stmtSelect = $pdo->prepare($sqlSelect);
    $stmtSelect->bindParam(1, $token_hash);
    $stmtSelect->execute();

    // Fetch the user record
    $user = $stmtSelect->fetch(PDO::FETCH_ASSOC);

    // Check if the user was found
    if (!$user) {
        die("Invalid token or user not found");
    }

    // Update the user record to mark the account as activated (set activation hash to NULL)
    $sqlUpdate = "UPDATE registration SET account_activation_hash = NULL WHERE email = ?";
    $stmtUpdate = $pdo->prepare($sqlUpdate);
    $stmtUpdate->bindParam(1, $user["email"]); // Assuming 'email' is the column containing the user's email
    $stmtUpdate->execute();

    // Output HTML response
    echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Activated</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    <h1>Account Activated</h1>
    <p>Account activated successfully. You can now <a href="login.html">log in</a>.</p>
</body>
</html>
HTML;
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
