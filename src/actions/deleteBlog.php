<?php
if (isset($_POST['post_id'])) {
    $post_id = $_POST['post_id'];

    /**
     * @var mysqli|null
     */
    $mysql = null;
    try {
        $mysql = new mysqli($_ENV['HOSTNAME'], $_ENV['USERNAME'], $_ENV['PASSWORD'], $_ENV['DB'], $_ENV['PORT']);
        $stmt = $mysql->prepare('DELETE FROM `blog_posts` WHERE `id` = ?');
        $stmt->bind_param('d', $post_id);
        $success = $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        $_SESSION['deleteError'] = true;
        error_log('Could not delete blog post! ' . $e->getMessage());
    }

    if ($mysql != null) {
        $mysql->close();
    }
}
header('Location: /' . AppRoutes::$administration->getPath());
