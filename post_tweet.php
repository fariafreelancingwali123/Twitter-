<?php
include('db.php');

if (isset($_POST['content'])) {
    $content = $_POST['content'];
    $user_id = 1; // Hardcoded for now, you can replace with session user ID

    $query = "INSERT INTO tweets (user_id, content) VALUES ('$user_id', '$content')";
    if ($conn->query($query) === TRUE) {
        header('Location: index.php');
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
