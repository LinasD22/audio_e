<?php
session_start();
require_once __DIR__ . '/../konfiguracija/nustatymai.php';

// Prisijungimas prie DB (paprastas MySQLi)
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Nepavyko prisijungti prie DB: " . $conn->connect_error);
}

$zinute = '';
$klaida = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vardas = trim($_POST['vardas'] ?? '');
    $pastas = trim($_POST['pastas'] ?? '');
    $slaptazodis = $_POST['slaptazodis'] ?? '';
    $slaptazodis2 = $_POST['slaptazodis2'] ?? '';

    // Paprasta validacija
    if ($vardas === '' || $pastas === '' || $slaptazodis === '') {
        $klaida = "Užpildykite visus laukus.";
    } elseif ($slaptazodis !== $slaptazodis2) {
        $klaida = "Slaptažodžiai nesutampa.";
    } else {
        // Patikriname ar el. paštas jau naudojamas
        $pastas_esc = $conn->real_escape_string($pastas);
        $res = $conn->query("SELECT id FROM naudotojas WHERE paštas='$pastas_esc'");
        if ($res && $res->num_rows > 0) {
            $klaida = "Toks el. paštas jau registruotas.";
        } else {
            $hash = password_hash($slaptazodis, PASSWORD_DEFAULT);
            $vardas_esc = $conn->real_escape_string($vardas);

            $sql = "INSERT INTO naudotojas (prisijungimo_vardas, paštas, slaptažodis, rolė, pinigai)
                    VALUES ('$vardas_esc', '$pastas_esc', '$hash', 'vartotojas', 0.00)";
            if ($conn->query($sql)) {
                $zinute = "Registracija sėkminga! Galite prisijungti.";
            } else {
                $klaida = "Klaida registruojant naudotoją: " . $conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="lt">
<head>
<meta charset="UTF-8">
<title>Registracija</title>
<style>
body { font-family: Arial; margin: 30px; background: #f9f9f9; }
form { background: #fff; padding: 20px; border-radius: 6px; width: 300px; }
label { display: block; margin-top: 10px; }
input[type=text], input[type=email], input[type=password] { width: 100%; padding: 5px; }
button { margin-top: 15px; padding: 6px 12px; }
.msg { color: green; }
.err { color: red; }
</style>
</head>
<body>

<h2>Naujo naudotojo registracija</h2>

<?php if ($zinute): ?><div class="msg"><?= htmlspecialchars($zinute) ?></div><?php endif; ?>
<?php if ($klaida): ?><div class="err"><?= htmlspecialchars($klaida) ?></div><?php endif; ?>

<form method="post">
    <label>Prisijungimo vardas:</label>
    <input type="text" name="vardas" required>

    <label>El. paštas:</label>
    <input type="email" name="pastas" required>

    <label>Slaptažodis:</label>
    <input type="password" name="slaptazodis" required>

    <label>Pakartokite slaptažodį:</label>
    <input type="password" name="slaptazodis2" required>

    <button type="submit">Registruotis</button>
</form>

<p>Jau turite paskyrą? <a href="prisijungti.php">Prisijunkite čia</a>.</p>

</body>
</html>
