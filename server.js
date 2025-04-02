const express = require('express');
const { Pool } = require('pg');
const os = require('os');

const app = express();
const port = process.env.PORT || 8080;

// Database configuration
const dbConfig = {
    host: process.env.DB_HOST || 'postgres',
    database: process.env.DB_NAME || 'lapp_db',
    user: process.env.DB_USER || 'lapp_user',
    password: process.env.DB_PASS || 'lapp_password',
    port: 5432
};

// Create HTML template
const htmlTemplate = `
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Node.js Application Status</title>
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
    <h1>Node.js Application Status</h1>
    
    <h2>Network Information:</h2>
    <div class="status-box">
        <p><strong>Local IP Address:</strong> {{localIP}}</p>
        <p><strong>Server IP Address:</strong> {{serverIP}}</p>
        <p><strong>Hostname:</strong> {{hostname}}</p>
    </div>

    <h2>Database Connection:</h2>
    <div class="status-box {{dbStatusClass}}">
        {{dbStatusMessage}}
    </div>
</body>
</html>
`;

// Main route handler
app.get('/', async (req, res) => {
    // Get network information
    const networkInfo = {
        localIP: Object.values(os.networkInterfaces())
            .flat()
            .find(interface => interface.family === 'IPv4' && !interface.internal)?.address || 'Not available',
        serverIP: req.socket.localAddress,
        hostname: os.hostname()
    };

    // Test database connection
    let dbStatus = {
        class: 'error',
        message: 'Database connection failed'
    };

    try {
        const pool = new Pool(dbConfig);
        await pool.query('SELECT 1');
        await pool.end();
        dbStatus = {
            class: 'success',
            message: 'Connected successfully to PostgreSQL database!'
        };
    } catch (error) {
        dbStatus.message = `Connection failed: ${error.message}`;
    }

    // Replace template variables
    const html = htmlTemplate
        .replace('{{localIP}}', networkInfo.localIP)
        .replace('{{serverIP}}', networkInfo.serverIP)
        .replace('{{hostname}}', networkInfo.hostname)
        .replace('{{dbStatusClass}}', dbStatus.class)
        .replace('{{dbStatusMessage}}', dbStatus.message);

    res.send(html);
});

app.listen(port, () => {
    console.log(`Server running on port ${port}`);
}); 