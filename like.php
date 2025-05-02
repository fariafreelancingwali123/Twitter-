<?php
include('db.php');
session_start();

$user_id = 1; // Replace this with $_SESSION['user_id'] when login system is added
$tweet_id = $_POST['tweet_id'];

// Check if user already liked the tweet
$query = "SELECT * FROM likes WHERE user_id = $user_id AND tweet_id = $tweet_id";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    // Unlike
    $delete = "DELETE FROM likes WHERE user_id = $user_id AND tweet_id = $tweet_id";
    $conn->query($delete);
    echo "unliked";
} else {
    // Likea
    $insert = "INSERT INTO likes (user_id, tweet_id) VALUES ($user_id, $tweet_id)";
    $conn->query($insert);
    echo "liked";
}
?>
