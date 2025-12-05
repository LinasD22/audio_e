<?php
require_once __DIR__ . '/../konfiguracija/nustatymai.php';
$c = db();
require_once __DIR__ . '/../konfiguracija/bibliotekos/auth.php';

$zinute = '';

// Handle "Pridėti į krepšelį" action
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['prideti'])) {
    $prekesId = (int)($_POST['prekesId'] ?? 0);
    if ($prekesId > 0) {
        // Ensure cart exists
        if (empty($_SESSION['krepselio_id'])) {
            $naud_id = !empty($_SESSION['naudotojas_id']) ? (int)$_SESSION['naudotojas_id'] : 'NULL';
            $c->query("INSERT INTO `krepšelis`(`naudotojo_id`) VALUES ($naud_id)");
            $_SESSION['krepselio_id'] = (int)$c->insert_id;
        }
        
        $krepselioId = (int)$_SESSION['krepselio_id'];
        
        // Check if item already in cart
        $check = $c->query("SELECT `kiekis` FROM `krepšelio_prekė` WHERE `krepšelio_id`=$krepselioId AND `prekės_id`=$prekesId");
        if ($check && $check->num_rows > 0) {
            // Update quantity
            $c->query("UPDATE `krepšelio_prekė` SET `kiekis`=`kiekis`+1 WHERE `krepšelio_id`=$krepselioId AND `prekės_id`=$prekesId");
        } else {
            // Insert new item
            $c->query("INSERT INTO `krepšelio_prekė`(`krepšelio_id`,`prekės_id`,`kiekis`) VALUES ($krepselioId, $prekesId, 1)");
        }
        $zinute = "Prekė pridėta į krepšelį!";
    }
}

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

<?php if ($zinute): ?><div class="msg"><?= h($zinute) ?></div><?php endif; ?>

<form method="get">
  Paskirtis: <input name="paskirtis" value="<?= h($paskirtis) ?>">
  Tipas: <input name="tipas" value="<?= h($tipas) ?>">
  Gamintojas: <input name="gamintojas" value="<?= h($gam) ?>">
  Modelis: <input name="modelis" value="<?= h($modelis) ?>">
  Kaina iki: <input type="number" step="0.01" name="kaina_max" value="<?= h($kaina_max) ?>">
  <button type="submit">Ieškoti</button>
</form>

<table>
  <tr><th>Pavadinimas</th><th>Kaina</th><th>Likutis</th><th>Palyginti</th><th>Krepšelis</th></tr>
  <?php foreach ($prekes as $p): ?>
    <tr>
      <td><?= h($p['pavadinimas']) ?></td>
      <td><?= number_format((float)$p['kaina'],2) ?> €</td>
      <td><?= (int)$p['likutis'] ?></td>
      <td><input type="checkbox" name="cmp[]" value="<?= (int)$p['id'] ?>" form="palyginimas-form"></td>
      <td>
        <form method="post" action="" style="display:inline;">
          <input type="hidden" name="prekesId" value="<?= (int)$p['id'] ?>">
          <button type="submit" name="prideti">Pridėti</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
</table>

<form method="post" action="palyginimai.php" id="palyginimas-form">
  <button type="submit">Rodyti palyginimą</button>
</form>
<?php require_once __DIR__ . '/bendra/footer.php'; ?>
