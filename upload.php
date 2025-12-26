<?php

// if (extension_loaded('imagick')) {
//     echo "Imagick is available.";
// } else {
//     echo "Imagick is not available.";
// }

// if (class_exists('Imagick')) {
//     echo "Imagick is installed and ready to use.";
// } else {
//     echo "Imagick is not installed.";
// }


function resize_image($inputFile, $outputFile, $ex) {
    try {
        // Create a new Imagick object and read the input file
        $image = new Imagick($inputFile);

        // Convert the image to the specified format
        $image->setImageFormat($ex);

        // Get original dimensions
        $originalWidth = $image->getImageWidth();
        $originalHeight = $image->getImageHeight();

        // Define the target dimensions
        $targetWidth = 640;
        $targetHeight = 360;

        // Calculate the new dimensions while maintaining the aspect ratio
        if ($originalWidth / $targetWidth > $originalHeight / $targetHeight) {
            $newWidth = $targetWidth;
            $newHeight = intval($originalHeight * ($targetWidth / $originalWidth));
        } else {
            $newHeight = $targetHeight;
            $newWidth = intval($originalWidth * ($targetHeight / $originalHeight));
        }

        // Resize the image
        $image->resizeImage($newWidth, $newHeight, Imagick::FILTER_LANCZOS, 1);

        // Save the output file
        $image->writeImage($outputFile);

        // Clear the Imagick object
        $image->clear();
        $image->destroy();

        return 'success';
    } catch (Exception $e) {
        return 'error';
    }
}

if($_SERVER['REQUEST_METHOD'] == "POST" && $_POST['origin'] == "deadtoons") {
        $base_dir = 'content/';
        
        $date_dir = date('Y/m/');
        
        $nwithex = basename($_FILES["fileToUpload"]["name"]);
        
        $rel_path = $date_dir.$nwithex;
        
        $directory = $base_dir . $date_dir;
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

        
        $target_file = $directory . $nwithex;
        
        $fname = pathinfo($target_file, PATHINFO_FILENAME); 
        
        $compressed_file = $directory . $fname . "-640x360." . pathinfo($target_file, PATHINFO_EXTENSION);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                
                if (resize_image($target_file, $compressed_file, $imageFileType) == "success") {
                    
                    require('db.php');
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