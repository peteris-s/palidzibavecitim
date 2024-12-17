<?php
require "functions.php";
require "Database.php";

$config = require("config.php");

$db = new Database($config["database"]);

// Iegūstam visas dāvanas
$gifts = $db->query("SELECT * FROM gifts")->fetchAll();

// Iegūstam bērnu vēstules
$letters = $db->query("SELECT * FROM letters")->fetchAll();

// Sagatavojam masīvu, kurā uzskaitīsim, cik reizes katra dāvana ir pieminēta
$gift_wishes = [];

// Pārskatām visas vēstules un skaitām, cik reizes katra dāvana ir pieminēta
foreach ($letters as $letter) {
    // Pārskatām visus bērnu vēlmju tekstus un skatāmies, vai dāvana ir minēta
    foreach ($gifts as $gift) {
        // Ja dāvana ir pieminēta, palielinām tās vēlmju skaitu
        if (stripos($letter["letter_text"], $gift["name"]) !== false) {
            if (!isset($gift_wishes[$gift["name"]])) {
                $gift_wishes[$gift["name"]] = 0;
            }
            $gift_wishes[$gift["name"]]++;
        }
    }
}

echo "<ol>";
foreach ($gifts as $gift) {
    // Iegūstam, cik daudz bērni vēlas šo dāvanu
    $wished_count = isset($gift_wishes[$gift["name"]]) ? $gift_wishes[$gift["name"]] : 0;

    // Salīdzinām, vai dāvanas ir pietiekami daudz noliktavā
    $status = "";
    if ($wished_count > $gift["count_available"]) {
        $status = "<span style='color: red;'>Trūkst " . ($wished_count - $gift["count_available"]) . " dāvanas!</span>";
    } elseif ($wished_count < $gift["count_available"]) {
        $status = "<span style='color: green;'>Ir par " . ($gift["count_available"] - $wished_count) . " vairāk dāvanu!</span>";
    } else {
        $status = "<span style='color: blue;'>Pietiekami daudz dāvanu!</span>";
    }

    // Parādām dāvanas nosaukumu, pieejamās dāvanas un dāvanas vēlmju skaitu
    echo "<li>" . $gift["name"] . " (" . $gift["count_available"] . " pieejamas) - Bērnu vēlmes: " . $wished_count . ". " . $status . "</li>";
}
echo "</ol>";
?>
