<?php
$id = isset($_GET['id']) ? $_GET['id'] : null;

?>
<?php
if ($id == null) {
    http_response_code(400);
    $_SERVER['status_message'] = 'Blog id is empty.';
    require 'errors/400.php';
?>
    
<?php
} else {
    try {
        $mysql = new mysqli($_ENV["HOSTNAME"], $_ENV["USERNAME"], $_ENV["PASSWORD"], $_ENV["DB"], $_ENV["PORT"]) or die('Something went wrong!');
        $stmt = $mysql->prepare('SELECT * FROM blog_post WHERE id = ?;');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if (!$row) {
            http_response_code(404);
            $_SERVER['status_message'] = "Blog post with id ($id) could not be found.";
            require 'errors/404.php';
        } else {
            print($row);
?>

<?php
        }
    } catch (mysqli_sql_exception $e) {
        http_response_code(500);
        require 'errors/500.php';
    } catch (Exception $e) {
        http_response_code(500);
        require 'errors/500.php';
    }
}

?>