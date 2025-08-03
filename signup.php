<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sign Up - Budget Buddy</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">
  <div class="auth-container">
    <h2>Sign Up</h2>
    <form action="signup.php" method="POST">
      <input type="text" name="username" placeholder="Username" required><br>
      <input type="email" name="email" placeholder="Email" required><br>
      <input type="password" name="password" placeholder="Password" required><br>
      <input type="password" name="confirm_password" placeholder="Confirm Password" required><br>
      <button type="submit">SignUp</button>
    </form>
    <p>Already have an account? <a href="login.php">Login</a></p>
  </div>


    <?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password !== $confirm) {
        echo "Passwords do not match.";
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "Email already registered.";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        header("Location: login.php?signup=success");
        exit;
    } else {
        echo "Registration failed: " . $stmt->error;
    }
}
?>



</body>
</html>
