
<?php
require_once __DIR__ . '/../konfiguracija/nustatymai.php';
require_once __DIR__ . '/../konfiguracija/bibliotekos/auth.php';
reikalauti_prisijungimo();
reikalauti_vaidmens('vartotojas');
$c = db();

$uid = (int)$_SESSION['naudotojas_id'];
$uzsakymo_id = $_GET['id'] ?? null;

require_once __DIR__ . '/bendra/header.php';

?>

<?php
$uzsakymo_duomenys = $c->query("SELECT * FROM `užsakymas`
INNER JOIN `užsakymo_prekė` ON `užsakymas`.`id`=`užsakymo_prekė`.`užsakymo_id`
INNER JOIN `prekė` ON `užsakymo_prekė`.`prekės_id`=`prekė`.`id`
WHERE `užsakymas`.`id`=$uzsakymo_id AND `užsakymas`.`naudotojo_id`=$uid");

if ($uzsakymo_duomenys && $uzsakymo_duomenys->num_rows > 0) {
      $rows = $uzsakymo_duomenys->fetch_all(MYSQLI_ASSOC);
      $first = $rows[0];
      echo "<h2>Užsakymas #".(int)$first['id']."</h2>";
      echo "<p>Būsena: ".h($first['būsena'])."</p>";
      echo "<p>Suma: ".number_format((float)$first['suma'], 2)." €</p>";
      echo "<p>Sukurta: ".h($first['sukūrimo_data'])."</p>";
      echo "<p>Rezervacijos pabaiga: ".h($first['rezervacijos_galiojimo_data'] ?? '')."</p>";
      echo "<h3>Prekės:</h3>";
      echo "<ul>";
         
      foreach ($rows as $duom) {
            echo "<li>".h($duom['pavadinimas']).
                  " - Kiekis: ".(int)$duom['kiekis'].
                  ", Kaina: ".number_format((float)$duom['kaina'], 2)." €</li>";
         }
      echo "</ul>";
}
else {
      echo "<div class='msg'>Užsakymas nerastas arba neturite teisės jį peržiūrėti.</div>";
}


?>