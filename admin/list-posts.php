<?php
require('../db.php');
require('functions.php');

// Fetch posts from the database

if(isset($_GET['search'])) {
    $s = $_GET['search'];
 $posts = $pdo->query("SELECT * FROM posts where posts.title LIKE '%$s%' ORDER BY posts.pubDate DESC")->fetchAll();   
} else {

$posts = $pdo->query("SELECT * FROM posts WHERE post_type = 'post' ORDER BY pubDate DESC LIMIT 10")->fetchAll();
}
$title = "All Posts - WordPress Style";
$headerTitle = "WordPress Style All Posts";
include 'header.php';
?>

<div class="main-content">
    <?php include 'sidebar.php'; ?>

    <!-- Content -->
    <div class="content">
        <h2>All Posts</h2>
        <form method="get">
            <input type="text" name="search" placeholder="Search Posts">
        </form>
        <hr>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Title</th>
                    <th scope="col">Views</th>
                    <th scope="col">Date</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($posts as $post): ?>
                <tr>
                    <th scope="row"><?php echo $post['id']; ?></th>
                    <td><?php echo $post['title']; ?></td>
                    <td><?php echo $post['views']; ?></td>
                    <td><?php echo $post['pubDate']; ?></td>
                    <td>
                        <a href="edit-post.php?id=<?php echo $post['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                        <button class="btn btn-secondary btn-sm quick-edit-btn">Quick Edit</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Quick Edit Form (Hidden by default) -->
        <div id="quick-edit-form" style="display: none;">
            <h3>Quick Edit</h3>
            <form action="update-post.php" method="POST">
                <input type="hidden" id="quick-edit-post-id" name="post_id" value="">
                <div class="form-group">
                    <label for="quick-edit-title">Title:</label>
                    <input type="text" class="form-control" id="quick-edit-title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="quick-edit-slug">Slug:</label>
                    <input type="text" class="form-control" id="quick-edit-slug" name="slug" required>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="quick-edit-sticky" name="sticky">
                    <label class="form-check-label" for="quick-edit-sticky">Sticky</label>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <button type="button" class="btn btn-secondary cancel-quick-edit">Cancel</button>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quick Edit Button Click Event
    const quickEditButtons = document.querySelectorAll('.quick-edit-btn');
    const quickEditForm = document.getElementById('quick-edit-form');
    const quickEditPostId = document.getElementById('quick-edit-post-id');
    const cancelQuickEditButton = document.querySelector('.cancel-quick-edit');

    quickEditButtons.forEach(button => {
        button.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');
            const postTitle = this.getAttribute('data-post-title');
            const postSlug = this.getAttribute('data-post-slug');
            const postSticky = this.getAttribute('data-post-sticky');

            // Set form values based on clicked post
            quickEditPostId.value = postId;
            document.getElementById('quick-edit-title').value = postTitle;
            document.getElementById('quick-edit-slug').value = postSlug;
            document.getElementById('quick-edit-sticky').checked = postSticky === '1';

            // Position the form below the respective post
            const tr = this.closest('tr');
            const formWrapper = tr.querySelector('.quick-edit-form-wrapper');
            if (formWrapper) {
                // Remove existing form wrapper to prevent duplicate
                formWrapper.parentNode.removeChild(formWrapper);
            }
            tr.insertAdjacentHTML('afterend', '<div class="quick-edit-form-wrapper"></div>');
            tr.nextElementSibling.appendChild(quickEditForm);

            // Show the quick edit form
            quickEditForm.style.display = 'block';
        });
    });

    // Cancel Quick Edit Button Click Event
    cancelQuickEditButton.addEventListener('click', function() {
        // Hide the quick edit form
        quickEditForm.style.display = 'none';
        // Remove form wrapper if exists
        const formWrapper = document.querySelector('.quick-edit-form-wrapper');
        if (formWrapper) {
            formWrapper.parentNode.removeChild(formWrapper);
        }
    });
});
</script>
