<?php
$fruits = array("Avocado", "Blueberry", "Cherry");

// Menambahkan 5 data baru ke dalam array terindeks
$fruits[] = "Durian";
$fruits[] = "Elderberry";
$fruits[] = "Fig";
$fruits[] = "Grape";
$fruits[] = "Honeydew";

// Menampilkan format array
echo 'fruits = ("' . implode('", "', $fruits) . '")<br>';

// Menampilkan nilai dengan indeks tertinggi menggunakan fungsi max() dan array_keys()
$highest_index = max(array_keys($fruits));
echo "Nilai dengan indeks tertinggi: " . $fruits[$highest_index];


// soal 1 1.2
echo "<br> ======================================= soal 1 1.2 ======================================= <br>" ;

// Menghapus data "Blueberry" (berada pada indeks 1) menggunakan unset()
unset($fruits[1]);
$fruits = array_values($fruits); // Menyusun ulang indeks array

echo "Data Blueberry dihapus.<br>";
echo 'fruits = ("' . implode('", "', $fruits) . '")<br>';

$highest_index = max(array_keys($fruits));
echo "Nilai dengan indeks tertinggi: " . $fruits[$highest_index];
?>