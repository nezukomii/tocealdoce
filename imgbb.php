<?php
// imgbb.php - Clase para manejar ImgBB uploads

class ImgBBUploader {
    public static function uploadImage($file) {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            throw new Exception('No file uploaded');
        }
        
        if ($file['size'] > Config::MAX_FILE_SIZE) {
            throw new Exception('File too large (max 5MB)');
        }
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Upload error: ' . $file['error']);
        }
        
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/jpg'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($file_type, $allowed_types)) {
            throw new Exception('Invalid file type. Only JPG, PNG, GIF, WebP allowed.');
        }
        
        $image_data = base64_encode(file_get_contents($file['tmp_name']));
        
        $data = [
            'key' => Config::getImgBBKey(),
            'image' => $image_data,
            'name' => pathinfo($file['name'], PATHINFO_FILENAME)
        ];
        
        $options = [
            'http' => [
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'method' => 'POST',
                'content' => http_build_query($data),
                'timeout' => 60
            ]
        ];
        
        $context = stream_context_create($options);
        $result = file_get_contents(Config::IMGBB_ENDPOINT, false, $context);
        
        if ($result === FALSE) {
            throw new Exception('Error uploading to ImgBB - Check your API key');
        }
        
        $response = json_decode($result, true);
        
        if (!$response || !$response['success']) {
            $error_msg = 'ImgBB upload failed';
            if (isset($response['error']['message'])) {
                $error_msg .= ': ' . $response['error']['message'];
            }
            throw new Exception($error_msg);
        }
        
        return [
            'url' => $response['data']['url'],
            'filename' => $file['name']
        ];
    }
    
    public static function validateImageFile($file) {
        $errors = [];
        
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            $errors[] = 'No file uploaded';
            return $errors;
        }
        
        if ($file['size'] > Config::MAX_FILE_SIZE) {
            $errors[] = 'File too large (max 5MB)';
        }
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Upload error code: ' . $file['error'];
        }
        
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($file_type, $allowed_types)) {
            $errors[] = 'Invalid file type. Only JPG, PNG, GIF, WebP allowed.';
        }
        
        return $errors;
    }
}
?>
