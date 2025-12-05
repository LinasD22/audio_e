<?php
require_once __DIR__ . '/../konfiguracija/nustatymai.php';
require_once __DIR__ . '/../konfiguracija/bibliotekos/auth.php';
$c = db();

reikalauti_vaidmens('vartotojas');
// Užtikriname krepšelį
if (empty($_SESSION['krepselio_id'])) {
    $naud_id = !empty($_SESSION['naudotojas_id']) ? (int)$_SESSION['naudotojas_id'] : 'NULL';
    $c->query("INSERT INTO `krepšelis`(`naudotojo_id`) VALUES ($naud_id)");
    $_SESSION['krepselio_id'] = (int)$c->insert_id;
}
$kid = (int)$_SESSION['krepselio_id'];

$veiksmas = $_GET['veiksmas'] ?? '';
if ($veiksmas==='prideti' && isset($_GET['id'])) {
    $pid = (int)$_GET['id'];
    // Jei jau yra — didinam kiekį
    $c->query("INSERT INTO `krepšelio_prekė`(`krepšelio_id`,`prekės_id`,`kiekis`)
               VALUES ($kid,$pid,1)
               ON DUPLICATE KEY UPDATE `kiekis`=`kiekis`+1");
    header("Location: krepselis.php");
    exit;
}
if ($veiksmas==='mazinti' && isset($_GET['id'])) {
    $pid = (int)$_GET['id'];
    $c->query("UPDATE `krepšelio_prekė` SET `kiekis`=`kiekis`-1 WHERE `krepšelio_id`=$kid AND `prekės_id`=$pid AND `kiekis`>1");
    header("Location: krepselis.php");
    exit;
}
if ($veiksmas==='pasalinti' && isset($_GET['id'])) {
    $pid = (int)$_GET['id'];
    $c->query("DELETE FROM `krepšelio_prekė` WHERE `krepšelio_id`=$kid AND `prekės_id`=$pid");
    header("Location: krepselis.php");
    exit;
}

// Užsakymo pateikimas
$zinute = ''; $klaida = '';
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['pateikti_uzsakyma'])) {
    reikalauti_prisijungimo();
    $res = $c->query("SELECT p.`id`, p.`kaina`, kp.`kiekis`
                      FROM `krepšelio_prekė` kp
                      JOIN `prekė` p ON p.`id`=kp.`prekės_id`
                      WHERE kp.`krepšelio_id`=$kid");
    $items = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    if (!$items) {
        $klaida = "Krepšelis tuščias.";
    } else {
        $suma = 0.0;
        foreach ($items as $it) {
            $suma += (float)$it['kaina'] * (int)$it['kiekis'];
        }
        $uid = (int)$_SESSION['naudotojas_id'];
        $ok1 = $c->query("INSERT INTO `užsakymas`(`naudotojo_id`,`būsena`,`suma`) VALUES ($uid,'pateiktas',$suma)");
        if ($ok1) {
            $uzid = (int)$c->insert_id;
            foreach ($items as $it) {
                $pid = (int)$it['id'];
                $kaina = (float)$it['kaina'];
                $kiekis = (int)$it['kiekis'];
                $c->query("INSERT INTO `užsakymo_prekė`(`užsakymo_id`,`prekės_id`,`kaina`,`kiekis`)
                           VALUES ($uzid,$pid,$kaina,$kiekis)");
            }
            $c->query("DELETE FROM `krepšelio_prekė` WHERE `krepšelio_id`=$kid");
            $zinute = "Užsakymas pateiktas (ID: $uzid). Būsena: 'pateiktas'.";
        } else $klaida = "Nepavyko pateikti užsakymo.";
    }
}

// Rodyti krepšelį
$res = $c->query("SELECT p.*, kp.`kiekis`
                  FROM `krepšelio_prekė` kp
                  JOIN `prekė` p ON p.`id`=kp.`prekės_id`
                  WHERE kp.`krepšelio_id`=$kid");
$prekes = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

require_once __DIR__ . '/bendra/header.php';
?>
<h2>Krepšelis</h2>
<?php if ($zinute): ?><div class="msg"><?= h($zinute) ?></div><?php endif; ?>
<?php if ($klaida): ?><div class="err"><?= h($klaida) ?></div><?php endif; ?>

<?php if (!$prekes): ?>
  <div class="msg">Krepšelis tuščias. Eikite į <a href="prekes.php">Prekes</a>.</div>
<?php else: ?>
  <table>
    <tr><th>Pavadinimas</th><th>Kaina</th><th>Kiekis</th><th>Suma</th><th>Veiksmai</th></tr>
    <?php $viso=0; foreach ($prekes as $p): 
          $sum = (float)$p['kaina'] * (int)$p['kiekis']; $viso += $sum; ?>
      <tr>
        <td><?= h($p['pavadinimas']) ?></td>
        <td><?= number_format((float)$p['kaina'],2) ?> €</td>
        <td><?= (int)$p['kiekis'] ?></td>
        <td><?= number_format($sum,2) ?> €</td>
        <td>
          <a href="?veiksmas=prideti&id=<?= (int)$p['id'] ?>">+</a>
          <a href="?veiksmas=mazinti&id=<?= (int)$p['id'] ?>">−</a>
          <a href="?veiksmas=pasalinti&id=<?= (int)$p['id'] ?>">Šalinti</a>
        </td>
      </tr>
    <?php endforeach; ?>
    <tr><th colspan="3">Iš viso</th><th><?= number_format($viso,2) ?> €</th><th></th></tr>
  </table>
  <form method="post">
    <button type="submit" name="pateikti_uzsakyma" value="1">Pateikti užsakymą</button>
  </form>
<?php endif; ?>
<?php require_once __DIR__ . '/bendra/footer.php'; ?>
