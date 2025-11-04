<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = htmlspecialchars($_POST["email"]);
  $password = htmlspecialchars($_POST["password"]);

  if (isset($_SESSION["user"])) {
    $user = $_SESSION["user"];
    if ($email === $user["email"] && $password === $user["password"]) {
      $_SESSION["loggedin"] = true;
      header("Location: home.php");
      exit();
    } else {
      $error = "Invalid email or password.";
    }
  } else {
    $error = "No account found. Please sign up first.";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Coinbase Clone</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f9fbfd;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      color: #333;
    }
    .container {
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      width: 350px;
      text-align: center;
    }
    h2 {
      color: #0052ff;
      margin-bottom: 20px;
    }
    input {
      width: 90%;
      padding: 10px;
      margin: 8px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    button {
      background: #0052ff;
      color: white;
      border: none;
      border-radius: 5px;
      padding: 10px 20px;
      cursor: pointer;
      margin-top: 10px;
    }
    button:hover { background: #003ecb; }
    .link {
      margin-top: 15px;
      font-size: 0.9em;
    }
    .error { color: red; margin-top: 10px; }
  </style>
</head>
<body>
  <div class="container">
    <h2>Login</h2>
    <form method="POST">
      <input type="email" name="email" placeholder="Email Address" required><br>
      <input type="password" name="password" placeholder="Password" required><br>
      <button type="submit">Login</button>
    </form>
    <div class="link">
      Don’t have an account? <a href="signup.php">Sign Up</a>
    </div>
    <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
  </div>
</body>
</html>
