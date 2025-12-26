<?php
require('../db.php');
require('functions.php');

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $p = $_POST;
    $id = $p['id'];
    $title = $p['title'];
    $slug = $p['slug'];

        if(isset($p['totele'])) {
            
            $imgid = $p['imgid'];
            $ep = $p['episode'];
            $movie = isset($p['movie']) ? true : false;
            $note = $p['note'];
            $imgsql = "SELECT file_path FROM images WHERE id = $imgid";
            $img = $pdo->query($imgsql)->fetchColumn();
            echo $img = "https://" . $_SERVER['HTTP_HOST'] .  "/content/".$img;
            telegram($note, $title, $img, "https://".$_SERVER['HTTP_HOST']."/".$slug,$ep, $movie);
        }
        
        $content = $p['content'];
        
        $date = '';
        $params = [
            ':content' => $content,
            ':title' => $title,
            ':slug' => $slug,
            ':id' => $id
        ];
        
        if (isset($_POST['date-now'])) {
            $datetime = new DateTime('now', new DateTimeZone('Asia/Karachi'));
            $pubDate = $datetime->format('Y-m-d H:i:s');
            $date = ", pubDate = :pubDate";
            $params[':pubDate'] = $pubDate;
        }
        
        // Prepare and execute query
        $up = $pdo->prepare("UPDATE posts SET content = :content, title = :title, slug = :slug $date WHERE id = :id");
        $up->execute($params);

}


$post = null;
if (isset($_GET['id'])) {
    $postId = intval($_GET['id']);
    $post = $pdo->query("SELECT * FROM posts WHERE id = $postId")->fetch();
}

$title = "Edit Post - WordPress Style";
$headerTitle = "WordPress Style Edit Post";
include 'header.php';
?>

<div class="main-content">
    <?php include 'sidebar.php'; ?>

    <!-- Content -->
    <div class="content">
        <?php if ($post): ?>
        <h2>Edit Post</h2>
        
        <form action="list-posts.php" method="get">
            <input type="text" name="search" placeholder="Search Posts">
        </form>
        <hr>
        <div class="toolbar mb-3">
            <button class="btn btn-light" onclick="execCommand('bold')"><b>B</b></button>
            <button class="btn btn-light" onclick="execCommand('italic')"><i>I</i></button>
            <button class="btn btn-light" onclick="execCommand('underline')"><u>U</u></button>
            <button class="btn btn-light" onclick="execCommand('justifyLeft')">Left</button>
            <button class="btn btn-light" onclick="execCommand('justifyCenter')">Center</button>
            <button class="btn btn-light" onclick="execCommand('justifyRight')">Right</button>
            <button class="btn btn-light" onclick="execCommand('insertHorizontalRule')">HR</button>
            <button class="btn btn-light" onclick="execCommand('foreColor', 'red')">Color</button>
            <button class="btn btn-secondary" onclick="switchEditor('visual')">Visual</button>
            <button class="btn btn-secondary" onclick="switchEditor('code')">Code</button>
        </div>
        
        
        
        
        <script>
        function titletoslug() {
    var title = document.getElementById('title').value;
    title = title.replace(/[^a-zA-Z0-9- ]+/g, "").toLowerCase().replace(/\s+/g, '-');
    document.getElementById('slug').value = title;
        }

        function switchEditor(mode) {
    var visual = document.getElementById('contentformated');
    var code = document.getElementById('postContent');

    if (mode == 'visual') {
        visual.innerHTML = code.value;
        code.style.display = 'none';
        visual.style.display = 'block';
    } else if (mode == 'code') {
        code.value = visual.innerHTML;
        visual.style.display = 'none';
        code.style.display = 'block';
    }
}


function syncEditors() {
    var visual = document.getElementById('contentformated');
    var code = document.getElementById('postContent');

    // If visual editor is active, copy its content to the textarea
    if (visual.style.display === 'block') {
        code.value = visual.innerHTML;
    }
}



</script>
        
        
        
        
        <form method="POST" onsubmit="syncEditors()">
            <div class="form-group">
                <label for="postTitle">Post Title</label>
                <input type="text" class="form-control" id="postTitle" name="title" value="<?php echo htmlspecialchars($post['title']); ?>">
                <label>Slug</label>
                <input type="text" class="form-control" name="slug" value="<?php echo $post['slug'] ?>"
            </div>
            <div class="form-group">
                <label for="postContentVisual">Post Content</label>
                <div id="contentformated" style="display:block" contenteditable="true"><?php echo $post['content'] ?></div>
                <textarea class="form-control" style="display:none" id="postContent" name="content" rows="10"></textarea>
            </div>
            <div class="form-group">
                <label for="telegram">Send to Telegram</label>
                <input type="checkbox" name="totele" checked>
                Episode 
                <input type="text" name="episode" value="Episode 0">
                <label>Movie: </label>
                <input type="checkbox" name="movie">
                Note
                <input type="text" name ="note">
            </div>
            <div class="form-group">
                <label>Date:</label>
                <strong><?php echo $post['pubDate'] ?></strong>
                <label>Now</label>
                <input type="checkbox" name="date-now">
            </div>
            <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
            <input type="hidden" name="imgid" value="<?php echo $post['thumbnail']; ?>">

            <button type="submit" class="btn btn-primary">Update Post</button>
        </form>
        <?php else: ?>
        <p>Post not found.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
