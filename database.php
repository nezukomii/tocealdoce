<?php
// database.php - Clase para manejar Cloudflare D1

class D1Database {
    private static function makeRequest($sql, $params = []) {
        $data = [
            'sql' => $sql,
            'params' => $params
        ];
        
        $options = [
            'http' => [
                'header' => [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . Config::getD1Token()
                ],
                'method' => 'POST',
                'content' => json_encode($data),
                'timeout' => 30
            ]
        ];
        
        $context = stream_context_create($options);
        $result = file_get_contents(Config::getD1Endpoint(), false, $context);
        
        if ($result === FALSE) {
            throw new Exception('Error connecting to D1 database');
        }
        
        $decoded = json_decode($result, true);
        
        if (!$decoded['success']) {
            throw new Exception('D1 Query failed: ' . ($decoded['errors'][0]['message'] ?? 'Unknown error'));
        }
        
        return $decoded;
    }
    
    public static function initDatabase() {
        $sql = "CREATE TABLE IF NOT EXISTS posts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            thread_id INTEGER DEFAULT 0,
            name TEXT DEFAULT 'Anonymous',
            email TEXT DEFAULT '',
            subject TEXT DEFAULT '',
            comment TEXT NOT NULL,
            image_url TEXT DEFAULT '',
            image_filename TEXT DEFAULT '',
            timestamp INTEGER NOT NULL,
            ip_hash TEXT NOT NULL
        )";
        
        return self::makeRequest($sql);
    }
    
    public static function createPost($data) {
        $sql = "INSERT INTO posts (thread_id, name, email, subject, comment, image_url, image_filename, timestamp, ip_hash) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $data['thread_id'] ?? 0,
            $data['name'] ?? 'Anonymous',
            $data['email'] ?? '',
            $data['subject'] ?? '',
            $data['comment'],
            $data['image_url'] ?? '',
            $data['image_filename'] ?? '',
            time(),
            md5($_SERVER['REMOTE_ADDR'] ?? 'unknown')
        ];
        
        return self::makeRequest($sql, $params);
    }
    
    public static function getThreads($limit = 10) {
        $sql = "SELECT * FROM posts WHERE thread_id = 0 ORDER BY timestamp DESC LIMIT ?";
        return self::makeRequest($sql, [$limit]);
    }
    
    public static function getThread($thread_id) {
        $sql = "SELECT * FROM posts WHERE id = ? OR thread_id = ? ORDER BY timestamp ASC";
        return self::makeRequest($sql, [$thread_id, $thread_id]);
    }
    
    public static function getPostCount() {
        $sql = "SELECT COUNT(*) as count FROM posts";
        return self::makeRequest($sql);
    }
}
?>
