<?php
// config.php - Configuración principal

class Config {
    // Cloudflare D1 Database - CAMBIAR ESTOS VALORES
    const D1_ENDPOINT = 'https://api.cloudflare.com/client/v4/accounts/29715a4edbff4e4241d11479cf326854/d1/database/c5541534-de10-4092-84d4-6266b84141b6/query';
    const D1_TOKEN = 'h-7pyVN7lUAMCSgVMI_rudNggpOl1jSCuEJ0yFUa'; // Bearer token para D1
    
    // ImgBB API - CAMBIAR ESTE VALOR
    const IMGBB_API_KEY = 'a829ef97aa2f2e24d7871d6b3ef0b52e';
    const IMGBB_ENDPOINT = 'https://api.imgbb.com/1/upload';
    
    // Configuración del sitio
    const MAX_FILE_SIZE = 5242880; // 5MB
    const BOARD_NAME = '/b/ - Random';
    const SITE_NAME = 'AnonChan';
    
    // Obtener configuración desde variables de entorno (Railway)
    public static function getD1Endpoint() {
        return $_ENV['D1_ENDPOINT'] ?? self::D1_ENDPOINT;
    }
    
    public static function getD1Token() {
        return $_ENV['D1_TOKEN'] ?? self::D1_TOKEN;
    }
    
    public static function getImgBBKey() {
        return $_ENV['IMGBB_API_KEY'] ?? self::IMGBB_API_KEY;
    }
}
?>
