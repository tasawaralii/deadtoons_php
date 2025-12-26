<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    require 'db.php';
    
    $comment = $_POST['comment'];
    $comment = trim($comment);
    
    if($comment == "") {
        
        $slug = $_POST['post_slug'].'#comments';
    
        header("Location: /$slug");
        return;
    }
    
    $author = $_POST['author'];
    $email = $_POST['email'];
    $url = $_POST['url'];
    $post_id = $_POST['comment_post_ID'];
    $parent_id = $_POST['comment_parent'];

    if (isset($_POST['wp-comment-cookies-consent'])) {
        setcookie("dead_comment", json_encode(['author' => $author, 'email' => $email, 'url' => $url]), time() + (86400 * 30), "/");
    }
    
$spam_terms = ['https://', 'http://', 'url'];
$status = '1';

// Check for spam terms
foreach ($spam_terms as $spam) {
    if (strpos($comment, $spam) !== false) {
        $status = 'pending';
        break;
    }
}

    if(strpos($email,"testing-your-form.info") != false) {
    echo "Nice Try Didi";
    exit;
}

if (preg_match('/[А-Яа-яЁё]/u', $comment)) {

    echo "You are Doing Spam";
    exit;
}

    
    $double = $pdo->query("SELECT com_id FROM comments WHERE post_id = $post_id AND com_author = '$author' AND com_email = '$email' AND com_content = '$comment'")->fetch();
    
    
    if(!$double) {
        
        
        $sql = $pdo->prepare("INSERT INTO comments (post_id, parent_id, com_author, com_email, com_author_url, com_date, com_content, com_status)
                              VALUES (:post_id, :parent_id, :author, :email, :url, NOW(), :comment, :status)");
        $sql->bindParam(':post_id', $post_id);
        $sql->bindParam(':parent_id', $parent_id);
        $sql->bindParam(':author', $author);
        $sql->bindParam(':email', $email);
        $sql->bindParam(':url', $url);
        $sql->bindParam(':comment', $comment);
        $sql->bindParam(':status', $status);
    
        $sql->execute();
        
        $inserted_comment_id = $pdo->lastInsertId();
        
    } else {
        
        $inserted_comment_id = $double['com_id'];

    }
    
    $slug = $_POST['post_slug'].'#comment-'.$inserted_comment_id;
    
    header("Location: /$slug");
}

?>
