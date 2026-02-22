<?php
require('db.php');
require('functions.php');

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $p = $_POST;
    $imgid = $p['selectedImageId'];
    $title = $p['postTitle'];
    $slug = $p['postSlug'] != '' ? $p['postSlug'] : slugify($title);
    $content = $p['content'];
    $is_dynamic = isset($p['is_dynamic']) ? 1 : 0;
    $deadbase_id = isset($p['deadbase_id']) ? $p['deadbase_id'] : null;
    $sticky = isset($p['sticky']) ? 1 : 0;
    $post_type = isset($p['post_type']) ? $p['post_type'] : 'post';
    $author = isset($p['author']) ? $p['author'] : 1;

    if (isset($p['totele'])) {
        $ep = $p['ep'];
        $movie = isset($p['movie']) ? true : false;
        $note = $p['note'];
        $imgsql = "SELECT file_path FROM images WHERE id = $imgid";
        $img = $pdo->query($imgsql)->fetchColumn();
        $img = "https://deadtoons.org/content/" . $img;
        telegram($note, $title, $img, "https://" . $_SERVER['HTTP_HOST'] . "/" . $slug, $ep, $movie);
    }

    $datetime = new DateTime('now', new DateTimeZone('Asia/Karachi'));
    $pubDate = $datetime->format('Y-m-d H:i:s');

    $update = "INSERT INTO `posts`(`title`, `slug`, `is_dynamic`, `deadbase_id`, `pubDate`, `author`, `content`, `sticky`, `post_type`, `thumbnail`) 
               VALUES (:title, :slug, :is_dynamic, :deadbase_id, :pubDate, :author, :content, :sticky, :post_type, :thumbnail)";

    $up = $pdo->prepare($update);
    $up->execute([
        ':title' => $title,
        ':slug' => $slug,
        ':is_dynamic' => $is_dynamic,
        ':deadbase_id' => $deadbase_id,
        ':pubDate' => $pubDate,
        ':author' => $author,
        ':content' => $content,
        ':sticky' => $sticky,
        ':post_type' => $post_type,
        ':thumbnail' => $imgid
    ]);

    $postId = $pdo->lastInsertId();

    if (isset($p['categories'])) {
        foreach ($p['categories'] as $c) {
            $pdo->query("INSERT IGNORE INTO post_categories (post_id,category_id) VALUES ($postId,$c)");
        }
    }

    if (isset($p['genres'])) {
        foreach ($p['genres'] as $g) {
            $pdo->query("INSERT IGNORE INTO post_genres (post_id,genre_id) VALUES ($postId,$g)");
        }
    }

    header("Location: edit-post.php?id=$postId");
}


$cats = $pdo->query("SELECT * FROM categories ORDER BY categories.cat_name ASC")->fetchAll();
$gens = $pdo->query("SELECT * FROM genres ORDER BY genres.genre_name ASC")->fetchAll();
$authors = $pdo->query("SELECT author_id, author_display_name FROM authors ORDER BY author_display_name ASC")->fetchAll();
$post = [
    'title' => '',
    'slug' => '',
    'content' => '',
    'is_dynamic' => 0,
    'deadbase_id' => '',
    'sticky' => 0,
    'post_type' => 'post',
    'author' => 1
];
$selectedCategoryIds = [];
$selectedGenreIds = [];

$title = "Edit Post - WordPress Style";
$headerTitle = "WordPress Style Edit Post";
include 'header.php';
?>

<div class="main-content">
    <?php include 'sidebar.php'; ?>

    <!-- Content -->
    <div class="content">
        <h2>Edit Post</h2>
        <div class="toolbar mb-3">
            <button class="btn btn-secondary" onclick="switchEditor('visual')">Visual</button>
            <button class="btn btn-secondary" onclick="switchEditor('code')">Code</button>
            <button class="btn btn-success" onclick="titletoslug()">Slug</button>
        </div>

        <hr>

        <form id="uploadForm" action="/upload/image" method="post" enctype="multipart/form-data">
            <input type="hidden" name="origin" value="deadtoons">
            <label>Select image:</label>
            <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" value="Upload">
        </form>

        <div id="loadingIndicator" style="display:none;">Uploading...</div>
        <hr>
        <button type="button" id="selectImageBtn">Select Image</button>

        <hr>

        <!-- Popup Modal -->
        <div id="imagePopup" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Select an Image</h2>
                <div id="imageList"></div>
                <button id="selectImageConfirm" disabled>Select</button>
            </div>
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


        <form method="post" onsubmit="syncEditors()">

            <input type="hidden" id="selectedImageId" name="selectedImageId">
            <button type="submit" class="btn btn-primary">Public Post</button>
            <hr>
            <?php include 'post-form-fields.php'; ?>

        </form>
    </div>

</div>



<!-- Styles for Popup Modal and Tag Buttons -->
<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
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

    .tag-pill input:checked + span {
        background: #1f6feb;
        border-color: #1f6feb;
        color: #fff;
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    .image-item {
        display: inline-block;
        margin: 10px;
        cursor: pointer;
    }

    .image-item img {
        max-width: 100px;
        max-height: 100px;
    }

    .image-item.selected {
        border: 2px solid blue;
    }
</style>





<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectImageBtn = document.getElementById('selectImageBtn');
        const imagePopup = document.getElementById('imagePopup');
        const closeBtn = document.getElementsByClassName('close')[0];
        const imageList = document.getElementById('imageList');
        const selectImageConfirm = document.getElementById('selectImageConfirm');
        const selectedImageIdInput = document.getElementById('selectedImageId');
        const uploadForm = document.getElementById('uploadForm');
        const loadingIndicator = document.getElementById('loadingIndicator');

        let selectedImageId = null;

        // Function to open the modal
        function openModal() {
            imagePopup.style.display = 'block';
            setTimeout(fetchImages, 100); // Delay fetchImages by 100ms to ensure DOM is ready
        }

        // Function to close the modal
        function closeModal() {
            imagePopup.style.display = 'none';
        }

        // Function to fetch images and display them in the modal
        function fetchImages() {
            fetch('/admin/show-images.php')
                .then(response => response.json())
                .then(data => {
                    imageList.innerHTML = '';
                    data.forEach(image => {
                        const imageItem = document.createElement('div');
                        imageItem.className = 'image-item';
                        imageItem.dataset.id = image.id;
                        imageItem.innerHTML = `<img src="/content/${image.file_path}" alt="Image">`;
                        imageList.appendChild(imageItem);
                    });
                    attachImageClickHandlers();
                });
        }

        // Function to attach click handlers to images
        function attachImageClickHandlers() {
            const imageItems = document.querySelectorAll('.image-item');
            imageItems.forEach(item => {
                item.addEventListener('click', function() {
                    imageItems.forEach(i => i.classList.remove('selected'));
                    this.classList.add('selected');
                    selectedImageId = this.dataset.id;
                    selectImageConfirm.disabled = false;
                });
            });
        }

        // Function to confirm image selection
        function confirmSelection() {
            if (selectedImageId) {
                selectedImageIdInput.value = selectedImageId;
                // alert('Selected Image ID: ' + selectedImageId);
                closeModal();
            }
        }

        // Event Listenersa
        selectImageBtn.addEventListener('click', openModal);
        closeBtn.addEventListener('click', closeModal);
        window.addEventListener('click', function(event) {
            if (event.target === imagePopup) {
                closeModal();
            }
        });
        selectImageConfirm.addEventListener('click', confirmSelection);

        // Form submission handling
        uploadForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            // Optional: Validate form fields here before submitting

            // Simulate form submission loading
            loadingIndicator.style.display = 'block';

            // Example: Perform actual form submission (if needed)
            var formData = new FormData(this);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', this.action, true);

            xhr.onload = function() {
                loadingIndicator.style.display = 'none'; // Hide loading indicator

                if (xhr.status === 200) {
                    if (xhr.responseText.trim() === 'success') {
                        alert('Image uploaded successfully');
                    } else {
                        alert('Failed to upload image');
                    }
                } else {
                    alert('An error occurred while uploading the image');
                }
            };

            xhr.onerror = function() {
                loadingIndicator.style.display = 'none'; // Hide loading indicator
                alert('An error occurred while uploading the image');
            };

            xhr.send(formData);
        });
    });
</script>


<?php include 'footer.php'; ?>