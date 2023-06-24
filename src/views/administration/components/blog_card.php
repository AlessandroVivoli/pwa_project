<?php
if (isset($id, $level)) {

    /**
     * @var mysqli|null
     */
    $mysql = null;

    try {
        $mysql = new mysqli($_ENV['HOSTNAME'], $_ENV['USERNAME'], $_ENV['PASSWORD'], $_ENV['DB'], $_ENV['PORT']);

        $stmt = $mysql->prepare('SELECT * FROM blog_posts WHERE id = ?;');

        $stmt->bind_param('i', $id[0]);

        $stmt->execute();

        $blog = $stmt->get_result()->fetch_assoc();

        $mysql->close();

        if ($blog) {
            ?>
            <div class="card border-primary h-100">
                <div class="row g-0 h-100">
                    <?php if ($blog['image'] != null) { ?>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-6">
                            <div class="h-100 ratio ratio-1x1">
                                <img src="<?php echo "data:image/jpeg;base64, " . base64_encode($blog['image']) ?>"
                                    class="img-fluid rounded object-fit-cover h-100 w-100 " alt="<?php echo $blog['title'] ?>">
                            </div>
                        </div>
                    <?php } ?>
                    <div class="col-12 <?php if ($blog['image'] != null) { ?>col-sm-6 col-md-8 col-lg-6<?php } ?>">
                        <div class="card-body d-flex flex-column justify-content-between h-100">
                            <h5 class="card-title text-truncate">
                                <?php echo $blog['title'] ?>
                            </h5>

                            <div class="row">
                                <div class="col-12 mb-2">
                                    <p class="card-text text-truncate"><small class="text-muted" style="font-size: .7rem;">
                                            <?php echo $blog['date_modified'] == null ? $blog['date_added'] : $blog['date_modified'] . '*' ?>
                                        </small></p>
                                </div>
                                <div class="col-6">
                                    <a class="btn btn-primary w-100 px-0 py-1"
                                        href="/<?php echo AppRoutes::$edit->getPath() . '?id=' . $id[0]; ?>"><i
                                            class="fa-solid fa-pen-to-square"></i></a>
                                </div>
                                <div class="col-6">
                                    <button class="btn btn-outline-danger w-100 px-0 py-1" data-bs-toggle="modal"
                                        data-bs-target="#deleteBlog<?php echo $id[0]; ?>"><i
                                            class=" fa-solid fa-xmark"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="deleteBlog<?php echo $id[0]; ?>" tabindex="-1"
                aria-labelledby="deleteBlog<?php echo $id[0]; ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" data-bs-config="{backdrop:true}">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title "><span class="text-truncate">Delete blog</span>?</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this post?
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                            <form action="/deleteBlog" method="POST">
                                <input type="hidden" name="post_id" value="<?php echo $id[0] ?>">
                                <button class="btn btn-danger" data-bs-dismiss="modal">Yes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    } catch (mysqli_sql_exception $e) {
        error_log($e->getMessage());

        if ($mysql != null)
            $mysql->close();

        echo "<p class=\"text-danger\">Something went wrong!</p>";
    }
}
?>