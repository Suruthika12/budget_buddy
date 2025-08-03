<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo "Unauthorized";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $transactionId = $_POST['id'];
    $userId = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM transactions WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $transactionId, $userId);

    if ($stmt->execute()) {
        echo "Transaction deleted.";
    } else {
        http_response_code(500);
        echo "Error: " . $stmt->error;
    }
}
?>
