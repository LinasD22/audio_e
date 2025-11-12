<?php
// konfiguracija/bibliotekos/auth.php
declare(strict_types=1); # Turi atitikti tipus
require_once __DIR__ . '/../nustatymai.php';

function prisijungti(string $vardas, string $slaptazodis): bool {
    $c = db();
    $saugus_vardas = $c->real_escape_string($vardas); #neleisti SQL injekciju
    $sql = "SELECT `id`,`prisijungimo_vardas`,`paštas`,`slaptažodis`,`rolė`,`pinigai`
            FROM `naudotojas`
            WHERE `prisijungimo_vardas`='$saugus_vardas' OR `paštas`='$saugus_vardas' LIMIT 1";
    $res = $c->query($sql);
    if ($res) {
        $eil = $res->fetch_assoc(); //viena eilute
        if ($eil) {    
            $hash = $eil['slaptažodis'];
            $ok = false;
            // Jeigu bcrypt)
            if (preg_match('~^\$2y\$~', $hash)) {
                $ok = password_verify($slaptazodis, $hash);
            } else {
                // paprastas tekstas
                $ok = ($slaptazodis === $hash);
            }
            if ($ok) {
                $_SESSION['naudotojas_id'] = (int)$eil['id'];
                $_SESSION['vardas']        = $eil['prisijungimo_vardas'];
                $_SESSION['rola']          = $eil['rolė'];
                return true;
            }
        }
    }
    return false;
}

function atsijungti(): void {
    $_SESSION = [];
    session_destroy();
}

function prisijunges(): bool {
    return !empty($_SESSION['naudotojas_id']);
}

function reikalauti_prisijungimo(): void {
    if (!prisijunges()) {
        header("Location: prisijungti.php?grįžti=" . urlencode($_SERVER['REQUEST_URI'])); #eiti kur norejo eit
        exit;
    }
}

function reikalauti_vaidmens(string $rola): void {
    reikalauti_prisijungimo();
    if (($_SESSION['rola'] ?? '') !== $rola) {
        header("Location: klaida.php?k=403"); // nera vartotojo tipo
        exit;
    }
}

function dabartinis_naudotojas(): ?array {
    if (!prisijunges()) return null;
    $c = db();
    $id = (int)$_SESSION['naudotojas_id'];
    $res = $c->query("SELECT * FROM `naudotojas` WHERE `id`=$id");
    return $res ? $res->fetch_assoc() : null;
}
// tikimasis "response":
// [
//   "id" => 3,
//   "prisijungimo_vardas" => "jonas",
//   "paštas" => "jonas@example.com",
//   "rolė" => "vartotojas",
//   "pinigai" => "150.00"
// ]