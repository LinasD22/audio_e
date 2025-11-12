<?php
// konfiguracija/bibliotekos/el_pastas.php
declare(strict_types=1);

function siusti_uzsakymo_pranesima(string $gavejasEmail, int $uzsakymoId, string $busena): bool {
    $tema    = "Užsakymas #$uzsakymoId — būsena: $busena";
    $zinute  = "Sveiki,\n\nJūsų užsakymo #$uzsakymoId būsena: $busena.\n\nAčiū, kad pirkote!";
    $antr    = "MIME-Version: 1.0\r\nContent-Type: text/plain; charset=UTF-8\r\n";
    $antr   .= "From: linas.danusevicius@ktu.edu\r\n";

    return @mail($gavejasEmail, $tema, $zinute, $antr);
}
