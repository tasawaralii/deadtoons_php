<?php

if ($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['origin'] == "deadtoons") {
    $base_dir = 'content/';

    $date_dir = date('Y/m/');

    $nwithex = basename($_FILES["fileToUpload"]["name"]);

    $rel_path = $date_dir . $nwithex;

    $directory = $base_dir . $date_dir;
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }


    $target_file = $directory . $nwithex;

    $fname = pathinfo($target_file, PATHINFO_FILENAME);

    $compressed_file = $directory . $fname . "-640x360." . pathinfo($target_file, PATHINFO_EXTENSION);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {

        if (resize_image($target_file, $compressed_file, $imageFileType) == "success") {

            $pdo->query("INSERT INTO `images`(`title`, `pubDate`, `file_path`) VALUES ('$fname',NOW(),'$rel_path')");


            echo "success";
        } else {
            echo "failed to resize image";
        }

    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

?>