<?php
$host = getenv('DB_HOST') ?: 'postgres';
$dbname = getenv('DB_NAME') ?: 'lapp_db';
$user = getenv('DB_USER') ?: 'lapp_user';
$pass = getenv('DB_PASS') ?: 'lapp_password';

// Get local IP address
$localIP = gethostbyname(gethostname());
$serverIP = $_SERVER['SERVER_ADDR'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Application Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            line-height: 1.6;
        }
        .status-box {
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        h1, h2 {
            color: #333;
        }
    </style>
</head>
<body>
    <h1>PHP Application Status</h1>
    
    <h2>Network Information:</h2>
    <div class="status-box">
        <p><strong>Local IP Address:</strong> <?php echo htmlspecialchars($localIP); ?></p>
        <p><strong>Server IP Address:</strong> <?php echo htmlspecialchars($serverIP); ?></p>
        <p><strong>Hostname:</strong> <?php echo htmlspecialchars(gethostname()); ?></p>
    </div>

    <h2>Database Connection:</h2>
    <?php
    try {
        $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "<div class='status-box success'>Connected successfully to PostgreSQL database!</div>";
    } catch(PDOException $e) {
        echo "<div class='status-box error'>Connection failed: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
    ?>
</body>
</html> 