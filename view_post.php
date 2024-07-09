<?php
require_once "config.php";

if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $sql = "SELECT posts.id, users.username, posts.title, posts.content, posts.created_at FROM posts INNER JOIN users ON posts.user_id = users.id WHERE posts.id = ?";
    
    if($stmt = mysqli_prepare($conn, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        $param_id = trim($_GET["id"]);
        
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result) == 1){
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $username = $row["username"];
                $title = $row["title"];
                $content = $row["content"];
                $created_at = $row["created_at"];
            } else{
                echo "No records found.";
            }
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
    mysqli_stmt_close($stmt);
} else{
    echo "URL doesn't contain valid id.";
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Post</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="wrapper">
        <h2><?php echo $title; ?></h2>
        <p>Posted by <strong><?php echo $username; ?></strong> on <?php echo $created_at; ?></p>
        <p><?php echo nl2br($content); ?></p>
        <a href="index.php" class="btn btn-primary">Back</a>
    </div>    
</body>
</html>
