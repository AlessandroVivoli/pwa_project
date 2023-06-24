<?php
$found = true;

if (isset($_GET['id'])) {

    /**
     * @var mysqli|null
     */
    $mysql = null;

    $blogId = $_GET['id'];

    try {
        $mysql = new mysqli($_ENV['HOSTNAME'], $_ENV['USERNAME'], $_ENV['PASSWORD'], $_ENV['DB'], $_ENV['PORT']);
        $stmt = $mysql->prepare("SELECT * FROM `blog_posts` WHERE `id` = ?");
        $stmt->bind_param('i', $blogId);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!($_SERVER['row'] = $result->fetch_assoc())) {
            $found = false;
        }

    } catch (mysqli_sql_exception $e) {
        error_log($e->getMessage());
    }


    if ($mysql != null) {
        $mysql->close();
    }

    if (isset($_SESSION['editBlogPostError'])) {
        echo "Something went wrong!";

        $_SERVER['editBlogPostError'] = 'Couldn\'t update blog post!';
        unset($_SESSION['editBlogPostError']);
    }
}
if ($found) {
    ?>
    <div class="container-fluid container-lg p-3 p-lg-0 pt-lg-3">
        <form
            action="/<?php echo isset($_SERVER['row']) ? AppRoutes::$save->getPath() : AppRoutes::$addBlogPost->getPath() ?>"
            method="post" class="row g-3 needs-validation" id="blog-form" enctype="multipart/form-data" novalidate>
            <div class="<?php echo isset($_SERVER['row']) ? 'col-12' : 'col-md-4'; ?>">
                <div class="row">
                    <?php
                    if (isset($_SERVER['row'])) {
                        ?>
                        <div class="col-12 col-md-4 mx-auto">
                            <img src="data:image/jpeg;base64, <?php echo base64_encode($_SERVER['row']['image']); ?>" alt=""
                                class="img-fluid">
                        </div>

                        <div class="col-12 col-md-8">
                            <?php
                    }
                    ?>
                        <label for="image" class="form-label">Blog Image</label>

                        <input type="file" name="image" id="image" class="form-control" accept="image/*" />
                        <?php
                        if (isset($_SERVER['row'])) {
                            ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php if (isset($_SERVER['row'])) { ?>
                <input type="hidden" name="id" id="id" value="<?php echo $_SERVER['row']['id'] ?>" />
            <?php } ?>
            <div class="<?php echo isset($_SERVER['row']) ? 'col-md-6' : 'col-md-4' ?>">
                <label for="title" class="form-label">Blog Headline</label>
                <input type="text" name="title" id="title" class="form-control" placeholder="Enter a headline..." required
                    <?php if (isset($_SERVER['row']))
                        echo 'value="' . $_SERVER['row']['title'] . '"'; ?> />
                <div class="invalid-feedback">
                    You must provide a headline for the blog post!
                </div>
            </div>
            <div class="<?php echo isset($_SERVER['row']) ? 'col-md-6' : 'col-md-4' ?>">
                <label for="type" class="form-label">Blog type</label>
                <select name="type" id="type" class="form-select" required>
                    <option value="" disabled <?php if (!isset($_SERVER['row']))
                        echo 'selected'; ?>>Select blog type...
                    </option>
                    <option value="music" <?php if (isset($_SERVER['row']) && $_SERVER['row']['type'] == 'music')
                        echo 'selected'; ?>>Music</option>
                    <option value="sport" <?php if (isset($_SERVER['row']) && $_SERVER['row']['type'] == 'sport')
                        echo 'selected'; ?>>Sport</option>
                </select>
                <div class="invalid-feedback">
                    You must select a blog post type!
                </div>
            </div>
            <div class="col-12">
                <label for="blog-text" class="form-label">Blog Text</label>
                <textarea name="blog-text" id="blog-text" class="form-control" required><?php if (isset($_SERVER['row']))
                    echo $_SERVER['row']['text'];
                ?></textarea>
                <div class="invalid-feedback">
                    Blog text must not be empty!
                </div>
            </div>
            <div class="col-12 d-flex justify-content-end mb-2">
                <button type="submit" id="submitBtn" class="btn btn-primary">
                    <?php echo isset($_SERVER['row']) ? 'Save' : 'New Blog Post' ?>
                </button>
            </div>
        </form>
    </div>
    <div class="toast-container top-0 end-0 p-3">
        <div class="toast align-items-center text-bg-success border-0" id="createBlogPostSuccess" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">Post created successfully!</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>
    <div class="toast-container top-0 end-0 p-3">
        <div class="toast align-items-center text-bg-danger border-0" id="createBlogPostError" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <?php echo isset($_SERVER['blogPostError']) ? $_SERVER['blogPostError'] : "Could not create the post!"; ?>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>
    <script>
        const createSuccess = $('#createBlogPostSuccess').get(0);
        const createSuccessBs = bootstrap.Toast.getOrCreateInstance(createSuccess);
        const createError = $('#createBlogPostError').get(0);
        const createErrorBs = bootstrap.Toast.getOrCreateInstance(createError);

        words_limit = 1600;
        previous_value = '';

        <?php
        if (isset($_SESSION['createBlogPostSuccess'])) {
            ?>
            createSuccessBs.show();
            <?php
            unset($_SESSION['createBlogPostSuccess']);
        } else if (isset($_SESSION['createBlogPostError'])) {
            ?>
                createErrorBs.show();
                <?php
                unset($_SESSION['createBlogPostError']);
        }

        if (isset($_SERVER['editBlogPostError'])) {
            ?>
            createErrorBs.show();
            <?php
        }
        ?>

        var simplemde = new SimpleMDE({
            element: $('#blog-text')[0],
            autosave: {
                enabled: false
            },
            status: [{
                className: "lines",
                defaultValue: (el) => {
                    el.innerHTML = '0';
                },
                onUpdate: (el) => {
                    const text = simplemde.value();

                    const lineCount = (text.match(/\n/gm) || []).length;

                    el.innerHTML = `${lineCount}`;
                },
            }, {
                className: "words",
                defaultValue: (el) => {
                    el.innerHTML = `0 / ${words_limit}`;
                },
                onUpdate: (el) => {
                    const text = simplemde.value();

                    const wordCount = (text.match(/\w+/g) || []).length;

                    el.innerHTML = `${wordCount} / ${words_limit}`;

                    limit_words(wordCount);
                }
            }],
            forceSync: true,
            promptURLs: true,
            shortcuts: {
                toggleStrikethrough: "Cmd-Alt-S",
                drawTable: "Cmd-Alt-T",
            },
            spellChecker: false,
            insertTexts: {
                horizontalRule: ["", "\n\n-----\n\n"],
            },
            showIcons: ["code", "table", "strikethrough", "horizontalRule"]
        });

        (() => {
            const form = $('#blog-form').get(0);

            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                form.classList.add('was-validated');
            })
        })();

        function limit_words(wordCount) {
            if (wordCount > words_limit) {
                $('#submitBtn').attr("disabled", true);
                $('.editor-statusbar > span.words').addClass('text-danger');
            } else {
                $('#submitBtn').attr("disabled", false);
                $('.editor-statusbar > span.words').removeClass('text-danger');
            }

            const value = simplemde.value();

            if (wordCount == words_limit && (value[value.length - 1] == ' ' || value[value.length - 1] == '\n') || wordCount > words_limit) {
                simplemde.codemirror.setValue(previous_value);
                simplemde.codemirror.setCursor(previous_value.length);
            }

            previous_value = simplemde.value();
        }
    </script>
    <?php
} else {
    $_SERVER['status_message'] = "The blog you're trying to edit does not exist!";
    http_response_code(404);
    require 'errors/404.php';
}
?>