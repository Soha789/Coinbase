<?php
session_start();
if (!isset($_SESSION["loggedin"])) {
  header("Location: login.php");
  exit();
}

$user = &$_SESSION["user"];
$wallet = &$user["wallet"];
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $coin = $_POST["coin"];
  $amountUSD = floatval($_POST["amount"]);

  // Fetch current price
  $api = "https://api.coingecko.com/api/v3/simple/price?ids=$coin&vs_currencies=usd";
  $data = json_decode(file_get_contents($api), true);
  $price = $data[$coin]["usd"];

  if ($wallet["USD"] >= $amountUSD) {
    $coinSymbol = strtoupper(substr($coin, 0, 3));
    $cryptoBought = $amountUSD / $price;

    $wallet["USD"] -= $amountUSD;
    $wallet[$coinSymbol] += $cryptoBought;

    // Save transaction
    $user["transactions"][] = [
      "type" => "Buy",
      "coin" => $coinSymbol,
      "amount" => round($cryptoBought, 6),
      "usd" => $amountUSD,
      "time" => date("H:i:s")
    ];
    $_SESSION["user"] = $user;

    $message = "✅ Successfully bought $cryptoBought $coinSymbol for $$amountUSD.";
  } else {
    $message = "❌ Not enough USD balance!";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buy Crypto - Coinbase Clone</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f9fbfd;
      margin: 0;
      padding: 0;
      color: #333;
      text-align: center;
    }
    header {
      background: #0052ff;
      color: white;
      padding: 15px;
    }
    form {
      background: white;
      width: 320px;
      margin: 50px auto;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    select, input {
      width: 90%;
      padding: 10px;
      margin: 10px 0;
      border-radius: 5px;
      border: 1px solid #ccc;
    }
    button {
      background: #0052ff;
      color: white;
      border: none;
      border-radius: 5px;
      padding: 10px 20px;
      cursor: pointer;
    }
    button:hover { background: #003ecb; }
    .msg { margin-top: 15px; font-weight: bold; }
    a {
      display: inline-block;
      margin-top: 20px;
      color: #0052ff;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <header>
    <h2>Buy Cryptocurrency</h2>
  </header>

  <form method="POST">
    <label for="coin">Select Coin:</label><br>
    <select name="coin" id="coin" required>
      <option value="bitcoin">Bitcoin (BTC)</option>
      <option value="ethereum">Ethereum (ETH)</option>
      <option value="binancecoin">BNB</option>
    </select><br>

    <label for="amount">Amount (in USD):</label><br>
    <input type="number" step="0.01" name="amount" id="amount" placeholder="Enter USD amount" required><br>

    <button type="submit">Buy</button>

    <?php if ($message) echo "<div class='msg'>$message</div>"; ?>
  </form>

  <a href="home.php">⬅ Back to Dashboard</a>
</body>
</html>
