<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>

<?php
$host = getenv('DB_HOST') ?: 'db';
$db   = getenv('DB_NAME') ?: 'demo_db';
$user = getenv('DB_USER') ?: 'demo_user';
$pass = getenv('DB_PASSWORD') ?: 'demo_pass';

 $pdo = new PDO(
            "mysql:host=$host;dbname=$db;charset=utf8mb4",
            $user,
            $pass,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );

if(isset($_POST['register'])){
    $sql = "INSERT INTO visitors (name) VALUES (:name)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $_POST['name']);
    $stmt->execute();

    if($stmt->rowCount() > 0){
        echo "<p>Registration successful!</p>";
    } else {
        echo "<p>Registration failed. Please try again.</p>";
    }
}

?>
<body>
    <h1>Register</h1>
     <a href="index.php">Home</a>
    <form method="post">
        <label for="name">Name:</label>
        <input type="name" name="name" required>
        <br>
        <input type="submit" name="register" value="Register">
    </form>
</body>
</html>