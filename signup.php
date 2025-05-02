<?php
include('db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $conn->query("SELECT * FROM users WHERE username = '$username'");
    if ($check->num_rows > 0) {
        echo "Username already exists!";
    } else {
        $conn->query("INSERT INTO users (username, password) VALUES ('$username', '$password')");
        echo "Signup successful. <a href='login.php'>Login here</a>";
    }
}
?>

<form method="POST">
    <h2>Signup</h2>
    <input type="text" name="username" placeholder="Username" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit">Signup</button>
</form>
