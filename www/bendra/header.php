<?php
// www/bendra/header.php
require_once __DIR__ . '/../../konfiguracija/bibliotekos/auth.php';
require_once __DIR__ . '/../../konfiguracija/nustatymai.php';
// Redirect unauthenticated users to prisijungti.php 
$currentScript = basename($_SERVER['PHP_SELF']);
if (!prisijunges() && $currentScript !== 'prisijungti.php') {
  // If we're in a subfolder like /vadybininkas/ add ../ so redirect points to root/prisijungti.php
  $prefix = (strpos($_SERVER['PHP_SELF'], '/vadybininkas/') !== false || strpos($_SERVER['PHP_SELF'], '/buhalteris/') !== false) ? '../' : '';
  header('Location: ' . $prefix . 'prisijungti.php');
  exit;
}
$pinigu_mygtuko_tekstas = 'Rodyti pinigus';
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
  
  <a href="<?= (strpos($_SERVER['PHP_SELF'], '/vadybininkas/') !== false || strpos($_SERVER['PHP_SELF'], '/buhalteris/') !== false) ? '../' : '' ?>zinutes.php">Žinutės</a>
  
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

  <?php if (!empty($_SESSION['naudotojas_id'])): ?>
  
  <?php
  if (isset($_POST['lesos'])) {
      $_SESSION['rodyti_pinigus'] = !($_SESSION['rodyti_pinigus'] ?? false);
  }
  
  $rodyti = $_SESSION['rodyti_pinigus'] ?? false;
  $button_text = $rodyti ? 'Slėpti pinigus' : 'Rodyti pinigus';
  ?>

  <form method="POST">
    <button type="submit" name="lesos" value="show">
        <?php echo $button_text; ?>
    </button>
  </form>
  <?php

  if (isset($_POST['lesos'])) {
      if ($button_text === 'Slėpti pinigus') {
        $c = db();
        
        $naud_id = !empty($_SESSION['naudotojas_id']) ? (int)$_SESSION['naudotojas_id'] : 'NULL';
        if ($naud_id !== 'NULL') {
          $lesu_sql = $c->query("SELECT `pinigai` 
                                 FROM `naudotojas`
                                 WHERE `id` = $naud_id
                                 LIMIT 1")->fetch_assoc();
          if ($lesu_sql){
          echo "<a>Jūsų pinigai: " . number_format((float)$lesu_sql['pinigai'], 2) . " €</a>";
          }
          else {
              echo "<a>Klaida gaunant pinigus.</a>";
          }
        }
      } else {
        echo "<a>Pinigai paslėpti.</a>";
      }
  }

  ?>
  <?php endif; ?>
</nav>

<hr>
