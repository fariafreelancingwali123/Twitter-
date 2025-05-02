session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];
<?php
include("db.php");
session_start();

// Set user manually for now
$user_id = 1;

// Handle tweet creation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tweet'])) {
    $tweet = $conn->real_escape_string($_POST['tweet']);
    if (!empty($tweet)) {
        $conn->query("INSERT INTO tweets (user_id, content) VALUES ($user_id, '$tweet')");
    }
}

// Get tweets
$tweets = $conn->query("
    SELECT tweets.id, tweets.content, tweets.created_at, users.username
    FROM tweets
    JOIN users ON tweets.user_id = users.id
    ORDER BY tweets.created_at DESC
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>MyTwitter</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #e6ecf0;
            margin: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            border-radius: 10px;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #1da1f2;
        }
        form textarea {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 10px;
            border: 1px solid #ccc;
        }
        form button {
            background: #1da1f2;
            color: #fff;
            border: none;
            padding: 10px 20px;
            margin-top: 10px;
            border-radius: 20px;
            cursor: pointer;
        }
        .tweet {
            background: #f5f8fa;
            border-radius: 10px;
            margin-top: 20px;
            padding: 15px;
        }
        .tweet .username {
            font-weight: bold;
            color: #14171a;
        }
        .tweet .time {
            font-size: 12px;
            color: #657786;
        }
        .tweet p {
            font-size: 15px;
            color: #14171a;
        }
        .like-form button {
            background: none;
            border: none;
            color: #1da1f2;
            cursor: pointer;
            font-weight: bold;
        }
        .likes {
            font-size: 13px;
            color: #657786;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Mini Twitter</h1>
        <form method="POST">
            <textarea name="tweet" placeholder="What's happening?" rows="3" required></textarea>
            <button type="submit">Tweet</button>
        </form>

        <?php while ($tweet = $tweets->fetch_assoc()): ?>
            <div class="tweet">
                <div class="username">@<?php echo htmlspecialchars($tweet['username']); ?></div>
                <div class="time"><?php echo $tweet['created_at']; ?></div>
                <p><?php echo htmlspecialchars($tweet['content']); ?></p>

                <?php
                    $tweet_id = $tweet['id'];
                    $likes_result = $conn->query("SELECT COUNT(*) AS total FROM likes WHERE tweet_id = $tweet_id");
                    $likes = $likes_result->fetch_assoc()['total'];

                    $liked_result = $conn->query("SELECT * FROM likes WHERE user_id = $user_id AND tweet_id = $tweet_id");
                    $liked = $liked_result->num_rows > 0;
                ?>

                <form class="like-form" method="POST">
                    <input type="hidden" name="tweet_id" value="<?php echo $tweet_id; ?>">
                    <button type="button" class="like-btn"><?php echo $liked ? "Unlike" : "Like"; ?></button>
                </form>
                <div class="likes"><?php echo $likes; ?> likes</div>
            </div>
        <?php endwhile; ?>
    </div>

    <script>
        document.querySelectorAll(".like-btn").forEach(button => {
            button.addEventListener("click", function () {
                const form = this.closest(".like-form");
                const formData = new FormData(form);
                fetch("like.php", {
                    method: "POST",
                    body: formData
                })
                .then(res => res.text())
                .then(data => {
                    if (data === "liked") {
                        this.textContent = "Unlike";
                    } else {
                        this.textContent = "Like";
                    }
                    location.reload(); // Refresh to update like count
                });
            });
        });
    </script>
</body>
</html>
