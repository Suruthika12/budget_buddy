<?php
session_start();
include 'db.php';

$user_id = $_SESSION['user_id'] ?? 0;
$type = $_POST['type'] ?? '';
$category = $_POST['category'] ?? '';

$query = "SELECT * FROM transactions WHERE user_id = ?";
$params = [$user_id];
$types = "i";

if (!empty($type)) {
  $query .= " AND type = ?";
  $params[] = $type;
  $types .= "s";
}

if (!empty($category)) {
  $query .= " AND category LIKE ?";
  $params[] = "%$category%";
  $types .= "s";
}

$stmt = $conn->prepare($query);

$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$transactions = [];
while ($row = $result->fetch_assoc()) {
  $transactions[] = $row;
}

echo json_encode($transactions);
?>
