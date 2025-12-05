<?php
require_once __DIR__ . '/../konfiguracija/nustatymai.php';
require_once __DIR__ . '/../konfiguracija/bibliotekos/auth.php';
reikalauti_prisijungimo();
reikalauti_vaidmens('vartotojas');
$c = db();

$uid = (int)$_SESSION['naudotojas_id'];

// Get all messages for the current user
$res = $c->query("SELECT * FROM `žinutė` WHERE `naudotojo_id`=$uid ORDER BY `id` DESC");
$zinutes = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

require_once __DIR__ . '/bendra/header.php';
?>
<h2>Mano žinutės</h2>

<?php if (empty($zinutes)): ?>
  <div class="msg">Jūs neturite žinučių.</div>
<?php else: ?>
  <table>
    <tr><th>ID</th><th>Turinys</th></tr>
    <?php foreach ($zinutes as $z): ?>
      <tr>
        <td>#<?= (int)$z['id'] ?></td>
        <td><?= h($z['turinys']) ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
<?php endif; ?>

<p><a href="index.php">Grįžti į pradžią</a></p>
<?php require_once __DIR__ . '/bendra/footer.php'; ?>