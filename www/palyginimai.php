<?php
require_once __DIR__ . '/../konfiguracija/nustatymai.php';
$c = db();
require_once __DIR__ . '/../konfiguracija/bibliotekos/auth.php';
reikalauti_vaidmens('vartotojas');
$ids = [];
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $ids = array_map('intval', $_POST['cmp'] ?? []);
} else {
    // leidžiame ir GET ?ids=1,2,3
    if (!empty($_GET['ids'])) {
        foreach (explode(',', $_GET['ids']) as $i) $ids[] = (int)$i;
    }
}

$prekes = [];
if ($ids) {
    $in = implode(',', $ids);
    $res = $c->query("SELECT * FROM `prekė` WHERE `id` IN ($in)");
    if ($res) $prekes = $res->fetch_all(MYSQLI_ASSOC);
}

require_once __DIR__ . '/bendra/header.php';
?>
<h2>Palyginimas</h2>
<?php if (!$prekes): ?>
  <div class="msg">Nepasirinkote prekių palyginimui.</div>
<?php else: ?>
<table>
  <tr>
    <th>Savybė</th>
    <?php foreach ($prekes as $p): ?>
      <th><?= h($p['pavadinimas']) ?></th>
    <?php endforeach; ?>
  </tr>
  <?php
  $laukeliai = ['pavadinimas','paskirtis','tipas','gamintojas','modelis','kaina','likutis'];
  foreach ($laukeliai as $l):
  ?>
    <tr>
      <td><?= h(ucfirst($l)) ?></td>
      <?php foreach ($prekes as $p): ?>
        <td>
          <?php
            if ($l==='kaina') echo number_format((float)$p['kaina'],2) . " €";
            else echo h((string)$p[$l]);
          ?>
        </td>
      <?php endforeach; ?>
    </tr>
  <?php endforeach; ?>
</table>
<?php endif; ?>
<?php require_once __DIR__ . '/bendra/footer.php'; ?>
