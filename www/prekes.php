<?php
require_once __DIR__ . '/../konfiguracija/nustatymai.php';
$c = db();

// Filtrai (paprasti, su LIKE ir <= kainai)
$paskirtis = trim($_GET['paskirtis'] ?? '');
$tipas     = trim($_GET['tipas'] ?? '');
$gam      = trim($_GET['gamintojas'] ?? '');
$modelis   = trim($_GET['modelis'] ?? '');
$kaina_max = trim($_GET['kaina_max'] ?? '');

$sql = "SELECT * FROM `prekė` WHERE 1=1";
if ($paskirtis!=='') $sql .= " AND `paskirtis` LIKE '%".$c->real_escape_string($paskirtis)."%'";
if ($tipas!=='')     $sql .= " AND `tipas` LIKE '%".$c->real_escape_string($tipas)."%'";
if ($gam!=='')       $sql .= " AND `gamintojas` LIKE '%".$c->real_escape_string($gam)."%'";
if ($modelis!=='')   $sql .= " AND `modelis` LIKE '%".$c->real_escape_string($modelis)."%'";
if ($kaina_max!=='') $sql .= " AND `kaina` <= ".(float)$kaina_max."";

$res = $c->query($sql);
$prekes = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

require_once __DIR__ . '/bendra/header.php';
?>
<h2>Prekės (paieška ir palyginimas)</h2>
<form method="get">
  Paskirtis: <input name="paskirtis" value="<?= h($paskirtis) ?>">
  Tipas: <input name="tipas" value="<?= h($tipas) ?>">
  Gamintojas: <input name="gamintojas" value="<?= h($gam) ?>">
  Modelis: <input name="modelis" value="<?= h($modelis) ?>">
  Kaina iki: <input type="number" step="0.01" name="kaina_max" value="<?= h($kaina_max) ?>">
  <button type="submit">Ieškoti</button>
</form>

<form method="post" action="palyginimai.php">
<table>
  <tr><th>Pavadinimas</th><th>Kaina</th><th>Likutis</th><th>Palyginti</th><th>Krepšelis</th></tr>
  <?php foreach ($prekes as $p): ?>
    <tr>
      <td><?= h($p['pavadinimas']) ?></td>
      <td><?= number_format((float)$p['kaina'],2) ?> €</td>
      <td><?= (int)$p['likutis'] ?></td>
      <td><input type="checkbox" name="cmp[]" value="<?= (int)$p['id'] ?>"></td>
      <td><a href="krepselis.php?veiksmas=prideti&id=<?= (int)$p['id'] ?>">Pridėti</a></td>
    </tr>
  <?php endforeach; ?>
</table>
<button type="submit">Rodyti palyginimą</button>
</form>
<?php require_once __DIR__ . '/bendra/footer.php'; ?>
