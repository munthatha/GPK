<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<style>
    body {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: #f4f4f4;
        font-family: Arial, sans-serif;
    }
    .login-container {
        background: white;
        padding: 1rem;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        width: 250px;
        text-align: center;
    }
    .login-container h2 {
        margin-bottom: 1rem;
    }
    .input-group {
        margin-bottom: 1rem;
        text-align: left;
    }
    .input-group label {
        display: block;
        font-weight: bold;
        margin-bottom: 2px;
    }
    .input-group input {
        width: 95%;
        padding: 6px;
        border: 1px solid #ccc;
        border-radius: 3px;
    }
    .btn {
        width: 100%;
        padding: 10px;
        background: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    .btn:hover {
        background: #0056b3;
    }
    .error {
        color: red;
        font-size: 0.9rem;
        margin-top: 5px;
    }
</style>
</head>
<body>
<div class="login-container">
    <h2>Login</h2>
    <form action="login.php" method="post">
        <div class="input-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>
            <input type="submit" value="Login">
    </form>
</body>
</html>
<?php
// Database connection
$host = 'localhost';
$dbname = 'mydatabase';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare SQL statement to prevent SQL injection
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND password = MD5(:password)");
    $stmt->execute(['username' => $username, 'password' => $password]);

    // Fetch the user
    $user = $stmt->fetch(PDO::FETCH_ASSOC); 

    if ($user) {
        // Check user type and redirect accordingly
        switch ($user["usertype"]) {
            case "admin":
                header('Location: welcome.php');
                break;
            case "stock":
                header('Location: stock_dashboard.php');
                break;
            case "sale":
                header('Location: sale_home.php');
                break;
            default:
                echo "Invalid user type.";
                break;
        }
    } else {
        // Invalid credentials
        echo "Invalid username or password.";
    }
}
?>