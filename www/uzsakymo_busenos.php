<?php
require_once __DIR__ . '/../konfiguracija/nustatymai.php';
require_once __DIR__ . '/../konfiguracija/bibliotekos/auth.php';
reikalauti_prisijungimo();
$c = db();
$uid = (int)$_SESSION['naudotojas_id'];

$res = $c->query("SELECT * FROM `užsakymas` WHERE `naudotojo_id`=$uid ORDER BY `sukūrimo_data` DESC");
$uzs = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

require_once __DIR__ . '/bendra/header.php';
?>
<h2>Mano užsakymai</h2>
<?php if (!$uzs): ?>
  <div class="msg">Neturite užsakymų.</div>
<?php else: ?>
  <table>
    <tr><th>ID</th><th>Būsena</th><th>Suma</th><th>Sukurta</th><th>Rezervacijos pabaiga</th></tr>
    <?php foreach ($uzs as $u): ?>
      <tr>
        <td>#<?= (int)$u['id'] ?></td>
        <td><?= h($u['būsena']) ?></td>
        <td><?= number_format((float)$u['suma'],2) ?> €</td>
        <td><?= h($u['sukūrimo_data']) ?></td>
        <td><?= h($u['rezervacijos_galiojimo_data'] ?? '') ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
<?php endif; ?>
<?php require_once __DIR__ . '/bendra/footer.php'; ?>
