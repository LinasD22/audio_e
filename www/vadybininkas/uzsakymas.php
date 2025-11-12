<?php
require_once __DIR__ . '/../../konfiguracija/nustatymai.php';
require_once __DIR__ . '/../../konfiguracija/bibliotekos/auth.php';
require_once __DIR__ . '/../../konfiguracija/bibliotekos/el_pastas.php';
reikalauti_vaidmens('vadybininkas');
$c = db();

$uzid = (int)($_GET['id'] ?? 0);
if ($uzid<=0) { header("Location: uzsakymai.php"); exit; }

// Užsakymas + vartotojas
$sql = "SELECT u.*, n.`prisijungimo_vardas`, n.`paštas`, n.`pinigai`
        FROM `užsakymas` u
        JOIN `naudotojas` n ON n.`id`=u.`naudotojo_id`
        WHERE u.`id`=$uzid LIMIT 1";
$res = $c->query($sql);
$uz = $res ? $res->fetch_assoc() : null;
if (!$uz) { header("Location: uzsakymai.php"); exit; }

// Užsakymo prekės
$res = $c->query("SELECT up.`prekės_id`, up.`kaina`, p.`pavadinimas`, p.`likutis`, up.`kiekis`
                  FROM `užsakymo_prekė` up
                  JOIN `prekė` p ON p.`id`=up.`prekės_id`
                  WHERE up.`užsakymo_id`=$uzid");
$items = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

// Patikrinimai
$pakanka_likučio = true;
foreach ($items as $it) {
    $pid = (int)$it['prekės_id'];
    $kiekis = (int)$it['kiekis'];
    $likutis = (int)$it['likutis'];
    
    // Get reserved quantities for this product (active reservations only, excluding current order)
    $res_check = $c->query("SELECT COALESCE(SUM(up.`kiekis`), 0) as `rezervuotas`
                            FROM `užsakymo_prekė` up
                            JOIN `užsakymas` u ON u.`id`=up.`užsakymo_id`
                            WHERE up.`prekės_id`=$pid 
                            AND u.`būsena`='rezervuotos_prekės'
                            AND u.`id`!=$uzid");
    
    $res_row = $res_check ? $res_check->fetch_assoc() : ['rezervuotas' => 0];
    $reserved_qty = (int)$res_row['rezervuotas'];
    
    // Available = Total likutis - Reserved qty
    $available = $likutis - $reserved_qty;
    
    if ($available < $kiekis) {
        $pakanka_likučio = false;
        break;
    }
}
$pakanka_pinigų = ((float)$uz['pinigai'] >= (float)$uz['suma']);

$zinute = '';
$klaida = '';

// Veiksmas: patvirtinti (įvykdyti pardavimą) ARBA rezervuoti savaitei
if ($_SERVER['REQUEST_METHOD']==='POST') {
    if (isset($_POST['patvirtinti'])) {
        if (!$pakanka_likučio) $klaida = "Sandėlyje trūksta prekių.";
        elseif (!$pakanka_pinigų) $klaida = "Naudotojui trūksta lėšų.";
        else {
            $c->autocommit(false);
            $ok = true;

            $uid = (int)$uz['naudotojo_id'];
            $suma = (float)$uz['suma'];
            $ok = $ok && $c->query("UPDATE `naudotojas` SET `pinigai`=`pinigai`-$suma WHERE `id`=$uid");

            // Nurašom kiekį
            foreach ($items as $it) {
                $pid = (int)$it['prekės_id'];
                $kiekis = (int)$it['kiekis'];
                $ok = $ok && $c->query("UPDATE `prekė` SET `likutis`=`likutis`-$kiekis WHERE `id`=$pid AND `likutis`>=$kiekis");
            }

            // 3) Užsakymo būsena
            $ok = $ok && $c->query("UPDATE `užsakymas` SET `būsena`='įvykdytas', `rezervacijos_galiojimo_data`=NULL WHERE `id`=$uzid");

            if ($ok) {
                $c->commit();
                $c->autocommit(true);
                $zinute = "Užsakymas įvykdytas. Išsiunčiamas el. laiškas...";
                // El. laiškas apie įvykdymą
                @siusti_uzsakymo_pranesima($uz['paštas'], $uzid, 'įvykdytas');
                // Reload, kad atsinaujintų rodoma info
                header("Refresh: 1; url=uzsakymas.php?id=$uzid");
            } else {
                $c->rollback();
                $c->autocommit(true);
                $klaida = "Nepavyko įvykdyti užsakymo (BD sandoris atmestas).";
            }
        }
    }

    if (isset($_POST['rezervuoti'])) {
        // Rezervacija savaitei (būsena -> rezervuotos_prekės)
        $ok = $c->query("UPDATE `užsakymas` SET `būsena`='rezervuotos_prekės', `rezervacijos_galiojimo_data`=DATE_ADD(NOW(), INTERVAL 7 DAY) WHERE `id`=$uzid");
        if ($ok) {
            $zinute = "Užsakymas rezervuotas savaitei.";
            header("Refresh: 1; url=uzsakymas.php?id=$uzid");
        } else {
            $klaida = "Nepavyko rezervuoti.";
        }
    }
}

require_once __DIR__ . '/../bendra/header.php';
?>
<h2>Užsakymas #<?= (int)$uz['id'] ?></h2>

<?php if ($zinute): ?><div class="msg"><?= h($zinute) ?></div><?php endif; ?>
<?php if ($klaida):  ?><div class="err"><?= h($klaida)  ?></div><?php endif; ?>

<div class="kortele">
  <b>Vartotojas:</b> <?= h($uz['prisijungimo_vardas']) ?><br>
  <b>El. paštas:</b> <?= h($uz['paštas']) ?><br>
  <b>Vartotojo pinigai:</b> <?= number_format((float)$uz['pinigai'],2) ?> €<br>
  <b>Būsena:</b> <?= h($uz['būsena']) ?><br>
  <b>Suma:</b> <?= number_format((float)$uz['suma'],2) ?> €<br>
  <b>Sukurta:</b> <?= h($uz['sukūrimo_data']) ?><br>
  <b>Rezervacijos pabaiga:</b> <?= h($uz['rezervacijos_galiojimo_data'] ?? '') ?><br>
</div>

<h3>Prekės užsakyme</h3>
<table>
  <tr><th>Pavadinimas</th><th>Kiekis</th><th>Kaina (vnt.)</th><th>Suma</th><th>Likutis</th></tr>
  <?php foreach ($items as $it): ?>
    <tr>
      <td><?= h($it['pavadinimas']) ?></td>
      <td><?= (int)$it['kiekis'] ?></td>
      <td><?= number_format((float)$it['kaina'],2) ?> €</td>
      <td><?= number_format((float)$it['kaina']*(int)$it['kiekis'],2) ?> €</td>
      <td><?= (int)$it['likutis'] ?></td>
    </tr>
  <?php endforeach; ?>
</table>

<form method="post">
  <?php if ($uz['būsena']!=='įvykdytas'): ?>
    <?php if ($pakanka_likučio && $pakanka_pinigų): ?>
      <button type="submit" name="patvirtinti">Įvykdyti pardavimą</button>
    <?php else: ?>
      <?php if (!$pakanka_likučio): ?>
        <div class="err">Neužtenka likučio sandėlyje.</div>
      <?php endif; ?>
      <?php if (!$pakanka_pinigų): ?>
        <div class="err">Naudotojui trūksta lėšų.</div>
      <?php endif; ?>
    <?php endif; ?>
    <button type="submit" name="rezervuoti">Rezervuoti prekę</button>
  <?php else: ?>
    <div class="msg">Užsakymas jau įvykdytas.</div>
  <?php endif; ?>
</form>

<p><a href="uzsakymai.php">Grįžti į sąrašą</a></p>
<?php require_once __DIR__ . '/../bendra/footer.php'; ?>
