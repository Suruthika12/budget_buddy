<?php
$host = 'localhost';
$user = 'root';
$password = ''; // Default XAMPP MySQL password is empty
$dbname = 'budget_buddy'; // make sure this matches your DB name

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT category, SUM(amount) as total 
        FROM transactions 
        WHERE type = 'expense' 
        GROUP BY category";

$result = $conn->query($sql);

$categoryData = [];

while ($row = $result->fetch_assoc()) {
  $categoryData[$row['category']] = (int)$row['total'];
}

header('Content-Type: application/json');
echo json_encode($categoryData);
$conn->close();
?>
