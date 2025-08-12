<?php
require_once 'config.php';
require_once 'database.php';
require_once 'imgbb.php';

// Inicializar base de datos
try {
    D1Database::initDatabase();
} catch (Exception $e) {
    die('Database initialization failed: ' . $e->getMessage());
}

// Procesar formulario
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    try {
        $comment = trim($_POST['comment'] ?? '');
        if (empty($comment)) {
            throw new Exception('Comment is required');
        }
        
        $post_data = [
            'name' => trim($_POST['name'] ?? '') ?: 'Anonymous',
            'email' => trim($_POST['email'] ?? ''),
            'subject' => trim($_POST['subject'] ?? ''),
            'comment' => $comment,
            'thread_id' => intval($_POST['thread_id'] ?? 0)
        ];
        
        // Manejar imagen si se subió
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_result = ImgBBUploader::uploadImage($_FILES['image']);
            $post_data['image_url'] = $upload_result['url'];
            $post_data['image_filename'] = $upload_result['filename'];
        }
        
        D1Database::createPost($post_data);
        $success = 'Post created successfully!';
        
        // Redirect para evitar repost
        header('Location: ' . $_SERVER['PHP_SELF'] . ($_GET['thread'] ? '?thread=' . $_GET['thread'] : ''));
        exit;
        
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Obtener threads
$thread_id = intval($_GET['thread'] ?? 0);
if ($thread_id > 0) {
    try {
        $posts_response = D1Database::getThread($thread_id);
        $posts = $posts_response['result'][0]['results'] ?? [];
    } catch (Exception $e) {
        $posts = [];
        $error = 'Error loading thread: ' . $e->getMessage();
    }
} else {
    try {
        $threads_response = D1Database::getThreads(15);
        $posts = $threads_response['result'][0]['results'] ?? [];
    } catch (Exception $e) {
        $posts = [];
        $error = 'Error loading threads: ' . $e->getMessage();
    }
}

function formatPost($post) {
    $comment = htmlspecialchars($post['comment']);
    $comment = nl2br($comment);
    
    // Greentext
    $comment = preg_replace('/^&gt;(.+)$/m', '<span style="color: #789922;">&gt;$1</span>', $comment);
    
    // Links
    $comment = preg_replace('/&gt;&gt;(\d+)/', '<a href="?thread=$1" style="color: #FF6600;">&gt;&gt;$1</a>', $comment);
    
    return $comment;
}

function timeAgo($timestamp) {
    $diff = time() - $timestamp;
    if ($diff < 60) return $diff . 's ago';
    if ($diff < 3600) return floor($diff / 60) . 'm ago';
    if ($diff < 86400) return floor($diff / 3600) . 'h ago';
    return floor($diff / 86400) . 'd ago';
}

include 'template.php';
?>
