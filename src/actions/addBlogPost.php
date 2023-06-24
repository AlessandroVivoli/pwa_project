<?php
if (isset($_POST['title'], $_POST['type'], $_POST['blog-text'])) {
    $title = $_POST['title'];
    $type = $_POST['type'];
    $text = $_POST['blog-text'];

    $mysql = null;

    $imageFile = isset($_FILES['image']) && trim($_FILES['image']['tmp_name']) != '' ? fopen($_FILES['image']['tmp_name'], "r") : null;
    $image = $imageFile != null ? fread($imageFile, $_FILES['image']['size']) : null;

    try {
        $mysql = new mysqli($_ENV['HOSTNAME'], $_ENV['USERNAME'], $_ENV['PASSWORD'], $_ENV['DB'], $_ENV['PORT']);
        $stmt = $mysql->prepare('INSERT INTO `blog_posts` (`type`, `image`, `title`, `text`) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssss', $type, $image, $title, $text);
        $stmt->execute();

        $_SESSION['createBlogPostSuccess'] = true;
    } catch (mysqli_sql_exception $e) {
        $_SESSION['createBlogPostError'] = true;
        error_log('Could not create a blog post! ' . $e->getMessage());
    }

    if ($mysql != null) {
        $mysql->close();
    }
}

header("Location: /" . AppRoutes::$newBlogPost->getPath());
