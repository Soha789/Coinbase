<?php
session_start();
if (!isset($_SESSION["loggedin"])) {
  header("Location: login.php");
  exit();
}

$user = $_SESSION["user"];
$wallet = $user["wallet"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Coinbase Clone</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f9fbfd;
      margin: 0;
      color: #333;
    }
    header {
      background: #0052ff;
      color: white;
      padding: 15px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    header h1 { margin: 0; font-size: 1.4em; }
    header a {
      color: white;
      text-decoration: none;
      background: #003ecb;
      padding: 6px 12px;
      border-radius: 5px;
      font-size: 0.9em;
    }
    .wallet {
      text-align: center;
      margin-top: 20px;
    }
    .wallet h2 {
      color: #0052ff;
    }
    .wallet-table {
      width: 80%;
      margin: 20px auto;
      border-collapse: collapse;
    }
    .wallet-table th, .wallet-table td {
      border: 1px solid #ddd;
      padding: 10px;
    }
    .wallet-table th {
      background: #0052ff;
      color: white;
    }
    .chart-container {
      width: 90%;
      max-width: 700px;
      margin: 40px auto;
      background: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .actions {
      text-align: center;
      margin: 30px;
    }
    .actions a {
      background: #0052ff;
      color: white;
      text-decoration: none;
      padding: 10px 20px;
      margin: 10px;
      border-radius: 5px;
      display: inline-block;
    }
    .actions a:hover { background: #003ecb; }
  </style>
</head>
<body>
  <header>
    <h1>Welcome, <?php echo htmlspecialchars($user["name"]); ?> 👋</h1>
    <a href="logout.php">Logout</a>
  </header>

  <section class="wallet">
    <h2>Your Wallet Summary</h2>
    <table class="wallet-table">
      <tr><th>Asset</th><th>Balance</th></tr>
      <tr><td>BTC</td><td><?php echo $wallet["BTC"]; ?></td></tr>
      <tr><td>ETH</td><td><?php echo $wallet["ETH"]; ?></td></tr>
      <tr><td>BNB</td><td><?php echo $wallet["BNB"]; ?></td></tr>
      <tr><td>USD</td><td>$<?php echo number_format($wallet["USD"], 2); ?></td></tr>
    </table>
  </section>

  <section class="chart-container">
    <h3 style="text-align:center;">Live Price Chart (BTC/USD)</h3>
    <canvas id="priceChart"></canvas>
  </section>

  <div class="actions">
    <a href="buy_crypto.php">Buy Crypto</a>
    <a href="sell_crypto.php">Sell Crypto</a>
    <a href="portfolio.php">View Portfolio</a>
  </div>

  <script>
    const ctx = document.getElementById('priceChart').getContext('2d');
    const priceChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: [],
        datasets: [{
          label: 'Bitcoin (USD)',
          data: [],
          borderColor: '#0052ff',
          borderWidth: 2,
          fill: false,
          tension: 0.3
        }]
      },
      options: {
        responsive: true,
        scales: {
          x: { display: false },
          y: { beginAtZero: false }
        }
      }
    });

    async function fetchBTC() {
      try {
        const res = await fetch("https://api.coingecko.com/api/v3/simple/price?ids=bitcoin&vs_currencies=usd");
        const data = await res.json();
        const price = data.bitcoin.usd;
        const time = new Date().toLocaleTimeString();

        priceChart.data.labels.push(time);
        priceChart.data.datasets[0].data.push(price);
        if (priceChart.data.labels.length > 20) {
          priceChart.data.labels.shift();
          priceChart.data.datasets[0].data.shift();
        }
        priceChart.update();
      } catch (e) {
        console.log("Error fetching BTC price:", e);
      }
    }

    fetchBTC();
    setInterval(fetchBTC, 10000);
  </script>
</body>
</html>
