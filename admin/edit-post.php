<?php
require('db.php');
require('functions.php');

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $p = $_POST;
    $id = $p['id'];
    $title = isset($p['postTitle']) ? $p['postTitle'] : $p['title'];
    $slug = isset($p['postSlug']) ? $p['postSlug'] : $p['slug'];
    $content = $p['content'];
    $is_dynamic = isset($p['is_dynamic']) ? 1 : 0;
    $deadbase_id = isset($p['deadbase_id']) ? $p['deadbase_id'] : null;
    $sticky = isset($p['sticky']) ? 1 : 0;
    $post_type = isset($p['post_type']) ? $p['post_type'] : 'post';
    $author = isset($p['author']) ? $p['author'] : 1;
    $imgid = isset($p['selectedImageId']) ? $p['selectedImageId'] : (isset($p['imgid']) ? $p['imgid'] : null);

    if (isset($p['totele'])) {
        $ep = isset($p['ep']) ? $p['ep'] : (isset($p['episode']) ? $p['episode'] : '');
        $movie = isset($p['movie']) ? true : false;
        $note = $p['note'];
        $imgsql = "SELECT file_path FROM images WHERE id = $imgid";
        $img = $pdo->query($imgsql)->fetchColumn();
        $img = "https://" . $_SERVER['HTTP_HOST'] .  "/content/" . $img;
        telegram($note, $title, $img, "https://" . $_SERVER['HTTP_HOST'] . "/" . $slug, $ep, $movie);
    }

    $date = '';
    $params = [
        ':content' => $content,
        ':title' => $title,
        ':slug' => $slug,
        ':is_dynamic' => $is_dynamic,
        ':deadbase_id' => $deadbase_id,
        ':sticky' => $sticky,
        ':post_type' => $post_type,
        ':author' => $author,
        ':thumbnail' => $imgid,
        ':id' => $id
    ];

    if (isset($_POST['date-now'])) {
        $datetime = new DateTime('now', new DateTimeZone('Asia/Karachi'));
        $pubDate = $datetime->format('Y-m-d H:i:s');
        $date = ", pubDate = :pubDate";
        $params[':pubDate'] = $pubDate;
    }

    $up = $pdo->prepare("UPDATE posts SET content = :content, title = :title, slug = :slug, is_dynamic = :is_dynamic, deadbase_id = :deadbase_id, sticky = :sticky, post_type = :post_type, author = :author, thumbnail = :thumbnail $date WHERE id = :id");
    $up->execute($params);

    $pdo->query("DELETE FROM post_categories WHERE post_id = $id");
    if (isset($p['categories'])) {
        foreach ($p['categories'] as $c) {
            $pdo->query("INSERT IGNORE INTO post_categories (post_id,category_id) VALUES ($id,$c)");
        }
    }

    $pdo->query("DELETE FROM post_genres WHERE post_id = $id");
    if (isset($p['genres'])) {
        foreach ($p['genres'] as $g) {
            $pdo->query("INSERT IGNORE INTO post_genres (post_id,genre_id) VALUES ($id,$g)");
        }
    }
}


$post = null;
$selectedCategoryIds = [];
$selectedGenreIds = [];
if (isset($_GET['id'])) {
    $postId = intval($_GET['id']);
    $post = $pdo->query("SELECT * FROM posts WHERE id = $postId")->fetch();
    if ($post) {
        $selectedCategoryIds = $pdo->query("SELECT category_id FROM post_categories WHERE post_id = $postId")->fetchAll(PDO::FETCH_COLUMN);
        $selectedGenreIds = $pdo->query("SELECT genre_id FROM post_genres WHERE post_id = $postId")->fetchAll(PDO::FETCH_COLUMN);
    }
}

$cats = $pdo->query("SELECT * FROM categories ORDER BY categories.cat_name ASC")->fetchAll();
$gens = $pdo->query("SELECT * FROM genres ORDER BY genres.genre_name ASC")->fetchAll();
$authors = $pdo->query("SELECT author_id, author_display_name FROM authors ORDER BY author_display_name ASC")->fetchAll();

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
                <?php include 'post-form-fields.php'; ?>
                <div class="form-group">
                    <label>Date:</label>
                    <strong><?php echo $post['pubDate'] ?></strong>
                    <label>Now</label>
                    <input type="checkbox" name="date-now">
                </div>
                <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
                <input type="hidden" name="selectedImageId" value="<?php echo $post['thumbnail']; ?>">

                <button type="submit" class="btn btn-primary">Update Post</button>
            </form>
        <?php else: ?>
            <p>Post not found.</p>
        <?php endif; ?>
    </div>
</div>

<style>
    #contentformated {
        display: block;
    }

    #postContent {
        display: none;
    }

    .tag-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .tag-pill {
        display: inline-flex;
        align-items: center;
        cursor: pointer;
    }

    .tag-pill input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .tag-pill span {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border: 1px solid #c9c9c9;
        border-radius: 999px;
        background: #f7f7f7;
        font-size: 14px;
        line-height: 1;
        user-select: none;
    }

    .tag-pill input:checked+span {
        background: #1f6feb;
        border-color: #1f6feb;
        color: #fff;
    }
</style>

<?php include 'footer.php'; ?>