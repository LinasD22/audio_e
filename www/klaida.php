<?php
require_once __DIR__ . '/../konfiguracija/nustatymai.php';
require_once __DIR__ . '/bendra/header.php';
$k = (int)($_GET['k'] ?? 404);
?>
<h2>Klaida</h2>
<?php if ($k===403): ?>
  <div class="err">Prieiga draudžiama (403). Neturite teisių.</div>
<?php else: ?>
  <div class="err">Puslapis nerastas (404).</div>
<?php endif; ?>
<?php require_once __DIR__ . '/bendra/footer.php'; ?>
