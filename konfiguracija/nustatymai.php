<?php
// konfiguracija/nustatymai.php
declare(strict_types=1);
// Start session only if none exists to avoid "session already active" notices
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define("DB_SERVER", "localhost");
define("DB_USER",   "stud");
define("DB_PASS",   "stud");
define("DB_NAME",   "audio_shop");

date_default_timezone_set('Europe/Vilnius');
mb_internal_encoding('UTF-8');

function db(): mysqli {
    static $conn = null; #sukurti viena karta
    if ($conn === null) {
        $conn = @new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            die("Nepavyko prisijungti prie DB: " . $conn->connect_error);
        }
        $conn->set_charset('utf8mb4');
        $conn->query("SET collation_connection = 'utf8mb4_lithuanian_ci'");
    }
    return $conn;
}

// specialus simboliai kaip string
function h(?string $s): string {
    return htmlspecialchars($s ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
