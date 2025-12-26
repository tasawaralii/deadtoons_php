<?php
require("../db.php");
require('functions.php');


    if($_SERVER['REQUEST_METHOD'] == "POST") {
        $id = $_POST['id'];
        
        if($_POST['action'] == "delete") {
            $pdo->query("DELETE FROM comments where com_id = $id");
            echo "1";
            exit;
        }
        
    if($_POST['action'] == "reply") {
        $reply = $_POST['reply'];
        $par_id = $_POST['com_par'];
        $sql = "INSERT INTO `comments`(`post_id`, `parent_id`, `com_author`, `com_email`, `com_author_url`, `com_date`, `com_content`, `com_status`) 
        VALUES (:id,:par_id, 'Admin', 'zikdeathnote@gmail.com', 'https://deadtoons.pro', NOW(), :reply, 1)";
        
        $rep = $pdo->prepare($sql);
        $rep->execute([
            ':id' => $id,
            ':par_id' => $par_id,
            ':reply' => $reply
            ]);
    }
        
    }


    $limit= isset($_GET['limit']) ? $_GET['limit'] : 20;


    if(isset($_GET['type'])) {
        $comments = $pdo->query("SELECT * FROM comments JOIN posts ON posts.id = comments.post_id WHERE com_status != 1 ORDER BY com_date DESC LIMIT $limit")->fetchAll();
    } else {
        $comments = $pdo->query("SELECT * FROM comments JOIN posts ON posts.id = comments.post_id ORDER BY com_date DESC LIMIT $limit")->fetchAll();

    }


$title = "All Comments - WordPress Style";
$headerTitle = "WordPress Style All Comments";
include 'header.php';
?>

<div class="main-content">
    <?php include 'sidebar.php'; ?>

    <!-- Content -->
    <div class="content">
        <h2>All Comments</h2> (<a href="?type=pending" >Pending</a>)
        <form><input name="limit"></form>
        
<script>
function delete_com(id) {
    var comment = document.getElementById(id);
    
    fetch("", {
        method: "post",
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            action: "delete",
            id: id
        })
    })
    .then(response => response.text())
    .then(result => {
        if (result == 1) {
            comment.remove();
        } else {
            console.error('Failed to delete the comment');
        }
    })
    .catch(error => console.error('Error:', error));
}

    function reply_com(post_id,com_id) {
        var com = document.getElementById(com_id);
        var com_box = document.getElementById('reply_box');
        if(com_box)
        com_box.remove();
        var formHtml = `
    <tr>
        <td id="reply_box" colspan="7">
            <form method="post">
                <div class="form-group">
                    <label for="reply">Reply:</label>
                    <input type="hidden" name="action" value="reply">
                    <input type="hidden" name="id" value="${post_id}">
                    <textarea class="form-control" name="reply" rows="3"></textarea>
                    <input type="hidden" name="com_par" value="${com_id}">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </td>
    </tr>
        `;
        com.insertAdjacentHTML('afterend', formHtml);        
    }


</script>
        
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Post</th>
                    <th scope="col">Author</th>
                    <th scope="col">Email</th>
                    <th scope="col">Date</th>
                    <th scope="col">Content</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($comments as $comment): ?>
                <tr id="<?php echo $comment['com_id']; ?>" >
                    <th scope="row"><?php echo '<a href="../../'.$comment['slug'].'/#comment-'.$comment['com_id'].'">'. ($comment['title']) .'</a>'; ?></th>
                    <td><?php echo htmlspecialchars($comment['com_author']); ?></td>
                    <td><?php echo htmlspecialchars($comment['com_email']); ?></td>
                    <td><?php echo $comment['com_date']; ?></td>
                    <td><?php echo htmlspecialchars($comment['com_content']); ?></td>
                    <td><?php echo $comment['com_status'] == 1 ? 'Approved' : 'Pending'; ?></td>
                    <td>
                        <a href="edit-comment.php?id=<?php echo $comment['com_id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                        <button class="btn btn-danger btn-sm" onclick="delete_com(<?php echo $comment['com_id'] ?>)">Delete</button>
                        <button class="btn btn-info btn-sm" onclick="reply_com(<?php echo $comment['id'] . ',' .$comment['com_id'] ?>)">Reply</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include 'footer.php'; ?>
