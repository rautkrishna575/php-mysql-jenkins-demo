<?php
$host = getenv('DB_HOST') ?: 'db';
$db   = getenv('DB_NAME') ?: 'demo_db';
$user = getenv('DB_USER') ?: 'demo_user';
$pass = getenv('DB_PASSWORD') ?: 'demo_pass';

$pdo = null;
$error = null;

for ($i = 0; $i < 15; $i++) {
    try {
        $pdo = new PDO(
            "mysql:host=$host;dbname=$db;charset=utf8mb4",
            $user,
            $pass,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        break;
    } catch (PDOException $e) {
        sleep(2);
    }
}

if (!$pdo) {
    http_response_code(503);
    exit('Database unavailable. Please retry shortly.');
}

$pdo->exec("
    CREATE TABLE IF NOT EXISTS visitors (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL
    )
");

$count = (int) $pdo->query(
    "SELECT COUNT(*) FROM visitors"
)->fetchColumn();

if ($count === 0) {
    $stmt = $pdo->prepare(
        "INSERT INTO visitors (name) VALUES (?)"
    );
    $stmt->execute(['Jenkins + PHP + MySQL']);
}

$visitors = $pdo->query(
    "SELECT id, name FROM visitors ORDER BY id DESC"
)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jenkins PHP MySQL Demo</title>
    <style>
        body { font: 16px Arial; margin: 40px auto; max-width: 750px; padding: 0 20px; }
        .status { color: green; font-weight: bold; }
        table { border-collapse: collapse; width: 100%; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
    </style>
</head>
<body>
    <h1>PHP + MySQL + Docker + Git</h1>
    <p class="status">Application running successfully! This is a demo</p>
    <p>MySQL connection: successful</p>

    <h2>Visitors</h2>
    <table>
        <tr><th>ID</th><th>Name</th></tr>
        <?php foreach ($visitors as $visitor): ?>
            <tr>
                <td><?= (int) $visitor['id'] ?></td>
                <td><?= htmlspecialchars($visitor['name'], ENT_QUOTES, 'UTF-8') ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>