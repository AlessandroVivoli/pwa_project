<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<?php
if (isset($_SESSION['editBlogPostSuccess'])) {
    $_SERVER['editBlogPostSuccess'] = 'Successfully updated the blog post!';
    unset($_SESSION['editBlogPostSuccess']);
}

if (!isset($_SESSION['user'])) {
    $_SESSION['redirectTo'] = 'administration';

    header('Location: /login');
} else if (json_decode($_SESSION['user'])->level < 1) {
    http_response_code(403);
    $_SERVER['status_message'] = "You're not permitted to use administration tools.<br><em>If you think this is a mistake, contact your admin to try to resolve this problem.</em>";
    require 'errors/403.php';
} else {
    $user = json_decode($_SESSION['user']);
    ?>
        <section class="my-5 d-flex flex-column justify-content-center align-items-center">
            <a href="/<?php echo AppRoutes::$newBlogPost->getPath() ?>" class="btn btn-outline-primary">
                Create a blog post
            </a>
        </section>

        <hr class="mt-0">
        <div class="d-flex flex-fill">
            <div class="container-fluid container-lg">
                <?php
                /**
                 * @var mysqli|null;
                 */
                $mysql = null;
                try {
                    $mysql = new mysqli($_ENV['HOSTNAME'], $_ENV['USERNAME'], $_ENV['PASSWORD'], $_ENV['DB'], $_ENV['PORT']);
                    $result = $mysql->query("SELECT id FROM blog_posts");

                    /**
                     * @var int[]
                     */
                    $posts = $result->fetch_all();

                    if (!count($posts)) {
                        ?>
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <h3 class="fw-bold">Nothing found</h3>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="row g-2">
                        <?php foreach ($posts as $key => $value) {
                            ?>
                            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                            <?php require_with("views/administration/components/blog_card.php", array('id' => $value, 'level' => $user->level)) ?>
                            </div>
                            <?php
                        } ?>
                        </div>
                        <?php
                    }
                    ?>
                <?php
                } catch (mysqli_sql_exception $e) {
                    error_log($e);
                    ?>
                    <div class="d-flex flex-column justify-content-center align-items-center text-danger">
                        <h3 class="fw-bold">Something went wrong!</h3>
                    </div>
                    <?php
                }

                if ($mysql != null)
                    $mysql->close();
}

?>
    </div>
</div>
<div class="toast-container top-0 end-0 p-3">
    <div class="toast align-items-center text-bg-success border-0" id="editSuccess" role="alert" aria-live="assertive"
        aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <?php
                if (isset($_SERVER['editBlogPostSuccess']))
                    echo $_SERVER['editBlogPostSuccess'];
                ?>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
    </div>
</div>
<div class="toast-container top-0 end-0 p-3">
    <div class="toast align-items-center text-bg-danger border-0" id="deleteError" role="alert" aria-live="assertive"
        aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">Could not delete post!</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
    </div>
</div>

<script>
    const deleteErrorToast = $('#deleteError').get(0);
    const deleteErrorToastBs = bootstrap.Toast.getOrCreateInstance(deleteErrorToast);

    const editSuccessToast = $('#editSuccess').get(0);
    const editSuccessToastBs = bootstrap.Toast.getOrCreateInstance(editSuccessToast);

    <?php
    if (isset($_SESSION['deleteError'])) {
        ?>
        deleteErrorToastBs.show();
        <?php
        unset($_SESSION['deleteError']);
    }

    if (isset($_SERVER['editBlogPostSuccess'])) {
        ?>
        editSuccessToastBs.show();
        <?php
    }
    ?>
</script>