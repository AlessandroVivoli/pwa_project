<div class="container-fluid container-lg p-3 p-lg-0 pt-lg-3">
    <form method="post" class="row g-3 needs-validation" id="blog-form" novalidate>
        <div class="col-lg-6">
            <label for="image" class="form-label">Blog Image</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*" />
        </div>
        <div class="col-lg-6">
            <label for="title" class="form-label">Blog Headline</label>
            <input type="text" name="title" id="title" class="form-control" placeholder="Enter a headline..." required />
            <div class="invalid-feedback">
                You must provide a headline for the blog post!
            </div>
        </div>
        <div class="col-12">
            <label for="blog-text" class="form-label">Blog Text</label>
            <textarea name="blog-text" id="blog-text" class="form-control" required></textarea>
            <div class="invalid-feedback">
                Blog text must not be empty!
            </div>
        </div>
        <div class="col-12 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">New Blog Post</button>
        </div>
    </form>
</div>
<script>
    var simplemde = new SimpleMDE({
        element: $('#blog-text')[0],
        autosave: {
            enabled: false
        },
        forceSync: true,
        promptURLs: true,
        shortcuts: {
            toggleStrikethrough: "Cmd-Alt-S",
            drawTable: "Cmd-Alt-T",
        },
        insertTexts: {
            horizontalRule: ["", "\n\n-----\n\n"],
        },
        showIcons: ["code", "table", "strikethrough", "horizontalrule"]
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
    })()
</script>