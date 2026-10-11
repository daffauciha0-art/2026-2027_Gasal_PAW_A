<?php
$height = array("Andy"=>"176", "Barry"=>"165", "Charlie"=>"170");

// menambahkan 5 data baru
$height["David"] = "180";
$height["Ethan"] = "172";
$height["Frank"] = "168";
$height["George"] = "175";
$height["Harry"] = "182";

// menampilkan format array height sesuai contoh
$output = array();
foreach($height as $k => $v) { $output[] = "\"$k\"=>\"$v\""; }
echo "height = (" . implode(", ", $output) . ")<br>";

// menampilkan nilai dengan indeks terakhir
$lastKey = array_key_last($height);
echo "Nilai dengan indeks terakhir: " . $height[$lastKey] . "<br>";

// menghapus satu data tertentu (contoh: "Barry")
unset($height["Barry"]);

// menampilkan kembali format array setelah dihapus
$output2 = array();
foreach($height as $k => $v) { $output2[] = "\"$k\"=>\"$v\""; }
echo "height = (" . implode(", ", $output2) . ")<br>";

$lastKey2 = array_key_last($height);
echo "Nilai dengan indeks terakhir setelah dihapus: " . $height[$lastKey2];

// soal 3 3.2
echo "<br> ======================================= soal 3 3.2 ======================================= <br>" ;

$weight = array("Andy"=>"70", "Barry"=>"65", "Charlie"=>"75");

$output = array();
foreach($weight as $k => $v) { $output[] = "\"$k\"=>\"$v\""; }
echo "weight = (" . implode(", ", $output) . ")<br>";

// Mengambil nilai data kedua menggunakan array_values
$values = array_values($weight);
echo "Data kedua: " . $values[1];
?>