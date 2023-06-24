<?php
if (isset($_POST['id'], $_POST['title'], $_POST['blog-text'], $_POST['type'])) {
    $id = $_POST['id'];
    $imageFile = isset($_FILES['image']) && trim($_FILES['image']['tmp_name']) != '' ? fopen($_FILES['image']['tmp_name'], 'r') : null;
    $image = $imageFile != null ? fread($imageFile, $_FILES['image']['size']) : null;

    $title = $_POST['title'];
    $type = $_POST['type'];
    $text = $_POST['blog-text'];

    /**
     * @var mysqli|null
     */
    $mysql = null;

    try {
        $updateWImage = "UPDATE `blog_posts` SET `title` = ?, `type` = ?, `text` = ?, `image` = ? WHERE `id` = ?";
        $updateWOImage = "UPDATE `blog_posts` SET `title` = ?, `type` = ?, `text` = ? WHERE `id` = ?";

        $mysql = new mysqli($_ENV['HOSTNAME'], $_ENV['USERNAME'], $_ENV['PASSWORD'], $_ENV['DB'], $_ENV['PORT']);
        $stmt = $image != null ? $mysql->prepare($updateWImage) : $mysql->prepare($updateWOImage);
        if ($image) {
            $stmt->bind_param('ssssi', $title, $type, $text, $image, $id);
        } else {
            $stmt->bind_param('sssi', $title, $type, $text, $id);
        }
        $success = $stmt->execute();
        $mysql->close();

        if ($success) {
            $_SESSION['editBlogPostSuccess'] = true;
            header('Location: /' . AppRoutes::$administration->getPath());
        } else {
            $_SESSION['editBlogPostError'] = true;
            //header("Location: /" . AppRoutes::$edit->getPath() . "?id=$id");
        }

    } catch (mysqli_sql_exception $e) {
        $_SESSION['editBlogPostError'] = true;
        error_log('Could not create a blog post!' . $e->getMessage());

        echo $e->getMessage();

        if ($mysql != null)
            $mysql->close();

        //header("Location: /" . AppRoutes::$edit->getPath() . "?id=$id");
    }


} else {
    echo isset( $_POST['title'], $_POST['blog-text'], $_POST['type']) ? 'True' : 'False';

   // header('Location: /');
}
?>