<?php
session_start();
require_once "config.php";

// Check if the user is logged in
$loggedin = false;
$username = "";
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    $loggedin = true;
    $username = htmlspecialchars($_SESSION["username"]);
}

// Query to fetch posts from the database
$sql = "SELECT posts.id, users.username, posts.title, posts.content, posts.created_at FROM posts INNER JOIN users ON posts.user_id = users.id ORDER BY posts.created_at DESC";

// Execute query
if($result = mysqli_query($conn, $sql)){
    $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
}

// Close connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blog Home</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="wrapper">
        <h2>Welcome to the Blog</h2>
        
        <?php if($loggedin): ?>
            <p>Hi, <b><?php echo $username; ?></b>. <a href="logout.php">Logout</a> | <a href="create_post.php">Create Post</a></p>
        <?php else: ?>
            <p><a href="login.php">Login</a> or <a href="register.php">Register</a></p>
        <?php endif; ?>

        <?php if(!empty($posts)): ?>
            <?php foreach($posts as $post): ?>
                <div class="post">
                    <h3><a href="view_post.php?id=<?php echo $post['id']; ?>"><?php echo $post['title']; ?></a></h3>
                    <p>Posted by <?php echo $post['username']; ?> on <?php echo $post['created_at']; ?></p>
                    <p><?php echo substr($post['content'], 0, 200); ?>...</p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No posts found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
