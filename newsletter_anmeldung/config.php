<?php
// Import der Klassen (Namespaces) für PHPMailer benötigen
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Verweis auf die zu importierenden PHP Dateien
require_once 'lib/PHPMailer/src/Exception.php';
require_once 'lib/PHPMailer/src/PHPMailer.php';
require_once 'lib/PHPMailer/src/SMTP.php';

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'newsletter_anmeldung');

define('SMTP_HOST', '');
define('SMTP_AUTHENTICATION', true);
define('SMTP_USER', '');
define('SMTP_PASSWORD', '');
define('SMTP_ENCRYPTION', PHPMailer::ENCRYPTION_STARTTLS);
define('SMTP_PORT', 587);

define('EMAIL_FROM_NAME', '');
define('EMAIL_FROM_ADDRESS', '');

define('BASE_URL', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . "/newsletter_anmeldung/");