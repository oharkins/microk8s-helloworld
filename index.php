<?php
$host = 'postgres';
$dbname = 'lapp_db';
$user = 'lapp_user';
$pass = 'lapp_password';

// Get local IP address
$localIP = gethostbyname(gethostname());
$serverIP = $_SERVER['SERVER_ADDR'];

echo "<h1>PHP Application Status</h1>";
echo "<h2>Network Information:</h2>";
echo "<p>Local IP Address: " . $localIP . "</p>";
echo "<p>Server IP Address: " . $serverIP . "</p>";
echo "<p>Hostname: " . gethostname() . "</p>";

echo "<h2>Database Connection:</h2>";
try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color: green;'>Connected successfully to PostgreSQL database!</p>";
} catch(PDOException $e) {
    echo "<p style='color: red;'>Connection failed: " . $e->getMessage() . "</p>";
}
?> 