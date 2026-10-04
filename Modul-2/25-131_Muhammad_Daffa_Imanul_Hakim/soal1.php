<?php
$matkul = ["PTI", "ALPRO", "DPW", "STRUKDAT", "JARKOM", "PAW", "PSBF", "RPL"];
$praktikum = ["JARKOM", "PAW"];

$jumlahMatkul = count($matkul);

for ($i = 0; $i < $jumlahMatkul; $i++) {
    // Pengecekan jika data $matkul dan $praktikum sama
    if (in_array($matkul[$i], $praktikum)) {
        echo "Saya sedang mengambil matkul " . $matkul[$i] . " termasuk praktikum nya<br>";
    } 
    // Pengecekan jika data $matkul indeks 6 atau 7
    elseif ($i == 6 || $i == 7) {
        echo "Saya belum mengambil matkul " . $matkul[$i] . "<br>";
    } 
    // Jika tidak memenuhi kondisi manapun
    else {
        echo "Saya sudah mengambil matkul " . $matkul[$i] . " semester lalu<br>";
    }
}
?>