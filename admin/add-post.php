<?php
require('../db.php');
require('functions.php');

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $p = $_POST;
        $imgid = $p['selectedImageId'];
        
        $title = $p['postTitle'];
        
        $slug = $p['postSlug'] != '' ? $p['postSlug'] : slugify($title);
        
        $content = $p['content'];
        
        
        if(isset($p['totele'])) {
            
            $ep = $p['ep'];
            $movie = isset($p['movie']) ? true : false;
            $note = $p['note'];
            $imgsql = "SELECT file_path FROM images WHERE id = $imgid";
            $img = $pdo->query($imgsql)->fetchColumn();
            $img = "https://deadtoons.one/content/".$img;
            telegram($note, $title, $img, "https://".$_SERVER['HTTP_HOST']."/".$slug,$ep, $movie);
        }

    $datetime = new DateTime('now', new DateTimeZone('Asia/Karachi'));
    $pubDate = $datetime->format('Y-m-d H:i:s');
    

    $update = "INSERT INTO `posts`(`title`, `slug`, `pubDate`, `author`, `content`, `post_type`, `thumbnail`) 
               VALUES (:title, :slug, :pubDate, 1, :content, 'post', :thumbnail)";
    

    $up = $pdo->prepare($update);
    $up->execute([
        ':title' => $title,
        ':slug' => $slug,
        ':pubDate' => $pubDate,
        ':content' => $content,
        ':thumbnail' => $imgid
    ]);

    $postId = $pdo->lastInsertId();


if(isset($p['categories'])) {
    foreach($p['categories'] as $c) {
        $pdo->query("INSERT IGNORE INTO posts_tag (post_id,tag_id,tag_type) VALUES ($postId,$c,2)");
    }
}


if(isset($p['genres'])) {
    foreach($p['genres'] as $g) {
        $pdo->query("INSERT IGNORE INTO posts_tag (post_id,tag_id,tag_type) VALUES ($postId,$g,3)");
    }
}

    header("Location: edit-post.php?id=$postId");

}

$cats = $pdo->query("SELECT * FROM categories ORDER BY categories.cat_name ASC")->fetchAll();
$gens = $pdo->query("SELECT * FROM genres ORDER BY genres.genre_name ASC")->fetchAll();
$tags = $pdo->query("SELECT * FROM tags ORDER BY tags.tag_name ASC")->fetchAll();

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
            <button class="btn btn-success" onclick="titletoslug()">Slug</button>
        </div>

        <hr>

        <form id="uploadForm" action="/upload.php" method="post" enctype="multipart/form-data">
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
            <div class="form-group">
                <label for="postTitle">Post Title</label>
                <input type="text" class="form-control" id="title" name="postTitle" placeholder="Enter title" required>
            </div>
            <div class="form-group">
                <label for="postSlug">Post Slug</label>
                <input type="text" class="form-control" id="slug" name="postSlug">
            </div>
            <div class="form-group">
                <label for="postContentVisual">Post Content</label>
                <div id="contentformated" contenteditable="true"></div>
                <textarea class="form-control" id="postContent" name="content" rows="10"></textarea>
            </div>

            <div class="form-group">
                <label>Telegram</label>
                <input type="checkbox" name="totele" checked>
                <label>Episode</label>
                <input type="text" name="ep" value="Episode ">
                <label>Movie: </label>
                <input type="checkbox" name="movie">
                <label> Note: </label>
                <input type="text" name="note">
            </div>

            <div style="display: flex;justify-content: center;">
                <div class="form-group mr-5">
                    <label>Categories: </label>
                    <?php foreach ($cats as $cat): ?>
                        <div>
                            <input type="checkbox" id="cat_<?php echo $cat['cat_id']; ?>" name="categories[]" value="<?php echo $cat['cat_id']; ?>">
                            <label for="cat_<?php echo $cat['cat_id']; ?>"><?php echo htmlspecialchars($cat['cat_name']); ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="form-group">
                    <label>Genres: </label>
                    <?php foreach ($gens as $gen): ?>
                        <div>
                            <input type="checkbox" id="genre_<?php echo $gen['genre_id']; ?>" name="genres[]" value="<?php echo $gen['genre_id']; ?>">
                            <label for="genre_<?php echo $gen['genre_id']; ?>"><?php echo htmlspecialchars($gen['genre_name']); ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="form-group">
                    <label>Tags: </label>
                    <?php foreach ($tags as $tag): ?>
                        <div>
                            <input type="checkbox" id="tag_<?php echo $tag['tag_id']; ?>" name="tags[]" value="<?php echo $tag['tag_id']; ?>">
                            <label for="tag_<?php echo $tag['tag_id']; ?>"><?php echo htmlspecialchars($tag['tag_name']); ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
        </form>
    </div>

</div>



        <!-- Styles for Popup Modal -->
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
            document.addEventListener('DOMContentLoaded', function () {
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
                    fetch('../show-images.php')
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
                        item.addEventListener('click', function () {
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
                window.addEventListener('click', function (event) {
                    if (event.target === imagePopup) {
                        closeModal();
                    }
                });
                selectImageConfirm.addEventListener('click', confirmSelection);

                // Form submission handling
                uploadForm.addEventListener('submit', function (event) {
                    event.preventDefault(); // Prevent the default form submission

                    // Optional: Validate form fields here before submitting

                    // Simulate form submission loading
                    loadingIndicator.style.display = 'block';

                    // Example: Perform actual form submission (if needed)
                    var formData = new FormData(this);

                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', this.action, true);

                    xhr.onload = function () {
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

                    xhr.onerror = function () {
                        loadingIndicator.style.display = 'none'; // Hide loading indicator
                        alert('An error occurred while uploading the image');
                    };

                    xhr.send(formData);
                });
            });
        </script>


<?php include 'footer.php'; ?>
