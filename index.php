<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Coinbase Clone - Crypto Exchange</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f9fbfd;
      margin: 0;
      padding: 0;
      text-align: center;
      color: #333;
    }
    header {
      background: #0052ff;
      color: white;
      padding: 15px;
      font-size: 1.5em;
      letter-spacing: 1px;
    }
    .crypto-container {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      margin-top: 40px;
    }
    .crypto-card {
      background: white;
      border-radius: 10px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      margin: 10px;
      padding: 20px;
      width: 220px;
    }
    h3 { margin-bottom: 5px; }
    .price { font-size: 1.3em; color: #0052ff; }
    button {
      margin-top: 40px;
      background: #0052ff;
      color: white;
      border: none;
      border-radius: 5px;
      padding: 12px 25px;
      font-size: 1em;
      cursor: pointer;
    }
    button:hover { background: #003ecb; }
    footer {
      margin-top: 50px;
      padding: 10px;
      color: #777;
    }
  </style>
</head>
<body>
  <header>Coinbase Clone</header>
  <h2>Buy, Sell & Track Cryptocurrencies Securely</h2>
  <p>Real-time crypto prices fetched from Coingecko API</p>

  <div class="crypto-container" id="cryptoContainer">
    <div class="crypto-card">
      <h3>Bitcoin (BTC)</h3>
      <p class="price" id="btc">Loading...</p>
    </div>
    <div class="crypto-card">
      <h3>Ethereum (ETH)</h3>
      <p class="price" id="eth">Loading...</p>
    </div>
    <div class="crypto-card">
      <h3>BNB</h3>
      <p class="price" id="bnb">Loading...</p>
    </div>
  </div>

  <button onclick="window.location.href='signup.php'">Get Started</button>

  <footer>Powered by Coingecko API | Coinbase Clone Demo</footer>

  <script>
    async function fetchPrices() {
      try {
        const response = await fetch("https://api.coingecko.com/api/v3/simple/price?ids=bitcoin,ethereum,binancecoin&vs_currencies=usd");
        const data = await response.json();
        document.getElementById("btc").textContent = "$" + data.bitcoin.usd.toLocaleString();
        document.getElementById("eth").textContent = "$" + data.ethereum.usd.toLocaleString();
        document.getElementById("bnb").textContent = "$" + data.binancecoin.usd.toLocaleString();
      } catch (error) {
        document.getElementById("cryptoContainer").innerHTML = "<p>Failed to load prices.</p>";
      }
    }
    fetchPrices();
    setInterval(fetchPrices, 30000);
  </script>
</body>
</html>
