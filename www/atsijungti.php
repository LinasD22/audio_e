<?php
require_once __DIR__ . '/../konfiguracija/nustatymai.php';
session_destroy();
header("Location: index.php");
exit;
