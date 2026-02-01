<?php
require('db.php');
require_once "./functions.php";

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment_id = isset($_POST['comment_id']) ? (int)$_POST['comment_id'] : 0;
    $author = trim($_POST['author'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $status = isset($_POST['status']) ? (int)$_POST['status'] : 0;

    if ($comment_id > 0 && !empty($author) && !empty($email) && !empty($content)) {
        try {
            $stmt = $pdo->prepare("UPDATE comments SET com_author = ?, com_email = ?, com_content = ?, com_status = ? WHERE com_id = ?");
            $stmt->execute([$author, $email, $content, $status, $comment_id]);
            
            $message = 'Comment updated successfully!';
            $messageType = 'success';
        } catch (PDOException $e) {
            $message = 'Error updating comment: ' . $e->getMessage();
            $messageType = 'error';
        }
    } else {
        $message = 'Please fill in all required fields.';
        $messageType = 'error';
    }
}

// Get comment ID from GET or POST
$comment_id = isset($_GET['id']) ? (int)$_GET['id'] : (isset($_POST['comment_id']) ? (int)$_POST['comment_id'] : 0);

// Fetch comment data
$stmt = $pdo->prepare("SELECT * FROM comments WHERE com_id = ?");
$stmt->execute([$comment_id]);
$comment = $stmt->fetch();

$title = "Edit Comment - WordPress Style";
$headerTitle = "WordPress Style Edit Comment";
include 'header.php';
?>

<div class="main-content">
    <?php include 'sidebar.php'; ?>

    <!-- Content -->
    <div class="content">
        <h2>Edit Comment</h2>
        
        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $messageType === 'success' ? 'success' : 'danger'; ?>" style="padding: 10px; margin-bottom: 15px; border-radius: 4px; background-color: <?php echo $messageType === 'success' ? '#d4edda' : '#f8d7da'; ?>; color: <?php echo $messageType === 'success' ? '#155724' : '#721c24'; ?>; border: 1px solid <?php echo $messageType === 'success' ? '#c3e6cb' : '#f5c6cb'; ?>;">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($comment): ?>
        <form action="" method="POST">
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
        <?php else: ?>
            <div class="alert alert-danger" style="padding: 10px; margin-bottom: 15px; border-radius: 4px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">
                Comment not found.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
