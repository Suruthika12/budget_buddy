<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Budget Buddy</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">
  <div class="auth-container">
    <h2>Login</h2>
    <form action="login.php" method="POST">
      <input type="email" name="email" placeholder="Email" required><br>
      <input type="password" name="password" id="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="signup.php">Sign up</a></p>
  </div>
  <?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($userId, $username, $hashedPassword);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            $_SESSION['user_id'] = $userId;
            $_SESSION['username'] = $username;

            header("Location: dashboard.html");
            exit;
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No account found with that email.";
    }
}
?>
</script>



</body>
</html>
