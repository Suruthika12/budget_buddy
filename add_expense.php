<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo "Unauthorized";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId     = $_SESSION['user_id'];
    $type       = $_POST['type'];
    $category   = $_POST['category'];
    $amount     = $_POST['amount'];
    $date       = $_POST['date'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("INSERT INTO transactions (user_id, type, category, amount, date, description) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issdss", $userId, $type, $category, $amount, $date, $description);

    if ($stmt->execute()) {
        echo "Transaction added successfully.";
    } else {
        http_response_code(500);
        echo "Error: " . $stmt->error;
    }
}
?>
