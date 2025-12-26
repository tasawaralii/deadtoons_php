<?php
require('../db.php');

// Sample comment data - Replace with database query
$comment_id = $_GET['id']; // Assuming you pass the comment ID via GET parameter
$comment = $pdo->query("SELECT * FROM comments WHERE com_id = $comment_id")->fetch();

$title = "Edit Comment - WordPress Style";
$headerTitle = "WordPress Style Edit Comment";
include 'header.php';
?>

<div class="main-content">
    <?php include 'sidebar.php'; ?>

    <!-- Content -->
    <div class="content">
        <h2>Edit Comment</h2>
        <form action="update-comment.php" method="POST">
            <input type="hidden" name="comment_id" value="<?php echo $comment['com_id']; ?>">
            <div class="form-group">
                <label for="author">Author:</label>
                <input type="text" class="form-control" id="author" name="author" value="<?php echo htmlspecialchars($comment['com_author']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($comment['com_email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="content">Content:</label>
                <textarea class="form-control" id="content" name="content" rows="5" required><?php echo htmlspecialchars($comment['com_content']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="status">Status:</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="1" <?php echo $comment['com_status'] ? 'selected' : ''; ?>>Approved</option>
                    <option value="0" <?php echo !$comment['com_status'] ? 'selected' : ''; ?>>Pending</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Comment</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
