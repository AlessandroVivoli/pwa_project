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
        $mysql = new mysqli($_ENV["HOSTNAME"], $_ENV["USERNAME"], $_ENV["PASSWORD"], $_ENV["DB"]) or die('Something went wrong!');
        $stmt = $mysql->prepare('SELECT * FROM `blog_posts` WHERE id = ?;');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if (!$row) {
            http_response_code(404);
            $_SERVER['status_message'] = "Blog post with id ($id) could not be found.";
            require 'errors/404.php';
        } else {
            ?>
            <div class="container-fluid container-lg my-3">
                <?php
                if ($row['image']) {
                    ?>
                    <div class="ratio ratio-16x9">
                        <img src="data:image/jpeg;base64, <?php echo base64_encode($row['image']); ?>" alt=""
                            class="img-fluid object-fit-cover">
                    </div>
                    <?php
                }
                ?>
                <h2>
                    <?php echo $row['title'] ?>
                </h2>
                <h6 class="text-muted my-3 fw-normal">
                    <?php echo $row['date_modified'] == null ? $row['date_added'] : $row['date_modified'] . '*'; ?>
                </h6>
                <div id="content"></div>
            </div>
            <script>
                const renderer = {
                    image(href, title, text) {
                        return `<img src="${href}" alt="${title}" class="img-fluid" />`
                    }
                }

                marked.use({ renderer });

                marked.use({
                    gfm: true,
                    breaks: true,
                });

                $('#content').html(marked.parse(`<?php echo $row['text'] ?>`))
            </script>
            <?php
        }
    } catch (mysqli_sql_exception $e) {
        error_log("Blog [$id]: " . $e->getMessage());
        $_SERVER['status_message'] = $e->getMessage();
        http_response_code(500);
        require 'errors/500.php';
    }
}

?>