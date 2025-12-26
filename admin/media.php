<?php
// Define the base directory where media files are stored
$base_directory = '../content';

// Function to fetch all media files from a directory recursively
function get_media_files($dir) {
    $files = [];
    // Ensure the directory exists and is readable
    if (is_dir($dir) && is_readable($dir)) {
        // Iterate through each file and subdirectory
        foreach (scandir($dir) as $file) {
            // Skip current and parent directory links
            if ($file == '.' || $file == '..') continue;
            // Build the full path
            $full_path = $dir . '/' . $file;
            // Check if it's a file or directory
            if (is_file($full_path)) {
                $files[] = $full_path;
            } elseif (is_dir($full_path)) {
                // Recursively get files from subdirectory
                $files = array_merge($files, get_media_files($full_path));
            }
        }
    }
    return $files;
}

// Function to handle media upload
function handle_upload($upload_dir) {
    if (!empty($_FILES['file'])) {
        $file = $_FILES['file'];
        $upload_path = $upload_dir . '/' . basename($file['name']);
        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
            return true;
        } else {
            return false;
        }
    }
    return false;
}

// Process media upload if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Define upload directory based on base directory
    $upload_directory = $base_directory . date('Y/m');
    // Create directory if it doesn't exist
    if (!file_exists($upload_directory)) {
        mkdir($upload_directory, 0777, true);
    }
    // Handle the upload
    $upload_success = handle_upload($upload_directory);
    if ($upload_success) {
        echo '<div class="alert alert-success" role="alert">File uploaded successfully!</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">Error uploading file.</div>';
    }
}

// Fetch existing media files
$media_files = get_media_files($base_directory);

// HTML starts here
$title = "Media Library - WordPress Style";
$headerTitle = "WordPress Style Media Library";
include 'header.php';
?>

<div class="main-content">
    <?php include 'sidebar.php'; ?>

    <!-- Content -->
    <div class="content">
        <h2>Media Library</h2>

        <!-- Upload Form -->
        <div class="upload-form">
            <h3>Upload New Media</h3>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="file">Choose File:</label>
                    <input type="file" class="form-control-file" id="file" name="file" required>
                </div>
                <button type="submit" class="btn btn-primary">Upload</button>
            </form>
        </div>

        <!-- Existing Media -->
        <div class="media-list">
            <h3>Existing Media</h3>
            <div class="row">
                <?php foreach ($media_files as $file): ?>
                    <div class="col-md-3 mb-3">
                        <div class="card">
                            <img src="<?php echo $file; ?>" class="card-img-top" alt="Media">
                            <div class="card-body">
                                <p class="card-text"><?php echo basename($file); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
