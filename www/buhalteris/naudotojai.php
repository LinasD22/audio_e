<?php
require_once __DIR__ . '/../../konfiguracija/nustatymai.php';
require_once __DIR__ . '/../../konfiguracija/bibliotekos/auth.php';
reikalauti_vaidmens('buhalteris');
$c = db();

$res = $c->query("SELECT `id`,`prisijungimo_vardas`,`paštas`,`pinigai`
                  FROM `naudotojas` WHERE `rolė`='vartotojas' ORDER BY `prisijungimo_vardas`");
$naud = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

require_once __DIR__ . '/../bendra/header.php';
?>
<h2>Buhalterio valdymas — vartotojai</h2>
<table>
  <tr><th>ID</th><th>Vardas</th><th>El. paštas</th><th>Pinigai</th><th>Veiksmas</th></tr>
  <?php foreach ($naud as $n): ?>
    <tr>
      <td><?= (int)$n['id'] ?></td>
      <td><?= h($n['prisijungimo_vardas']) ?></td>
      <td><?= h($n['paštas']) ?></td>
      <td><?= number_format((float)$n['pinigai'],2) ?> €</td>
      <td><a href="pinigu_pridejimas.php?id=<?= (int)$n['id'] ?>">Pridėti pinigų</a></td>
    </tr>
  <?php endforeach; ?>
</table>
<?php require_once __DIR__ . '/../bendra/footer.php'; ?>
