<?php
// www/bendra/header.php
// Guard: redirect unauthenticated users to prisijungti.php (except that page itself)
require_once __DIR__ . '/../../konfiguracija/bibliotekos/auth.php';

$currentScript = basename($_SERVER['PHP_SELF']);
if (!prisijunges() && $currentScript !== 'prisijungti.php') {
  // If we're in a subfolder like /vadybininkas/ add ../ so redirect points to root/prisijungti.php
  $prefix = (strpos($_SERVER['PHP_SELF'], '/vadybininkas/') !== false || strpos($_SERVER['PHP_SELF'], '/buhalteris/') !== false) ? '../' : '';
  header('Location: ' . $prefix . 'prisijungti.php');
  exit;
}

?>
<!doctype html>
<html lang="lt">
<head>
<meta charset="utf-8">
<title>Audio aparatūros parduotuvių tinklas</title>
<style>

body { font-family: Arial, sans-serif; margin:20px; }
nav a { margin-right:10px; }
table { border-collapse: collapse; }
td, th { border:1px solid #ccc; padding:6px 8px; }
input, select, button { padding:4px 6px; margin:3px 0; }
.kortele { border:1px solid #ddd; padding:10px; margin:8px 0; }
.msg { padding:8px; margin:8px 0; background:#eef; }
.err { padding:8px; margin:8px 0; background:#fee; }

</style>
</head>
<body>
<!-- absolutines path -->
<nav>
  <a href="<?= (strpos($_SERVER['PHP_SELF'], '/vadybininkas/') !== false || strpos($_SERVER['PHP_SELF'], '/buhalteris/') !== false) ? '../' : '' ?>index.php">Pradžia</a>

  <a href="<?= (strpos($_SERVER['PHP_SELF'], '/vadybininkas/') !== false || strpos($_SERVER['PHP_SELF'], '/buhalteris/') !== false) ? '../' : '' ?>prekes.php">Prekės</a>

  <a href="<?= (strpos($_SERVER['PHP_SELF'], '/vadybininkas/') !== false || strpos($_SERVER['PHP_SELF'], '/buhalteris/') !== false) ? '../' : '' ?>palyginimai.php">Palyginimai</a>

  <a href="<?= (strpos($_SERVER['PHP_SELF'], '/vadybininkas/') !== false || strpos($_SERVER['PHP_SELF'], '/buhalteris/') !== false) ? '../' : '' ?>krepselis.php">Krepšelis</a>

  <a href="<?= (strpos($_SERVER['PHP_SELF'], '/vadybininkas/') !== false || strpos($_SERVER['PHP_SELF'], '/buhalteris/') !== false) ? '../' : '' ?>uzsakymo_busenos.php">Mano užsakymai</a>

  <?php if (!empty($_SESSION['rola']) && $_SESSION['rola']==='vadybininkas'): ?>
    <a href="<?= (strpos($_SERVER['PHP_SELF'], '/vadybininkas/') !== false) ? '' : 'vadybininkas/' ?>uzsakymai.php">Vadybininko užsakymai</a>
  <?php endif; ?>

  <?php if (!empty($_SESSION['rola']) && $_SESSION['rola']==='buhalteris'): ?>
    <a href="<?= (strpos($_SERVER['PHP_SELF'], '/buhalteris/') !== false) ? '' : 'buhalteris/' ?>naudotojai.php">Buhalterio valdymas</a>
  <?php endif; ?>

  <?php if (!empty($_SESSION['naudotojas_id'])): ?>
    <a href="<?= (strpos($_SERVER['PHP_SELF'], '/vadybininkas/') !== false || strpos($_SERVER['PHP_SELF'], '/buhalteris/') !== false) ? '../' : '' ?>atsijungti.php">Atsijungti (<?= h($_SESSION['vardas']??'') ?>)</a>
  <?php else: ?>
    <a href="<?= (strpos($_SERVER['PHP_SELF'], '/vadybininkas/') !== false || strpos($_SERVER['PHP_SELF'], '/buhalteris/') !== false) ? '../' : '' ?>prisijungti.php">Prisijungti</a>
  <?php endif; ?>

</nav>

<hr>
