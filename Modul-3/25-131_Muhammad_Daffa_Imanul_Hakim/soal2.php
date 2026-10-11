<?php
$fruits = array("Avocado", "Blueberry", "Cherry");

// Menambahkan 5 data baru dengan perulangan for
for ($i = 1; $i <= 5; $i++) {
    $fruits[] = "Buah Tambahan " . $i;
}

$arrlength = count($fruits);
echo "Panjang array saat ini: " . $arrlength . "<br>";
echo "<br>";

for ($x = 0; $x < $arrlength; $x++) {
    echo $fruits[$x];
    echo "<br>";
}

// soal 2 2.2
echo "======================================= soal 2 2.2 ======================================= <br>" ;

// variabel baru karena value berbeda
$vegies = array("Carrot", "Broccoli", "Spinach");
$arrlength = count($vegies);

for ($x = 0; $x < $arrlength; $x++) {
    echo $vegies[$x];
    echo "<br>";
}
?>