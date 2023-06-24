<?php
/**
 * @var mysqli|null
 */
$mysql = null;

try {
    $mysql = new mysqli($_ENV['HOSTNAME'], $_ENV['USERNAME'], $_ENV['PASSWORD'], $_ENV['DB'], $_ENV['PORT']);
    $musicResult = $mysql->query('SELECT * FROM `blog_posts` WHERE `type` = "music" ORDER BY `date_added` DESC LIMIT 3');
    $sportResult = $mysql->query('SELECT * FROM `blog_posts` WHERE `type` = "SPORT" ORDER BY `date_added` DESC LIMIT 3');
    $_SERVER['music_row'] = $musicResult->fetch_all(MYSQLI_ASSOC);
    $_SERVER['sport_row'] = $sportResult->fetch_all(MYSQLI_ASSOC);
} catch (mysqli_sql_exception $e) {
    error_log('[home.php]: ' . $e->getMessage());
    http_response_code(500);
    $_SERVER['status_message'] = 'Something went wrong!';
    require 'errors/500.php';
    $_SERVER['ERROR'] = true;
}
if (!isset($_SERVER['ERROR'])) {
    ?>
    <div class="container-lg container-fluid">
        <section id="music-blogs" class="blog-section">
            <hr data-content="Music">

            <?php
            if (isset($_SERVER['music_row']) && count($_SERVER['music_row']) > 0) {
                ?>
                <div class="row">
                    <?php
                    foreach ($_SERVER['music_row'] as $element) {
                        ?>
                        <a href="/<?php echo AppRoutes::$blog->getPath() . '?id=' . $element['id']; ?>"
                            class="text-decoration-none col-12 col-md-4 d-flex flex-column justify-content-end blog-card">
                            <?php
                            require_with('components/blog_card.php', array('row' => $element));
                            ?>
                        </a>
                        <?php
                    }
                    ?>
                </div>
                <?php
            } else {
                ?>
                <div class="text-center">
                    <h3 class="fw-bold">No music blogs found.</h3>
                </div>
                <?php
            }
            ?>
        </section>
        <section id="sport-blogs" class="blog-section">
            <hr data-content="Sport">

            <?php
            if (isset($_SERVER['sport_row']) && count($_SERVER['sport_row']) > 0) {
                ?>
                <div class="row">


                    <?php
                    foreach ($_SERVER['sport_row'] as $element) {
                        ?>
                        <a href="/<?php echo AppRoutes::$blog->getPath() . '?id=' . $element['id']; ?>"
                            class="text-decoration-none col-12 col-md-4 d-flex flex-column justify-content-end blog-card">
                            <?php
                            require_with('components/blog_card.php', array('row' => $element));
                            ?>
                        </a>
                        <?php
                    }
                    ?>
                </div>
                <?php
            } else {
                ?>
                <div class="text-center">
                    <h3 class="fw-bold">No sport blogs found.</h3>
                </div>
                <?php
            }
            ?>
        </section>
    </div>
    <?php
}
?>