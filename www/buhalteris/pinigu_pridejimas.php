<?php
require_once __DIR__ . '/../../konfiguracija/nustatymai.php';
require_once __DIR__ . '/../../konfiguracija/bibliotekos/auth.php';
reikalauti_vaidmens('buhalteris');
$c = db();

$id = (int)($_GET['id'] ?? 0);
if ($id<=0) { header("Location: naudotojai.php"); exit; }

$res = $c->query("SELECT `id`,`prisijungimo_vardas`,`paštas`,`pinigai` FROM `naudotojas` WHERE `id`=$id LIMIT 1");
$u = $res ? $res->fetch_assoc() : null;
if (!$u) { header("Location: naudotojai.php"); exit; }

$zinute=''; $klaida='';

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $suma = (float)($_POST['suma'] ?? 0);
    if ($suma<=0) {
        $klaida = "Suma turi būti teigiama.";
    } else {
        $ok = $c->query("UPDATE `naudotojas` SET `pinigai`=`pinigai`+$suma WHERE `id`=$id");
        if ($ok) {
            $zinute = "Pridėta ".number_format($suma,2)." €.";
            // pakartotinai paimame naują pinigų likutį
            $res = $c->query("SELECT `pinigai` FROM `naudotojas` WHERE `id`=$id");
            if ($res) { $tmp = $res->fetch_assoc(); $u['pinigai'] = $tmp['pinigai']; }
        } else {
            $klaida = "Nepavyko atnaujinti.";
        }
    }
}

require_once __DIR__ . '/../bendra/header.php';
?>
<h2>Pridėti pinigų naudotojui</h2>
<div class="kortele">
  <b>ID:</b> <?= (int)$u['id'] ?><br>
  <b>Vardas:</b> <?= h($u['prisijungimo_vardas']) ?><br>
  <b>El. paštas:</b> <?= h($u['paštas']) ?><br>
  <b>Dabartiniai pinigai:</b> <?= number_format((float)$u['pinigai'],2) ?> €<br>
</div>

<?php if ($zinute): ?><div class="msg"><?= h($zinute) ?></div><?php endif; ?>
<?php if ($klaida):  ?><div class="err"><?= h($klaida)  ?></div><?php endif; ?>

<form method="post">
  Suma (€): <input type="number" step="0.01" min="0.01" name="suma" required>
  <button type="submit">Pridėti</button>
</form>

<p><a href="naudotojai.php">Grįžti</a></p>
<?php require_once __DIR__ . '/../bendra/footer.php'; ?>
