<?php
require('functions.php');
$directory = 'content'; // Replace with your directory path

// Create a RecursiveDirectoryIterator
$directoryIterator = new RecursiveDirectoryIterator($directory);

// Create a RecursiveIteratorIterator
$iterator = new RecursiveIteratorIterator($directoryIterator);
 
// Loop through the files and directories
foreach ($iterator as $fileInfo) {
    // Check if not a file (not a directory)
    if ($fileInfo->isFile()) {
        $name = $fileInfo->getPathname();
        $info = pathinfo($name);
        $a = $info['dirname'].'/'.$info['filename'].'-640x360.'.$info['extension'];
        $output = $info['dirname'].'\\'.$info['filename'].'.'.$info['extension'];
        if(strpos($name,'-640x360.') !== false) {
            continue;
        } elseif (file_exists($a)) {
            // echo $a."<br>";
            // echo "yes";
            continue;
        } else {
            echo $a."<br>";
            // if($info['extension'] == 'jpg') {
            resize_image($name,$a, $info['extension']);
            // } else {
            //     continue;
            // }
            // exit;
        }
    }
}
?>
