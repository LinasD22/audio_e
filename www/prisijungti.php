<?php
require_once __DIR__ . '/../konfiguracija/nustatymai.php';
require_once __DIR__ . '/../konfiguracija/bibliotekos/auth.php';

$klaida = '';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $vardas = trim($_POST['vardas'] ?? '');
    $slapt  = $_POST['slaptazodis'] ?? '';
    if ($vardas==='' || $slapt==='') {
        $klaida = 'Užpildykite visus laukus.';
    } else {
        if (prisijungti($vardas, $slapt)) {
            $gr = $_GET['grįžti'] ?? 'index.php';
            header("Location: $gr");
            exit;
        } else {
            $klaida = 'Neteisingi prisijungimo duomenys.';
        }
    }
}

require_once __DIR__ . '/bendra/header.php';
?>
<h2>Prisijungimas</h2>
<p>Neturite paskyros? <a href="registracija.php">Registruokitės čia</a></p>
<?php if ($klaida): ?><div class="err"><?= h($klaida) ?></div><?php endif; ?>
<form method="post">
  <div>Vartotojo vardas arba el. paštas:<br>
    <input name="vardas" value="<?= h($_POST['vardas'] ?? '') ?>" required>
  </div>
  <div>Slaptažodis:<br>
    <input type="password" name="slaptazodis" required>
  </div>
  <button type="submit">Prisijungti</button>
</form>
<?php require_once __DIR__ . '/bendra/footer.php'; ?>
