<?php
require_once __DIR__ . '/../../konfiguracija/nustatymai.php';
require_once __DIR__ . '/../../konfiguracija/bibliotekos/auth.php';
reikalauti_vaidmens('vadybininkas');
$c = db();

$res = $c->query("SELECT u.*, n.`prisijungimo_vardas`
                  FROM `užsakymas` u
                  JOIN `naudotojas` n ON n.`id`=u.`naudotojo_id`
                  ORDER BY u.`sukūrimo_data` DESC");
$uzs = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

require_once __DIR__ . '/../bendra/header.php';
?>
<h2>Vadybininko užsakymai</h2>
<table>
  <tr><th>ID</th><th>Vartotojas</th><th>Būsena</th><th>Suma</th><th>Sukurta</th><th>Veiksmai</th></tr>
  <?php foreach ($uzs as $u): ?>
    <tr>
      <td>#<?= (int)$u['id'] ?></td>
      <td><?= h($u['prisijungimo_vardas']) ?></td>
      <td><?= h($u['būsena']) ?></td>
      <td><?= number_format((float)$u['suma'],2) ?> €</td>
      <td><?= h($u['sukūrimo_data']) ?></td>
      <td><a href="uzsakymas.php?id=<?= (int)$u['id'] ?>">Peržiūrėti</a></td>
    </tr>
  <?php endforeach; ?>
</table>
<?php require_once __DIR__ . '/../bendra/footer.php'; ?>
