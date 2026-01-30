<?php

require('db.php');

$res = $pdo->query("SELECT id,file_path FROM images ORDER BY images.pubDate DESC LIMIT 10")->fetchAll();
echo json_encode($res);