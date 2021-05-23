<?php

// Auslesen des aktullen Verzeichnisses (c:\xampp\htdocs\blog)
$currentDirectory = __DIR__;

define('BASE_URL', 'http://localhost/blog/');
define('BASE_PATH', $currentDirectory);
define('APP_TITLE', 'Blog');

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'blog');
define('DB_CHARSET', 'utf8mb4');

define('MAX_FILESIZE', 2*1024*1024);
define('ALLOWED_FILE_TYPES', ['pdf', 'txt', 'jpg', 'jpeg', 'png', 'gif']);
define('IMAGE_FILE_TYPES', ['jpg', 'jpeg', 'png', 'gif']);

require_once BASE_PATH . '/inc/functions.php';